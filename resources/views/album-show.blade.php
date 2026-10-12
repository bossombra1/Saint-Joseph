<x-public-layout>
<x-slot name="meta">
    <meta name="description" content="{{ $album->title }} – View photos and highlights from this album at Paroisse Saint-Joseph Artisan.">
    <style>

        .eyebrow {
            font-size: 12px; font-weight: 600;
            letter-spacing: 0.32em; text-transform: uppercase;
            color: var(--gold);
        }
        .divider-ornament {
            display: flex; align-items: center; gap: 12px;
        }
        .divider-ornament::after {
            content: ''; height: 1px; flex: 1;
            background: linear-gradient(90deg, rgba(245,197,24,0.35), transparent);
        }

        .gold-btn {
            background: linear-gradient(135deg, #FFD740 0%, #F5C518 55%, #E0A800 100%);
            color: var(--blue-deep);
            box-shadow: 0 4px 20px rgba(245,197,24,0.35), 0 1px 0 rgba(255,255,255,0.35) inset;
            transition: all 0.25s ease; font-weight: 700;
        }
        .gold-btn:hover {
            box-shadow: 0 8px 32px rgba(245,197,24,0.55), 0 1px 0 rgba(255,255,255,0.35) inset;
            transform: translateY(-2px);
        }

        .reveal {
            opacity: 0; transform: translateY(40px);
            transition: all 1.2s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .reveal.active { opacity: 1; transform: translateY(0); }

        /* Photo grid */
        .photo-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            aspect-ratio: 4/3;
            border: 1px solid rgba(26,64,128,0.1);
            cursor: zoom-in;
            box-shadow: 0 4px 20px rgba(13,42,82,0.07);
            transition: all 0.4s cubic-bezier(0.16,1,0.3,1);
        }
        .photo-card:hover {
            border-color: rgba(245,197,24,0.5);
            box-shadow: 0 16px 48px rgba(13,42,82,0.14);
            transform: translateY(-4px);
        }
        .photo-card img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.9s ease;
        }
        .photo-card:hover img { transform: scale(1.08); }
        .photo-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top,
                rgba(8,20,45,0.88) 0%,
                rgba(8,20,45,0.1) 50%,
                transparent 100%);
            opacity: 0; transition: opacity 0.35s ease;
            display: flex; flex-direction: column;
            justify-content: flex-end; padding: 1.25rem;
        }
        .photo-card:hover .photo-overlay { opacity: 1; }

        /* Lightbox */
        .lightbox {
            position: fixed; inset: 0; z-index: 200;
            background: rgba(5,12,30,0.97);
            backdrop-filter: blur(20px);
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
            animation: lbFadeIn 0.25s ease;
        }
        @keyframes lbFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        .lightbox-img {
            max-width: 100%; max-height: 82vh;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 24px 80px rgba(0,0,0,0.6);
            animation: lbScaleIn 0.3s cubic-bezier(0.22,1,0.36,1);
        }
        @keyframes lbScaleIn {
            from { opacity: 0; transform: scale(0.94); }
            to   { opacity: 1; transform: scale(1); }
        }
        .lb-close {
            position: absolute; top: 1.25rem; right: 1.25rem;
            width: 44px; height: 44px; border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.15);
            background: rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.8);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s ease;
            backdrop-filter: blur(8px);
        }
        .lb-close:hover {
            background: rgba(245,197,24,0.15);
            border-color: rgba(245,197,24,0.5);
            color: var(--gold-light);
        }
        .lb-nav {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 48px; height: 48px; border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.7);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s ease;
            backdrop-filter: blur(8px);
        }
        .lb-nav:hover {
            background: rgba(245,197,24,0.12);
            border-color: rgba(245,197,24,0.45);
            color: var(--gold-light);
        }
        .lb-prev { left: 1.25rem; }
        .lb-next { right: 1.25rem; }
    </style>
</x-slot>

