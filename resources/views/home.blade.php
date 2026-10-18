<x-public-layout>
@php
    $isFrench = app()->getLocale() === 'fr';
@endphp
<x-slot name="meta">
    <meta name="description" content="Welcome to Paroisse Saint-Joseph Artisan – Riviera Bonoumin, Abidjan, Côte d'Ivoire. Mass schedules, intentions, events, and community news.">
    <link rel="preload" href="{{ asset('fonts/Canterbury.ttf') }}" as="font" type="font/ttf" crossorigin>
    <style>
        @font-face{font-family:'Canterbury';src:url('{{ asset('fonts/Canterbury.ttf') }}') format('truetype');font-weight:normal;font-style:normal;font-display:swap;}
    </style>
</x-slot>

<section class="hero-section" style="position:relative;min-height:100vh;min-height:100svh;display:flex;flex-direction:column;align-items:center;justify-content:center;overflow:hidden;">
    <div style="position:absolute;inset:0;z-index:0;">
        <img src="{{ asset('images/parish-logo.png') }}" alt="Paroisse Saint-Joseph Artisan" fetchpriority="high" decoding="async" width="1920" height="1080" style="width:100%;height:100%;object-fit:cover;filter:saturate(.75) brightness(.85);transform:scale(1.04);">
        <div class="hero-overlay" style="position:absolute;inset:0;"></div>
    </div>
    <div style="position:absolute;inset:0;z-index:1;pointer-events:none;background:radial-gradient(ellipse 80% 60% at 50% 30%,rgba(26,64,128,.22) 0%,transparent 70%);"></div>
    <div style="position:absolute;inset:0;z-index:2;pointer-events:none;opacity:.03;background-image:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22><filter id=%22n%22><feTurbulence type=%22fractalNoise%22 baseFrequency=%220.75%22 stitchTiles=%22stitch%22/></filter><rect width=%22200%22 height=%22200%22 filter=%22url(%23n)%22/></svg>');"></div>

    <div style="position:relative;z-index:10;text-align:center;display:flex;flex-direction:column;align-items:center;padding:80px 24px 0;width:100%;max-width:960px;">
        <div class="hero-badge animate-fade-in-down" style="margin-bottom:24px;animation-delay:.1s;">
            <span style="width:6px;height:6px;border-radius:50%;background:var(--gold);display:block;box-shadow:0 0 8px rgba(245,197,24,.8);"></span>
            <span style="font-size:11.5px;font-weight:600;letter-spacing:.38em;text-transform:uppercase;color:rgba(255,248,180,.85);">Diocèse d'Abidjan</span>
        </div>

        <h1 class="animate-fade-in-up" style="font-family:var(--font-body);font-weight:400;line-height:1.1;letter-spacing:.01em;margin-bottom:20px;text-shadow:0 4px 48px rgba(0,0,0,.55);font-size:clamp(2.2rem,8vw,5.5rem);color:#fff;animation-delay:.2s;">
            <span class="hero-title-accent">Paroisse Saint-Joseph Artisan</span>
        </h1>

        <p class="font-heading animate-fade-in-up" style="font-style:italic;color:rgba(255,215,64,.82);margin-bottom:14px;font-size:clamp(.9rem,1.8vw,1.15rem);font-weight:300;text-shadow:0 2px 12px rgba(0,0,0,.4);animation-delay:.3s;">Riviera Bonoumin Cocody, Abidjan, Côte d'Ivoire</p>
        <div style="width:56px;height:1px;margin-bottom:22px;background:linear-gradient(90deg,transparent,rgba(245,197,24,.65),transparent);"></div>
        <p class="animate-fade-in-up" style="color:rgba(220,232,255,.78);font-size:clamp(.85rem,1.4vw,1rem);line-height:1.78;max-width:430px;font-weight:300;letter-spacing:.01em;margin-bottom:36px;animation-delay:.4s;">Home to the Queen of the Most Holy Rosary — a beacon of faith, community, and service for over four decades.</p>

        <div class="hero-cta-wrap animate-fade-in-up" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:20px;margin-bottom:40px;animation-delay:.5s;">
            <a href="{{ route('mass-schedule') }}" class="ghost-btn inline-flex items-center gap-2 rounded-full font-bold uppercase" style="padding:13px 30px;font-size:12.5px;letter-spacing:.18em;text-decoration:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                Mass Schedule
            </a>
            <a href="{{ route('submit-intention') }}" class="gold-btn inline-flex items-center gap-2 rounded-full" style="padding:13px 30px;font-size:12.5px;letter-spacing:.18em;text-transform:uppercase;text-decoration:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                Offer an Intention
            </a>
            <a href="{{ route('inquiry') }}" class="ghost-btn inline-flex items-center gap-2 rounded-full font-bold uppercase" style="padding:13px 30px;font-size:12.5px;letter-spacing:.18em;text-decoration:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                Parish Inquiry
            </a>
        </div>

        <div class="hero-stats-strip animate-fade-in-up" style="display:flex;align-items:center;justify-content:center;gap:60px;padding:20px 32px;width:100%;max-width:440px;border-top:1px solid rgba(245,197,24,.15);border-radius:16px;background:rgba(255,255,255,0.04);backdrop-filter:blur(6px);animation-delay:.6s;">
            @php
            $foundedYear = 2010;
            $yearsOfService = max(1, now('Africa/Abidjan')->year - $foundedYear);
        @endphp
        @foreach([[$yearsOfService.'+','Years of Service'],['13','Weekly Masses']] as $stat)
            <div style="text-align:center;">
                <div class="font-heading stat-val" style="font-size:1.75rem;font-weight:700;font-style:italic;color:var(--gold-light);line-height:1;">{{ $stat[0] }}</div>
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:.3em;color:rgb(255, 255, 255);margin-top:5px;">{{ $stat[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<x-live-mass-slot :nextMass="$nextMass" />

