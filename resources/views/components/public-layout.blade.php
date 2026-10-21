<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Paroisse Saint-Joseph Artisan') }}</title>
    <script>document.documentElement.className='js';</script>

    <!-- Fonts Preload -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Cinzel:wght@400;500;600&family=Jost:wght@300;400;500;600&display=swap">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Cinzel:wght@400;500;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SEO and Meta Tags -->
    @if(isset($meta))
        {{ $meta }}
    @else
        <meta name="description" content="Official website of Paroisse Saint-Joseph Artisan - Pacita. Providing spiritual guidance, sacramental services, and community outreach in San Pedro, Laguna.">
    @endif
    
    <!-- Open Graph / Facebook -->
    @php
        $ogImageUrl = \Illuminate\Support\Facades\Cache::remember('public_og_image_url', now()->addDay(), function () {
            return asset('images/parish-logo.png');
        });
    @endphp
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ config('app.name', 'Paroisse Saint-Joseph Artisan') }}">
    <meta property="og:description" content="{{ $meta_description ?? 'Official website of Paroisse Saint-Joseph Artisan - Pacita. Providing spiritual guidance, sacramental services, and community outreach in San Pedro, Laguna.' }}">
    <meta property="og:image" content="{{ $ogImageUrl }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $title ?? 'Paroisse Saint-Joseph Artisan' }} | Divine Grace & Community">
    <meta property="twitter:description" content="{{ $description ?? 'Official portal of Paroisse Saint-Joseph Artisan - Riviera Bonoumin. Experience our community of faith through daily masses, sacraments, and spiritual activities.' }}">
    <meta property="twitter:image" content="{{ $ogImageUrl }}">
    
    <!-- Favicon (works on all devices) -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/images/parish-logo.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="theme-color" content="#0D2A52">
    <meta name="msapplication-TileColor" content="#0D2A52">
    <meta name="msapplication-TileImage" content="/images/parish-logo.png">

    <!-- Alpine.js (bundled via Vite) -->
    <style>[x-cloak] { display: none !important; }</style>
    
    <!-- Page Transition Animation -->
    <style>
        @keyframes pageFadeIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        #main-content { animation: pageFadeIn 0.4s ease both; }
    </style>
