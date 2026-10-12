<?php

namespace App\Http\Controllers;

use App\Mail\DonationReceiptMail;
use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DonationController extends Controller
{
    /**
     * Show the donation page.
     */
    public function create()
    {
        $paymongoEnabled = ! empty(config('services.paymongo.secret_key'));

        return view('donate', compact('paymongoEnabled'));
    }

    /**
     * Create a PayMongo checkout session and redirect.
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:2000', // min ₱20 in centavos (PayMongo's platform floor)
            'donor_name' => 'nullable|string|max:255',
            'donor_email' => 'nullable|email|max:255',
            'purpose' => 'required|string|in:General Donation,Church Maintenance,Outreach,Youth Ministry',
            'message' => 'nullable|string|max:500',
            'channel' => 'nullable|string|in:online,qr,bank',
            'consent' => 'required|accepted',
        ], [
            'consent.accepted' => 'Please agree to the Privacy Policy and Terms & Conditions to continue.',
        ]);

        // Which tab the donor submitted from determines which PayMongo payment
        // methods are offered on the hosted checkout page. All three still go
        // through the same Checkout Session + webhook flow, so every channel
        // is fully automated and confirmed server-side — none of them rely on
        // manually matching a screenshot or bank memo.
        $paymentMethodTypes = match ($validated['channel'] ?? 'online') {
            // Scan QR tab: generates a fresh QR Ph code for this donation only,
            // scannable by GCash, Maya, or any QR Ph-enabled bank app.
            'qr' => ['qrph'],
            // Bank Transfer tab: redirects to the donor's own online banking
            // login (BPI / UnionBank via PayMongo's "dob" method) to authorize
            // the transfer. Add 'brankas_bdo', 'brankas_metrobank', or
            // 'brankas_landbank' here once those are activated on your
            // PayMongo account (Dashboard → Settings → Payment Methods).
            'bank' => ['dob'],
            // Online Payment tab: the general e-wallet/card bundle.
            default => ['gcash', 'paymaya', 'card'],
        };

        $donation = Donation::create([
            'donor_name' => $validated['donor_name'] ?? null,
            'donor_email' => $validated['donor_email'] ?? null,
            'amount' => $validated['amount'],
            'currency' => 'PHP',
            'purpose' => $validated['purpose'],
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
            // Unique placeholder until PayMongo returns the real session id —
            // prevents a unique-constraint crash if two donations are created
            // concurrently (column is unique + not nullable).
            'checkout_session_id' => 'pending_'.\Illuminate\Support\Str::uuid(),
        ]);

        $attributes = [
            'line_items' => [
                [
                    'name' => 'Parish Donation',
                    'amount' => (int) $validated['amount'],
                    'currency' => 'PHP',
                    'quantity' => 1,
                ],
            ],
            'payment_method_types' => $paymentMethodTypes,
            'success_url' => route('donate.success').'?donation_id='.$donation->id,
            'cancel_url' => route('donate.cancel').'?donation_id='.$donation->id,
            'reference_number' => 'DON-'.strtoupper(substr($donation->id, 0, 8)),
            'description' => 'Paroisse Saint-Joseph Artisan — '.$validated['purpose'],
        ];

        $billing = array_filter([
            'name' => $validated['donor_name'] ?? null,
            'email' => $validated['donor_email'] ?? null,
        ]);

        if (! empty($billing)) {
            $attributes['billing'] = $billing;
        }

        try {
            $response = Http::withBasicAuth(config('services.paymongo.secret_key'), '')
                ->post('https://api.paymongo.com/v1/checkout_sessions', [
                    'data' => [
                        'attributes' => $attributes,
                    ],
                ]);

            if ($response->failed()) {
                Log::error('PayMongo checkout creation failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                $donation->update(['status' => 'failed']);

                $pmError = $response->json('errors.0.detail') ?? $response->body();
                return back()->with('error', 'PayMongo: '.$pmError);
            }

            $data = $response->json();
            $checkoutUrl = $data['data']['attributes']['checkout_url'];
            $checkoutId = $data['data']['id'];

            $donation->update(['checkout_session_id' => $checkoutId]);

            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            Log::error('PayMongo checkout exception', ['error' => $e->getMessage()]);
            $donation->update(['status' => 'failed']);

            return back()->with('error', 'Payment service unavailable. Please try again later.');
        }
    }

    /**
     * Success return URL after payment.
     */
    public function success(Request $request)
    {
        $donation = null;

        if ($request->has('donation_id')) {
            $donation = Donation::find($request->donation_id);

            // Poll PayMongo to confirm payment if still pending
            if ($donation && $donation->status === 'pending' && $donation->checkout_session_id) {
                try {
                    $response = Http::withBasicAuth(config('services.paymongo.secret_key'), '')
                        ->get('https://api.paymongo.com/v1/checkout_sessions/'.$donation->checkout_session_id);

                    if ($response->successful()) {
                        $sessionData = $response->json();
                        $paymentStatus = $sessionData['data']['attributes']['payment_intent']['attributes']['status'] ?? null;

                        if ($paymentStatus === 'succeeded') {
                            $paymentId = $sessionData['data']['attributes']['payment_intent']['id'] ?? null;
                            $paymentMethod = $sessionData['data']['attributes']['payments'][0]['attributes']['source']['type'] ?? null;
                            $donation->update([
                                'status' => 'paid',
                                'paid_at' => now(),
                                'paymongo_payment_id' => $paymentId,
                                'payment_method' => $paymentMethod,
                            ]);

                            if ($donation->donor_email) {
                                try {
                                    Mail::to($donation->donor_email)
                                        ->queue(new DonationReceiptMail($donation));
                                } catch (\Exception $e) {
                                    Log::error('Failed to send donation receipt email: '.$e->getMessage());
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to poll PayMongo on success page', ['error' => $e->getMessage()]);
                }
            }
        }

        return view('donate-success', compact('donation'));
    }

    /**
     * Cancel return URL.
     */
    public function cancel(Request $request)
    {
        if ($request->has('donation_id')) {
            $donation = Donation::find($request->donation_id);
            if ($donation && $donation->status === 'pending') {
                $donation->update(['status' => 'expired']);
            }
        }

        return view('donate-cancel');
    }

    /**
     * Handle PayMongo webhook for payment confirmation.
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Paymongo-Signature');

        // Verify webhook signature
        $webhookSecret = config('services.paymongo.webhook_secret');
        if ($webhookSecret && $sigHeader) {
            $parts = collect(explode(',', $sigHeader))
                ->mapWithKeys(function ($part) {
                    [$key, $value] = explode('=', $part, 2);

                    return [$key => $value];
                });

            $timestamp = $parts->get('t');
            $testSignature = $parts->get('te');
            $liveSignature = $parts->get('li');

            $expectedPayload = $timestamp.'.'.$payload;
            $computedSignature = hash_hmac('sha256', $expectedPayload, $webhookSecret);

            $signature = $liveSignature ?: $testSignature;
            if (! hash_equals($computedSignature, $signature ?? '')) {
                Log::warning('PayMongo webhook signature mismatch');

                return response()->json(['error' => 'Invalid signature'], 403);
            }
        }

        $event = json_decode($payload, true);
        $eventType = $event['data']['attributes']['type'] ?? null;

        if ($eventType === 'checkout_session.payment.paid') {
            $checkoutSessionId = $event['data']['attributes']['data']['id'] ?? null;

            if ($checkoutSessionId) {
                $donation = Donation::where('checkout_session_id', $checkoutSessionId)->first();

                if ($donation && $donation->status !== 'paid') {
                    $payments = $event['data']['attributes']['data']['attributes']['payments'] ?? [];
                    $paymentMethod = $payments[0]['attributes']['source']['type'] ?? null;
                    $paymentId = ! empty($payments) ? ($payments[0]['id'] ?? null) : null;

                    $donation->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                        'payment_method' => $paymentMethod,
                        'paymongo_payment_id' => $paymentId,
                    ]);

                    if ($donation->donor_email) {
                        try {
                            Mail::to($donation->donor_email)
                                ->queue(new DonationReceiptMail($donation));
                        } catch (\Exception $e) {
                            Log::error('Failed to send webhook donation receipt email: '.$e->getMessage());
                        }
                    }

                    Log::info('Donation marked as paid via webhook', ['donation_id' => $donation->id]);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Admin: list all donations.
     */
    public function adminIndex()
    {
        $donations = Donation::latest()->paginate(25);
        $totalPaid = Donation::where('status', 'paid')->sum('amount');
        $todayPaid = Donation::where('status', 'paid')->whereDate('paid_at', today())->sum('amount');
        $totalCount = Donation::where('status', 'paid')->count();

        return view('admin.donations', compact('donations', 'totalPaid', 'todayPaid', 'totalCount'));
    }

    /**
     * Stream a PDF receipt for direct browser viewing or download.
     * Accessible only via a signed URL tied to the specific donation.
     */
    public function receipt(Request $request, Donation $donation)
    {
        return Pdf::loadView('pdfs.donation-receipt', ['donation' => $donation])
            ->stream('donation-receipt-DON-'.strtoupper(substr($donation->id, 0, 8)).'.pdf');
    }
}