<section class="py-32 bg-[var(--cream)] reveal reveal-up section-pad-mobile section-pad-tablet">
    <div class="max-w-[1200px] mx-auto px-6 section-px-mobile">
        <div class="text-center mb-16">
            <div class="divider-ornament mb-4"><span class="eyebrow">Quick Access</span></div>
            <h2 class="font-heading text-4xl md:text-5xl font-bold italic" style="color:var(--blue-deep);">How Can We Serve You?</h2>
        </div>
        <div class="quick-actions-grid grid grid-cols-2 md:grid-cols-4 gap-8 max-w-5xl mx-auto reveal reveal-stagger">
            @php
            $actions = [
                ['href'=>'/mass-schedule','icon'=>'<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>','label'=>'Mass Schedule','sub'=>'Times & days'],
                ['href'=>'/submit-intention','icon'=>'<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/><path d="M12 8v8"/><path d="M8 12h8"/>','label'=>'Offer Intention','sub'=>'Submit online'],
                ['href'=>'/inquiry','icon'=>'<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.19 12 19.79 19.79 0 0 1 1.12 3.33A2 2 0 0 1 3.09 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>','label'=>'Inquiry','sub'=>'Sacraments & docs'],
                ['href'=>'/donate','icon'=>'<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.505 4.04 3 5.5L12 21l7-7Z"/>','label'=>'Donate','sub'=>'Support the parish'],
            ];
            @endphp
            @foreach($actions as $a)
            <a href="{{ $a['href'] }}" class="card-sacred group flex flex-col items-center gap-5 p-8 text-center" style="text-decoration:none;">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-300 group-hover:scale-110" style="background:linear-gradient(135deg,rgba(245,197,24,.14),rgba(245,197,24,.04));border:1px solid rgba(245,197,24,.32);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">{!! $a['icon'] !!}</svg>
                </div>
                <div>
                    <p class="font-heading font-bold text-lg italic transition-colors duration-200 group-hover:text-[#C9A200]" style="color:var(--blue-deep);">{{ $a['label'] }}</p>
                    <p class="text-sm mt-1 tracking-wide" style="color:rgba(13,42,82,.4);">{{ $a['sub'] }}</p>
                </div><br><br>
            </a>
            @endforeach
        </div>
    </div><br>
</section>