</head>
<body class="antialiased">
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <div class="flex min-h-screen flex-col bg-background text-foreground">
        <x-navbar />

        <main id="main-content" class="flex-1" role="main">
            {{ $slot }}
        </main>

        <x-footer />
        
        <x-chatbot />

        <style>
            @keyframes livePulse { 0%,100% { opacity:1; box-shadow:0 0 0 0 rgba(220,38,38,.45); } 50% { opacity:.72; box-shadow:0 0 0 8px rgba(220,38,38,0); } }
            #live-mass-banner { animation: livePulse 1.2s infinite; }
        </style>
        <div id="live-mass-notice" class="fixed top-20 left-1/2 z-[110] hidden -translate-x-1/2 rounded-xl bg-red-700 px-5 py-3 text-center text-sm font-bold text-white shadow-2xl" role="status" aria-live="polite"></div>
        <a href="{{ url('/#live-mass') }}" id="live-mass-banner"
           class="fixed bottom-0 left-0 right-0 z-[90] hidden items-center justify-center gap-3 py-3 px-4 text-white font-bold text-sm tracking-wide shadow-lg transition-all hover:brightness-110"
           style="background:linear-gradient(135deg,#b91c1c 0%,#991b1b 100%);">
            <span class="h-2.5 w-2.5 rounded-full bg-white animate-pulse"></span>
            <span class="font-cinzel text-xs tracking-[.2em] uppercase" data-live-label>🔴 EN DIRECT — Rejoignez la messe</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        </a>
        <script>
        (() => {
            const endpoint = @json(route('live-mass.status'));
            const banner = document.getElementById('live-mass-banner');
            const notice = document.getElementById('live-mass-notice');
            const label = banner?.querySelector('[data-live-label]');
            if (!banner || !notice || !label) return;
            let previousState = null;
            const refreshLiveState = async () => {
                try {
                    const response = await fetch(endpoint, { headers: { 'Accept': 'application/json' }, cache: 'no-store' });
                    if (!response.ok) return;
                    const state = await response.json();
                    const active = state.is_live === true && Boolean(state.url);
                    banner.classList.toggle('hidden', !active);
                    banner.classList.toggle('flex', active);
                    if (active) {
                        label.textContent = state.platform === 'facebook' ? '🔴 EN DIRECT SUR FACEBOOK — Rejoignez la messe' : '🔴 EN DIRECT SUR YOUTUBE — Rejoignez la messe';
                        banner.href = state.url;
                        banner.target = '_blank';
                        banner.rel = 'noopener noreferrer';
                    } else {
                        banner.href = @json(url('/#live-mass'));
                        banner.removeAttribute('target');
                        banner.removeAttribute('rel');
                    }
                    if (previousState === false && active) {
                        notice.textContent = state.message || '🔴 La messe est en direct ! Cliquez pour nous rejoindre.';
                        notice.classList.remove('hidden');
                        window.setTimeout(() => notice.classList.add('hidden'), 9000);
                    }
                    previousState = active;
                } catch (_) {
                    // Keep the public page usable if the status endpoint is temporarily unavailable.
                }
            };
            refreshLiveState();
            window.setInterval(refreshLiveState, 5000);
        })();
        </script>

        <!-- Back to Top Button -->
        <button 
            x-data="{ show: false }" 
            x-init="window.addEventListener('scroll', () => { show = window.scrollY > 500 })"
            x-show="show"
            x-transition 
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            style="position: fixed; z-index: 100;"
            class="bottom-20 right-4 md:bottom-20 md:right-8 flex h-10 w-10 md:h-12 md:w-12 rounded-2xl bg-primary text-primary-foreground shadow-2xl items-center justify-center hover:-translate-y-2 transition-all active:scale-95"
            title="Back to Top"
            aria-label="Back to top"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
        </button>
    </div>

    <!-- Global Public Notification Toast -->
    <div x-data="{
            notification: { show: false, message: '', type: 'success' },
            init() {
                window.showToast = (message, type = 'success') => {
                    this.notification.message = message;
                    this.notification.type = type;
                    this.notification.show = true;
                    setTimeout(() => this.notification.show = false, 5000);
                };
            }
        }">
        <template x-teleport="body">
            <div 
                x-show="notification.show" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                class="fixed bottom-6 right-6 z-[9999] max-w-sm w-full bg-white border-l-4 shadow-2xl rounded-xl p-5 flex items-start gap-4 animate-in slide-in-from-right-10"
                :class="notification.type === 'success' ? 'border-green-500' : 'border-red-500'"
                :role="notification.type === 'error' ? 'alert' : 'status'"
                :aria-live="notification.type === 'error' ? 'assertive' : 'polite'"
                aria-atomic="true"
                x-cloak
            >
                <div :class="notification.type === 'success' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'" 
                     class="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-full shadow-sm">
                    <template x-if="notification.type === 'success'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </template>
                    <template x-if="notification.type === 'error'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    </template>
                </div>
                <div class="flex-1 pt-0.5">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground mb-1" x-text="notification.type === 'success' ? 'Success' : 'Notice'"></p>
                    <p class="text-sm font-bold text-primary leading-tight" x-text="notification.message"></p>
                </div>
                <button @click="notification.show = false" class="p-1 hover:bg-muted rounded-md transition-colors text-muted-foreground mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
        </template>
    </div>

    <script>
        // Entrance Animations Reveal System
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.15,
                rootMargin: '0px 0px -50px 0px'
            };

            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, observerOptions);

            const revealElements = document.querySelectorAll('.reveal, .reveal-stagger');
            revealElements.forEach(el => revealObserver.observe(el));
        });
    </script>
    {{-- Cookie Consent Banner (Alpine) --}}
    @php
        $gtmId = config('services.analytics.gtm_id');
    @endphp
    <div x-data="cookieConsent('{{ $gtmId }}')" x-init="init()">
        <template x-teleport="body">
            <div x-show="show" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-8"
                 class="fixed bottom-0 left-0 right-0 z-[500] flex justify-center px-4 pb-4"
                 role="region" aria-label="Cookie consent">
                <div class="w-full max-w-3xl rounded-2xl bg-white shadow-2xl border border-[rgba(26,64,128,0.12)] p-5 flex flex-col sm:flex-row items-center gap-4"
                     style="box-shadow:0 20px 60px rgba(13,42,82,0.18);">
                    <div class="flex-1 text-center sm:text-left">
                        <p class="font-heading font-bold italic" style="color:var(--blue-deep);font-size:1.05rem;">We value your privacy</p>
                        <p class="text-xs leading-relaxed mt-1" style="color:rgba(13,42,82,.62);">
                            We use essential cookies to keep this site working. With your consent, we also use
                            <span x-text="gaEnabled ? 'Google Analytics' : 'analytics'"></span>
                            to understand how visitors use our website. See our
                            <a href="{{ route('privacy-policy') }}" class="underline font-semibold" style="color:var(--blue-deep);">Privacy Policy</a>.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <button @click="accept('essential')" class="px-4 py-2.5 rounded-full text-[11px] font-bold uppercase tracking-widest transition-all hover:bg-muted/50"
                                style="border:1.5px solid rgba(26,64,128,0.25);color:var(--blue-deep);background:transparent;">
                            Essential Only
                        </button>
                        <button x-show="gaEnabled" @click="accept('all')" class="px-5 py-2.5 rounded-full text-[11px] font-bold uppercase tracking-widest text-white transition-all hover:opacity-90"
                                style="background:var(--blue-deep);">
                            Accept All
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        // Cookie-driven analytics loader — GA4 loads ONLY after explicit opt-in.
        function cookieConsent(gtmId) {
            return {
                show: false,
                gaEnabled: !!gtmId,
                init() {
                    const stored = localStorage.getItem('parish_cookie_consent');
                    if (stored === null) {
                        setTimeout(() => { this.show = true; }, 800);
                    } else if (stored === 'all' && gtmId) {
                        this.loadAnalytics(gtmId);
                    }
                },
                accept(choice) {
                    localStorage.setItem('parish_cookie_consent', choice);
                    if (choice === 'all' && gtmId) this.loadAnalytics(gtmId);
                    this.show = false;
                },
                loadAnalytics(id) {
                    if (window.__gaLoaded) return;
                    window.__gaLoaded = true;
                    const script = document.createElement('script');
                    script.async = true;
                    script.src = 'https://www.googletagmanager.com/gtag/js?id=' + id;
                    document.head.appendChild(script);
                    window.dataLayer = window.dataLayer || [];
                    window.gtag = function(){ dataLayer.push(arguments); };
                    gtag('js', new Date());
                    gtag('config', id, { anonymize_ip: true });
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