{{-- ═══════════════ HERO / HEADER ═══════════════ --}}
<section style="position:relative; background:var(--blue-deep); overflow:hidden;
                padding:5rem 1.5rem 3.5rem;">

    {{-- Gold rules --}}
    <div style="position:absolute; top:0; left:0; right:0; height:1px;
                background:linear-gradient(90deg,transparent,rgba(245,197,24,0.45),transparent);"></div>
    <div style="position:absolute; bottom:0; left:0; right:0; height:1px;
                background:linear-gradient(90deg,transparent,rgba(245,197,24,0.2),transparent);"></div>

    {{-- Radial glow --}}
    <div style="position:absolute; inset:0; pointer-events:none;
                background:radial-gradient(ellipse 70% 70% at 50% 20%, rgba(26,64,128,0.5) 0%, transparent 70%);"></div>

    {{-- Watermark --}}
    <div class="font-cinzel" style="position:absolute; font-size:320px; color:rgba(255,255,255,0.018);
                top:50%; left:50%; transform:translate(-50%,-50%);
                pointer-events:none; user-select:none; line-height:1;">✝</div>

    <div style="max-width:1100px; margin:0 auto; position:relative; z-index:5;">

        {{-- Back link --}}
        <a href="{{ route('gallery') }}"
           style="display:inline-flex; align-items:center; gap:8px; margin-bottom:2rem;
                  font-size:11.5px; font-weight:600; letter-spacing:0.28em; text-transform:uppercase;
                  color:rgba(235,242,255,0.45); text-decoration:none; transition:color 0.2s;"
           onmouseover="this.style.color='rgba(245,197,24,0.85)';"
           onmouseout="this.style.color='rgba(235,242,255,0.45)';">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            All Albums
        </a>

        <div style="display:flex; flex-wrap:wrap; align-items:flex-end;
                    justify-content:space-between; gap:1.5rem;">
            <div style="flex:1; min-width:0;">
                <div class="divider-ornament mb-4" style="display:flex; align-items:center; gap:12px;">
                    <span class="eyebrow" style="color:rgba(245,197,24,0.75); white-space:nowrap;">
                        Gallery Album
                    </span>
                </div>
                <h1 class="font-heading"
                    style="font-size:clamp(2.4rem,5vw,5rem); font-weight:700; font-style:italic;
                           color:#EBF2FF; letter-spacing:-0.02em; line-height:1.05; margin-bottom:1rem;">
                    {{ $album->title }}
                </h1>
                @if($album->description)
                <p style="font-size:1.15rem; font-style:italic; color:rgba(235,242,255,0.45);
                          line-height:1.7; max-width:600px;">
                    {!! nl2br(e($album->description)) !!}
                </p>
                @endif
            </div>

            <div style="text-align:right; flex-shrink:0;">
                <div class="gold-btn"
                     style="display:inline-block; font-size:11px; letter-spacing:0.25em;
                            text-transform:uppercase; padding:6px 18px; border-radius:100px;
                            margin-bottom:8px;">
                    {{ $album->images->count() }} Items
                </div>
                <p class="font-cinzel"
                   style="font-size:11px; letter-spacing:0.2em; color:rgba(235,242,255,0.3);
                          text-transform:uppercase;">
                    Added {{ $album->created_at->format('M d, Y') }}
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════ FEATURED VIDEO ═══════════════ --}}
@if($album->featured_video_url)
<section class="reveal" style="padding:4rem 1.5rem 0; max-width:1100px; margin:0 auto;">
    <div class="divider-ornament mb-8" style="justify-content:flex-start;">
        <span class="eyebrow">Album Highlight Video</span>
        <div style="flex:1; height:1px; background:linear-gradient(90deg,rgba(245,197,24,0.3),transparent);"></div>
    </div>

    <div class="card-sacred overflow-hidden" 
         style="position:relative; aspect-ratio:16/9; background:var(--blue-deep); 
                border-radius:24px; box-shadow:0 20px 60px rgba(13,42,82,0.2); border:1px solid rgba(245,197,24,0.1);">
        
        @if(Str::contains($album->featured_video_url, ['youtube.com', 'youtu.be']))
            @php
                $videoId = Str::contains($album->featured_video_url, 'youtu.be') 
                    ? Str::afterLast($album->featured_video_url, '/') 
                    : Str::after(Str::after($album->featured_video_url, 'v='), '&');
                if (Str::contains($videoId, '&')) $videoId = Str::before($videoId, '&');
            @endphp
            <iframe src="https://www.youtube-nocookie.com/embed/{{ $videoId }}" 
                    title="{{ $album->title }} highlight video"
                    class="w-full h-full" frameborder="0" allowfullscreen></iframe>
        @else
            <video controls class="w-full h-full object-cover">
                <source src="{{ \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->url($album->featured_video_url) }}" type="video/mp4">
            </video>
        @endif
    </div>
</section>
@endif

{{-- ═══════════════ MULTIMEDIA GRID ═══════════════ --}}
<section class="reveal" style="padding:3.5rem 1.5rem 5rem; max-width:1100px; margin:0 auto;">

    @if($album->images->isEmpty())
        <div style="text-align:center; padding:6rem 2rem;
                    border:1px dashed rgba(26,64,128,0.15); border-radius:20px;
                    background:var(--cream-deep);">
            <p style="font-size:1.05rem; font-style:italic; color:rgba(13,42,82,0.4);">
                No items in this album yet.
            </p>
        </div>
    @else
        <div id="photo-grid"
             style="display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:1.25rem;">
            @foreach($album->images as $idx => $img)
            <div class="photo-card"
                 data-idx="{{ $idx }}"
                 onclick="openLightbox({{ $idx }})"
                 x-data="{ loaded: false }">
                {{-- Skeleton --}}
                <div x-show="!loaded" class="absolute inset-0 skeleton z-10"></div>
                
                @if($idx === 0)
                {{-- First image loads faster usually, but we keep skeleton for consistency --}}
                @endif

                @if($img->type === 'video')
                    <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; z-index:5; pointer-events:none;">
                        <div style="width:48px; height:48px; border-radius:50%; background:rgba(245,197,24,0.9); display:flex; align-items:center; justify-content:center; box-shadow:0 8px 24px rgba(0,0,0,0.3);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#0D2A52"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>
                @endif

                <img src="{{ $img->type === 'video' ? 'https://images.pexels.com/photos/1117132/pexels-photo-1117132.jpeg' : $img->url }}"
                     alt="{{ $img->publicAlt($album) }}"
                     width="400" height="300"
                     @load="loaded = true"
                     :class="loaded ? '' : 'opacity-0'"
                     loading="lazy">

                @if($img->publicCaption() || $img->type === 'video')
                <div class="photo-overlay">
                    @if($img->publicCaption())
                    <p style="font-size:1rem; font-weight:600; color:#EBF2FF;
                              white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $img->publicCaption() }}
                    </p>
                    @endif
                    @if($img->type === 'video')
                        <span style="font-size:11px; color:var(--gold); font-weight:800; text-transform:uppercase; letter-spacing:1px; margin-top:4px;">Video Highlight</span>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
    @endif