<section class="relative pt-12 pb-24 overflow-hidden reveal reveal-up section-pad-mobile section-pad-tablet">
    <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">
        <img src="{{ asset('images/parish-logo.png') }}" alt="" class="w-full h-full object-cover" style="filter:saturate(.2) brightness(1.2) blur(4px);transform:scale(1.06);">
        <div style="position:absolute;inset:0;background:rgba(247,249,255,.89);"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 section-px-mobile">
        <div class="text-center mb-14"><br>
            <div class="flex justify-center mb-5">
                <div class="relative flex items-center justify-center" style="width:50px;height:50px;">
                    <svg width="50" height="50" viewBox="0 0 50 50" style="position:absolute;inset:0;" fill="none" aria-hidden="true">
                        <line x1="25" y1="2" x2="25" y2="9" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/><line x1="25" y1="41" x2="25" y2="48" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/><line x1="2" y1="25" x2="9" y2="25" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/><line x1="41" y1="25" x2="48" y2="25" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/><line x1="7" y1="7" x2="12" y2="12" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/><line x1="38" y1="38" x2="43" y2="43" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/><line x1="43" y1="7" x2="38" y2="12" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/><line x1="7" y1="43" x2="12" y2="38" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span class="font-cinzel relative z-10" style="color:#C9A200;font-size:1.35rem;line-height:1;" aria-hidden="true">✝</span>
                </div>
            </div>
            <div class="eyebrow mb-3">{{ $isFrench ? 'ÉVÉNEMENTS À VENIR' : 'UPCOMING EVENTS' }}</div>
            <h2 class="font-heading font-bold italic" style="font-size:clamp(2.4rem,5vw,4rem);color:var(--blue-deep);line-height:1.1;margin-bottom:12px;">{{ $isFrench ? 'Nos prochains rendez-vous' : "What's Happening" }}</h2>
            <div class="flex justify-center mb-4"><div style="width:7px;height:7px;background:rgba(201,162,0,.42);transform:rotate(45deg);"></div></div>
            <p style="color:rgba(13,42,82,.45);font-size:16px;max-width:480px;margin:0 auto;line-height:1.7;">{{ $isFrench ? 'Restez en lien avec la paroisse et participez à nos célébrations liturgiques et à nos événements.' : 'Stay connected. Join us in our liturgical celebrations and events.' }}</p>
        </div>

        <div class="events-grid grid md:grid-cols-3 gap-8 mb-8 reveal reveal-stagger">
            @if($nextMass)
            <div class="card-event card-event-featured relative">
                <div class="event-badge-today">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    <span style="font-size:11px;font-weight:700;letter-spacing:.18em;color:#C9A200;text-transform:uppercase;">{{ $nextMass->calculated_day }}</span>
                </div><br>
                <div class="flex flex-col items-center text-center pt-14 pb-5 px-6" style="flex:1;">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background:rgba(245,197,24,.07);border:1.5px solid rgba(201,162,0,.28);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 22h8"/><path d="M12 11v11"/><path d="M5 3h14L18 9a6 6 0 0 1-12 0L5 3z"/><path d="M3 3h18"/></svg>
                    </div>
                    <p style="font-size:12px;letter-spacing:.22em;color:rgba(13,42,82,.5);text-transform:uppercase;font-weight:600;margin-bottom:10px;">{{ mb_strtoupper($nextMass->title ?? ($nextMass->mass_type === 'sunday' ? ($isFrench ? 'Messe du dimanche' : 'Sunday Mass') : ($isFrench ? 'Messe en semaine' : 'Weekday Mass')), 'UTF-8') }}</p>
                    <div class="flex justify-center mb-3"><div style="width:5px;height:5px;background:rgba(201,162,0,.45);transform:rotate(45deg);"></div></div>
                    <p class="font-heading font-bold" style="font-size:clamp(1.8rem,3vw,2.5rem);color:var(--blue-deep);line-height:1.05;">{{ $nextMass->calculated_time }}</p>
                </div>
            </div>
            @endif

            @php
            $evtIcons = [
                '<circle cx="12" cy="12" r="4"/><path d="M12 2v3M12 19v3M4.93 4.93l2.12 2.12M16.95 16.95l2.12 2.12M2 12h3M19 12h3M4.93 19.07l2.12-2.12M16.95 7.05l2.12-2.12"/>',
                '<path d="M12 22V12"/><path d="M12 12C9 12 4 10 4 6a4 4 0 0 1 8 0"/><path d="M12 12c3 0 8-2 8-6a4 4 0 0 0-8 0"/><path d="M8 22h8"/>',
            ];
            @endphp

            @foreach($upcomingEvents as $evt)
            <div class="card-event">
                <div class="flex flex-col items-center text-center pt-5 pb-5 px-6" style="flex:1;">
                    <div class="flex items-center gap-1.5 mb-4" style="align-self:flex-start;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,.4)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        <span style="font-size:12px;letter-spacing:.14em;color:rgba(13,42,82,.4);text-transform:uppercase;font-weight:500;">{{ \Carbon\Carbon::parse($evt->home_occurrence_date)->locale(app()->getLocale())->isoFormat('LL') }}</span>
                    </div>
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background:rgba(245,197,24,.07);border:1.5px solid rgba(201,162,0,.28);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $evtIcons[$loop->index % 2] !!}</svg>
                    </div>
                    <p style="font-size:12px;letter-spacing:.22em;color:rgba(13,42,82,.5);text-transform:uppercase;font-weight:600;margin-bottom:10px;">{{ mb_strtoupper($evt->title, 'UTF-8') }}</p>
                    <div class="flex justify-center mb-3"><div style="width:5px;height:5px;background:rgba(201,162,0,.45);transform:rotate(45deg);"></div></div>
                    <p style="font-size:15px;color:var(--blue-deep);line-height:1.8;">
                        @php
                            $times = is_array($evt->event_time) ? $evt->event_time : [$evt->event_time];
                            $formattedTimes = array_map(function($t) use ($isFrench) {
                                if (is_array($t)) {
                                    $timeStr = '';
                                    if (!empty($t['date'])) $timeStr .= \Carbon\Carbon::parse($t['date'])->locale(app()->getLocale())->isoFormat('ll') . ' ';
                                    if (!empty($t['time'])) {
                                        try {
                                            $timeStr .= \Carbon\Carbon::parse($t['time'], 'Africa/Abidjan')->locale(app()->getLocale())->isoFormat($isFrench ? 'HH:mm' : 'h:mm A');
                                        } catch (\Throwable $exception) {
                                            $timeStr .= $t['time'];
                                        }
                                    }
                                    if (!empty($t['title'])) $timeStr .= " ({$t['title']})";
                                    return trim($timeStr);
                                }
                                try {
                                    return \Carbon\Carbon::parse((string) $t, 'Africa/Abidjan')->locale(app()->getLocale())->isoFormat($isFrench ? 'HH:mm' : 'h:mm A');
                                } catch (\Throwable $exception) {
                                    return (string) $t;
                                }
                            }, $times);
                        @endphp
                        @if(!empty($formattedTimes))
                            {{ implode(' · ', array_slice($formattedTimes, 0, 3)) }}
                            @if(count($formattedTimes) > 3)<br>{{ implode(' · ', array_slice($formattedTimes, 3)) }}@endif
                        @endif
                    </p>
                </div>
            </div>
            @endforeach

            @for($evtFill = 0; $evtFill < (2 - $upcomingEvents->count()); $evtFill++)
            <div class="card-event" style="opacity:.38;">
                <div class="flex flex-col items-center text-center px-6 py-10" style="flex:1;justify-content:center;">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4" style="background:rgba(26,64,128,.04);border:1px solid rgba(26,64,128,.08);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,.25)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    </div>
                    <p style="font-size:11px;color:rgba(13,42,82,.3);">{{ $isFrench ? 'Aucun événement à venir' : 'No upcoming event' }}</p>
                </div>
            </div>
            @endfor
        </div>

        <a href="{{ route('events') }}" class="group relative flex flex-col items-center justify-center overflow-hidden rounded-2xl events-cta-banner" style="background:#0A2342;text-decoration:none;padding:24px;min-height:100px;transition:all .35s ease;" aria-label="{{ $isFrench ? 'Voir le calendrier complet des événements' : 'View full events schedule' }}">
            <div class="absolute left-0 top-[70%] -translate-y-1/2 pointer-events-none transition-transform duration-700 group-hover:scale-110" style="opacity:.5;height:150%;width:auto;" aria-hidden="true">
                <img src="{{ asset('images/parish-logo.png') }}" alt="Parish Illustration" width="285" height="135" style="height:90%;width:auto;object-fit:contain;filter:brightness(0) invert(1);">
            </div>
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="flex items-center gap-3">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    <span class="font-cinzel" style="font-size:13px;letter-spacing:.35em;color:#fff;font-weight:700;text-transform:uppercase;">{{ $isFrench ? 'Voir tout le programme' : 'View Full Schedule' }}</span>
                </div>
                <span class="transition-all duration-300 group-hover:translate-x-2" style="color:#C9A200;font-size:18px;line-height:1;" aria-hidden="true">→</span>
            </div>
        </a>
    </div><br><br>
