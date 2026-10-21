<?php

namespace App\\Http\\Controllers;

use Illuminate\\Http\\Request;
use Illuminate\\Support\\Facades\\Cache;
use Illuminate\\Support\\Facades\\URL;

class LiveMassController extends Controller
{
    private const LIVE_URL_KEY = 'live_mass_url';
    private const PLATFORM_KEY = 'live_mass_platform';

    public function toggle(Request $request)
    {
        $request->validate(['state' => 'required|in:on,off']);

        if ($request->state === 'on') {
            if (! Cache::get(self::LIVE_URL_KEY)) {
                return back()->withErrors(['live_url' => 'Ajoutez et enregistrez le lien du direct avant de démarrer.']);
            }

            Cache::put('manual_live_override', true, now()->addHours(8));
            Cache::put('live_mass_started_at', now('Africa/Abidjan')->toIso8601String(), now()->addHours(8));
        } else {
            // Keep an explicit OFF state so the scheduled Sunday window cannot restart the live.
            Cache::put('manual_live_override', false, now()->addHours(8));
            Cache::forget('live_mass_started_at');
        }

        return back()->with('success', $request->state === 'on' ? 'Le direct est maintenant actif.' : 'Le direct a été arrêté.');
    }

    public function updateLiveLink(Request $request)
    {
        $validated = $request->validate([
            'platform' => ['required', 'in:youtube,facebook'],
            'live_url' => ['required', 'url', 'max:2048', function ($attribute, $value, $fail) use ($request) {
                $host = strtolower((string) parse_url($value, PHP_URL_HOST));
                $allowed = $request->input('platform') === 'youtube'
                    ? ['youtube.com', 'www.youtube.com', 'youtu.be', 'm.youtube.com', 'youtube-nocookie.com']
                    : ['facebook.com', 'www.facebook.com', 'm.facebook.com', 'fb.watch', 'web.facebook.com'];
                if (! in_array($host, $allowed, true)) {
                    $fail('Le lien doit provenir de la plateforme sélectionnée.');
                }
            }],
        ]);

        Cache::put(self::LIVE_URL_KEY, $validated['live_url'], now()->addDays(7));
        Cache::put(self::PLATFORM_KEY, $validated['platform'], now()->addDays(7));

        return back()->with('success', 'Le lien du direct a été enregistré.');
    }

    public function updateFacebookLink(Request $request)
    {
        $request->merge(['platform' => 'facebook', 'live_url' => $request->input('facebook_live_url')]);
        return $this->updateLiveLink($request);
    }

    public function updateLivePlatform(Request $request)
    {
        $validated = $request->validate(['platform' => 'required|in:youtube,facebook,both']);
        Cache::put(self::PLATFORM_KEY, $validated['platform'], now()->addDays(7));
        return back()->with('success', 'Plateforme du direct mise à jour.');
    }

    public function status()
    {
        $override = Cache::get('manual_live_override');
        $now = now('Africa/Abidjan');
        $scheduledWindow = $now->isSunday() && $now->copy()->setTime(9, 55)->lessThanOrEqualTo($now)
            && $now->copy()->setTime(11, 30)->greaterThanOrEqualTo($now);
        $isLive = $override === true || ($override === null && $scheduledWindow);
        $url = Cache::get(self::LIVE_URL_KEY) ?: Cache::get(FacebookLiveWebhookController::CACHE_KEY);

        return response()->json([
            'is_live' => $isLive && (bool) $url,
            'url' => $url,
            'platform' => Cache::get(self::PLATFORM_KEY, 'facebook'),
            'started_at' => Cache::get('live_mass_started_at'),
            'message' => app()->getLocale() === 'fr' ? 'La messe est en direct ! Cliquez pour nous rejoindre.' : 'Mass is live! Click to join.',
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}