</section>

{{-- ═══════════════ LIGHTBOX ═══════════════ --}}
<div id="lightbox" class="lightbox" style="display:none;"
     onclick="if(event.target===this) closeLightbox()">

    <button class="lb-close" onclick="closeLightbox()" aria-label="Close">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18M6 6l12 12"/>
        </svg>
    </button>

    <a id="lb-download" href="#" download
       class="lb-close" style="right:56px;text-decoration:none;"
       aria-label="Download image" title="Download">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/>
            <line x1="12" x2="12" y1="15" y2="3"/>
        </svg>
    </a>

    <button class="lb-nav lb-prev" onclick="lbNav(-1)" aria-label="Previous">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
    </button>

    <div style="display:flex; flex-direction:column; align-items:center;
                max-width:1000px; width:100%;">
        <div id="lb-content" style="width:100%; display:flex; justify-content:center;">
            {{-- Content will be injected here --}}
        </div>
        <div id="lb-caption" style="margin-top:1.5rem; text-align:center;">
            <h3 id="lb-title" class="font-heading"
                style="font-size:1.75rem; font-weight:700; font-style:italic;
                       color:#EBF2FF; margin-bottom:0.25rem;"></h3>
            <p id="lb-sub"
               style="font-size:1.05rem; font-style:italic; color:rgba(235,242,255,0.4);"></p>
            <p id="lb-counter" class="font-cinzel"
               style="font-size:11px; letter-spacing:0.3em; color:rgba(235,242,255,0.25);
                      text-transform:uppercase; margin-top:0.75rem;"></p>
        </div>
    </div>

    <button class="lb-nav lb-next" onclick="lbNav(1)" aria-label="Next">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
    </button>
</div>

<script>
const images = {!! json_encode($album->images->map(function($i) use ($album) {
    return [
        'url' => $i->url,
        'alt' => $i->publicAlt($album),
        'caption' => $i->publicCaption(),
        'albumTitle' => $album->title,
        'type' => $i->type
    ];
})) !!};
let lbIdx = 0;

const lb         = document.getElementById('lightbox');
const lbContent  = document.getElementById('lb-content');
const lbTitle    = document.getElementById('lb-title');
const lbSub      = document.getElementById('lb-sub');
const lbCount    = document.getElementById('lb-counter');
const lbDownload = document.getElementById('lb-download');

function openLightbox(idx) {
    lbIdx = idx;
    renderLb();
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    lb.style.display = 'none';
    document.body.style.overflow = '';
    lbContent.innerHTML = ''; // Stop video playback
}
function lbNav(dir) {
    lbIdx = (lbIdx + dir + images.length) % images.length;
    renderLb();
}
function renderLb() {
    const item = images[lbIdx];
    lbContent.innerHTML = '';
    
    if (item.type === 'video') {
        const video = document.createElement('video');
        video.src = item.url;
        video.controls = true;
        video.autoplay = true;
        video.className = 'lightbox-img'; // Re-use styling
        video.style.maxHeight = '75vh';
        lbContent.appendChild(video);
    } else {
        const img = document.createElement('img');
        img.src = item.url;
        img.alt = item.alt;
        img.className = 'lightbox-img';
        lbContent.appendChild(img);
    }

    lbTitle.textContent = item.caption || item.albumTitle || '';
    lbSub.textContent   = item.caption ? '' : '';
    lbCount.textContent = `${lbIdx + 1} / ${images.length}`;

    // Update download link
    if (item.type !== 'video' && lbDownload) {
        lbDownload.href = item.url;
        lbDownload.style.display = 'flex';
    } else if (lbDownload) {
        lbDownload.style.display = 'none';
    }
}

document.addEventListener('keydown', e => {
    if (lb.style.display === 'none') return;
    if (e.key === 'Escape')     closeLightbox();
    if (e.key === 'ArrowLeft')  lbNav(-1);
    if (e.key === 'ArrowRight') lbNav(1);
});

// Reveal observer
document.addEventListener('DOMContentLoaded', () => {
    const obs = new IntersectionObserver(
        entries => entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); obs.unobserve(e.target); } }),
        { threshold: 0.1 }
    );
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
});
</script>

</x-public-layout>