</section>

@if($nextHomeRefreshAt)
<script>
    (() => {
        const delay = {{ $nextHomeRefreshAt * 1000 }} - Date.now();
        if (delay > 0) window.setTimeout(() => window.location.reload(), Math.min(delay, 2147483647));
    })();
</script>
@endif

@if($announcements->isNotEmpty())
    @php
        $featuredAnnouncement = $announcements->firstWhere('is_featured', true);
        $carouselAnnouncements = $featuredAnnouncement
            ? $announcements->filter(fn($a) => $a->id !== $featuredAnnouncement->id)->values()
            : $announcements->values();
    @endphp

    <section class="py-24 reveal section-pad-mobile section-pad-tablet">
        <div class="max-w-6xl mx-auto px-6 section-px-mobile">

            <div class="text-center mb-12">
                <div class="flex items-center justify-center gap-4 mb-5">
                    <span style="display:block;flex:1;max-width:60px;height:1px;background:linear-gradient(90deg,transparent,rgba(201,162,0,.4));"></span>
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/><line x1="12" y1="2" x2="12" y2="0"/><line x1="10" y1="1" x2="14" y2="1"/></svg>
                    <span style="display:block;flex:1;max-width:60px;height:1px;background:linear-gradient(90deg,rgba(201,162,0,.4),transparent);"></span>
                </div>
                <h2 class="font-cinzel font-semibold" style="font-size:clamp(1.25rem,3vw,2.15rem);color:var(--blue-deep);letter-spacing:.16em;margin-bottom:8px;">LATEST ANNOUNCEMENTS</h2>
                <p style="color:rgba(13,42,82,.4);font-size:15.5px;max-width:480px;margin:0 auto 14px;">Stay informed. Be involved. Grow in faith together.</p>
                <div class="flex justify-center"><div style="width:6px;height:6px;background:rgba(201,162,0,.42);transform:rotate(45deg);"></div></div>
            </div>

            @if($carouselAnnouncements->isEmpty())
                <div class="text-center py-16">
                    <p style="color:rgba(13,42,82,.3);font-size:14px;">No announcements at this time.</p>
                </div>
            @else
                <div x-data="announcementCarousel(@js($carouselAnnouncements->count()))" x-init="init()" class="relative">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($carouselAnnouncements as $index => $ann)
                            <div x-show="isVisible({{ $index }})" x-cloak>
                                <x-announcement-card :ann="$ann" />
                            </div>
                        @endforeach
                    </div>

                    <div x-show="totalPages > 1" x-cloak class="flex items-center justify-center gap-4 mt-8">
                        <button @click="prev()" :disabled="!hasPrev"
                            class="w-10 h-10 rounded-full bg-card border border-muted shadow-sm flex items-center justify-center text-primary hover:bg-muted/50 disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 19 12 12 15 5"/></svg>
                        </button>

                        <div class="flex items-center justify-center gap-1.5">
                            <template x-for="i in totalPages" :key="i">
                                <button @click="goToPage(i - 1)"
                                    :class="currentPage === i - 1 ? 'w-6 bg-accent' : 'w-2 bg-muted'"
                                    class="h-2 rounded-full transition-all duration-200">
                                </button>
                            </template>
                        </div>

                        <button @click="next()" :disabled="!hasNext"
                            class="w-10 h-10 rounded-full bg-card border border-muted shadow-sm flex items-center justify-center text-primary hover:bg-muted/50 disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 5 15 12 9 19"/></svg>
                        </button>
                    </div>
                </div>

                <div class="mt-10 text-center">
                    <a href="{{ route('announcements.index') }}" class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-widest text-primary hover:text-accent transition-colors group focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded p-1">
                        <span>View all announcements</span>
                        <span class="transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            @endif

            <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('announcementCarousel', (count) => ({
                    cardsCount: count,
                    currentPage: 0,
                    perPageDesktop: 3,
                    perPageTablet: 2,
                    perPageMobile: 1,
                    perPage: 3,

                    init() {
                        this.updatePerPage();
                        window.addEventListener('resize', () => this.updatePerPage());
                    },

                    updatePerPage() {
                        const w = window.innerWidth;
                        this.perPage = w >= 1024 ? this.perPageDesktop : w >= 640 ? this.perPageTablet : this.perPageMobile;
                        if (this.currentPage >= this.totalPages) this.currentPage = Math.max(0, this.totalPages - 1);
                    },

                    get totalPages() {
                        return Math.ceil(this.cardsCount / this.perPage);
                    },

                    isVisible(index) {
                        return index >= this.currentPage * this.perPage && index < (this.currentPage + 1) * this.perPage;
                    },

                    get hasPrev() {
                        return this.currentPage > 0;
                    },

                    get hasNext() {
                        return this.currentPage < this.totalPages - 1;
                    },

                    next() {
                        if (this.hasNext) this.currentPage++;
                    },

                    prev() {
                        if (this.hasPrev) this.currentPage--;
                    },

                    goToPage(page) {
                        if (page >= 0 && page < this.totalPages) this.currentPage = page;
                    }
                }));
            });
            </script>
        </div>
    </section>

    @if($featuredAnnouncement)
        @php
            $categoryConfigs = [
                'Parish Life'  => ['tint' => '#FBEEE7', 'color' => '#B5562F'],
                'Liturgical'   => ['tint' => '#F3ECFA', 'color' => '#6B3FA0'],
                'Sacraments'   => ['tint' => '#FBF3DC', 'color' => '#A87F22'],
                'Formation'    => ['tint' => '#EEF1F6', 'color' => '#1B2A4A'],
                '_default'     => ['tint' => '#E8F4F0', 'color' => '#2D6A4F'],
            ];
            $categoryIcons = [
                'Parish Life' => '<path d="M16 21v-2a4 4 0 0 0-3.7-3.97"/><path d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/><path d="M22 21v-2a4 4 0 0 0-4-4h-2"/><circle cx="6" cy="9" r="4"/>',
                'Liturgical'  => '<path d="M12 2L2 21h10l2-4 2 4z"/><path d="M12 2L22 21H12z"/><path d="M6 17V9"/>',
                'Sacraments'  => '<path d="M20 14.69 12 23l-8-8.31A6 6 0 0 1 12 7a6 6 0 0 1 10 7.69Z"/>',
                'Formation'   => '<path d="M4 19V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v13"/><path d="M4 19l8-8 8 8"/><path d="M8 7v4"/><path d="M16 7v2"/>',
                '_default'    => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
            ];
            $heroCategory = $featuredAnnouncement->category ?? 'Parish Life';
            $heroCfg = $categoryConfigs[$heroCategory] ?? $categoryConfigs['_default'];
            $heroIconPath = $categoryIcons[$heroCategory] ?? $categoryIcons['_default'];
        @endphp

        <section class="py-12 reveal">
            <div class="max-w-6xl mx-auto px-6 section-px-mobile">
                <div class="text-center mb-10">
                    <p class="text-[12px] font-black uppercase tracking-[0.3em] text-accent mb-3">Featured</p>
                    <div class="h-1 w-16 bg-accent mx-auto rounded-full"></div>
                </div>

                <article class="bg-card border border-muted rounded-2xl shadow-xl overflow-hidden group relative" style="border-top-width:4px;border-top-color:{{ $heroCfg['tint'] }};">
                    <div class="p-6 md:p-8">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-2">
                                @if($featuredAnnouncement->is_recruitment)
                                    <span class="inline-flex items-center gap-1 text-[8px] font-black uppercase tracking-widest text-accent">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                        <span>Recruitment</span>
                                    </span>
                                @endif
                            </div>
                            <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center" style="background:{{ $heroCfg['tint'] }};">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ $heroCfg['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    {!! $heroIconPath !!}
                                </svg>
                            </div>
                        </div>

                        <h2 class="font-heading font-bold italic text-xl md:text-2xl text-primary leading-tight line-clamp-2 min-h-[2.7em] mb-3 group-hover:text-accent transition-colors">
                            <a href="{{ route('announcements.show', $featuredAnnouncement) }}" class="focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded">
                                {{ $featuredAnnouncement->title }}
                            </a>
                        </h2>

                        @if($featuredAnnouncement->content)
                            <p class="text-sm text-muted-foreground leading-relaxed line-clamp-2 min-h-[2.6em] mb-4">
                                {{ strip_tags($featuredAnnouncement->content) }}
                            </p>
                        @endif

                        <div class="flex items-center gap-4">
                            <a href="{{ route('announcements.show', $featuredAnnouncement) }}" class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-primary hover:text-accent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded">
                                Read more <span aria-hidden="true">&rarr;</span>
                            </a>
                            @if($featuredAnnouncement->is_recruitment)
                                @if($featuredAnnouncement->registration_link)
                                    <a href="{{ $featuredAnnouncement->registration_link }}" target="_blank" rel="noopener" class="gold-btn text-[9.5px] px-3 py-1 rounded-lg font-bold uppercase tracking-wider">Register Now</a>
                                @else
                                    <a href="{{ route('about') }}#visit-map" class="gold-btn text-[9.5px] px-3 py-1 rounded-lg font-bold uppercase tracking-wider">Visit Parish Office</a>
                                @endif
                            @endif
                        </div>
                    </div>
                </article>
            </div>
        </section>
    @endif
