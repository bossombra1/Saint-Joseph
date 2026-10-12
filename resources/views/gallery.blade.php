<x-public-layout>
<x-slot name="meta">
    <meta name="description" content="Explore photo and video highlights from the Paroisse Saint-Joseph Artisan community in our official gallery.">
    <style>
        .eyebrow {
            font-size: 10px; font-weight: 600;
            letter-spacing: 0.32em; text-transform: uppercase;
            color: var(--gold);
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

        .gold-btn {
            background: linear-gradient(135deg, #FFD740 0%, #F5C518 55%, #E0A800 100%);
            color: var(--blue-deep);
            box-shadow: 0 4px 20px rgba(245,197,24,0.40), 0 1px 0 rgba(255,255,255,0.35) inset;
            transition: all 0.25s ease; font-weight: 700;
        }
        .gold-btn:hover {
            box-shadow: 0 8px 32px rgba(245,197,24,0.55), 0 1px 0 rgba(255,255,255,0.35) inset;
            transform: translateY(-2px);
        }

        .card-sacred {
            background: #FFFFFF;
            border: 1px solid rgba(26,64,128,0.12);
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        .card-sacred:hover {
            border-color: rgba(245,197,24,0.55);
            box-shadow: 0 10px 40px rgba(13,42,82,0.12);
            transform: translateY(-3px);
        }



        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.4; transform: scale(0.75); }
        }

        /* Gallery-specific */
        .album-card-overlay {
            background: linear-gradient(to top,
                rgba(8,20,45,0.92) 0%,
                rgba(8,20,45,0.25) 60%,
                transparent 100%);
        }
        .recent-photo { filter: grayscale(0.6); transition: all 0.7s ease; }
        .recent-photo:hover { filter: grayscale(0); transform: scale(1.08); }

        .video-description {
            font-size: 1.1rem;
            color: rgba(13,42,82,0.7);
            line-height: 1.8;
            margin-bottom: 2rem;
            white-space: pre-wrap;
            font-weight: 400;
            letter-spacing: 0.01em;
        }

        /* Highlight card — no hover lift on the outer wrapper */
        .highlight-card-outer { transition: none !important; transform: none !important; }
        .highlight-card-outer:hover { transform: none !important; box-shadow: none !important; border-color: rgba(245,197,24,0.18) !important; }

        /* Video Controls */
        .video-btn {
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18);
            color: #fff; display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s ease;
        }
        .video-btn:hover { background: rgba(255,255,255,0.2); transform: translateY(-1px); }
        .video-btn:active { transform: translateY(0); }

        .progress-container {
            height: 5px; background: rgba(255,255,255,0.2); border-radius: 3px;
            position: relative; cursor: pointer; transition: all 0.2s ease;
        }
        .progress-container:hover { height: 8px; }
        .progress-fill {
            height: 100%; width: 0%; background: linear-gradient(90deg, #FFD740, #F5C518);
            border-radius: 3px; position: relative;
        }
        .progress-handle {
            position: absolute; right: -6px; top: 50%; transform: translateY(-50%);
            width: 12px; height: 12px; background: #F5C518; border-radius: 50%;
            box-shadow: 0 2px 6px rgba(0,0,0,0.4); opacity: 0; transition: opacity 0.2s;
        }
        .progress-container:hover .progress-handle { opacity: 1; }

        .video-btn-custom {
            background: transparent; border: none; color: rgba(255, 255, 255, 0.8);
            padding: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;
            border-radius: 50%; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .video-btn-custom:hover {
            color: #F5C518; background: rgba(255, 255, 255, 0.12); transform: scale(1.1);
        }
        .video-btn-custom:active {
            transform: scale(0.95);
        }

        .play-btn-circle {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .play-btn-circle:hover {
            background: #F5C518 !important; border-color: #F5C518 !important;
            transform: scale(1.12); box-shadow: 0 0 30px rgba(245, 197, 24, 0.5) !important;
        }
        .play-btn-circle:hover svg {
            fill: #0D2A52 !important;
        }

        .highlight-cinema:hover .video-controls-wrapper {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        /* Hide native HTML5 video controls */
        #hv-0::-webkit-media-controls {
            display: none !important;
        }
        #hv-0::-webkit-media-controls-panel {
            display: none !important;
        }
        #hv-0::-webkit-media-controls-play-button {
            display: none !important;
        }
        #hv-0::-webkit-media-controls-start-playback-button {
            display: none !important;
        }

        /* Lightbox */
        .lightbox-backdrop {
            position: fixed; inset: 0; background: rgba(8,20,45,0.98);
            z-index: 9999; display: none; align-items: center; justify-content: center;
            backdrop-filter: blur(12px); opacity: 0; transition: opacity 0.4s ease;
        }
        .lightbox-backdrop.active { display: flex; opacity: 1; }
        .lightbox-content {
            width: 90%; max-width: 1200px; position: relative;
            transform: scale(0.9); transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .lightbox-backdrop.active .lightbox-content { transform: scale(1); }
    </style>
</x-slot>

{{-- ═══════════════ HERO ═══════════════ --}}
<section style="position:relative; min-height:38svh; display:flex; flex-direction:column;
                align-items:center; justify-content:center; overflow:hidden;
                background:var(--blue-deep);">

    <div style="position:absolute; inset:0; pointer-events:none;
                background:radial-gradient(ellipse 80% 60% at 50% 30%, rgba(26,64,128,0.55) 0%, transparent 70%);"></div>

    <div style="position:absolute; top:0; left:0; right:0; height:1px;
                background:linear-gradient(90deg,transparent,rgba(245,197,24,0.5),transparent);"></div>

    <div class="font-cinzel" style="position:absolute; font-size:360px; color:rgba(255,255,255,0.018);
                line-height:1; pointer-events:none; user-select:none;">✝</div>

    <div style="position:relative; z-index:10; text-align:center; padding:132px 24px 60px;">
        <div class="divider-ornament mb-5">
            <span class="eyebrow" style="color:rgba(245,197,24,0.75);">Capturing Moments</span>
        </div>
        <h1 class="font-heading"
            style="font-size:clamp(2.8rem,7vw,6rem); font-weight:700; font-style:italic;
                   letter-spacing:-0.02em; color:#EBF2FF; line-height:1; margin-bottom:20px;
                   text-shadow:0 4px 48px rgba(0,0,0,0.4);">
            Parish Gallery
        </h1>
        <div style="width:48px; height:1px; margin:0 auto 20px;
                    background:linear-gradient(90deg,transparent,rgba(245,197,24,0.7),transparent);"></div>
        <p style="max-width:500px; margin:0 auto; font-size:0.95rem; font-style:italic;
                  color:rgba(235,242,255,0.5); line-height:1.7;">
            A visual chronicle of our community's journey in faith, service, and celebration.
        </p>
    </div>

    <div style="position:absolute; bottom:0; left:0; right:0; height:1px;
                background:linear-gradient(90deg,transparent,rgba(245,197,24,0.25),transparent);"></div>
</section>

<div class="pb-24">

    @if($albums->isEmpty())
    {{-- ═══════════════ EMPTY STATE ═══════════════ --}}
    <div class="reveal" style="padding:8rem 1rem; text-align:center;">
        <div style="width:80px; height:80px; border-radius:50%; margin:0 auto 1.5rem;
                    background:var(--cream-deep); border:1px solid rgba(26,64,128,0.1);
                    display:flex; align-items:center; justify-content:center;">
            <svg width="32" height="32" fill="none" viewBox="0 0 24 24">
                <rect width="18" height="18" x="3" y="3" rx="2" stroke="#F5C518" stroke-width="1.5"/>
                <circle cx="9" cy="9" r="2" stroke="#F5C518" stroke-width="1.5"/>
                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" stroke="#F5C518" stroke-width="1.5"/>
            </svg>
        </div>
        <h3 class="font-heading" style="font-size:1.8rem; font-weight:700; font-style:italic;
                   color:var(--blue-deep); margin-bottom:0.75rem;">No Albums Yet</h3>
        <p style="font-size:0.875rem; font-style:italic; color:rgba(13,42,82,0.45);">
            We are currently curating our latest community photos. Please check back soon.
        </p>
    </div>

    @endif
    @php
        $featured    = $albums->first();
        $otherAlbums = $albums->slice(1);
        $highlightsVideo = $global_settings['gallery_highlights_video'] ?? null;
    @endphp

    {{-- ═══════════════ PARISH HIGHLIGHTS ═══════════════ --}}
    @if($highlights->isNotEmpty())
    @php $v = $highlights->first(); @endphp
    @php
        $isExternal = Str::startsWith($v->video_url, ['http://', 'https://', 'www.']);
    @endphp

    <section class="parish-highlights reveal" style="padding:5rem 0 0; background:var(--cream, #F7F9FF);">

        {{-- Section eyebrow header --}}
        <div style="text-align:center; margin-bottom:32px; padding:0 1.5rem;">
            <div class="divider-ornament" style="justify-content:center; display:flex; align-items:center; gap:12px; margin-bottom:0;">
                <span style="height:1px; width:48px; display:block; background:linear-gradient(90deg,transparent,rgba(245,197,24,0.5));"></span>
                <span style="font-size:10px; font-weight:700; letter-spacing:0.32em; text-transform:uppercase; color:#F5C518; font-family:'Cinzel',serif;">
                    Parish Highlights
                </span>
                <span style="height:1px; width:48px; display:block; background:linear-gradient(90deg,rgba(245,197,24,0.5),transparent);"></span>
            </div>
        </div>

        {{-- Cinematic video container --}}
        <div class="highlight-cinema group" id="highlight-cinema"
             style="position:relative; width:100%; max-width:1200px; margin:0 auto; border-radius:28px; overflow:hidden;
                    box-shadow:0 24px 80px rgba(13,42,82,0.22); background:#000; aspect-ratio:16/9;">

            {{-- THE VIDEO --}}
            @if($isExternal)
                <iframe class="highlight-video"
                        src="{{ $v->embed_url }}"
                        style="width:100%; height:100%; border:0; display:block; position:relative; z-index:1;"
                        frameborder="0" allowfullscreen
                        allow="accelerometer autoplay clipboard-write encrypted-media gyroscope web-share"
                        referrerpolicy="strict-origin-when-cross-origin"
                        title="{{ $v->title }}"></iframe>
            @else
                  <video id="hv-0" class="highlight-video"
                       playsinline preload="metadata"
                       style="width:100%; height:100%; object-fit:cover; display:block; position:relative; z-index:1;"
                       onclick="toggleVideoPlay(0)"
                       onplay="syncVideoUI(0, true)"
                       onpause="syncVideoUI(0, false)"
                       ontimeupdate="updateVideoProgress(0)"
                              onended="onVideoEnded(0)"
                              onloadedmetadata="document.getElementById('time-dur-0').innerText = Math.floor(this.duration / 60) + ':' + Math.floor(this.duration % 60).toString().padStart(2, '0')">
                          <source src="{{ $v->video_url }}" type="{{ $v->video_mime_type }}">
                </video>

                {{-- Center Play/Pause Circle Overlay --}}
                <div id="play-overlay-0" onclick="toggleVideoPlay(0)"
                     style="position:absolute; inset:0; z-index:3; display:flex; align-items:center; justify-content:center;
                            background:rgba(13,42,82,0.15); cursor:pointer; transition:all 0.3s ease;">
                    <div class="play-btn-circle"
                         style="width:76px; height:76px; border-radius:50%; background:rgba(255,255,255,0.12);
                                border:1px solid rgba(255,255,255,0.25); backdrop-filter:blur(8px);
                                display:flex; align-items:center; justify-content:center;
                                box-shadow:0 12px 36px rgba(0,0,0,0.3); color:#FFFFFF;">
                        <svg id="overlay-play-icon-0" width="28" height="28" viewBox="0 0 24 24" fill="currentColor" style="margin-left:3px;">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                </div>
            @endif

            {{-- BOTTOM CONTROLS OVERLAY (text moved below player) --}}
            @if(!$isExternal)
            <div id="hl-controls-overlay"
                 style="position:absolute; bottom:0; left:0; right:0; z-index:3;
                        background:linear-gradient(to top, rgba(13,42,82,0.88) 0%, transparent 100%);
                        padding:16px 44px 18px; pointer-events:none;">
                    <div class="video-controls-wrapper"
                         style="display:flex; flex-direction:column; gap:12px; pointer-events:auto;
                                opacity:0; transform:translateY(10px); transition:all 0.3s ease;">
                        
                        {{-- Progress Bar --}}
                        <div style="display:flex; align-items:center; gap:12px;">
                            <span id="time-curr-0" style="font-family:'Jost',sans-serif; font-size:11px; color:rgba(255,255,255,0.8); min-width:32px;">0:00</span>
                            <div class="progress-container" onclick="seekVideo(0, event)" style="flex:1;">
                                <div id="prog-fill-0" class="progress-fill">
                                    <div class="progress-handle"></div>
                                </div>
                            </div>
                            <span id="time-dur-0" style="font-family:'Jost',sans-serif; font-size:11px; color:rgba(255,255,255,0.6); min-width:32px;">0:00</span>
                        </div>

                        {{-- Buttons Row --}}
                        <div style="display:flex; align-items:center; justify-content:space-between; width:100%;">
                            {{-- Left Side Controls --}}
                            <div style="display:flex; align-items:center; gap:16px;">
                                {{-- Play/Pause --}}
                                <button onclick="toggleVideoPlay(0)" class="video-btn-custom" title="Play/Pause">
                                    <svg id="play-icon-0" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                    <svg id="pause-icon-0" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="display:none;">
                                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                                    </svg>
                                </button>

                                {{-- Skip Backward 10s --}}
                                <button onclick="skipVideo(0, -10)" class="video-btn-custom" title="Rewind 10s">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                        <path d="M3 3v5h5"/>
                                        <text x="12" y="15.5" font-size="8" font-family="sans-serif" font-weight="bold" fill="currentColor" text-anchor="middle" stroke="none">10</text>
                                    </svg>
                                </button>

                                {{-- Skip Forward 10s --}}
                                <button onclick="skipVideo(0, 10)" class="video-btn-custom" title="Forward 10s">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 12a9 9 0 1 1-9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                                        <path d="M21 3v5h-5"/>
                                        <text x="12" y="15.5" font-size="8" font-family="sans-serif" font-weight="bold" fill="currentColor" text-anchor="middle" stroke="none">10</text>
                                    </svg>
                                </button>

                                {{-- Volume/Mute --}}
                                <button onclick="toggleVideoMute(0)" class="video-btn-custom" title="Mute/Unmute">
                                    <svg id="vol-icon-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                                        <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/>
                                    </svg>
                                    <svg id="mute-icon-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                                        <line x1="23" y1="9" x2="17" y2="15"/>
                                        <line x1="17" y1="9" x2="23" y2="15"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Right Side Controls --}}
                            <div style="display:flex; align-items:center; gap:16px;">
                                {{-- Picture-in-Picture --}}
                                <button onclick="triggerPiP(0)" class="video-btn-custom" title="Picture in Picture">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                        <rect x="13" y="11" width="7" height="5" rx="1" ry="1"/>
                                    </svg>
                                </button>

                                {{-- Fullscreen --}}
                                <button onclick="toggleFullscreen('hv-0')" class="video-btn-custom" title="Fullscreen">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
            </div>
            @endif

        </div>

        {{-- Title & description below the player --}}
        <div id="hl-info-bar" class="card-sacred"
             style="max-width:1200px; margin:1.25rem auto 0; padding:1.75rem 2.75rem;">
            <p style="font-family:'Jost',sans-serif; font-size:10px; font-weight:700;
                      letter-spacing:0.32em; text-transform:uppercase;
                      color:rgba(245,197,24,0.85); margin-bottom:10px;">
                Featured Highlight
            </p>
            <h2 class="font-heading"
                style="font-style:italic; font-weight:700;
                       font-size:clamp(1.6rem, 3vw, 2.5rem); color:var(--blue-deep); line-height:1.1;
                       letter-spacing:-0.01em; margin-bottom:10px;">
                @php
                    $cleanVideoTitle = preg_replace('/[\x{1D400}-\x{1D7FF}]/u', '', $v->title ?? 'Parish Highlight');
                @endphp
                {{ trim($cleanVideoTitle) }}
            </h2>
            <span style="display:block; width:56px; height:3px; background:linear-gradient(90deg,#FFD740,#F5C518);
                         border-radius:999px; margin-bottom:14px;"></span>
            @if($v->event_date ?? null)
            <p style="font-family:'Jost',sans-serif; font-size:11px; font-weight:600;
                      letter-spacing:0.18em; text-transform:uppercase;
                      color:rgba(13,42,82,0.45); margin-bottom:10px;">
                {{ $v->event_date }}
            </p>
            @endif
            @if($v->description ?? null)
            @php
                $videoDesc = preg_replace('/^(NASA LARAWAN:|IN PHOTOS:|TINGNAN:|IN VIDEO:|WATCH:)\s*/iu', '', $v->description);
                $videoDesc = preg_replace('/#\w+/u', '', $videoDesc);
                $videoDesc = trim(preg_replace('/\s+/', ' ', $videoDesc));
            @endphp
            <p style="font-family:'Cormorant Garamond',Georgia,serif; font-style:italic;
                       font-size:clamp(0.95rem, 1.5vw, 1.1rem); color:rgba(13,42,82,0.65);
                       line-height:1.7; max-width:720px;">
                {{ $videoDesc }}
            </p>
            @endif
        </div>

        {{-- Responsive styles --}}
        <style>
            /* ── Mobile ── */
            @media (max-width: 768px) {
                .highlight-cinema {
                    border-radius: 20px !important;
                    margin-left: 16px !important;
                    margin-right: 16px !important;
                    width: calc(100% - 32px) !important;
                }
                #hl-controls-overlay { padding: 12px 16px 14px !important; }
                #hl-info-bar {
                    margin-left: 16px !important;
                    margin-right: 16px !important;
                    padding: 1.25rem 1.25rem 1.5rem !important;
                }
            }
        </style>


    </section>
    @endif

    @if($featured)
    {{-- ═══════════════ FEATURED ALBUM ═══════════════ --}}
    <section class="reveal" style="padding:4rem 1.5rem 0; max-width:1100px; margin:0 auto 5rem;">
        <div class="divider-ornament mb-8" style="justify-content:flex-start;">
            <span class="eyebrow">Featured Album</span>
            <div style="flex:1; height:1px; background:linear-gradient(90deg,rgba(245,197,24,0.3),transparent);"></div>
        </div>

        <a href="{{ route('gallery.album', $featured) }}"
           class="group"
           style="display:block; position:relative; border-radius:20px; overflow:hidden;
                  height:480px; border:1px solid rgba(26,64,128,0.12);
                  box-shadow:0 12px 50px rgba(13,42,82,0.12); text-decoration:none;">

            @if($featured->coverImage)
                @php $firstItem = $featured->coverImage; @endphp
                <img src="{{ $firstItem->type === 'video' ? 'https://images.pexels.com/photos/1117132/pexels-photo-1117132.jpeg' : $firstItem->url }}" alt="{{ $featured->title }}"
                     style="width:100%; height:100%; object-fit:cover; transition:transform 3s ease;"
                     class="group-hover:scale-105">
                @if($firstItem->type === 'video' || ($featured->video_count ?? 0) > 0)
                    <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; z-index:5;">
                        <div style="width:72px; height:72px; border-radius:50%; background:rgba(245,197,24,0.9); display:flex; align-items:center; justify-content:center; box-shadow:0 12px 32px rgba(0,0,0,0.4);">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="#0D2A52"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>
                @endif
            @else
                <div style="width:100%; height:100%; background:var(--cream-deep);"></div>
            @endif

            <div class="album-card-overlay"
                 style="position:absolute; inset:0; display:flex; flex-direction:column;
                        justify-content:flex-end; padding:2.5rem;">
                <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
                    <span class="gold-btn"
                          style="font-size:10px; font-weight:700; letter-spacing:0.25em;
                                 text-transform:uppercase; padding:8px 18px; border-radius:100px;">
                        Featured
                    </span>
                    <span style="font-size:10px; font-weight:700; letter-spacing:0.25em;
                                 text-transform:uppercase; padding:8px 18px; border-radius:100px;
                                 color:rgba(235,242,255,0.55); border:1px solid rgba(235,242,255,0.2);">
                        {{ $featured->images_count }} Items
                    </span>
                </div>
                <h2 class="font-heading group-hover:text-[#F5C518]"
                    style="font-size:clamp(2rem,4vw,3.5rem); font-weight:700; font-style:italic;
                           color:#EBF2FF; letter-spacing:-0.01em; margin-bottom:0.75rem;
                           transition:color 0.3s; line-height:1.1;">
                    {{ $featured->title }}
                </h2>
                <p style="font-size:0.875rem; font-style:italic; max-width:600px;
                          color:rgba(235,242,255,0.5); line-height:1.6;
                          display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                    @php
                        $cleanDesc = preg_replace(
                            '/^(NASA LARAWAN:|IN PHOTOS:|TINGNAN:|IN VIDEO:|WATCH:)\s*/i',
                            '',
                            $featured->description ?? ''
                        );
                        $cleanDesc = preg_replace('/#\w+/u', '', $cleanDesc);
                        $cleanDesc = trim($cleanDesc);
                    @endphp
                    {{ Str::limit($cleanDesc, 160) }}
                </p>
            </div>
        </a>
    </section>
    @endif

    {{-- ═══════════════ MORE GALLERIES ═══════════════ --}}
    @if($otherAlbums->isNotEmpty())
    <section class="reveal" style="padding:0 1.5rem; max-width:1100px; margin:0 auto 5rem;">
        <div class="divider-ornament mb-12" style="justify-content:flex-start; gap:16px;">
            <h2 class="font-heading"
                style="font-size:2rem; font-weight:700; font-style:italic;
                       color:var(--blue-deep); white-space:nowrap;">More Galleries</h2>
            <div style="flex:1; height:1px; background:linear-gradient(90deg,rgba(26,64,128,0.12),transparent);"></div>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(min(100%,300px),1fr)); gap:2rem;">
            @foreach($otherAlbums as $album)
            <a href="{{ route('gallery.album', $album) }}"
               class="group card-sacred"
               style="display:flex; flex-direction:column; text-decoration:none; overflow:hidden;">

                <div style="position:relative; aspect-ratio:4/3; overflow:hidden; background:var(--cream-deep);">
                    @if($album->coverImage)
                        @php $thumb = $album->coverImage; @endphp
                        <div x-data="{ loaded: false }" class="relative w-full h-full">
                            <div x-show="!loaded" class="absolute inset-0 skeleton z-10"></div>
                            <img src="{{ $thumb->type === 'video' ? 'https://images.pexels.com/photos/1117132/pexels-photo-1117132.jpeg' : $thumb->url }}"
                                 alt="{{ $album->title }}"
                                 width="400" height="300"
                                 style="width:100%; height:100%; object-fit:cover; transition:transform 0.7s ease;"
                                 class="group-hover:scale-110" loading="lazy"
                                 @load="loaded = true">
                        </div>
                        @if($thumb->type === 'video' || ($album->video_count ?? 0) > 0)
                            <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; z-index:5;">
                                <div style="width:40px; height:40px; border-radius:50%; background:rgba(245,197,24,0.85); display:flex; align-items:center; justify-content:center; box-shadow:0 8px 20px rgba(0,0,0,0.3);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="#0D2A52"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        @endif
                    @endif
                    <div style="position:absolute; inset:0; background:rgba(13,42,82,0.15); transition:background 0.3s;"
                         class="group-hover:bg-[rgba(13,42,82,0.05)]"></div>
                    <div style="position:absolute; top:12px; right:12px;
                                font-size:9px; font-weight:700; letter-spacing:0.2em;
                                text-transform:uppercase; padding:4px 10px; border-radius:100px;
                                background:rgba(255,255,255,0.92); color:var(--blue-deep);">
                        {{ $album->images_count }} Items
                    </div>
                </div>

                <div style="padding:1.25rem 1.25rem 1.5rem;">
                    <h3 class="font-heading group-hover:text-[#F5C518]"
                        style="font-size:1.25rem; font-weight:700; font-style:italic;
                               color:var(--blue-deep); margin-bottom:0.35rem; transition:color 0.25s;">
                        {{ $album->title }}
                    </h3>
                    <p style="font-size:0.8125rem; font-style:italic; color:rgba(13,42,82,0.45);
                              display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;
                              overflow:hidden; line-height:1.6;">
                        {{ $album->description }}
                    </p>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ═══════════════ RECENT HIGHLIGHTS ═══════════════ --}}
    @if($latestItems->isNotEmpty())
    <section class="reveal" style="background:var(--blue-deep); padding:5rem 1.5rem;
                                     margin-bottom:5rem; position:relative; overflow:hidden;">

        <div style="position:absolute; top:0; left:0; right:0; height:1px;
                    background:linear-gradient(90deg,transparent,rgba(245,197,24,0.35),transparent);"></div>
        <div style="position:absolute; bottom:0; left:0; right:0; height:1px;
                    background:linear-gradient(90deg,transparent,rgba(245,197,24,0.2),transparent);"></div>

        <div class="font-cinzel" style="position:absolute; font-size:320px; color:rgba(255,255,255,0.018);
                    top:50%; left:50%; transform:translate(-50%,-50%);
                    pointer-events:none; user-select:none; line-height:1;">✝</div>

        <div style="max-width:1100px; margin:0 auto; position:relative; z-index:1;">
            <div class="text-center mb-12">
                <div class="divider-ornament mb-3">
                    <span class="eyebrow" style="color:rgba(245,197,24,0.75);">Latest</span>
                </div>
                <h2 class="font-heading"
                    style="font-size:2.25rem; font-weight:700; font-style:italic;
                           color:var(--blue-pale); letter-spacing:-0.01em;">
                    Recent Highlights
                </h2>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:0.75rem;">
                @foreach($latestItems->take(6) as $item)
                <a href="{{ route('gallery.album', $item->album_id) }}"
                   class="highlight-thumb"
                   style="position:relative; aspect-ratio:4/3; border-radius:12px; overflow:hidden;
                          display:block; background:rgba(255,255,255,0.05);
                          border:1px solid rgba(255,255,255,0.06); cursor:pointer;">

                    @if($item->type === 'video')
                        <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; z-index:5;">
                            <div style="width:32px; height:32px; border-radius:50%; background:rgba(245,197,24,0.8); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(0,0,0,0.3);">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="#0D2A52"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                    @endif

                    <div x-data="{ loaded: false }" class="relative w-full h-full">
                        <div x-show="!loaded" class="absolute inset-0 skeleton-dark z-10"></div>
                        <img src="{{ $item->type === 'video' ? 'https://images.pexels.com/photos/1117132/pexels-photo-1117132.jpeg' : $item->url }}"
                             alt="{{ $item->album->title ?? 'Recent highlight' }}"
                             style="width:100%; height:100%; object-fit:cover; object-position:center;
                                    transition:transform 0.4s cubic-bezier(0.22,1,0.36,1);"
                             loading="lazy"
                             @load="loaded = true">
                    </div>

                    {{-- Hover overlay --}}
                    <div style="position:absolute; inset:0;
                                background:rgba(13,42,82,0.25); opacity:0;
                                transition:opacity 0.3s ease; border-radius:inherit; pointer-events:none;"></div>

                    {{-- Caption overlay --}}
                    <div class="thumb-caption"
                         style="position:absolute; bottom:0; left:0; right:0;
                                padding:28px 12px 10px;
                                background:linear-gradient(to top, rgba(13,42,82,0.75), transparent);
                                color:#FFFFFF; font-family:'Jost',sans-serif;
                                font-size:10px; font-weight:600; letter-spacing:0.15em;
                                text-transform:uppercase; border-radius:0 0 12px 12px;
                                opacity:0; transition:opacity 0.3s ease; z-index:3;">
                        <span>{{ Str::limit(strip_tags($item->album->title ?? ''), 40) }}</span>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- View All link --}}
            <div style="text-align:center; margin-top:28px;">
                <a href="{{ route('gallery.index') }}"
                   style="display:inline-flex; align-items:center; gap:8px;
                          height:44px; padding:0 28px; border-radius:999px;
                          border:1.5px solid rgba(255,255,255,0.25);
                          color:rgba(255,255,255,0.70);
                          font-family:'Jost',sans-serif;
                          font-size:10px; font-weight:700;
                          letter-spacing:0.25em; text-transform:uppercase;
                          text-decoration:none; transition:all 0.2s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.10)'; this.style.borderColor='rgba(255,255,255,0.45)'; this.style.color='#FFFFFF';"
                   onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(255,255,255,0.25)'; this.style.color='rgba(255,255,255,0.70)';">
                    View All Galleries
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- ═══════════════ STAY CONNECTED / SOCIAL FEED ═══════════════ --}}
    <section class="social-feed-section reveal" style="background:var(--cream, #F7F9FF); position:relative; padding-top:64px; padding-bottom:80px;">
        {{-- Background cross motif --}}
        <div class="social-feed-bg-cross" aria-hidden="true">✝</div>
        {{-- Gold radial glow --}}
        <div class="social-feed-glow"></div>

        <div style="max-width:1100px; margin:0 auto; position:relative; z-index:1;">

            {{-- Section Header --}}
            <div style="text-align:center; margin-bottom:3rem;">
                <div class="divider-ornament mb-5">
                    <span class="eyebrow">Social Feed</span>
                </div>
                <h2 class="font-heading"
                    style="font-size:clamp(2.2rem,4vw,3.6rem); font-weight:700; font-style:italic;
                           color:#0D2A52; letter-spacing:-0.01em; margin-bottom:1rem;">
                    Stay Connected
                </h2>
                <p class="font-heading" style="font-size:1.1rem; font-style:italic;
                   color:rgba(13,42,82,0.48); max-width:520px; margin:0 auto; line-height:1.7;">
                    Follow our latest updates and community announcements live from Facebook.
                </p>
            </div>

            {{-- Facebook Embed Wrapper --}}
            <div class="fb-embed-wrapper" style="margin-bottom:2.5rem;">
                <div class="fb-embed-card" style="display:flex; justify-content:center; align-items:flex-start;">
                    {{-- Shimmer loader --}}
                    <div class="fb-shimmer"></div>

                    {{-- Facebook Page Plugin --}}
                    <div id="fb-root"></div>
                    <!-- 
                        TODO: Once Meta rate limit clears, register domain at 
                        developers.facebook.com → Settings → Basic → App Domains
                        and add appId parameter to the SDK script src for 
                        better reliability.
                    -->
                    <script async defer crossorigin="anonymous"
                            src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v19.0"></script>

                    <div class="fb-page"
                         data-href="https://www.facebook.com/saintjosephartisanrivierabonoumin?locale=fr_FR"
                         data-tabs="timeline"
                         data-width="500"
                         data-height="700"
                         data-small-header="false"
                         data-adapt-container-width="true"
                         data-hide-cover="false"
                         data-show-facepile="true"
                         style="position:relative; z-index:1; margin:0 auto !important;">
                    </div>
                </div>
            </div>

            {{-- CTA Buttons --}}
            <div class="social-feed-cta-row" style="gap:0.75rem;">
                <a href="https://www.facebook.com/saintjosephartisanrivierabonoumin?locale=fr_FR" target="_blank" rel="noopener noreferrer"
                   class="social-feed-cta-gold">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Follow on Facebook
                </a>
                <a href="{{ route('inquiry') }}" class="social-feed-cta-ghost"
                   style="border:1.5px solid rgba(13,42,82,0.30); color:rgba(13,42,82,0.65);"
                   onmouseover="this.style.background='rgba(13,42,82,0.06)'; this.style.borderColor='rgba(13,42,82,0.50)'; this.style.color='rgba(13,42,82,0.85)';"
                   onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(13,42,82,0.30)'; this.style.color='rgba(13,42,82,0.65)';">
                    Contact the Office
                </a>
            </div>

        </div>

        {{-- Responsive width fix + shimmer dismiss --}}
        <script>
        function resizeFbPlugin() {
            var wrapper = document.querySelector('.fb-embed-wrapper');
            var fbEl = document.querySelector('.fb-page');
            if (!wrapper || !fbEl) return;
            var w = Math.min(wrapper.offsetWidth - 48, 500);
            if (w < 280) w = 280;
            fbEl.setAttribute('data-width', w);
            if (window.FB) FB.XFBML.parse();
        }
        window.addEventListener('resize', resizeFbPlugin);
        window.fbAsyncInit = function() {
            FB.init({ version: 'v19.0', xfbml: true });
            FB.Event.subscribe('xfbml.render', function() {
                var shimmer = document.querySelector('.fb-shimmer');
                if (shimmer) shimmer.style.display = 'none';
            });
            resizeFbPlugin();
        };
        </script>
    </section>

