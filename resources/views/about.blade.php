<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="Learn about Paroisse Saint-Joseph Artisan in Riviera Bonoumin, Abidjan, Côte d'Ivoire. Discover our history, the Queen of the Most Holy Rosary, office hours, and contact details.">
    </x-slot>

    @php
        // Settings images must be publicly accessible (works for both local `public` disk and Supabase).
        $settingsDisk = config('filesystems.default') === 'local' ? 'public' : config('filesystems.default');
        $settingsImageUrl = static function (?string $path) use ($settingsDisk): string {
            if (! $path) {
                return '';
            }

            return config('filesystems.default') === 'local'
                ? '/storage/'.ltrim($path, '/')
                : \Illuminate\Support\Facades\Storage::disk($settingsDisk)->url($path);
        };
    @endphp

    {{-- ───────────── STYLES + ANIMATIONS ───────────── --}}
    <style>
        /* ── Tokens ── */
        :root {
            --maroon: var(--color-primary);
            --gold:   var(--color-gold);
            --cream:  var(--color-cream);
            --border: rgba(26,64,128,0.12);
            --text:   #4A5568;
            --muted:  rgba(13,42,82,0.45);
        }

        /* .font-heading now comes from app.css (Cormorant Garamond) */

        /* ════════════════════════════════════════
           KEYFRAMES
        ════════════════════════════════════════ */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-32px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(32px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.92); }
            to   { opacity: 1; transform: scale(1); }
        }
        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }
        @keyframes pulse-dot {
            0%, 100% { box-shadow: 0 0 0 0 rgba(245,197,24,.6); }
            50%       { box-shadow: 0 0 0 7px rgba(245,197,24,0); }
        }
        @keyframes lineGrow {
            from { transform: scaleY(0); transform-origin: top center; }
            to   { transform: scaleY(1); transform-origin: top center; }
        }
        @keyframes countUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }

        /* Respect reduced-motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }

        /* ════════════════════════════════════════
           SCROLL-REVEAL BASE
        ════════════════════════════════════════ */


        /* ════════════════════════════════════════
           HERO
        ════════════════════════════════════════ */
        .about-hero {
            background: var(--maroon);
            color: #fff;
            text-align: center;
            padding: 96px 24px 80px;
            position: relative;
            overflow: hidden;
        }
        .about-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 80% 60% at 50% 110%, rgba(245,197,24,.18) 0%, transparent 70%);
            pointer-events: none;
        }
        .about-hero::after {
            content: '';
            position: absolute;
            width: 520px; height: 520px;
            border-radius: 50%;
            border: 1px solid rgba(245,197,24,.07);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation: spin-slow 60s linear infinite;
        }
        .hero-eyebrow {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .35em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 18px;
            animation: fadeIn .6s ease both;
        }
        .hero-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            font-size: clamp(2.6rem, 7vw, 5rem);
            line-height: 1.05;
            margin-bottom: 20px;
            animation: fadeUp .8s .1s ease both;
        }
        .hero-divider {
            width: 48px; height: 1px;
            background: var(--gold);
            margin: 0 auto 20px;
            animation: scaleIn .6s .3s ease both;
        }
        .hero-sub {
            color: rgba(255,255,255,.65);
            font-size: .97rem;
            line-height: 1.75;
            max-width: 500px;
            margin: 0 auto;
            animation: fadeUp .8s .25s ease both;
        }

        /* ════════════════════════════════════════
           STATS BAR
        ════════════════════════════════════════ */
        .stats-bar {
            background: var(--cream);
            border-bottom: 1px solid var(--border);
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
        }
        .stat-cell {
            padding: 40px 0;
            text-align: center;
            border-right: 1px solid var(--border);
        }
        .stat-cell:last-child { border-right: none; }
        .stat-cell:nth-child(3) { border-right: none; }
        .stat-number {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 3.4rem;
            color: var(--maroon);
            line-height: 1;
        }
        .stat-number.counted { animation: countUp .7s ease both; }
        .stat-label {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: rgba(13,42,82,.58);
            margin-top: 8px;
        }

        /* ════════════════════════════════════════
           OUR CALLING
        ════════════════════════════════════════ */
        .calling-section {
            padding: 88px 24px;
            max-width: 960px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 48px;
            align-items: center;
        }
        @media (min-width: 700px) {
            .calling-section { flex-direction: row; gap: 72px; }
        }
        .calling-img-wrap {
            width: 100%;
            max-width: 380px;
            flex-shrink: 0;
            aspect-ratio: 4/3;
            border-radius: 18px;
            overflow: hidden;
            position: relative;
            background: var(--border);
            transition: transform .4s ease, box-shadow .4s ease;
        }
        .calling-img-wrap:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 48px rgba(13,42,82,.18);
        }
        .calling-img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform .6s ease;
        }
        .calling-img-wrap:hover img { transform: scale(1.04); }
        .calling-badge {
            position: absolute;
            bottom: 14px; left: 14px;
            background: var(--maroon);
            color: var(--gold);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .15em;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 4px;
        }
        .calling-eyebrow {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .25em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 14px;
        }
        .calling-title {
            font-size: clamp(1.6rem, 4vw, 2.4rem);
            color: var(--maroon);
            line-height: 1.2;
            margin-bottom: 18px;
        }
        .calling-rule { width: 36px; height: 2px; background: var(--gold); margin-bottom: 22px; }
        .calling-body { color: var(--text); line-height: 1.8; font-size: .95rem; margin-bottom: 14px; }

        /* ════════════════════════════════════════
           TIMELINE
        ════════════════════════════════════════ */
        .timeline-section {
            background: var(--cream);
            padding: 88px 0; /* Bleed to edges */
            overflow: hidden;
        }
        .timeline-section .section-eyebrow,
        .timeline-section .section-title {
            padding: 0 24px;
        }
        .section-eyebrow {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .25em;
            text-transform: uppercase;
            color: var(--gold);
            text-align: center;
            margin-bottom: 10px;
        }
        .section-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            font-size: clamp(2rem, 5vw, 3.2rem);
            color: var(--maroon);
            text-align: center;
            margin-bottom: 0;
        }

        .tl-scroll-container {
            width: 100%;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scrollbar-width: none; /* Firefox */
            padding-bottom: 20px;
            margin-top: 56px;
            cursor: grab;
        }
        .tl-scroll-container::-webkit-scrollbar { display: none; } /* Chrome/Safari */
 
        .tl-wrapper {
            display: flex;
            position: relative;
            width: max-content;
            padding: 20px 48px; /* 48px side padding so it doesn't stick to edge */
            margin: 0 auto;
            gap: 56px; /* Space between timeline items */
        }
 
        /* ── Timeline Navigation ── */
        .tl-nav-wrapper {
            position: relative;
            padding: 0 40px; /* Space for arrows */
        }
        .tl-btn {
            position: absolute;
            top: 40px; /* Center with the dots */
            width: 44px; height: 44px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--maroon);
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(13,42,82,0.08);
        }
        .tl-btn:hover {
            background: var(--gold);
            color: var(--maroon);
            border-color: var(--gold);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(245,197,24,0.3);
        }
        .tl-btn-left { left: 10px; }
        .tl-btn-right { right: 10px; }
        @media (max-width: 640px) {
            .tl-btn { width: 36px; height: 36px; }
            .tl-btn-left { left: 4px; }
            .tl-btn-right { right: 4px; }
            .tl-wrapper { padding: 20px 36px; gap: 40px; }
        }
        .tl-btn svg { width: 20px; height: 20px; }

        .tl-spine {
            position: absolute;
            top: 29px; /* 20px top padding + 9px (center of 18px dot) */
            left: 57px; /* 48px left padding + 9px */
            right: 57px; /* 48px right padding + 9px */
            height: 1px;
            background: var(--maroon);
            transform-origin: left center;
            transform: scaleX(0);
            transition: transform 1.5s cubic-bezier(.16,1,.3,1);
            z-index: 0;
        }
        .tl-spine.revealed { transform: scaleX(1); }

        .tl-item {
            position: relative;
            flex: 0 0 280px; /* Fixed width per item */
            scroll-snap-align: center;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity .5s ease, transform .5s ease, scale .4s cubic-bezier(.175, .885, .32, 1.275);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            z-index: 1;
            will-change: transform;
        }
        .tl-item.visible { opacity: 1; transform: none; }
        .tl-item.dragging { transform: scale(0.96) translateY(2px); transition: transform 0.2s ease; }

        .tl-dot {
            width: 18px; height: 18px;
            border-radius: 50%;
            background: var(--maroon);
            border: 4px solid var(--cream);
            box-shadow: 0 0 0 1px var(--maroon);
            transition: transform .3s ease, background .3s, box-shadow .3s;
            margin-bottom: 32px;
            position: relative;
            z-index: 2;
            flex-shrink: 0;
        }
        .tl-item:hover .tl-dot {
            transform: scale(1.3);
            background: var(--gold);
            box-shadow: 0 0 0 1px var(--gold);
        }

        .tl-badge {
            display: inline-block;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            background: var(--maroon);
            color: #fff;
            padding: 3px 10px;
            border-radius: 4px;
            margin-bottom: 14px;
        }
        .tl-year {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 2.2rem;
            color: var(--maroon);
            line-height: 1;
            margin-bottom: 8px;
        }
        .tl-title {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--maroon);
            margin-bottom: 12px;
            line-height: 1.3;
        }
        .tl-body {
            font-size: .85rem;
            color: var(--text);
            line-height: 1.6;
        }
        .tl-content {
            display: block;
            text-align: left;
        }

        .tl-body-full { display: none; }
        .tl-item.expanded .tl-body-full { display: inline; }
        .tl-toggle {
            display: inline-block;
            margin-top: 12px;
            font-size: .8rem;
            font-weight: 700;
            color: var(--maroon);
            cursor: pointer;
            letter-spacing: .05em;
            user-select: none;
            transition: opacity .2s;
            background: none;
            border: none;
            padding: 0;
            font-family: inherit;
        }
        .tl-toggle:hover { opacity: .7; }

        /* ════════════════════════════════════════
           LEADERSHIP
        ════════════════════════════════════════ */
        .leadership-section {
            background: var(--maroon);
            padding: 88px 24px;
            text-align: center;
        }
        .leadership-section .section-eyebrow { color: var(--gold); }
        .leadership-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            font-size: clamp(2rem, 5vw, 3.2rem);
            color: #fff;
            margin-bottom: 44px;
        }
        .leader-card {
            background: #fff;
            border-radius: 24px;
            padding: 48px 40px;
            max-width: 400px;
            width: 100%;
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .leader-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--gold), #ffdf70);
            transform: scaleX(0);
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            transform-origin: center;
        }
        .leader-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 32px 64px rgba(0,0,0,0.25);
        }
        .leader-card:hover::before {
            transform: scaleX(1);
        }
        .leader-avatar {
            width: 160px; height: 160px;
            border-radius: 50%;
            background: var(--maroon);
            color: var(--gold);
            font-size: 3.5rem;
            font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 32px;
            box-shadow: 0 12px 24px rgba(0,0,0,0.1);
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            border: 4px solid #fff;
            outline: 2px solid var(--gold);
            outline-offset: 4px;
        }
        .leader-card:hover .leader-avatar {
            transform: scale(1.05) translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.18);
        }
        .leader-name {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.4rem;
            color: var(--maroon);
            margin-bottom: 8px;
            transition: color 0.3s ease;
        }
        .leader-role {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--gold);
            margin: 0 0 24px;
        }
        .leader-rule { 
            width: 40px; 
            height: 2px; 
            background: var(--gold); 
            margin: 0 auto 20px; 
            transition: width 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .leader-card:hover .leader-rule {
            width: 80px;
        }
        .leader-quote { font-size: .87rem; font-style: italic; color: var(--muted); line-height: 1.7; }

        /* ════════════════════════════════════════
           PRIEST CONTRIBUTIONS
        ════════════════════════════════════════ */
        .priest-contrib {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid rgba(139,69,19,0.1);
        }
        .priest-contrib-short {
            font-size: 0.85rem;
            color: var(--maroon);
            line-height: 1.6;
            margin-bottom: 8px;
        }
        .priest-contrib-details {
            font-size: 0.8rem;
            color: var(--muted);
            line-height: 1.6;
        }
        .priest-contrib-details summary {
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 6px;
            list-style: none;
        }
        .priest-contrib-details summary::-webkit-details-marker { display: none; }
        .priest-contrib-details summary::before {
            content: '+ ';
            font-weight: 800;
        }
        .priest-contrib-details[open] summary::before {
            content: '− ';
        }
        .priest-contrib-details p {
            margin-top: 6px;
        }
        .leader-card.former-priest-card .priest-contrib {
            margin-top: 12px;
            padding-top: 12px;
        }

        /* ════════════════════════════════════════
           ABOUT VIDEO
        ════════════════════════════════════════ */
        .about-video-section {
            background: var(--cream);
            padding: 88px 24px;
            text-align: center;
        }
        .about-video-inner { max-width: 960px; margin: 0 auto; }
        .about-video-desc {
            font-size: 1rem;
            color: var(--muted);
            max-width: 640px;
            margin: 0 auto 32px;
            line-height: 1.7;
            font-style: italic;
        }
        .about-video-frame {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 24px 64px rgba(13, 42, 82, 0.15);
            background: #000;
        }
        .about-video-frame iframe {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
        }

        /* Former priests — smaller cards */
        .former-priests-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
            max-width: 1100px;
            margin: 48px auto 0;
        }
        @media (min-width: 640px) { .former-priests-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 960px) { .former-priests-grid { grid-template-columns: repeat(3, 1fr); } }
        .leader-card.former-priest-card {
            max-width: none;
            padding: 32px 28px;
        }
        .leader-card.former-priest-card .leader-avatar {
            width: 120px;
            height: 120px;
            font-size: 2.5rem;
            margin-bottom: 24px;
        }
        .former-priests-heading {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            font-size: clamp(1.5rem, 3vw, 2rem);
            color: rgba(255,255,255,0.9);
            margin-top: 64px;
            margin-bottom: 8px;
        }
        .former-priests-sub {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.55);
            margin-bottom: 0;
            font-style: italic;
        }

        /* ════════════════════════════════════════
           FIND US
        ════════════════════════════════════════ */
        .findus-section { background: var(--cream); padding: 88px 24px; }
        .findus-inner { max-width: 960px; margin: 0 auto; }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
            margin: 48px 0 28px;
        }
        @media (min-width: 600px) { .info-grid { grid-template-columns: repeat(3,1fr); } }

        .info-card {
            background: #fff;
            border: 0.5px solid var(--border);
            border-radius: 14px;
            padding: 28px 22px;
            text-align: center;
            transition: transform .35s ease, box-shadow .35s ease;
        }
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 36px rgba(13,42,82,.1);
        }
        .info-icon {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: var(--cream);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
        }
        .info-card-label {
            font-size: 8px;
            font-weight: 800;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: #bbb;
            margin-bottom: 10px;
        }
        .info-card p  { font-size: .88rem; font-weight: 600; color: var(--maroon); line-height: 1.6; }
        .info-card small { font-size: .78rem; color: var(--muted); }

        .map-wrap {
            border-radius: 18px;
            overflow: hidden;
            border: 0.5px solid var(--border);
            box-shadow: 0 4px 28px rgba(13,42,82,.09);
        }
        .map-credit { text-align: center; font-size: .72rem; color: #bbb; margin-top: 10px; }
        .map-credit a { color: var(--gold); }

        /* ════════════════════════════════════════
           CTA
        ════════════════════════════════════════ */
        .cta-section {
            background: var(--maroon);
            padding: 88px 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 70% 50% at 50% 100%, rgba(245,197,24,.14) 0%, transparent 70%);
            pointer-events: none;
        }
        .cta-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            font-size: clamp(2.2rem, 6vw, 4rem);
            color: #fff;
            margin-bottom: 14px;
        }
        .cta-rule { width: 40px; height: 1px; background: var(--gold); margin: 0 auto 20px; }
        .cta-sub { font-size: .87rem; color: rgba(255,255,255,.45); margin-bottom: 40px; }
        .cta-btns { display: flex; flex-wrap: wrap; gap: 14px; justify-content: center; }

        .btn-gold {
            display: inline-block;
            background: var(--gold);
            color: var(--maroon);
            font-size: .87rem;
            font-weight: 700;
            padding: 13px 30px;
            border-radius: 8px;
            text-decoration: none;
            transition: opacity .2s, transform .2s;
        }
        .btn-gold:hover { opacity: .88; transform: translateY(-2px); }

        .btn-ghost {
            display: inline-block;
            border: 1px solid rgba(255,255,255,.28);
            color: #fff;
            font-size: .87rem;
            font-weight: 700;
            padding: 13px 30px;
            border-radius: 8px;
            text-decoration: none;
            transition: background .2s, transform .2s;
        }
        .btn-ghost:hover { background: rgba(255,255,255,.08); transform: translateY(-2px); }

        .hero-divider:hover {
            background: linear-gradient(90deg, var(--gold), #fff, var(--gold));
            background-size: 200% auto;
            animation: shimmer 1.2s linear infinite;
        }


        /* ═══════════════ SACRAMENTS ═══════════════ */
        .sacraments-section {
            padding: 100px 24px; background: var(--blue-pale); position: relative; overflow: hidden;
        }
        .sacraments-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px; max-width: 1200px; margin: 60px auto 0;
        }
        .sacrament-card {
            background: #fff; padding: 40px 30px; border-radius: 24px;
            border: 1px solid rgba(13,42,82,0.08); transition: all 0.4s ease;
            text-align: center; position: relative; overflow: hidden;
        }
        .sacrament-card:hover {
            transform: translateY(-8px); border-color: var(--gold);
            box-shadow: 0 20px 40px rgba(13,42,82,0.1);
        }
        .sacrament-icon {
            width: 56px; height: 56px; background: rgba(245,197,24,0.1);
            border-radius: 16px; display: flex; align-items: center;
            justify-content: center; margin: 0 auto 24px; color: var(--gold);
            transition: all 0.4s ease;
        }
        .sacrament-card:hover .sacrament-icon {
            background: var(--gold); color: #fff; transform: scale(1.1) rotate(5deg);
        }
        .sacrament-img-wrap {
            width: 100%; height: 200px; border-radius: 16px; overflow: hidden;
            margin-bottom: 24px; position: relative; background: var(--cream);
        }
        .sacrament-img-wrap img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.6s ease;
        }
        .sacrament-card:hover .sacrament-img-wrap img {
            transform: scale(1.05);
        }
        .sacrament-img-overlay {
            position: absolute; bottom: 0; left: 0; right: 0;
            height: 60px; background: linear-gradient(transparent, rgba(255,255,255,0.9));
            pointer-events: none;
        }
        .sacrament-title {
            font-family: 'Cormorant Garamond', serif; font-size: 1.5rem;
            font-weight: 700; color: var(--blue-deep); margin-bottom: 12px; font-style: italic;
        }
        .sacrament-body {
            font-size: 0.9rem; color: rgba(13,42,82,0.5); line-height: 1.6;
        }

        /* ═══════════════ PATRON SAINTS ═══════════════ */
        .patrons-section {
            padding: 100px 24px; background: #fff;
        }
        .patrons-layout {
            display: grid; grid-template-columns: 1fr 1.2fr; gap: 80px;
            max-width: 1100px; margin: 60px auto 0; align-items: center;
        }
        .patron-content h3 {
            font-family: 'Cormorant Garamond', serif; font-size: 2.8rem;
            font-weight: 700; color: var(--blue-deep); margin-bottom: 24px; font-style: italic; line-height: 1.1;
        }
        .patron-rule {
            width: 60px; height: 2px; background: var(--gold); margin-bottom: 30px;
        }
        .patron-description {
            font-size: 1.05rem; color: rgba(13,42,82,0.6); line-height: 1.8; margin-bottom: 24px;
        }
        .patron-img-wrap {
            position: relative; border-radius: 30px; overflow: hidden;
            box-shadow: 0 30px 60px rgba(13,42,82,0.15); aspect-ratio: 4/5;
        }
        .patron-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .patron-badge {
            position: absolute; bottom: 30px; left: 30px; background: rgba(13,42,82,0.95);
            color: #fff; padding: 12px 24px; border-radius: 100px;
            font-size: 11px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase;
            backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);
        }

        @media (max-width: 992px) {
            .patrons-layout { grid-template-columns: 1fr; gap: 40px; }
            .patron-content { order: 2; text-align: center; }
            .patron-rule { margin: 0 auto 30px; }
            .patron-img-wrap { order: 1; max-width: 500px; margin: 0 auto; }
        }
    </style>

    {{-- ═══════════════ HERO ═══════════════ --}}
    <section class="about-hero">
        <p class="hero-eyebrow">Riviera Bonoumin, Abidjan, Côte d'Ivoire</p>
        <h1 class="hero-title font-heading">About Our Parish</h1>
        <div class="hero-divider"></div>
        <p class="hero-sub">
            Home to the Queen of the Most Holy Rosary — a beacon of faith,<br class="hidden md:block">
            community, and service for over four decades.
        </p>
    </section>

    {{-- ═══════════════ STATS ═══════════════ --}}
    <section class="stats-bar">
        @php
            $foundedYear = 2010;
            $yearsOfService = max(1, now('Asia/Manila')->year - $foundedYear);
        @endphp
        <div class="stat-cell">
            <p class="stat-number" data-target="{{ $yearsOfService }}" data-suffix="+">{{ $yearsOfService }}+</p>
            <p class="stat-label">Years of Service</p>
        </div>
        <div class="stat-cell">
            <p class="stat-number" data-target="{{ $foundedYear }}" data-suffix="">{{ $foundedYear }}</p>
            <p class="stat-label">Year Founded</p>
        </div>
        <div class="stat-cell">
            <p class="stat-number" data-target="13" data-suffix="">13</p>
            <p class="stat-label">Weekly Masses</p>
        </div>
    </section>

    {{-- ═══════════════ OUR CALLING ═══════════════ --}}
    <section style="background:#fff;">
        <div class="calling-section">
            <div class="calling-img-wrap" data-reveal="scale">
                <img
                    src="{{ asset('images/eglise-interieur.jpg') }}"
                    alt="Intérieur de l’église paroissiale Saint-Joseph Artisan"
                >
                <div class="calling-badge">Depuis 2010</div>
            </div>
            <div data-reveal="right">
                <p class="calling-eyebrow">Our Calling</p>
                    <h2 class="font-heading calling-title">Bâtir un sanctuaire<br>de foi, d'unité et de service</h2>
                <div class="calling-rule"></div>
                <p class="calling-body">
                    La Paroisse Saint-Joseph Artisan de Riviera Bonoumin est un foyer spirituel vibrant ancré au cœur d'Abidjan. Née de l'élan des fidèles rassemblés dès 2006 sous un apatam, la communauté a été érigée canoniquement en 2010 sous le patronage de Saint-Joseph Artisan. Elle est guidée aujourd'hui par son curé, le Révérend Père Serge-Daniel Acaffou, et son vicaire, le Père Joseph Lobakre.
                </p>
                <p class="calling-body">
    Ancrée dans le doyenné Père Michel Pango de l'Archidiocèse d'Abidjan, notre paroisse se distingue par son dynamisme pastoral à travers ses Communautés Ecclésiales de Base (CEB), ses mouvements d'action catholique, ses chorales et ses commissions engagées dans la vie spirituelle et sociale des fidèles.