@endif

<section class="py-28 relative overflow-hidden reveal reveal-up section-pad-mobile section-pad-tablet" style="background:var(--blue-deep);">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none select-none font-cinzel leading-none" style="font-size:420px;color:rgba(255,255,255,.018);">✝</div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 pointer-events-none" style="width:600px;height:300px;background:radial-gradient(ellipse,rgba(245,197,24,.08) 0%,transparent 70%);"></div>
    <div class="absolute top-0 left-0 right-0 h-px" style="background:linear-gradient(90deg,transparent,rgba(245,197,24,.4),transparent);"></div>

    <div class="max-w-[1200px] mx-auto px-6 section-px-mobile relative z-10"><br><br>
        <div class="text-center mb-16">
            <div class="divider-ornament mb-4"><span style="font-size:10px;font-weight:600;letter-spacing:.35em;text-transform:uppercase;color:rgba(245,197,24,.65);">Sacramental Services</span></div>
            <h2 class="font-heading text-4xl md:text-5xl font-bold italic" style="color:#EBF2FF;">How We Serve</h2>
            <p class="mt-4 text-sm font-light" style="color:rgba(235,242,255,.4);letter-spacing:.05em;">Inquire about any sacramental service at our parish office.</p>
        </div>

        <div class="services-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 max-w-5xl mx-auto reveal reveal-stagger">
            @php
            $services = [
                ['label'=>'Baptism','href'=>'/inquiry?type=Baptism','svg'=>'<circle cx="12" cy="6" r="3"/><path d="M5 12a7 7 0 0 1 14 0"/><line x1="12" y1="12" x2="12" y2="20"/><path d="M9 20h6"/>'],
                ['label'=>'Wedding','href'=>'/inquiry?type=Wedding','svg'=>'<path d="M8.5 9.5 5 15h14l-3.5-5.5"/><circle cx="12" cy="5" r="2.5"/><path d="M12 8v10"/><path d="M9.5 18h5"/>'],
                ['label'=>'Confirmation','href'=>'/inquiry?type=Confirmation','svg'=>'<path d="M12 2v8"/><path d="M8 5l4 5 4-5"/><circle cx="12" cy="17" r="4"/><path d="M12 14v6"/>'],
                ['label'=>'Funeral Mass','href'=>'/inquiry?type=Funeral+Mass','svg'=>'<path d="M12 2v12"/><path d="M8 6h8"/><path d="M5 14h14l-1.5 6H6.5L5 14z"/>'],
                ['label'=>'House Blessing','href'=>'/inquiry?type=House+Blessing','svg'=>'<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
                ['label'=>'Car Blessing','href'=>'/inquiry?type=Car+Blessing','svg'=>'<path d="M19 17H5v-5l2-6h10l2 6v5Z"/><circle cx="7.5" cy="17.5" r="1.5"/><circle cx="16.5" cy="17.5" r="1.5"/><path d="M5 12h14"/>'],
            ];
            @endphp
            @foreach($services as $s)
            <a href="{{ $s['href'] }}" class="group flex flex-col items-center gap-4 p-6 rounded-2xl text-center transition-all duration-300 hover:-translate-y-1" style="border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.03);text-decoration:none;" onmouseover="this.style.borderColor='rgba(245,197,24,0.50)';this.style.background='rgba(245,197,24,0.06)';" onmouseout="this.style.borderColor='rgba(255,255,255,0.08)';this.style.background='rgba(255,255,255,0.03)';">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110" style="border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.04);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(235,242,255,.55)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="transition-all duration-300">{!! $s['svg'] !!}</svg>
                </div>
                <span class="text-[10.5px] font-semibold uppercase tracking-wider transition-colors duration-200" style="color:rgba(235,242,255,.55);">{{ $s['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div><br><br>

    <div class="absolute bottom-0 left-0 right-0 h-px" style="background:linear-gradient(90deg,transparent,rgba(245,197,24,.25),transparent);"></div>
</section>

<section class="py-28 relative overflow-hidden bg-[var(--cream)] reveal reveal-up section-pad-mobile section-pad-tablet">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full pointer-events-none" style="background:radial-gradient(circle,rgba(26,64,128,.05) 0%,transparent 70%);"></div>

    <div class="max-w-[1200px] mx-auto px-6 section-px-mobile relative z-10">
        <div class="max-w-2xl mx-auto text-center">
            <div class="font-cinzel text-4xl mb-8 opacity-60" style="color:var(--gold);">✝</div>
            <div class="divider-ornament mb-6"><span class="eyebrow">Unite Your Prayers</span></div>
            <h2 class="font-heading text-4xl md:text-6xl font-bold italic leading-[1.05] mb-6" style="color:var(--blue-deep);">Demander une Intention de <br>Messe</h2>
            <p class="text-lg leading-relaxed mb-12 font-light" style="color:rgba(13,42,82,.55);">Unite your prayers with the Holy Sacrifice of the Mass. Submit your intention online and our staff will include it in the upcoming liturgy.</p>

            <div class="intention-btns flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('submit-intention') }}" class="gold-btn inline-flex items-center gap-2.5 px-10 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest" style="text-decoration:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                    Submit Intention
                </a>
                <a href="{{ route('track') }}" class="inline-flex items-center gap-2.5 px-10 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest border-2 transition-all duration-300 hover:-translate-y-0.5" style="border-color:rgba(26,64,128,.25);color:var(--blue-deep);background:transparent;text-decoration:none;" onmouseover="this.style.borderColor='var(--blue-mid)';this.style.background='rgba(26,64,128,0.05)';" onmouseout="this.style.borderColor='rgba(26,64,128,0.25)';this.style.background='transparent';">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Track Status
                </a>
            </div>

            <p class="mt-16 text-[10px] font-medium uppercase tracking-[0.4em]" style="color:rgba(13,42,82,.25);">Paroisse Saint-Joseph Artisan · Riviera Bonoumin, Abidjan, Côte d'Ivoire</p>
        </div>
    </div>
</section>

</x-public-layout>