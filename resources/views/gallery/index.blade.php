<x-public-layout>
<x-slot name="meta">
    <meta name="description" content="Browse all photo and video albums from the Paroisse Saint-Joseph Artisan community.">
</x-slot>

{{-- ═══════════════ HERO ═══════════════ --}}
<section style="position:relative; min-height:38svh; display:flex; flex-direction:column;
                align-items:center; justify-content:center; overflow:hidden;
                background:linear-gradient(160deg, #0D2A52 0%, #1A4080 60%, #0f3060 100%);">

    <div style="position:absolute; inset:0; pointer-events:none;
                background:radial-gradient(ellipse 80% 60% at 50% 30%, rgba(26,64,128,0.55) 0%, transparent 70%);"></div>

    <div style="position:absolute; top:0; left:0; right:0; height:1px;
                background:linear-gradient(90deg,transparent,rgba(245,197,24,0.35),transparent);"></div>

    <div class="font-cinzel" style="position:absolute; font-size:340px; color:rgba(255,255,255,0.018);
                line-height:1; pointer-events:none; user-select:none;">✝</div>

    <div style="position:relative; z-index:10; text-align:center; padding:80px 24px 60px;">

        {{-- Radiant cross icon --}}
        <div style="position:relative; width:48px; height:48px; margin:0 auto 24px;">
            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:48px; height:48px;">
                @for($i = 0; $i < 8; $i++)
                    <line x1="24" y1="24" x2="24" y2="{{ 8 }}"
                          stroke="rgba(245,197,24,0.55)" stroke-width="1"
                          transform="rotate({{ $i * 45 }} 24 24)"
                          stroke-linecap="round"/>
                @endfor
                <circle cx="24" cy="24" r="3" fill="rgba(245,197,24,0.6)"/>
            </svg>
        </div>

        <div class="divider-ornament mb-5">
            <span class="eyebrow" style="color:rgba(245,197,24,0.75);">Parish Gallery</span>
        </div>
        <h1 class="font-heading"
            style="font-size:clamp(2.8rem, 6vw, 5.5rem); font-weight:700; font-style:italic;
                   letter-spacing:-0.02em; color:#FFFFFF; line-height:1; margin-bottom:20px;
                   text-shadow:0 4px 48px rgba(0,0,0,0.4);">
            All Albums
        </h1>
        <div style="width:48px; height:1px; margin:0 auto 20px;
                    background:linear-gradient(90deg,transparent,rgba(245,197,24,0.7),transparent);"></div>
        <p style="max-width:520px; margin:0 auto; font-size:clamp(0.9rem, 1.8vw, 1.1rem); font-style:italic;
                  color:rgba(245,197,24,0.75); line-height:1.7;">
            A visual chronicle of our community's journey in faith, service, and celebration.
        </p>
    </div>

    <div style="position:absolute; bottom:0; left:0; right:0; height:1px;
                background:linear-gradient(90deg,transparent,rgba(245,197,24,0.25),transparent);"></div>
</section>

{{-- ═══════════════ ALBUM GRID ═══════════════ --}}
<section style="padding:5rem 1.5rem; background:var(--cream, #F7F9FF);">
    <div style="max-width:1200px; margin:0 auto;">

        @if($albums->isEmpty())
            {{-- ═══════════════ EMPTY STATE ═══════════════ --}}
            <div style="text-align:center; padding:80px 24px;">
                <div style="width:64px; height:64px; border-radius:20px;
                            background:rgba(13,42,82,0.06);
                            border:1px solid rgba(13,42,82,0.10);
                            display:flex; align-items:center; justify-content:center;
                            margin:0 auto 20px;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="rgba(13,42,82,0.35)" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                        <circle cx="12" cy="13" r="4"/>
                    </svg>
                </div>
                <p style="font-family:'Cormorant Garamond',serif; font-style:italic; font-size:1.4rem;
                          color:rgba(13,42,82,0.40); margin-bottom:8px;">
                    No albums yet
                </p>
                <p style="font-family:'Jost',sans-serif; font-size:13px;
                          color:rgba(13,42,82,0.30);">
                    Check back soon for photos from our parish events.
                </p>
            </div>

        @else
            {{-- Filter/Sort Bar --}}
            <div style="text-align:center; margin-bottom:40px;">
                <p style="font-family:'Jost',sans-serif; font-size:12px; font-weight:600;
                          letter-spacing:0.15em; text-transform:uppercase;
                          color:rgba(13,42,82,0.40);">
                    Showing {{ $albums->total() }} Albums
                </p>
            </div>

            {{-- Album Grid --}}
            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:28px;"
                 class="album-grid-responsive">

                @foreach($albums as $album)
                    @php
                        $cleanTitle = preg_replace(
                            '/[\x{1D400}-\x{1D7FF}]/u', '', $album->title ?? ''
                        );
                        $cleanTitle = trim($cleanTitle);

                        $desc = preg_replace(
                            '/^(NASA LARAWAN:|IN PHOTOS:|TINGNAN:|IN VIDEO:|WATCH:)\s*/iu',
                            '', $album->description ?? ''
                        );
                        $desc = preg_replace('/#\w+/u', '', $desc);

                        $coverUrl = null;
                        if ($album->images->count() > 0) {
                            $coverUrl = $album->images->first()->url;
                        }
                    @endphp

                    <a href="{{ route('gallery.album', $album->id) }}"
                       class="album-card">

                        {{-- Thumbnail --}}
                        <div class="album-thumb">
                            @if($coverUrl)
                                <img src="{{ $coverUrl }}"
                                     alt="{{ $cleanTitle }}"
                                     loading="lazy" />
                            @else
                                <div style="width:100%; height:100%; background:var(--cream-deep, #EDF2FC);
                                            display:flex; align-items:center; justify-content:center;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                         stroke="rgba(13,42,82,0.15)" stroke-width="1.5"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                        <polyline points="21 15 16 10 5 21"/>
                                    </svg>
                                </div>
                            @endif

                            {{-- Item count badge --}}
                            <span class="item-badge">
                                {{ $album->images_count }} Items
                            </span>

                            {{-- Hover overlay --}}
                            <div class="album-overlay">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <span>View Album</span>
                            </div>
                        </div>

                        {{-- Card body --}}
                        <div class="album-body">
                            <p class="album-date">
                                {{ $album->created_at->format('F d, Y') }}
                            </p>
                            <h3 class="album-title">
                                {{ $cleanTitle }}
                            </h3>
                            <p class="album-desc">
                                {{ Str::limit(trim($desc), 100) }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($albums->hasPages())
                <div style="text-align:center; margin-top:48px;">
                    {{ $albums->links() }}
                </div>
            @endif

        @endif
    </div>
</section>

{{-- ═══════════════ ALBUM CARD STYLES ═══════════════ --}}
<style>
    .album-card {
        display: block;
        background: #FFFFFF;
        border: 1px solid rgba(26,64,128,0.10);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(13,42,82,0.07);
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.22,1,0.36,1);
    }
    .album-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 48px rgba(13,42,82,0.14);
        border-color: rgba(245,197,24,0.30);
    }

    .album-thumb {
        position: relative;
        aspect-ratio: 16 / 10;
        overflow: hidden;
        background: #EDF2FC;
    }
    .album-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s cubic-bezier(0.22,1,0.36,1);
    }
    .album-card:hover .album-thumb img {
        transform: scale(1.05);
    }

    .item-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        background: rgba(13,42,82,0.75);
        backdrop-filter: blur(8px);
        color: #FFFFFF;
        font-family: 'Jost', sans-serif;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.20em;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 999px;
    }

    .album-overlay {
        position: absolute;
        inset: 0;
        background: rgba(13,42,82,0.45);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        opacity: 0;
        transition: opacity 0.3s ease;
        color: #FFFFFF;
        font-family: 'Jost', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.20em;
        text-transform: uppercase;
    }
    .album-card:hover .album-overlay {
        opacity: 1;
    }

    .album-body {
        padding: 20px 24px 24px;
    }

    .album-date {
        font-family: 'Jost', sans-serif;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: rgba(13,42,82,0.38);
        margin-bottom: 8px;
    }

    .album-title {
        font-family: 'Cormorant Garamond', serif;
        font-style: italic;
        font-weight: 700;
        font-size: 1.25rem;
        line-height: 1.2;
        color: #0D2A52;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .album-desc {
        font-family: 'Jost', sans-serif;
        font-size: 13px;
        line-height: 1.7;
        color: rgba(13,42,82,0.48);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    {{-- Pagination styling --}}
    .pagination {
        display: flex;
        gap: 6px;
        justify-content: center;
        align-items: center;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .pagination li a,
    .pagination li span {
        font-family: 'Jost', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.15em;
        border-radius: 12px;
        border: 1px solid rgba(26,64,128,0.15);
        color: rgba(13,42,82,0.60);
        padding: 8px 16px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        transition: all 0.2s ease;
        background: transparent;
    }
    .pagination li a:hover {
        background: rgba(13,42,82,0.05);
        border-color: rgba(26,64,128,0.25);
        color: #0D2A52;
    }
    .pagination li.active span {
        background: #0D2A52;
        color: #FFFFFF;
        border-color: #0D2A52;
    }
    .pagination li.disabled span {
        opacity: 0.35;
        pointer-events: none;
    }

    {{-- Responsive grid --}}
    @media (max-width: 1023px) {
        .album-grid-responsive {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 20px !important;
        }
    }
    @media (max-width: 639px) {
        .album-grid-responsive {
            grid-template-columns: 1fr !important;
            gap: 16px !important;
        }
    }
</style>

</x-public-layout>
