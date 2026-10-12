<x-public-layout>
<x-slot name="meta">
    <meta name="description" content="Stay updated with the latest events and celebrations at Paroisse Saint-Joseph Artisan. Join our community activities and spiritual gatherings.">
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

        @keyframes fadeUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        .animate-fade-up { animation: fadeUp 0.9s ease both; }
        .animate-fade-in { animation: fadeIn 1.2s ease both; }
        .delay-1 { animation-delay: 0.15s; }
        .delay-2 { animation-delay: 0.30s; }



        /* ── Hero ── */
        .page-hero {
            background: linear-gradient(160deg, var(--blue-deep) 0%, var(--blue-mid) 60%, #0f3060 100%);
            position: relative; overflow: hidden;
        }
        .page-hero::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse 70% 60% at 50% 0%, rgba(245,197,24,0.10) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ── Event card ── */
        .event-card {
            background: #FFF;
            border: 1px solid rgba(26,64,128,0.12);
            border-radius: 24px;
            padding: 32px;
            transition: all 0.35s cubic-bezier(0.22,1,0.36,1);
        }
        .event-card:hover {
            border-color: rgba(245,197,24,0.50);
            box-shadow: 0 16px 52px rgba(13,42,82,0.12);
            transform: translateY(-4px);
        }

        /* ── Date badge ── */
        .date-badge {
            flex-shrink: 0;
            width: 88px; height: 88px;
            border-radius: 18px;
            background: var(--color-blue-pale);
            border: 1px solid rgba(26,64,128,0.14);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            transition: all 0.3s ease;
        }
        .event-card:hover .date-badge {
            background: rgba(245,197,24,0.10);
            border-color: rgba(245,197,24,0.40);
        }
        .date-badge .month {
            font-size: 9px; font-weight: 700;
            letter-spacing: 0.22em; text-transform: uppercase;
            color: rgba(13,42,82,0.45);
            transition: color 0.3s;
        }
        .date-badge .day {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 2.6rem; font-weight: 700; font-style: italic;
            line-height: 1; color: var(--color-blue-deep);
            transition: color 0.3s;
        }
        .date-badge .year {
            font-size: 9px; font-weight: 600;
            color: rgba(13,42,82,0.30);
        }
        .event-card:hover .date-badge .month { color: #8a6800; }
        .event-card:hover .date-badge .day   { color: #7a5800; }

        /* ── Cal links ── */
        .cal-link {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 9px; font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase;
            color: rgba(13,42,82,0.38); text-decoration: none;
            padding: 5px 12px; border-radius: 100px;
            border: 1px solid rgba(13,42,82,0.10);
            background: transparent;
            transition: all 0.2s ease; cursor: pointer;
        }
        .cal-link:hover {
            border-color: rgba(245,197,24,0.55);
            color: #8a6800;
            background: rgba(245,197,24,0.06);
        }

        /* ── Time slot pill ── */
        .time-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--color-blue-pale);
            border: 1px solid rgba(26,64,128,0.14);
            border-radius: 100px;
            padding: 5px 14px;
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 1rem; font-weight: 700; font-style: italic;
            color: var(--color-blue-deep);
            transition: all 0.2s ease;
        }
        .event-card:hover .time-pill {
            background: rgba(245,197,24,0.08);
            border-color: rgba(245,197,24,0.38);
            color: #7a5800;
        }

        /* ── Gold btn (CTA section) ── */
        .gold-btn {
            background: linear-gradient(135deg, #FFD740 0%, #F5C518 55%, #E0A800 100%);
            color: var(--color-blue-deep);
            box-shadow: 0 4px 20px rgba(245,197,24,0.40), 0 1px 0 rgba(255,255,255,0.35) inset;
            transition: all 0.25s ease; font-weight: 700;
        }
        .gold-btn:hover {
            box-shadow: 0 8px 32px rgba(245,197,24,0.55), 0 1px 0 rgba(255,255,255,0.35) inset;
            transform: translateY(-2px);
        }

        html { scroll-behavior: smooth; }
    </style>
</x-slot>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- PAGE HERO                                          --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="page-hero py-24 md:py-32">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none select-none font-cinzel leading-none"
         style="font-size:340px; color:rgba(255,255,255,0.018);" aria-hidden="true">✝</div>
    <div class="absolute top-0 left-0 right-0 h-px"
         style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.35),transparent);"></div>

    <div class="max-w-[1200px] mx-auto px-6 relative z-10 text-center">
        <div class="flex justify-center mb-6 animate-fade-in">
            <div class="relative flex items-center justify-center" style="width:52px;height:52px;">
                <svg width="52" height="52" viewBox="0 0 52 52" style="position:absolute;inset:0;" fill="none" aria-hidden="true">
                    <line x1="26" y1="2"  x2="26" y2="10" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="26" y1="42" x2="26" y2="50" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="2"  y1="26" x2="10" y2="26" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="42" y1="26" x2="50" y2="26" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="8"  y1="8"  x2="13" y2="13" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="39" y1="39" x2="44" y2="44" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="44" y1="8"  x2="39" y2="13" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="8"  y1="44" x2="13" y2="39" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <span class="font-cinzel relative z-10" style="color:var(--color-gold);font-size:1.5rem;" aria-hidden="true">✝</span>
            </div>
        </div>

        <div class="divider-ornament mb-4 animate-fade-in delay-1">
            <span class="eyebrow" style="color:rgba(245,197,24,0.70);">Community & Liturgy</span>
        </div>

        <h1 class="font-heading animate-fade-up delay-1"
            style="font-size:clamp(2.8rem,6vw,5.5rem); font-weight:700; font-style:italic;
                   color:#FFF; line-height:1.05; letter-spacing:-0.01em; margin-bottom:16px;
                   text-shadow:0 4px 32px rgba(0,0,0,0.45);">
            Parish Events
        </h1>

        <p class="font-heading animate-fade-up delay-2"
           style="font-style:italic; color:rgba(245,197,24,0.75);
                  font-size:clamp(0.9rem,1.8vw,1.1rem); font-weight:300;">
            Join our community activities and spiritual gatherings
        </p>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-px"
         style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.25),transparent);"></div>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- EVENT LIST                                         --}}
{{-- ═══════════════════════════════════════════════════ --}}
<script>window.__events = @json($eventsJson); window.__view = @json($view); window.__calMonth = {{ (int) $month }}; window.__calYear = {{ (int) $year }};</script>
<div x-data="{
    viewMode: window.__view,
    selectedEvent: null,
    calMonth: window.__calMonth,
    calYear: window.__calYear,
    events: window.__events,
    get calendarEvents() {
        return this.events.filter(e => {
            // Check main event date
            const d = new Date(e.event_date + 'T00:00:00');
            if (d.getMonth() + 1 === this.calMonth && d.getFullYear() === this.calYear) return true;
            // Check service time dates and date ranges
            if (e.event_time && Array.isArray(e.event_time)) {
                for (const slot of e.event_time) {
                    if (slot.date) {
                        const sd = new Date(slot.date + 'T00:00:00');
                        if (sd.getMonth() + 1 === this.calMonth && sd.getFullYear() === this.calYear) return true;
                    }
                    if (slot.date_to) {
                        const ed = new Date(slot.date_to + 'T00:00:00');
                        if (ed.getMonth() + 1 === this.calMonth && ed.getFullYear() === this.calYear) return true;
                    }
                }
            }
            return false;
        });
    },
    get monthName() {
        return new Date(this.calYear, this.calMonth - 1, 1)
            .toLocaleDateString(document.documentElement.lang, { month: 'long', year: 'numeric' });
    },
    get daysInMonth() {
        return new Date(this.calYear, this.calMonth, 0).getDate();
    },
    get firstDayOfWeek() {
        return new Date(this.calYear, this.calMonth - 1, 1).getDay();
    },
    prevMonth() {
        if (this.calMonth === 1) { this.calMonth = 12; this.calYear--; }
        else { this.calMonth--; }
    },
    nextMonth() {
        if (this.calMonth === 12) { this.calMonth = 1; this.calYear++; }
        else { this.calMonth++; }
    },
    eventsForDate(dateStr) {
        return this.calendarEvents.filter(e => {
            // Check main event date
            if (e.event_date === dateStr) return true;
            // Check service time dates and date ranges
            if (e.event_time && Array.isArray(e.event_time)) {
                for (const slot of e.event_time) {
                    if (slot.date && slot.date === dateStr) return true;
                    if (slot.date && slot.date_to && dateStr >= slot.date && dateStr <= slot.date_to) return true;
                }
            }
            return false;
        });
    },
    downloadICS(title, desc, start, end, loc) {
        const ics = [
            'BEGIN:VCALENDAR','VERSION:2.0','PRODID:-//Paroisse Saint-Joseph Artisan//EN',
            'BEGIN:VEVENT',
            'UID:' + Math.random().toString(36).substr(2,9) + '@storosario',
            'DTSTAMP:' + new Date().toISOString().replace(/[-:]/g,'').split('.')[0] + 'Z',
            'DTSTART:' + start, 'DTEND:' + end,
            'SUMMARY:' + title, 'DESCRIPTION:' + desc, 'LOCATION:' + loc,
            'END:VEVENT','END:VCALENDAR'
        ].join('\r\n');
        const a = Object.assign(document.createElement('a'), {
            href: URL.createObjectURL(new Blob([ics], { type: 'text/calendar;charset=utf-8' })),
            download: title.replace(/\s+/g,'_') + '.ics'
        });
        document.body.appendChild(a); a.click(); document.body.removeChild(a);
    }
}">