</div>

{{-- ═══════════════ CINEMATIC LIGHTBOX ═══════════════ --}}
<div id="video-lightbox" class="lightbox-backdrop" onclick="closeCinematicLightbox(event)">
    <div class="lightbox-content" onclick="event.stopPropagation()">
        <button class="video-btn"
                style="position:absolute; top:-50px; right:0; width:40px; height:40px; border-radius:50%; background:rgba(255,255,255,0.15);"
                onclick="closeCinematicLightbox()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
        <div class="card-sacred overflow-hidden" style="background:#0a0a0a; border:1px solid rgba(255,255,255,0.1);">
            <div style="position:relative; aspect-ratio:16/9; background:#000;">
                <video id="lightbox-video" style="width:100%; height:100%;" controls preload="auto"></video>
            </div>
            <div style="padding:2rem; background:var(--blue-deep);">
                <h3 id="lightbox-title" class="font-heading" style="font-size:1.75rem; color:#fff; margin-bottom:0.5rem; font-style:italic;">Video Title</h3>
                <p id="lightbox-desc" style="color:rgba(255,255,255,0.5); font-size:0.9rem; line-height:1.6; font-style:italic;">Video Description</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver(
        entries => entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); observer.unobserve(e.target); } }),
        { threshold: 0.12, rootMargin: '0px 0px -50px 0px' }
    );
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
});

