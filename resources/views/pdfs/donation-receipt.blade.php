<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Donation Receipt</title>
    <style>
        @page { margin: 20px; }
        body { font-family: sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
    </style>
</head>
<body>
    <div style="background-color: #1e3a8a; color: #fff; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h2 style="margin: 0;">Paroisse Saint-Joseph Artisan</h2>
        <p style="margin: 5px 0 0 0; font-size: 14px;">Official Donation Receipt</p>
    </div>

    <div style="border: 1px solid #e5e7eb; border-top: none; padding: 25px; border-radius: 0 0 8px 8px;">
        <p>Dear {{ $donation->donor_name ?: 'Generous Donor' }},</p>

        <p>Thank you for your generous contribution to Paroisse Saint-Joseph Artisan. May God bless your stewardship and continuous support for our church community.</p>

        <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 6px; margin: 20px 0;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 6px 0; color: #64748b;">Transaction Reference:</td>
                    <td style="padding: 6px 0; font-weight: bold; text-align: right;">DON-{{ strtoupper(substr($donation->id, 0, 8)) }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #64748b;">Amount Paid:</td>
                    <td style="padding: 6px 0; font-weight: bold; text-align: right; color: #16a34a;">₱{{ number_format($donation->amount / 100, 2) }} {{ $donation->currency }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #64748b;">Purpose:</td>
                    <td style="padding: 6px 0; font-weight: bold; text-align: right;">{{ $donation->purpose }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #64748b;">Payment Method:</td>
                    <td style="padding: 6px 0; font-weight: bold; text-align: right;">{{ strtoupper($donation->payment_method ?? 'PayMongo') }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #64748b;">Date:</td>
                    <td style="padding: 6px 0; font-weight: bold; text-align: right;">{{ $donation->paid_at ? $donation->paid_at->format('M d, Y h:i A') : now()->format('M d, Y') }}</td>
                </tr>
            </table>
        </div>

        @if($donation->message)
            <p style="font-style: italic; color: #475569;">"{{ $donation->message }}"</p>
        @endif

        <p style="margin-top: 30px; font-size: 13px; color: #94a3b8; text-align: center;">
            This serves as an official acknowledgment of your online donation.<br>
            Paroisse Saint-Joseph Artisan - Riviera Bonoumin, Abidjan, Côte d'Ivoire
        </p>
    </div>
</body>
</html>