</p>
                <p class="calling-body">
                    Nous vous accueillons chaleureusement, que vous soyez un paroissien de longue date, un visiteur ou quelqu'un qui cherche à se rapprocher de la foi. Rejoignez-nous pour la messe, participez à nos activités et faites de la Paroisse Saint-Joseph Artisan un lieu où la prière devient communion et où la communauté devient mission.
                </p>
            </div>
        </div>
    </section>

    {{-- ═══════════════ THE SEVEN SACRAMENTS ═══════════════ --}}
    <section class="sacraments-section">
        <div class="text-center">
            <p class="section-eyebrow" data-reveal>Les Piliers de notre Foi</p>
            <h2 class="section-title" data-reveal style="font-size:3rem; font-style:italic;">Les Sept Sacrements</h2>
            <div style="width:40px; height:1px; background:var(--gold); margin:20px auto 0;"></div>
        </div>

        <div class="sacraments-grid">
            @php
            $settingsDisk = config('filesystems.default') === 'local' ? 'public' : config('filesystems.default');

            $sacraments = [
                ['name' => 'Baptême',        'desc' => 'Porte des sacrements et nécessaire au salut, le baptême nous libère du péché et nous fait renaître comme enfants de Dieu.', 'icon' => 'droplets', 'image_key' => 'sacrament_baptism_image', 'fallback' => 'assets/sacraments/baptism.svg', 'alt' => 'Baptême à la Paroisse Saint-Joseph Artisan'],
                ['name' => 'Confirmation',   'desc' => 'Perfection de la grâce baptismale, la confirmation nous fortifie par les dons de l\'Esprit Saint afin que nous soyons témoins du Christ.', 'icon' => 'flame', 'image_key' => 'sacrament_confirmation_image', 'fallback' => 'assets/sacraments/confirmation.svg', 'alt' => 'Confirmation à la Paroisse Saint-Joseph Artisan'],
                ['name' => 'Eucharistie',    'desc' => 'Source et sommet de la vie chrétienne, l\'Eucharistie nous donne de recevoir réellement le corps et le sang de notre Seigneur Jésus-Christ.', 'icon' => 'sun', 'image_key' => 'sacrament_eucharist_image', 'fallback' => 'assets/sacraments/eucharist.svg', 'alt' => 'Célébration eucharistique à la Paroisse Saint-Joseph Artisan'],
                ['name' => 'Réconciliation', 'desc' => 'Le sacrement de pénitence et de réconciliation nous permet de recevoir la miséricorde de Dieu pour les péchés commis contre Lui.', 'icon' => 'shield-check', 'image_key' => 'sacrament_penance_image', 'fallback' => 'assets/sacraments/penance.svg', 'alt' => 'Confession à la Paroisse Saint-Joseph Artisan'],
                ['name' => 'Onction des malades', 'desc' => 'Source de guérison spirituelle et physique pour les personnes gravement affaiblies par la maladie ou la vieillesse.', 'icon' => 'hand-heart', 'image_key' => 'sacrament_anointing_image', 'fallback' => 'assets/sacraments/anointing.svg', 'alt' => 'Sacrement des malades à la Paroisse Saint-Joseph Artisan'],
                ['name' => 'Ordre',          'desc' => 'Le sacrement par lequel la mission confiée par le Christ à ses apôtres continue d\'être exercée dans l\'Église.', 'icon' => 'cross', 'image_key' => 'sacrament_holy_orders_image', 'fallback' => 'assets/sacraments/holy-orders.svg', 'alt' => 'Ordination à la Paroisse Saint-Joseph Artisan'],
                ['name' => 'Mariage',        'desc' => 'Alliance sacrée entre un homme et une femme, le mariage est un engagement de toute une vie pour leur bien commun.', 'icon' => 'heart', 'image_key' => 'sacrament_matrimony_image', 'fallback' => 'assets/sacraments/matrimony.svg', 'alt' => 'Mariage à la Paroisse Saint-Joseph Artisan'],
            ];

            $icons = [
                'droplets' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>',
                'flame' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.5 4 6.5 2 2 3 5.5 3 8.5a7 7 0 0 1-14 0c0-1.15.3-2.35.9-3.5 1.5.5 2.5 1.5 3 2.5z"/></svg>',
                'sun' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>',
                'shield-check' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>',
                'hand-heart' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 14h2a2 2 0 1 0 0-4h-3.5a2 2 0 1 0 0 4"/><path d="M20.42 4.58a5.4 5.4 0 0 0-7.65 0l-.77.78-.77-.78a5.4 5.4 0 0 0-7.65 0C1.46 6.7 1.33 10.28 4 13l8 8 8-8c2.67-2.72 2.54-6.3.42-8.42z"/></svg>',
                'cross' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 2h2v20h-2z"/><path d="M5 8h14v2H5z"/></svg>',
                'heart' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>',
            ];
            @endphp

            @foreach($sacraments as $s)
            @php
                $sacramentImage = $global_settings[$s['image_key']] ?? null;
                $sacramentSrc = $sacramentImage
                    ? $settingsImageUrl($sacramentImage)
                    : asset($s['fallback']);
            @endphp
            <div class="sacrament-card" data-reveal="up" style="transition-delay: {{ $loop->index * 0.1 }}s">
                <div class="sacrament-img-wrap">
                    <img
                        src="{{ $sacramentSrc }}"
                        alt="{{ $s['alt'] }}"
                        loading="lazy"
                    >
                    <div class="sacrament-img-overlay"></div>
                </div>
                <h3 class="sacrament-title">{{ $s['name'] }}</h3>
                <p class="sacrament-body">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ═══════════════ TIMELINE ═══════════════ --}}
    <section class="timeline-section">
        <p class="section-eyebrow" data-reveal>The Journey</p>
        <h2 class="section-title" data-reveal>Our sacred history</h2>

        <div class="tl-nav-wrapper">
            <button class="tl-btn tl-btn-left" id="tl-left" aria-label="Scroll Left">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <button class="tl-btn tl-btn-right" id="tl-right" aria-label="Scroll Right">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-9-6"/></svg>
            </button>

            <div class="tl-scroll-container">
                <div class="tl-wrapper">
                    <div class="tl-spine" id="tl-spine"></div>

                    @php
                    $timelineRaw = $global_settings['parish_timeline'] ?? '';
                    $timeline = is_string($timelineRaw) && $timelineRaw !== ''
                        ? (json_decode($timelineRaw, true) ?: \App\Data\DefaultTimeline::entries())
                        : (is_array($timelineRaw) && !empty($timelineRaw) ? $timelineRaw : \App\Data\DefaultTimeline::entries());
                    @endphp

                    @foreach($timeline as $i => $e)
                    <div class="tl-item" data-index="{{ $i }}">
                        <div class="tl-dot"></div>
                        <div class="tl-content">
                            @if($e['badge'])
                                <span class="tl-badge">{{ $e['badge'] }}</span>
                            @endif
                            <div class="tl-year">{{ $e['year'] }}</div>
                            <div class="tl-title">{{ $e['title'] }}</div>
                            <p class="tl-body">
                                {{ $e['short'] }}
                                <span class="tl-body-full"> {{ $e['full'] }}</span>
                            </p>
                            <button class="tl-toggle" onclick="toggleItem(this)" aria-expanded="false" aria-controls="tl-body-{{ $i }}">Read more ↓</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @php
        $aboutVideoUrl = $aboutVideoUrl ?? ($global_settings['about_video_url'] ?? '');
        $aboutVideoEmbed = $aboutVideoEmbed ?? ($aboutVideoUrl ? \App\Support\VideoEmbed::embedUrl($aboutVideoUrl) : null);
        $aboutVideoTitle = $aboutVideoTitle ?? ($global_settings['about_video_title'] ?? null);
        $aboutVideoDescription = $aboutVideoDescription ?? ($global_settings['about_video_description'] ?? null);
        if (! isset($formerPriests)) {
            $formerPriestsRaw = $global_settings['former_priests'] ?? '[]';
            $formerPriests = is_string($formerPriestsRaw)
                ? (json_decode($formerPriestsRaw, true) ?: [])
                : (is_array($formerPriestsRaw) ? $formerPriestsRaw : []);
        }
    @endphp
    @if($aboutVideoEmbed)
    {{-- ═══════════════ FEATURED VIDEO ═══════════════ --}}
    <section class="about-video-section">
        <div class="about-video-inner">
            <p class="section-eyebrow" data-reveal>Featured Video</p>
            <h2 class="section-title" data-reveal>{{ $aboutVideoTitle ?: 'Our Parish Story' }}</h2>
            @if(!empty($aboutVideoDescription))
                <p class="about-video-desc" data-reveal>{{ $aboutVideoDescription }}</p>
            @endif
            <div class="about-video-frame" data-reveal="scale">
                <iframe
                    src="{{ $aboutVideoEmbed }}"
                    title="{{ $aboutVideoTitle ?: 'Parish video' }}"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allowfullscreen></iframe>
            </div>
        </div>
    </section>
    @endif

    {{-- ═══════════════ CELESTIAL INTERCESSORS ═══════════════ --}}
    <section class="patrons-section">
        <div class="text-center">
            <p class="section-eyebrow" data-reveal>Celestial Intercessors</p>
            <h2 class="section-title" data-reveal style="font-size:3rem; font-style:italic;">Our Patron Saints</h2>
        </div>

        <div class="patrons-layout">
            <div class="patron-content" data-reveal="left">
                <h3>Saint Joseph Artisan</h3>
                <div class="patron-rule"></div>
                <p class="patron-description">
                    Établi au cœur de notre paroisse à la Riviera Bonoumin, Saint Joseph Artisan se dresse comme le patron titulaire et le protecteur bien-aimé de notre communauté. 
                </p>
                <p class="patron-description" style="font-size:0.95rem; font-style:italic;">
                    « Par son intercession, nous trouvons la force d'accomplir nos tâches quotidiennes avec amour, unis comme une seule famille dans la grâce de son Fils, notre Seigneur Jésus-Christ. »
                </p>
                <div style="margin-top:40px;">
                    <span style="font-size:12px; font-weight:700; color:var(--gold); letter-spacing:0.1em; text-transform:uppercase;">Fête patronale : 1er Mai</span>
                </div>
            </div>
            <div class="patron-img-wrap" data-reveal="scale">
                <img src="{{ asset('images/saint-joseph-artisan.jpg') }}" alt="Statue de Saint Joseph Artisan tenant un lys et un outil">
                <div class="patron-badge">Saint Patron Titulaire</div>
            </div>
        </div>

        <div class="patrons-layout" style="margin-top:100px;">
            <div class="patron-img-wrap" data-reveal="scale">
                <img src="{{ asset('images/Marie.jpg') }}" alt="La Sainte Vierge Marie">
                <div class="patron-badge">Seconde Patronne</div>
            </div>
            <div class="patron-content" data-reveal="right">
                <h3>La Sainte Vierge Marie</h3>
                <div class="patron-rule"></div>
                <p class="patron-description">
                    Modèle de foi et Mère de notre communauté, la Sainte Vierge Marie veille sur les fidèles de notre paroisse et de nos Communautés Ecclésiales de Base (CEB). Elle guide nos pas vers son Fils avec un amour maternel et une intercession constante.
                </p>
                <p class="patron-description" style="font-size:0.95rem; font-style:italic;">
                    « Sous son manteau protecteur, notre communauté trouve la paix, l'unité et l'inspiration pour grandir dans la charité et le service. »
                </p>
                <div style="margin-top:40px;">
                    <span style="font-size:12px; font-weight:700; color:var(--gold); letter-spacing:0.1em; text-transform:uppercase;">Fête : 8 Décembre (Immaculée Conception)</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════ LEADERSHIP ═══════════════ --}}
    <section class="leadership-section">
        <p class="section-eyebrow" data-reveal>Our Leadership</p>
        <h2 class="leadership-title" data-reveal>Les prêtres de<br>the flock</h2>
        <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:32px; max-width:960px; margin:0 auto;">
            <div class="leader-card" data-reveal="scale">
                @if(isset($global_settings['priest_image']))
                    <div class="leader-avatar" style="background-image: url('{{ $settingsImageUrl($global_settings['priest_image']) }}'); background-size: cover; background-position: center;"></div>
                @else
                    <div class="leader-avatar">FV</div>
                @endif
                <h3 class="leader-name">{{ $global_settings['priest_name'] ?? 'Rev. Fr. Parish Priest' }}</h3>
                <p class="leader-role">{{ $global_settings['priest_role'] ?? 'Parish Priest' }}</p>
                <div class="leader-rule"></div>
                @if(!empty($global_settings['priest_quote']))
                    <p class="leader-quote">"{{ $global_settings['priest_quote'] }}"</p>
                @endif
                @if(!empty($currentPriestContrib['show']))
                <div class="priest-contrib">
                    @if(!empty($currentPriestContrib['short']))
                        <p class="priest-contrib-short">{{ $currentPriestContrib['short'] }}</p>
                    @endif
                    @if(!empty($currentPriestContrib['full']))
                        <details class="priest-contrib-details">
                            <summary>Read more</summary>
                            <p>{{ $currentPriestContrib['full'] }}</p>
                        </details>
                    @endif
                </div>
                @endif
            </div>
            @if(!empty($global_settings['assistant_priest_name']))
            <div class="leader-card" data-reveal="scale">
                @if(isset($global_settings['assistant_priest_image']))
                    <div class="leader-avatar" style="background-image: url('{{ $settingsImageUrl($global_settings['assistant_priest_image']) }}'); background-size: cover; background-position: center;"></div>
                @else
                    <div class="leader-avatar">AP</div>
                @endif
                <h3 class="leader-name">{{ $global_settings['assistant_priest_name'] }}</h3>
                <p class="leader-role">{{ $global_settings['assistant_priest_role'] ?? 'Assistant Parish Priest' }}</p>
                <div class="leader-rule"></div>
                @if(!empty($global_settings['assistant_priest_quote']))
                    <p class="leader-quote">"{{ $global_settings['assistant_priest_quote'] }}"</p>
                @endif
            </div>
            @endif
        </div>

        @if(!empty($formerPriests))
        <div style="max-width:1100px; margin:0 auto;">
            <h3 class="former-priests-heading" data-reveal>Former Parish Priests</h3>
            <p class="former-priests-sub" data-reveal>Shepherds who faithfully served our community</p>
            <div class="former-priests-grid">
                @foreach($formerPriests as $fp)
                @php
                    $initials = collect(preg_split('/\s+/', $fp['name'] ?? ''))
                        ->filter(fn ($p) => !preg_match('/^(rev|fr|father|msgr|monsignor)\.?$/i', $p))
                        ->map(fn ($p) => mb_substr($p, 0, 1))
                        ->take(2)
                        ->implode('');
                @endphp
                <div class="leader-card former-priest-card" data-reveal="scale">
                    @if(!empty($fp['image']))
                        <div class="leader-avatar" style="background-image: url('{{ $settingsImageUrl($fp['image']) }}'); background-size: cover; background-position: center;"></div>
                    @else
                        <div class="leader-avatar">{{ $initials ?: '?' }}</div>
                    @endif
                    <h3 class="leader-name">{{ $fp['name'] }}</h3>
                    <p class="leader-role">{{ $fp['role'] ?? 'Parish Priest' }}@if(!empty($fp['years'])) · {{ $fp['years'] }}@endif</p>
                    <div class="leader-rule"></div>
                    @if(!empty($fp['quote']))
                        <p class="leader-quote">"{{ $fp['quote'] }}"</p>
                    @endif
                    @if(!empty($fp['show_contrib']))
                    <div class="priest-contrib">
                        @if(!empty($fp['contrib_short']))
                            <p class="priest-contrib-short">{{ $fp['contrib_short'] }}</p>
                        @endif
                        @if(!empty($fp['contrib_full']))
                            <details class="priest-contrib-details">
                                <summary>Read more</summary>
                                <p>{{ $fp['contrib_full'] }}</p>
                            </details>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </section>

    {{-- ═══════════════ FIND US ═══════════════ --}}
    <section class="findus-section">
        <div class="findus-inner">
            <p class="section-eyebrow" data-reveal>Find Us</p>
            <h2 class="section-title" data-reveal>We're here for you</h2>

            <div class="info-grid">
                <div class="info-card" data-reveal>
                    <div class="info-icon">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z" fill="#F5C518"/></svg>
                    </div>
                    <p class="info-card-label">Address</p>
                    @php
                        $address = $global_settings['parish_address'] ?? 'Paroisse St Joseph https://maps.app.goo.gl/ineJKGS5ux346zJR9';
                        $addressHtml = nl2br(e($address));
                    @endphp
                    <p>{!! $addressHtml !!}</p>
                </div>

                <div class="info-card" data-reveal style="transition-delay:.1s">
                    <div class="info-icon">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24c1.12.37 2.33.57 3.58.57a1 1 0 011 1V20a1 1 0 01-1 1C9.39 21 3 14.61 3 7a1 1 0 011-1h3.5a1 1 0 011 1c0 1.25.2 2.45.57 3.57a1 1 0 01-.25 1.01l-2.2 2.21z" fill="#F5C518"/></svg>
                    </div>
                    <p class="info-card-label">Contact</p>
                    @php
                        $contactRaw = $global_settings['parish_contact'] ?? '';
                        if (is_string($contactRaw) && $contactRaw !== '') {
                            $decoded = json_decode($contactRaw, true);
                            $contactNumbers = is_array($decoded) ? $decoded : [$contactRaw];
                        } elseif (is_array($contactRaw)) {
                            $contactNumbers = $contactRaw;
                        } else {
                            $contactNumbers = ['2722496069'];
                        }
                    @endphp
                    @foreach($contactNumbers as $number)
                    <p>{{ $number }}</p>
                    @endforeach
                    <small>{{ $global_settings['parish_email'] ?? config('services.parish.office_email', 'paroisse.stjoseph.bonoumin@gmail.com') }}</small>
                </div>

                <div class="info-card" data-reveal style="transition-delay:.2s">
                    <div class="info-icon">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke="#F5C518" stroke-width="2"/><path d="M12 7v5l3 3" stroke="#F5C518" stroke-width="2" stroke-linecap="round"/></svg>
                    </div>
                    <p class="info-card-label">Office Hours</p>
                    <p>Tue–Sat</p>
                    <small>6:00 AM – 12:00 NN · 1:30 PM – 6:00 PM</small>
                    <p style="margin-top:8px">Sunday</p>
                    <small>6:00 AM – 12:00 NN · 3:00 PM – 6:00 PM</small>
                </div>
            </div>

            {{-- MAP --}}
            <div class="map-wrap" id="visit-map" data-reveal>
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <div id="parish-map" style="width:100%;height:400px;"></div>
            </div>
            <p class="map-credit">
                Map data © <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors
            </p>
        </div>
    </section>

    {{-- ═══════════════ CTA ═══════════════ --}}
    <section class="cta-section">
        <h2 class="cta-title" data-reveal>Visit us today</h2>
        <div class="cta-rule" data-reveal></div>
        <p class="cta-sub" data-reveal>Our doors and hearts are always open to you.</p>
        <div class="cta-btns" data-reveal>
            <a href="{{ route('mass-schedule') }}" class="btn-gold">View schedule</a>
            <a href="{{ route('inquiry') }}"       class="btn-ghost">Contact office</a>
        </div>
    </section>

    {{-- ═══════════════ SCRIPTS ═══════════════ --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    /* ── Leaflet map ── */
    document.addEventListener('DOMContentLoaded', function () {
        const lat = 14.345435, lng = 121.061630;

        const map = L.map('parish-map', {
            scrollWheelZoom: false,
            zoomControl: true
        }).setView([lat, lng], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: false
        }).addTo(map);

        const icon = L.divIcon({
            className: '',
            html: `<div style="
                width:36px;height:36px;
                background:#0D2A52;
                border:3px solid #F5C518;
                border-radius:50% 50% 50% 0;
                transform:rotate(-45deg);
                box-shadow:0 2px 8px rgba(0,0,0,.3);
            "></div>`,
            iconSize:    [36, 36],
            iconAnchor:  [18, 36],
            popupAnchor: [0, -36]
        });

        const directionsUrl = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;

        L.marker([lat, lng], { icon }).addTo(map)
            .bindPopup(`
                <div style="font-family:sans-serif;padding:4px 2px;min-width:200px;">
                    <p style="font-weight:700;font-size:14px;color:#0D2A52;margin:0 0 4px;">Paroisse Saint-Joseph Artisan</p>
                    <p style="font-size:12px;color:#777;margin:0 0 10px;line-height:1.5;">
                        1 Saint-JosephDrive,<br>Riviera Bonoumin, Abidjan, Côte d'Ivoire
                    </p>
                    <a href="${directionsUrl}" target="_blank"
                       style="display:inline-block;background:#F5C518;color:#0D2A52;font-size:12px;font-weight:700;padding:6px 14px;border-radius:6px;text-decoration:none;">
                        Get Directions ↗
                    </a>
                </div>
            `, { maxWidth: 260 }).openPopup();

        setTimeout(() => map.invalidateSize(), 300);
    });

    /* ── Timeline: spine + staggered items ── */
    const spine  = document.getElementById('tl-spine');
    const tlItems = document.querySelectorAll('.tl-item');
    const slider = document.querySelector('.tl-scroll-container');
    const timelineSection = document.querySelector('.timeline-section');

    const spineObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                spine.classList.add('revealed');
                spineObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    spineObserver.observe(spine);

    const itemObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const idx = parseInt(entry.target.dataset.index, 10);
                setTimeout(() => entry.target.classList.add('visible'), idx * 90);
                itemObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    tlItems.forEach(el => itemObserver.observe(el));

    /* ── Scroll-Driven Horizontal Animation ── */
    window.addEventListener('scroll', () => {
        if (!slider || !timelineSection || isDown) return; // Don't fight manual dragging
        
        const rect = timelineSection.getBoundingClientRect();
        const viewHeight = window.innerHeight;
        
        if (rect.top < viewHeight && rect.bottom > 0) {
            const progress = 1 - (rect.bottom / (viewHeight + rect.height));
            const scrollRange = slider.scrollWidth - slider.clientWidth;
            const targetScroll = scrollRange * progress;
            
            // Smoother programmatic scroll
            slider.scrollTo({
                left: targetScroll,
                behavior: 'auto'
            });
        }
    });

    /* ── Timeline expand/collapse ── */
    function toggleItem(toggle) {
        const item = toggle.closest('.tl-item');
        const expanded = item.classList.toggle('expanded');
        toggle.textContent = expanded ? 'Read less ↑' : 'Read more ↓';
        toggle.setAttribute('aria-expanded', expanded.toString());
    }

    /* ── Stat counter animation ── */
    const statObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el     = entry.target;
            const target = parseInt(el.dataset.target, 10);
            const suffix = el.dataset.suffix || '';
            const start  = Date.now();
            const dur    = 1200;

            el.classList.add('counted');

            (function tick() {
                const elapsed = Date.now() - start;
                const progress = Math.min(elapsed / dur, 1);
                const eased = 1 - Math.pow(1 - progress, 3); // ease-out-cubic
                const current = Math.round(eased * target);
                el.textContent = current + suffix;
                if (progress < 1) requestAnimationFrame(tick);
            })();

            statObserver.unobserve(el);
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stat-number[data-target]').forEach(el => {
        statObserver.observe(el);
    });



    /* ── Timeline Navigation Buttons ── */
    const btnLeft  = document.getElementById('tl-left');
    const btnRight = document.getElementById('tl-right');

    if (slider) {
        btnLeft.addEventListener('click', () => {
            slider.scrollBy({ left: -320, behavior: 'smooth' });
        });
        btnRight.addEventListener('click', () => {
            slider.scrollBy({ left: 320, behavior: 'smooth' });
        });

        /* ── Wheel-to-Horizontal ── */
        slider.addEventListener('wheel', (e) => {
            if (e.deltaY !== 0) {
                e.preventDefault();
                slider.scrollLeft += e.deltaY;
            }
        });

        // Hide/Show buttons based on scroll position
        const updateBtns = () => {
            btnLeft.style.opacity  = slider.scrollLeft <= 0 ? '0.3' : '1';
            btnLeft.style.pointerEvents = slider.scrollLeft <= 0 ? 'none' : 'auto';
            
            const max = slider.scrollWidth - slider.clientWidth;
            btnRight.style.opacity = slider.scrollLeft >= max - 1 ? '0.3' : '1';
            btnRight.style.pointerEvents = slider.scrollLeft >= max - 1 ? 'none' : 'auto';
        };
        slider.addEventListener('scroll', updateBtns);
        window.addEventListener('resize', updateBtns);
        updateBtns();
    }
    </script>

</x-public-layout>