/* Video Controls */
let lastActiveVideoIndex = 0;

function toggleSettingsMenu(index) {
    const menu = document.getElementById(`settings-${index}`);
    menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
    const closeMenu = (e) => {
        if (!menu.contains(e.target) && !e.target.closest('button[title="More Options"]')) {
            menu.style.display = 'none';
            document.removeEventListener('click', closeMenu);
        }
    };
    if (menu.style.display === 'flex') setTimeout(() => document.addEventListener('click', closeMenu), 1);
}

function setVideoSpeed(index, speed, btn) {
    const vid = document.getElementById(`hv-${index}`);
    const menu = document.getElementById(`settings-${index}`);
    vid.playbackRate = speed;
    menu.querySelectorAll('button').forEach(b => b.style.color = '#fff');
    btn.style.color = 'var(--gold)';
    menu.style.display = 'none';
}

function skipVideo(index, seconds) {
    const vid = document.getElementById(`hv-${index}`);
    if (!vid) return;
    vid.currentTime = Math.max(0, Math.min(vid.duration, vid.currentTime + seconds));
    updateVideoProgress(index);
}

function toggleVideoPlay(index) {
    lastActiveVideoIndex = index;
    const vid = document.getElementById(`hv-${index}`);
    if (vid.paused) vid.play();
    else vid.pause();
}