{{-- View Toggle Tabs --}}
<div class="max-w-[900px] mx-auto px-6 pt-14 pb-2">
    <div class="flex items-center gap-2 bg-white rounded-2xl p-1.5 border shadow-sm w-fit mx-auto">
        <button @click="viewMode = 'list'"
                class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                :class="viewMode === 'list' ? 'bg-primary text-white shadow-md' : 'text-muted-foreground hover:text-primary hover:bg-muted/30'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1.5 -mt-0.5"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            List View
        </button>
        <button @click="viewMode = 'calendar'"
                class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                :class="viewMode === 'calendar' ? 'bg-primary text-white shadow-md' : 'text-muted-foreground hover:text-primary hover:bg-muted/30'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1.5 -mt-0.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Calendar View
        </button>
    </div>
</div>

{{-- ── LIST VIEW ── --}}
<section x-show="viewMode === 'list'" class="py-16 max-w-[900px] mx-auto px-6 space-y-6">

    @forelse($events as $event)
    @php
        $startTime = !empty($event->event_time[0]['time'])
            ? \Carbon\Carbon::parse($event->event_time[0]['time'])->format('Hi00')
            : '080000';
        $endTime = !empty($event->event_time[0]['time'])
            ? \Carbon\Carbon::parse($event->event_time[0]['time'])->addHour()->format('Hi00')
            : '090000';
        $dtStart   = $event->event_date->format('Ymd').'T'.$startTime;
        $dtEnd     = $event->event_date->format('Ymd').'T'.$endTime;
        $googleUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
            .'&text='.urlencode($event->title)
            .'&dates='.$dtStart.'/'.$dtEnd
            .'&details='.urlencode($event->description ?? '');
    @endphp

    <article id="event-{{ $event->id }}"
             class="event-card reveal scroll-mt-24">
        <div class="flex flex-col md:flex-row gap-8 md:gap-10 items-start">

            {{-- Date badge --}}
            <div class="date-badge">
                <span class="month">{{ $event->event_date->format('M') }}</span>
                <span class="day">{{ $event->event_date->format('d') }}</span>
                <span class="year">{{ $event->event_date->format('Y') }}</span>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0 flex flex-col gap-5">

                {{-- Title --}}
                <div>
                    <a href="{{ route('events.show', $event) }}" style="text-decoration:none;">
                        <h3 class="font-heading font-bold italic"
                            style="font-size:clamp(1.6rem,3vw,2.2rem); color:var(--blue-deep);
                                   line-height:1.15; transition:color 0.25s ease;"
                            onmouseover="this.style.color='#7a5800';"
                            onmouseout="this.style.color='var(--blue-deep)';">
                            {{ $event->title }}
                        </h3>
                    </a>
                </div>

                {{-- Cal actions --}}
                <div class="flex flex-wrap items-center gap-3 mt-1 mb-2">
                    <a href="{{ $googleUrl }}" target="_blank" rel="noopener" class="cal-link">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M12 14v4M10 16h4"/></svg>
                        Google Cal
                    </a>
                    <button class="cal-link"
                            @click="downloadICS(
                                '{{ addslashes($event->title) }}',
                                '{{ addslashes($event->description ?? '') }}',
                                '{{ $dtStart }}',
                                '{{ $dtEnd }}',
                                ''
                            )">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        iCal
                    </button>


                </div>

                {{-- Description --}}
                @if($event->description)
                <p style="font-size:14px; color:rgba(13,42,82,0.52); line-height:1.85;">
                    {{ $event->description }}
                </p>
                @endif

                {{-- Time slots --}}
                @if(!empty($event->event_time))
                @php
                    $groupedSlots = collect($event->event_time)
                        ->filter(fn($s) => !empty($s['time']) || !empty($s['title']))
                        ->groupBy(fn($s) => $s['title'] ?? '');
                @endphp
                <div style="padding-top:24px; border-top:1px solid rgba(26,64,128,0.08);">
                    <div class="flex flex-wrap gap-2 items-center">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                             stroke="rgba(13,42,82,0.35)" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             style="flex-shrink:0;" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        @foreach($groupedSlots as $title => $slots)
                        <div class="time-pill">
                            @if(!empty($title))
                                <span style="font-size:9px; font-weight:700; letter-spacing:0.18em;
                                             text-transform:uppercase; color:rgba(13,42,82,0.45);
                                             font-family:'Jost',sans-serif; font-style:normal;">
                                    {{ $title }}
                                </span>
                                <span style="opacity:0.30;">·</span>
                            @endif
                            @foreach($slots as $slot)
                                @if(!empty($slot['date']))
                                    <span style="font-size:10px; font-weight:600; color:rgba(13,42,82,0.40); font-family:'Jost',sans-serif; font-style:normal;">
                                        {{ \Carbon\Carbon::parse($slot['date'])->format('M d') }}
                                        @if(!empty($slot['date_to'])) – {{ \Carbon\Carbon::parse($slot['date_to'])->format('M d') }}@endif
                                    </span>
                                @endif
                                @if(!empty($slot['time']))
                                    @if(!empty($slot['date']))<span style="opacity:0.30;">·</span>@endif
                                    {{ \Carbon\Carbon::parse($slot['time'])->format('g:i A') }}
                                @endif
                                @if(!$loop->last)<span style="opacity:0.30;">·</span>@endif
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </article>

    @empty

    {{-- Empty state --}}
    <div class="reveal flex flex-col items-center justify-center py-28 text-center rounded-3xl"
         style="border:1.5px dashed rgba(26,64,128,0.14); background:rgba(235,242,255,0.35);">
        <div class="font-cinzel text-5xl mb-5 opacity-20" style="color:var(--blue-deep);">✝</div>
        <h3 class="font-heading font-bold italic text-2xl mb-2" style="color:var(--blue-deep);">
            No Upcoming Events
        </h3>
        <p style="font-size:13.5px; color:rgba(13,42,82,0.42); max-width:340px; line-height:1.80; font-style:italic;">
            We are currently planning more community activities. Check back soon or follow our announcements!
        </p>
    </div>

    @endforelse
