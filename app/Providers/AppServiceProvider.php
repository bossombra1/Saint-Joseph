<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->isProduction()) {
            URL::forceScheme('https');

            if ($appUrl = config('app.url')) {
                URL::forceRootUrl($appUrl);
            }

            // Render terminates TLS at the edge; ensure session cookies are HTTPS-only.
            config(['session.secure' => true]);
        }

        $this->configureRateLimiting();

        try {
            $settingsReady = \Illuminate\Support\Facades\Cache::remember('settings_table_ready', 86400, function () {
                return \Illuminate\Support\Facades\Schema::hasTable('settings');
            });

            if ($settingsReady) {
                $settings = \Illuminate\Support\Facades\Cache::remember('global_settings', now()->addHour(), function () {
                    return \App\Models\Setting::all()->pluck('value', 'key')->all();
                });
                view()->share('global_settings', $settings);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to load global settings: ' . $e->getMessage());
        }
    }

    protected function configureRateLimiting(): void
    {
        // ─── AUTH ROUTES — strictest ───────────────────────
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->input('email') . '|' . $request->ip())
                ->response(function (Request $request) {
                    $message = 'Too many login attempts. Please try again in a minute.';

                    if ($request->expectsJson()) {
                        return response()->json(['message' => $message], 429);
                    }

                    return back()
                        ->withErrors(['email' => $message])
                        ->withInput($request->only('email'));
                });
        });

        // ─── PASSWORD RESET ────────────────────────────────
        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perMinute(3)
                ->by($request->input('email') . '|' . $request->ip())
                ->response(fn() => response()->json([
                    'message' => 'Too many reset attempts. Try again later.'
                ], 429));
        });

        // ─── PUBLIC FORM SUBMISSIONS ───────────────────────
        RateLimiter::for('submissions', function (Request $request) {
            return Limit::perMinute(10)
                ->by($request->ip())
                ->response(fn() => response()->json([
                    'message' => 'Submission limit reached. Please wait.'
                ], 429));
        });

        // ─── GENERAL API ───────────────────────────────────
        RateLimiter::for('api', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(120)->by($request->user()->id)
                : Limit::perMinute(30)->by($request->ip());
        });

        // ─── CHAT / AI ENDPOINTS — expensive, strict ───────
        RateLimiter::for('chat', function (Request $request) {
            return Limit::perMinute(15)
                ->by($request->ip())
                ->response(fn() => response()->json([
                    'message' => 'Chat limit reached. Please wait a moment.'
                ], 429));
        });

        // ─── ADMIN PANEL ───────────────────────────────────
        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(60)
                ->by($request->user()?->id ?? $request->ip());
        });
    }
}