function syncVideoUI(index, isPlaying) {
    const overlay = document.getElementById(`play-overlay-${index}`);
    const playIcon = document.getElementById(`play-icon-${index}`);
    const pauseIcon = document.getElementById(`pause-icon-${index}`);
    if (isPlaying) {
        if (overlay) { overlay.style.opacity = '0'; overlay.style.pointerEvents = 'none'; }
        playIcon.style.display = 'none';
        pauseIcon.style.display = 'block';
    } else {
        if (overlay) { overlay.style.opacity = '1'; overlay.style.pointerEvents = 'auto'; }
        playIcon.style.display = 'block';
        pauseIcon.style.display = 'none';
    }
}

function toggleVideoMute(index) {
    const vid = document.getElementById(`hv-${index}`);
    const volIcon = document.getElementById(`vol-icon-${index}`);
    const muteIcon = document.getElementById(`mute-icon-${index}`);
    vid.muted = !vid.muted;
    if (vid.muted) { volIcon.style.display = 'none'; muteIcon.style.display = 'block'; }
    else { volIcon.style.display = 'block'; muteIcon.style.display = 'none'; }
}

function updateVideoProgress(index) {
    const vid = document.getElementById(`hv-${index}`);
    const fill = document.getElementById(`prog-fill-${index}`);
    const curr = document.getElementById(`time-curr-${index}`);
    fill.style.width = `${(vid.currentTime / vid.duration) * 100}%`;
    const mins = Math.floor(vid.currentTime / 60);
    const secs = Math.floor(vid.currentTime % 60).toString().padStart(2, '0');
    curr.innerText = `${mins}:${secs}`;
}