</section>

{{-- ── CALENDAR VIEW ── --}}
<section x-show="viewMode === 'calendar'" x-cloak class="py-16 max-w-[900px] mx-auto px-6">
    <div class="bg-card border rounded-[2rem] md:rounded-[3rem] shadow-2xl overflow-x-auto md:overflow-hidden">
        <div class="min-w-[700px] md:min-w-full font-serif">
            {{-- Calendar Header --}}
            <div class="grid grid-cols-7 border-b bg-primary">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="py-6 text-center text-[10px] font-black uppercase tracking-[0.3em] text-white/60">
                        <span class="hidden sm:inline">{{ $day }}</span>
                        <span class="sm:hidden">{{ substr($day, 0, 1) }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Month Nav --}}
            <div class="flex items-center justify-between px-6 py-4 bg-muted/5 border-b">
                <button @click="prevMonth()" class="p-3 hover:bg-white rounded-xl transition-all text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <span class="font-black uppercase text-sm tracking-widest text-primary" x-text="monthName"></span>
                <button @click="nextMonth()" class="p-3 hover:bg-white rounded-xl transition-all text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>

            {{-- Calendar Grid --}}
            <div class="grid grid-cols-7 bg-muted/10">
                <template x-for="i in firstDayOfWeek" :key="'empty-' + i">
                    <div class="aspect-square border-r border-b bg-muted/5"></div>
                </template>
                <template x-for="day in daysInMonth" :key="'day-' + day">
                    <div class="aspect-square border-r border-b p-4 relative group hover:bg-white transition-colors">
                        <span class="text-sm font-black text-primary"
                              x-text="day"></span>
                        <div class="mt-2 space-y-1">
                            <template x-for="ev in eventsForDate(calYear + '-' + String(calMonth).padStart(2,'0') + '-' + String(day).padStart(2,'0'))" :key="ev.id">
                                <div class="px-2 py-1 rounded-md bg-primary/5 border-l-2 border-primary text-[10px] font-bold text-primary truncate hover:whitespace-normal hover:bg-primary hover:text-white transition-all cursor-pointer"
                                     @click="selectedEvent = ev">
                                    <span x-text="ev.title"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Calendar legend --}}
    <div class="mt-8 p-6 bg-accent/5 rounded-[2.5rem] border border-accent/10 flex items-center gap-6 italic">
        <div class="h-12 w-12 rounded-2xl bg-accent flex items-center justify-center text-white shrink-0 shadow-lg shadow-accent/20">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </div>
        <p class="text-sm text-primary font-medium leading-relaxed">
            <span class="font-black uppercase tracking-widest mr-2">Note:</span> 
            All events are subject to change. Please follow our social media pages for real-time updates and live stream announcements.
        </p>
    </div>
