<x-public-layout>
<x-slot name="meta">
    <meta name="description" content="Mass schedule at Paroisse Saint-Joseph Artisan – Riviera Bonoumin, Abidjan, Côte d'Ivoire. View weekly and special Mass times.">
    <style>
        .eyebrow {
            font-size: 10px; font-weight: 600;
            letter-spacing: 0.32em; text-transform: uppercase;
            color: var(--color-gold-dark);
        }
        .divider-ornament {
            display: flex; align-items: center; gap: 12px; justify-content: center;
        }
        .divider-ornament::before,
        .divider-ornament::after {
            content: ''; height: 1px; width: 48px;
            background: linear-gradient(90deg, transparent, rgba(245,197,24,0.5));
        }
        .divider-ornament::after {
            background: linear-gradient(90deg, rgba(245,197,24,0.5), transparent);
        }

        .animate-fade-up { animation: fadeUp 0.9s ease both; }
        .animate-fade-in { animation: fadeIn 1.2s ease both; }
        .delay-1 { animation-delay: 0.15s; }
        .delay-2 { animation-delay: 0.30s; }
        .delay-3 { animation-delay: 0.45s; }

        /* ── Page hero banner ── */
        .page-hero {
            background: linear-gradient(160deg, var(--blue-deep) 0%, var(--blue-mid) 60%, #0f3060 100%);
            position: relative; overflow: hidden;
        }
        .page-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse 70% 60% at 50% 0%, rgba(245,197,24,0.10) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ── Schedule cards redesigned ── */
        .card-schedule {
            display: flex;
            background: #FFFFFF;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(26,64,128,0.08);
            box-shadow: 0 4px 20px rgba(13,42,82,0.04);
            margin-bottom: 24px;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card-schedule:hover {
            box-shadow: 0 12px 40px rgba(13,42,82,0.12);
            transform: translateY(-2px);
            border-color: rgba(245,197,24,0.3);
        }

        .schedule-sidebar {
            width: 260px;
            background: linear-gradient(160deg, #0d2a52 0%, #153a70 100%);
            padding: 40px;
            display: flex;
            flex-direction: column;
            color: #FFFFFF;
            position: relative;
            flex-shrink: 0;
        }
        .schedule-sidebar::after {
            content: '';
            position: absolute; inset: 0;
            background: url("{{ \Illuminate\Support\Facades\Storage::disk('supabase')->url('assets/img/christ1.webp') }}") center center no-repeat;
            background-size: cover;
            opacity: 0.3;
            mix-blend-mode: overlay;
            pointer-events: none;
        }

        .schedule-content {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* ── Time chips ── */
        .time-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            background: #F8FAFF;
            border: 1px solid rgba(26,64,128,0.08);
            border-radius: 100px;
            font-size: 14px;
            font-weight: 600;
            color: var(--blue-deep);
            transition: all 0.2s ease;
        }
        .time-chip svg { color: rgba(13,42,82,0.3); }
        .time-chip:hover {
            background: #FFFFFF;
            border-color: var(--gold);
            box-shadow: 0 4px 12px rgba(245,197,24,0.15);
        }

        .schedule-divider {
            height: 1px;
            background: linear-gradient(90deg, rgba(26,64,128,0.05) 0%, rgba(26,64,128,0.02) 100%);
        }

        .schedule-desc-box {
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }
        .desc-icon {
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            color: var(--gold);
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .card-schedule { flex-direction: column; }
            .schedule-sidebar { width: 100%; padding: 32px; text-align: center; align-items: center; }
            .schedule-content { padding: 32px; }
        }

        html { scroll-behavior: smooth; }
    </style>
</x-slot>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- PAGE HERO                                          --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="page-hero py-24 md:py-32">

    {{-- Watermark cross --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none select-none font-cinzel leading-none"
         style="font-size:340px; color:rgba(255,255,255,0.018);" aria-hidden="true">✝</div>

    {{-- Top gold rule --}}
    <div class="absolute top-0 left-0 right-0 h-px"
         style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.35),transparent);"></div>

    <div class="max-w-[1200px] mx-auto px-6 relative z-10 text-center">

        {{-- Cross icon with rays --}}
        <div class="flex justify-center mb-6 animate-fade-in">
            <div class="relative flex items-center justify-center" style="width:52px; height:52px;">
                <svg width="52" height="52" viewBox="0 0 52 52" style="position:absolute;inset:0;" fill="none" aria-hidden="true">
                    <line x1="26" y1="2"  x2="26" y2="10" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="26" y1="42" x2="26" y2="50" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="2"  y1="26" x2="10" y2="26" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="42" y1="26" x2="50" y2="26" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="8"  y1="8"  x2="13" y2="13" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="39" y1="39" x2="44" y2="44" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="44" y1="8"  x2="39" y2="13" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="8"  y1="44" x2="13" y2="39" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <span class="font-cinzel relative z-10" style="color:var(--yellowish); font-size:1.5rem;" aria-hidden="true">✝</span>
            </div>
        </div>

        <div class="divider-ornament mb-4 animate-fade-in delay-1">
            <span class="eyebrow" style="color:rgba(253, 248, 172, 1);">Holy Eucharist</span>
        </div>

        <h1 class="font-heading animate-fade-up delay-1"
            style="font-size:clamp(2.8rem,6vw,5.5rem); font-weight:700; font-style:italic;
                   color:#FFFFFF; line-height:1.05; letter-spacing:-0.01em; margin-bottom:16px;
                   text-shadow:0 4px 32px rgba(0,0,0,0.45);">
            Mass Schedule
        </h1>

        <p class="animate-fade-up delay-2 font-heading"
           style="font-style:italic; color:rgba(253, 248, 172, 1); font-size:clamp(0.95rem,1.8vw,1.15rem);
                  font-weight:300; margin-bottom:0;">
            Riviera Bonoumin Cocody, Abidjan, Côte d'Ivoire
        </p>
    </div>

    {{-- Bottom gold rule --}}
    <div class="absolute bottom-0 left-0 right-0 h-px"
         style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.25),transparent);"></div>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- SCHEDULE CONTENT                                   --}}
{{-- ═══════════════════════════════════════════════════ --}}
<div x-data="{
    downloadICS(title, desc, start, end, loc, rrule = '') {
        let ics = [
            'BEGIN:VCALENDAR','VERSION:2.0','PRODID:-//Paroisse Saint-Joseph Artisan//EN',
            'BEGIN:VEVENT',
            'UID:' + Math.random().toString(36).substr(2,9) + '@storosario',
            'DTSTAMP:' + new Date().toISOString().replace(/[-:]/g,'').split('.')[0] + 'Z',
            'DTSTART:' + start, 'DTEND:' + end,
            'SUMMARY:' + title, 'DESCRIPTION:' + desc, 'LOCATION:' + loc
        ];
        if (rrule) ics.push('RRULE:' + rrule);
        ics.push('END:VEVENT','END:VCALENDAR');
        const blob = new Blob([ics.join('\r\n')], { type: 'text/calendar;charset=utf-8' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = title.replace(/\s+/g,'_') + '.ics';
        document.body.appendChild(a); a.click(); document.body.removeChild(a);
    }
}">

@php
    $order  = ['sunday','anticipated','weekday','saturday','special'];
    $labels = [
        'sunday'      => 'Sunday Masses',
        'anticipated' => 'Anticipated Sunday Masses',
        'weekday'     => 'Weekday Masses',
        'saturday'    => 'Saturday Masses',
        'special'     => 'Special Masses',
    ];
    $dayMap = [
        'Sunday'=>'SU','Monday'=>'MO','Tuesday'=>'TU','Wednesday'=>'WE',
        'Thursday'=>'TH','Friday'=>'FR','Saturday'=>'SA',
    ];
@endphp

<section class="py-20 max-w-[1100px] mx-auto px-6">

    @if(count($schedules) === 0)
    {{-- Empty state --}}
    <div class="reveal flex flex-col items-center justify-center py-28 text-center rounded-3xl"
         style="border:1.5px dashed rgba(26,64,128,0.14); background:rgba(235,242,255,0.35);">
        <div class="font-cinzel text-3xl mb-4 opacity-30" style="color:var(--blue-deep);">✝</div>
        <h3 class="font-heading font-bold italic text-2xl mb-2" style="color:var(--blue-deep);">No Schedules Available</h3>
        <p style="color:rgba(13,42,82,0.42); font-size:14px; max-width:380px; line-height:1.75;">
            No mass schedules posted at this time. Contact the parish office for inquiries.
        </p>
    </div>
    @endif

    @foreach($order as $type)
    @if(isset($schedules[$type]) && $schedules[$type]->count() > 0)

    <div class="reveal mb-20">


        {{-- Cards --}}
        <div class="space-y-5 p-6 md:p-8 rounded-2xl" style="background: #FDFBF5; border: 1px solid rgba(245, 197, 24, 0.25);">
        @foreach($schedules[$type] as $s)
        @php
            $times      = is_array($s->time) ? array_filter($s->time) : array_filter([$s->time]);
            $firstTime  = !empty($times[0]) ? \Carbon\Carbon::parse(is_array($times[0]) ? ($times[0]['time'] ?? '08:00') : $times[0])->format('Hi00') : '080000';
            $endTime    = !empty($times[0]) ? \Carbon\Carbon::parse(is_array($times[0]) ? ($times[0]['time'] ?? '08:00') : $times[0])->addHour()->format('Hi00') : '090000';
            $rrule      = '';
            $dtStart    = now()->format('Ymd').'T'.$firstTime;
            $dtEnd      = now()->format('Ymd').'T'.$endTime;

            if ($s->day_of_week) {
                $days    = is_array($s->day_of_week) ? $s->day_of_week : [$s->day_of_week];
                $rrule   = 'FREQ=WEEKLY;BYDAY='.implode(',', array_map(fn($d) => $dayMap[$d] ?? 'SU', $days));
                $next    = now();
                while (!in_array($next->format('l'), $days)) { $next->addDay(); }
                $dtStart = $next->format('Ymd').'T'.$firstTime;
                $dtEnd   = $next->format('Ymd').'T'.$endTime;
            } elseif ($s->specific_date) {
                $dtStart = $s->specific_date->format('Ymd').'T'.$firstTime;
                $dtEnd   = $s->specific_date->format('Ymd').'T'.$endTime;
            }

            $googleUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
                .'&text='.urlencode('Mass: '.($s->title ?? 'Sto Rosario'))
                .'&dates='.$dtStart.'/'.$dtEnd
                .'&location='.urlencode("Paroisse Saint-Joseph Artisan, Riviera Bonoumin Cocody, Abidjan, Côte d'Ivoire");
            if ($rrule) $googleUrl .= '&recur=RRULE:'.$rrule;

            // Image-specific mapping
            $icon = match($type) {
                'sunday' => '<path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/><path d="M3 12h1m8-9v1m8 8h1m-9 8v1m-6.4-15.4l.7.7m12.1-.7l-.7.7m0 11.4l.7.7m-12.1-.7l-.7.7"/>',
                'weekday' => '<path d="M12 3v18M3 12h18"/>',
                'saturday' => '<path d="M3 21h18M9 21V10a3 3 0 0 1 6 0v11M12 10V5m-2 0h4"/>',
                default => '<path d="M12 2v20M5 12h14"/>'
            };
            $subTitle = match($type) {
                'sunday' => 'The Lord\'s Day',
                'anticipated' => 'Anticipated Mass',
                'weekday' => 'Monday – Friday',
                'saturday' => 'Anticipated Mass',
                default => 'Special Liturgy'
            };
            $descText = match($type) {
                'sunday' => 'Main parish assembly for the community.',
                'weekday' => 'Start your day with prayer. All are welcome.',
                'saturday' => 'Anticipated Mass for Sunday obligation.',
                default => 'Special liturgical celebration.'
            };
            $descIcon = match($type) {
                'sunday' => '<path d="M9 7a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"/><path d="M3 21v-2a4 4 0 0 1 4 -4h10a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/>',
                'weekday' => '<path d="M7 20h10"/><path d="M6 6l6 -1l6 1"/><path d="M12 3v17"/>',
                'saturday' => '<path d="M8 22h8"/><path d="M12 11v11"/><path d="M5 3h14L18 9a6 6 0 0 1-12 0L5 3z"/><path d="M3 3h18"/>',
                default => '<circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/>'
            };
        @endphp

        <article class="card-schedule">
            {{-- Left: Sidebar --}}
            @php
                $todayName = \Carbon\Carbon::now('Asia/Manila')->format('l');
                $scheduleDays = is_array($s->day_of_week) ? $s->day_of_week : ($s->day_of_week ? [$s->day_of_week] : []);
                $isToday = in_array($todayName, $scheduleDays) || $type === 'weekday' && in_array($todayName, ['Monday','Tuesday','Wednesday','Thursday','Friday']);
            @endphp
            <div class="schedule-sidebar" @if($isToday) style="border-left:4px solid var(--gold);" @endif>
                @if($isToday)
                    <div style="position:absolute;top:16px;right:16px;background:var(--gold);color:#0D2A52;font-size:9px;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;padding:4px 10px;border-radius:4px;z-index:1;">Today</div>
                @endif
                <div class="mb-6 opacity-80" style="color:var(--gold);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">{!! $icon !!}</svg>
                </div>
                <h3 class="font-heading font-bold italic" style="font-size:2.2rem; color:#FFFFFF; line-height:1.1;">
                    {{ $type === 'weekday' ? 'Weekdays' : (is_array($s->day_of_week) ? implode(', ', $s->day_of_week) : ($s->day_of_week ?: $s->title)) }}
                </h3>
                <p class="font-heading italic mt-1" style="color:var(--gold); font-size:1rem; opacity:0.9;">
                    {{ $subTitle }}
                </p>
            </div>

            {{-- Right: Content --}}
            <div class="schedule-content">
                {{-- Time chips row --}}
                <div class="flex flex-wrap gap-3">
                    @foreach($times as $t)
                    @php
                        $timeFormat = app()->getLocale() === 'fr' ? 'H\hi' : 'g:i A';
                        $label = is_array($t) ? \Carbon\Carbon::parse($t['time'] ?? '00:00')->format($timeFormat) : \Carbon\Carbon::parse($t)->format($timeFormat);
                    @endphp
                    <div class="time-chip">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        {{ $label }}
                    </div>
                    @endforeach
                </div>

                <div class="schedule-divider"></div>

                {{-- Description area --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="schedule-desc-box">
                        <div class="desc-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $descIcon !!}</svg>
                        </div>
                        <div>
                            <p class="font-bold text-primary text-sm">{{ $s->title ?? ($type === 'sunday' ? 'Sunday Celebration' : 'Mass Celebration') }}</p>
                            <p class="text-xs text-muted-foreground mt-1">{{ $descText }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ $googleUrl }}" target="_blank" class="p-2 rounded-full border border-border text-muted-foreground hover:border-primary hover:text-primary transition-all" title="Add to Google">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M12 14v4M10 16h4"/></svg>
                        </a>
                        <button @click="downloadICS('Mass: {{ addslashes($s->title ?? 'Sto Rosario') }}', 'Regular mass schedule', '{{ $dtStart }}','{{ $dtEnd }}', 'Paroisse Saint-Joseph Artisan', '{{ $rrule }}')" 
                                class="p-2 rounded-full border border-border text-muted-foreground hover:border-primary hover:text-primary transition-all" title="Download iCal">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </article>
        @endforeach
        </div>
    </div>

    @endif
    @endforeach

    {{-- ── Special Masses Note ── --}}
    <div class="reveal mt-4 rounded-2xl flex items-start gap-5 p-6 md:p-8"
         style="background:#FFFFFF; border:1px solid rgba(245,197,24,0.28);
                box-shadow:0 4px 24px rgba(13,42,82,0.06);">
        <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center"
             style="background:rgba(245,197,24,0.10); border:1px solid rgba(245,197,24,0.28);">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#C9A200"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
            </svg>
        </div>
        <div>
            <p class="eyebrow mb-2">Note for Special Masses</p>
            <p style="font-size:13px; color:rgba(13,42,82,0.52); line-height:1.8;">
                <span>Mass times for holy days of obligation, feast days, and other special occasions may vary.</span>
                <span>Follow our announcements or social media pages for the most up-to-date information.</span>
            </p>
            <a href="https://www.facebook.com/saintjosephartisanrivierabonoumin?locale=fr_FR" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 mt-3 px-5 py-2 rounded-full text-[11px] font-bold uppercase tracking-widest transition-all hover:-translate-y-0.5"
               style="border:1px solid rgba(245,197,24,0.35); color:var(--blue-deep); background:rgba(245,197,24,0.06); text-decoration:none;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Follow us on Facebook
            </a>
        </div>
    </div>

</section>

{{-- /x-data --}}



</x-public-layout>