function seekVideo(index, event) {
    const vid = document.getElementById(`hv-${index}`);
    const rect = event.currentTarget.getBoundingClientRect();
    vid.currentTime = ((event.clientX - rect.left) / rect.width) * vid.duration;
}

function onVideoEnded(index) {
    const overlay = document.getElementById(`play-overlay-${index}`);
    if (overlay) { overlay.style.opacity = '1'; overlay.style.pointerEvents = 'auto'; }
    document.getElementById(`play-icon-${index}`).style.display = 'block';
    document.getElementById(`pause-icon-${index}`).style.display = 'none';
}


function toggleFullscreen(id) {
    const vid = document.getElementById(id);
    if (vid.requestFullscreen) vid.requestFullscreen();
    else if (vid.webkitRequestFullscreen) vid.webkitRequestFullscreen();
    else if (vid.msRequestFullscreen) vid.msRequestFullscreen();
}

function triggerPiP(index) {
    const vid = document.getElementById(`hv-${index}`);
    if (!vid) return;
    if (document.pictureInPictureElement) {
        document.exitPictureInPicture();
    } else if (vid.requestPictureInPicture) {
        vid.requestPictureInPicture();
    }
}

function openCinematicLightbox(url, title, desc) {
    const modal = document.getElementById('video-lightbox');
    const vid = document.getElementById('lightbox-video');
    document.getElementById('lightbox-title').innerText = title;
    document.getElementById('lightbox-desc').innerText = desc || '';
    vid.src = url;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    vid.play();
}

function closeCinematicLightbox(event) {
    const modal = document.getElementById('video-lightbox');
    const vid = document.getElementById('lightbox-video');
    modal.classList.remove('active');
    document.body.style.overflow = '';
    vid.pause();
    vid.src = '';
}

async function shareVideo(title, url) {
    if (navigator.share) {
        try { await navigator.share({ title, url }); } catch (err) {}
    } else {
        navigator.clipboard.writeText(url);
        alert('Video link copied to clipboard!');
    }
}

document.addEventListener('keydown', (e) => {
    if (['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) return;
    if (e.key === 'ArrowRight') { e.preventDefault(); skipVideo(lastActiveVideoIndex, 5); }
    else if (e.key === 'ArrowLeft') { e.preventDefault(); skipVideo(lastActiveVideoIndex, -5); }
    else if (e.key === ' ' || e.key === 'k') { e.preventDefault(); toggleVideoPlay(lastActiveVideoIndex); }
    else if (e.key === 'f') { e.preventDefault(); toggleFullscreen(`hv-${lastActiveVideoIndex}`); }
    else if (e.key === 'm') { e.preventDefault(); toggleVideoMute(lastActiveVideoIndex); }
});
</script>

</x-public-layout>