</section>

{{-- Event Details Modal --}}
<template x-teleport="body">
    <div x-show="selectedEvent" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition duration-300 ease-out"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition duration-200 ease-in"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="selectedEvent = null"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl max-w-lg w-full p-8"
             x-transition:enter="transition duration-300 ease-out"
             x-transition:enter-start="scale-95 opacity-0 translate-y-4"
             x-transition:enter-end="scale-100 opacity-100 translate-y-0"
             x-transition:leave="transition duration-200 ease-in"
             x-transition:leave-start="scale-100 opacity-100 translate-y-0"
             x-transition:leave-end="scale-95 opacity-0 translate-y-4">
            <button @click="selectedEvent = null" class="absolute top-4 right-4 p-2 rounded-xl hover:bg-muted/30 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
            <div class="space-y-4">
                <div>
                    <h3 class="font-heading text-2xl font-black text-primary" x-text="selectedEvent?.title"></h3>
                    <div class="h-1 w-16 bg-accent mt-3 rounded-full"></div>
                </div>
                <div class="flex items-center gap-4 text-sm text-primary/60">
                    <span class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span x-text="selectedEvent?.event_date ? new Date(selectedEvent.event_date + 'T00:00:00').toLocaleDateString(document.documentElement.lang, { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }) : ''"></span>
                    </span>
                </div>
                <template x-if="selectedEvent?.event_time && selectedEvent.event_time.length > 0">
                    <div class="flex flex-wrap gap-2" x-data="{
                        get groupedSlots() {
                            if (!selectedEvent?.event_time) return [];
                            const groups = {};
                            selectedEvent.event_time.forEach(s => {
                                const key = s.title || '';
                                if (!groups[key]) groups[key] = [];
                                groups[key].push(s);
                            });
                            return Object.entries(groups);
                        }
                    }">
                        <template x-for="(group, gi) in groupedSlots" :key="gi">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-primary/5 border text-[11px] font-bold text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span x-show="group[0]" x-text="group[0] + ' \u00b7 '" class="text-[10px] uppercase font-black tracking-wider text-primary/60"></span>
                                <template x-for="(slot, si) in group[1]" :key="si">
                                    <span>
                                        <span x-show="slot.date" x-text="slot.date ? new Date(slot.date + 'T00:00:00').toLocaleDateString(document.documentElement.lang, { month: 'short', day: 'numeric' }) + (slot.date_to ? ' \u2013 ' + new Date(slot.date_to + 'T00:00:00').toLocaleDateString(document.documentElement.lang, { month: 'short', day: 'numeric' }) : '') : ''"></span>
                                        <span x-show="slot.date && slot.time" style="opacity:0.30;">\u00b7</span>
                                        <span x-text="slot.time ? new Date('2000-01-01T' + slot.time).toLocaleTimeString(document.documentElement.lang, { hour: 'numeric', minute: '2-digit' }) : ''"></span>
                                        <span x-show="si < group[1].length - 1" style="opacity:0.30;">\u00b7</span>
                                    </span>
                                </template>
                            </span>
                        </template>
                    </div>
                </template>
                <p class="text-primary/70 leading-relaxed" x-text="selectedEvent?.description || 'No description available.'"></p>
                <template x-if="selectedEvent?.url">
                    <a :href="selectedEvent.url" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-primary/90 transition-all">
                        View Details
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M7 7h10v10"/><path d="M7 17 21 3"/></svg>
                    </a>
                </template>
            </div>
        </div>
    </div>
