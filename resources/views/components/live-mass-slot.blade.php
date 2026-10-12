@props(['nextMass' => null])

@php
    $isFrench = app()->getLocale() === 'fr';
    $now = \Carbon\Carbon::now('Asia/Manila');
    $isSunday = $now->dayOfWeek === \Carbon\Carbon::SUNDAY;
    $liveStart = $now->copy()->setTime(9, 55);
    $liveEnd = $now->copy()->setTime(11, 30);
    $isManualLive = \Illuminate\Support\Facades\Cache::get('manual_live_override', false);
    $isLiveWindow = $isManualLive || ($isSunday && $now->gte($liveStart) && $now->lte($liveEnd));
    $facebookLivePermalink = \Illuminate\Support\Facades\Cache::get(
        \App\Http\Controllers\FacebookLiveWebhookController::CACHE_KEY
    );
    $livePlatform = \Illuminate\Support\Facades\Cache::get('live_mass_platform', 'both');
@endphp

<section id="live-mass" class="max-w-5xl mx-auto px-6 mt-48 reveal reveal-up section-px-mobile">
@if($isLiveWindow)
    {{-- LIVE STATE --}}
    <div x-data="{ activeTab: '{{ $livePlatform === 'both' ? 'youtube' : $livePlatform }}' }" class="rounded-3xl overflow-hidden" style="background:#0d2a52;border:1px solid rgba(201,162,0,.22);box-shadow:0 12px 50px rgba(13,42,82,.09);">
        {{-- Header --}}
        <div class="px-6 md:px-10 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="h-2.5 w-2.5 rounded-full bg-red-500 animate-pulse shadow-lg shadow-red-500/40"></span>
                <span class="font-cinzel font-semibold text-xs tracking-[.25em] uppercase text-white/80">{{ $isFrench ? 'Messe en direct' : 'Live Mass' }}</span>
            </div>
            @if($livePlatform === 'both')
            <div class="flex items-center gap-1 bg-white/10 rounded-lg p-0.5 border border-white/10">
                <button @click="activeTab = 'youtube'" class="px-3 py-1 text-[10px] font-bold rounded-md transition-all uppercase"
                    :class="activeTab === 'youtube' ? 'bg-white shadow-sm text-primary' : 'text-white/60 hover:text-white'">YouTube</button>
                <button @click="activeTab = 'facebook'" class="px-3 py-1 text-[10px] font-bold rounded-md transition-all uppercase"
                    :class="activeTab === 'facebook' ? 'bg-white shadow-sm text-primary' : 'text-white/60 hover:text-white'">Facebook</button>
            </div>
            @else
            <span class="px-3 py-1 text-[10px] font-bold rounded-md uppercase text-white/70 tracking-[.15em]">{{ $livePlatform === 'youtube' ? 'YouTube' : 'Facebook' }}</span>
            @endif
        </div>
        {{-- Embed --}}
        <div class="aspect-video bg-black">
            @if($livePlatform === 'youtube' || $livePlatform === 'both')
            <template x-if="activeTab === 'youtube'">
                <iframe src="https://www.youtube-nocookie.com/embed/live_stream?channel={{ config('services.parish.youtube_channel_id') }}&autoplay=1&mute=1"
                    class="w-full h-full" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen loading="lazy"></iframe>
            </template>
            @endif
            @if($livePlatform === 'facebook' || $livePlatform === 'both')
            <template x-if="activeTab === 'facebook'">
                <div class="w-full h-full" style="min-height:100%;">
                    @if($facebookLivePermalink)
                        <iframe src="https://www.facebook.com/plugins/video.php?href={{ urlencode($facebookLivePermalink) }}&show_text=false&autoplay=true&dnt=1"
                            class="w-full h-full" style="border:none;overflow:hidden;" scrolling="no" frameborder="0"
                            allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                            allowfullscreen loading="lazy"></iframe>
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-center px-6" x-show="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                            <p class="text-white/50 text-sm font-medium mt-3 mb-4">{{ $isFrench ? 'Suivez la messe en direct sur notre page Facebook' : 'Watch live on our Facebook page' }}</p>
                            <a href="{{ config('services.parish.facebook_page_url') }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all hover:scale-105 active:scale-95"
                               style="background:#1877F2;color:#fff;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                {{ $isFrench ? 'Ouvrir Facebook' : 'Open Facebook' }}
                            </a>
                        </div>
                    @endif
                </div>
            </template>
            @endif
        </div>
    </div>
    @else
        {{-- NOT-LIVE STATE --}}
        <div class="rounded-3xl overflow-hidden" style="background:#fff;border:1px solid rgba(201,162,0,.22);box-shadow:0 12px 50px rgba(13,42,82,.09);">
            <div class="relative overflow-hidden" style="min-height:215px;background:#0d2a52;">
                <div class="absolute inset-0">
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('supabase')->url('assets/img/mass.webp') }}" alt="Mass" loading="lazy" width="1200" height="800" style="width:100%;height:100%;object-fit:cover;opacity:.35;">
                    <div class="absolute inset-0" style="background:linear-gradient(90deg,#0d2a52 0%,rgba(13,42,82,.4) 50%,#0d2a52 100%);"></div>
                </div>

                <div class="next-mass-inner relative z-10 flex flex-col md:flex-row items-center gap-6 px-6 md:px-10 py-8 flex-1" style="min-height:215px;">
                    <div class="relative shrink-0 flex items-center justify-center" style="width:82px;height:82px;">
                        <svg width="82" height="82" viewBox="0 0 82 82" style="position:absolute;inset:0;" fill="none" aria-hidden="true">
                            <line x1="41" y1="3" x2="41" y2="13" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/><line x1="41" y1="69" x2="41" y2="79" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/><line x1="3" y1="41" x2="13" y2="41" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/><line x1="69" y1="41" x2="79" y2="41" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/><line x1="11" y1="11" x2="18" y2="18" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/><line x1="64" y1="64" x2="71" y2="71" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/><line x1="71" y1="11" x2="64" y2="18" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/><line x1="11" y1="71" x2="18" y2="64" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <div class="rounded-full flex items-center justify-center" style="width:62px;height:62px;background:rgba(245,197,24,.1);border:1.5px solid rgba(245,197,24,.4);">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#F5C518" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 22h8"/><path d="M12 11v11"/><path d="M5 3h14L18 9a6 6 0 0 1-12 0L5 3z"/><path d="M3 3h18"/></svg>
                        </div>
                    </div>

                    <div>
                        <div class="eyebrow-row flex items-center gap-3 mb-2">
                            <span style="display:inline-block;height:1px;width:32px;background:linear-gradient(90deg,transparent,rgba(245,197,24,.6));"></span>
                            <span class="eyebrow" style="color:rgba(235,242,255,.7);">{{ $isFrench ? 'PROCHAINE MESSE' : 'NEXT MASS' }}</span>
                            <span style="display:inline-block;height:1px;width:32px;background:linear-gradient(90deg,rgba(245,197,24,.6),transparent);"></span>
                        </div>
                        @if($nextMass)
                        <h2 class="font-heading font-bold italic leading-none" style="font-size:clamp(2.2rem,4vw,3.6rem);color:#fff;letter-spacing:-.01em;">{{ $nextMass->calculated_day }}</h2>
                        <p class="font-heading font-bold italic" style="font-size:clamp(1.8rem,3.5vw,3rem);color:#F5C518;line-height:1.1;">{{ $nextMass->calculated_time }}</p>
                        <p style="font-size:12px;letter-spacing:.22em;text-transform:uppercase;color:rgba(235,242,255,.5);margin-top:6px;">{{ mb_strtoupper($nextMass->title ?? ($nextMass->mass_type === 'sunday' ? ($isFrench ? 'Messe du dimanche' : 'Sunday Mass') : ($isFrench ? 'Messe en semaine' : 'Weekday Mass')), 'UTF-8') }}</p>
                        @else
                        <h2 class="font-heading font-bold italic leading-none" style="font-size:2rem;color:#fff;">{{ $isFrench ? 'Aucune messe à venir' : 'No upcoming Mass' }}</h2>
                        @endif
                    </div>
                </div>
            </div>

            <div style="height:1px;background:linear-gradient(90deg,transparent,rgba(201,162,0,.2),transparent);"></div>

            <div class="px-8 pt-7 pb-0 section-px-mobile"><br>
                <div class="flex items-center gap-3 mb-2">
                    <span style="flex:1;height:1px;background:linear-gradient(90deg,transparent,rgba(201,162,0,.3));"></span>
                    <div class="flex items-center gap-2">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span class="font-cinzel" style="font-size:12.5px;letter-spacing:.3em;color:var(--blue-deep);font-weight:600;">{{ $isFrench ? 'HORAIRES DU SECRÉTARIAT' : 'OFFICE HOURS' }}</span>
                    </div>
                    <span style="flex:1;height:1px;background:linear-gradient(90deg,rgba(201,162,0,.3),transparent);"></span>
                </div>

                <div class="office-cols grid grid-cols-1 md:grid-cols-3 gap-5" style="border-radius:16px;">
                    @foreach([
                        ['icon'=>'<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>','day'=>$isFrench ? '· MAR. – SAM. ·' : '· TUE – SAT ·','hours'=>$isFrench ? ['06:00 – 12:00','13:30 – 18:00'] : ['6:00 AM – 12:00 NN','1:30 PM – 6:00 PM'],'closed'=>false],
                        ['icon'=>'<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>','day'=>$isFrench ? '· DIMANCHE ·' : '· SUNDAY ·','hours'=>$isFrench ? ['06:00 – 12:00','15:00 – 18:00'] : ['6:00 AM – 12:00 NN','3:00 PM – 6:00 PM'],'closed'=>false],
                        ['icon'=>'<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>','day'=>$isFrench ? '· LUNDI ·' : '· MONDAY ·','hours'=>[],'closed'=>true],
                    ] as $ohCol)
                    <div class="office-col flex flex-col items-center py-7 px-4 text-center bg-white rounded-2xl shadow-sm border border-[rgba(26,64,128,0.07)]">
                        <br>
                        <div class="w-11 h-11 rounded-full flex items-center justify-center mb-4" style="background:var(--blue-deep);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $ohCol['icon'] !!}</svg>
                        </div>
                        <p style="font-size:11.5px;letter-spacing:.2em;color:rgba(13,42,82,.45);font-weight:500;margin-bottom:10px;">{{ $ohCol['day'] }}</p>
                        @if($ohCol['closed'])
                        <p class="font-cinzel font-semibold" style="color:#C9A200;font-size:.8rem;letter-spacing:.08em;">{{ $isFrench ? 'FERMÉ' : 'CLOSED' }}</p>
                        @else
                        @foreach($ohCol['hours'] as $ohHour)
                        <p style="font-size:14.5px;color:var(--blue-deep);line-height:1.9;">{{ $ohHour }}</p>
                        @endforeach
                        @endif
                    </div>
                    @endforeach<br>
                </div>
            </div>


        </div>
    @endif
</section>