</template>

</div>{{-- /x-data --}}


{{-- ═══════════════════════════════════════════════════ --}}
{{-- ANNOUNCEMENTS CTA                                  --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-24 relative overflow-hidden" style="background:var(--blue-deep);">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none select-none font-cinzel leading-none"
         style="font-size:380px; color:rgba(255,255,255,0.018);" aria-hidden="true">✝</div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 pointer-events-none"
         style="width:500px;height:260px;background:radial-gradient(ellipse,rgba(245,197,24,0.08) 0%,transparent 70%);"></div>
    <div class="absolute top-0 left-0 right-0 h-px"
         style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.4),transparent);"></div>

    <div class="max-w-2xl mx-auto px-6 text-center relative z-10">
        <div class="font-cinzel text-3xl mb-6 opacity-55" style="color:var(--gold);">✝</div>
        <div class="divider-ornament mb-5">
            <span class="eyebrow" style="color:rgba(245,197,24,0.65);">Stay Informed</span>
        </div>
        <h2 class="font-heading font-bold italic mb-5"
            style="font-size:clamp(2.2rem,5vw,3.8rem); color:#EBF2FF; line-height:1.1;">
            Don't Miss an<br>Announcement
        </h2>
        <p class="mb-10 font-light" style="color:rgba(235,242,255,0.45); font-size:15px; line-height:1.8;">
            Stay connected with the parish. View the latest news, novenas, and community updates.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="https://www.facebook.com/saintjosephartisanrivierabonoumin?locale=fr_FR" target="_blank" rel="noopener noreferrer"
               class="gold-btn inline-flex items-center gap-2.5 px-10 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest"
               style="text-decoration:none;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                View Announcements
            </a>
            <a href="{{ route('inquiry') }}"
               class="inline-flex items-center gap-2.5 px-10 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest border transition-all duration-300 hover:-translate-y-0.5"
               style="border-color:rgba(245,197,24,0.35); color:rgba(235,242,255,0.80);
                      background:rgba(255,255,255,0.04); backdrop-filter:blur(6px); text-decoration:none;"
               onmouseover="this.style.borderColor='var(--gold-light)'; this.style.color='var(--gold-light)'; this.style.background='rgba(245,197,24,0.10)';"
               onmouseout="this.style.borderColor='rgba(245,197,24,0.35)'; this.style.color='rgba(235,242,255,0.80)'; this.style.background='rgba(255,255,255,0.04)';">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                Parish Inquiry
            </a>
        </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-px"
         style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.25),transparent);"></div>
</section>



</x-public-layout>