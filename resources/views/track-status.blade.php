<x-public-layout>
<x-slot name="meta">
    <style>
        :root {
            --gold:       #F5C518;
            --gold-light: #FFD740;
            --blue-deep:  #0D2A52;
            --blue-mid:   #1A4080;
            --blue-pale:  #EBF2FF;
            --cream:      #F7F9FF;
            --cream-deep: #EDF2FC;
        }
        body { background: var(--cream); font-family: 'Jost', sans-serif; }
        .font-heading { font-family: 'Cormorant Garamond', Georgia, serif; }
        .font-cinzel  { font-family: 'Cinzel', Georgia, serif; }

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
            background: linear-gradient(90deg, transparent, rgba(245,197,24,0.45));
        }
        .divider-ornament::after {
            background: linear-gradient(90deg, rgba(245,197,24,0.45), transparent);
        }

        .gold-btn {
            background: linear-gradient(135deg, #FFD740 0%, #F5C518 55%, #E0A800 100%);
            color: var(--blue-deep);
            box-shadow: 0 4px 20px rgba(245,197,24,0.35), 0 1px 0 rgba(255,255,255,0.35) inset;
            transition: all 0.25s ease; font-weight: 700; border: none; cursor: pointer;
        }
        .gold-btn:hover {
            box-shadow: 0 8px 32px rgba(245,197,24,0.55), 0 1px 0 rgba(255,255,255,0.35) inset;
            transform: translateY(-2px);
        }
        .gold-btn:active { transform: scale(0.97); }

        .card-sacred {
            background: #FFFFFF;
            border: 1px solid rgba(26,64,128,0.1);
            border-radius: 24px;
            transition: all 0.3s ease;
        }

        .track-input {
            width: 100%; padding: 14px 20px;
            border: 1.5px solid rgba(26,64,128,0.15);
            border-radius: 14px;
            background: var(--cream-deep);
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 1rem; color: var(--blue-deep);
            outline: none; transition: border-color 0.25s, box-shadow 0.25s;
            box-sizing: border-box;
        }
        .track-input:focus {
            border-color: rgba(245,197,24,0.65);
            box-shadow: 0 0 0 4px rgba(245,197,24,0.1);
        }
        .track-input::placeholder { color: rgba(13,42,82,0.28); }

        .reveal {
            opacity: 0; transform: translateY(32px);
            transition: all 1s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .reveal.active { opacity: 1; transform: translateY(0); }

        /* Status colors */
        .status-pending  { --s-bg: #D97706; --s-dot: #F59E0B; --s-text: #92400E; --s-light: #FFFBEB; --s-border: #FDE68A; }
        .status-approved,
        .status-accepted { --s-bg: #059669; --s-dot: #10B981; --s-text: #065F46; --s-light: #ECFDF5; --s-border: #A7F3D0; }
        .status-rejected { --s-bg: #DC2626; --s-dot: #EF4444; --s-text: #7F1D1D; --s-light: #FEF2F2; --s-border: #FECACA; }

        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }
        .dot-pulse { animation: pulse 1.8s ease-in-out infinite; }

        @keyframes slideUp {
            from { opacity:0; transform:translateY(24px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .result-in { animation: slideUp 0.5s cubic-bezier(0.22,1,0.36,1) both; }
    </style>
</x-slot>

{{-- ═══════════════ HERO ═══════════════ --}}
<section style="position:relative; background:var(--blue-deep); overflow:hidden;
                padding:5rem 1.5rem 3.5rem; text-align:center;">

    <div style="position:absolute; top:0; left:0; right:0; height:1px;
                background:linear-gradient(90deg,transparent,rgba(245,197,24,0.45),transparent);"></div>
    <div style="position:absolute; bottom:0; left:0; right:0; height:1px;
                background:linear-gradient(90deg,transparent,rgba(245,197,24,0.2),transparent);"></div>
    <div style="position:absolute; inset:0; pointer-events:none;
                background:radial-gradient(ellipse 70% 60% at 50% 20%, rgba(26,64,128,0.5) 0%, transparent 70%);"></div>
    <div class="font-cinzel" style="position:absolute; font-size:320px; color:rgba(255,255,255,0.018);
                top:50%; left:50%; transform:translate(-50%,-50%);
                pointer-events:none; user-select:none; line-height:1;">✝</div>

    <div style="position:relative; z-index:5; max-width:640px; margin:0 auto;">
        <div class="divider-ornament mb-4">
            <span class="eyebrow" style="color:rgba(245,197,24,0.75);">Sacramental Services</span>
        </div>
        <h1 class="font-heading"
            style="font-size:clamp(2.4rem,6vw,4.5rem); font-weight:700; font-style:italic;
                   color:#EBF2FF; letter-spacing:-0.02em; line-height:1; margin-bottom:1rem;">
            Track Your Status
        </h1>
        <div style="width:48px; height:1px; margin:0 auto 1.25rem;
                    background:linear-gradient(90deg,transparent,rgba(245,197,24,0.6),transparent);"></div>
        <p style="font-size:0.9rem; font-style:italic; color:rgba(235,242,255,0.42); line-height:1.7;">
            Enter reference ID to check status of sacramental inquiry or mass intention.
        </p>
    </div>
</section>

<div style="max-width:680px; margin:0 auto; padding:3rem 1.5rem 5rem;">

    {{-- ═══════════════ SEARCH FORM ═══════════════ --}}
    <div class="card-sacred reveal"
         style="padding:2.25rem; margin-bottom:2rem;
                box-shadow:0 8px 40px rgba(13,42,82,0.08);">

        <form action="{{ route('track.post') }}" method="POST">
            @csrf
            <div style="margin-bottom:1.25rem;">
                <label for="reference_id"
                       class="font-cinzel"
                       style="display:block; font-size:9px; letter-spacing:0.3em;
                              text-transform:uppercase; color:var(--blue-deep);
                              margin-bottom:10px; opacity:0.7;">
                    Reference ID
                </label>
                <input type="text"
                       name="reference_id"
                       id="reference_id"
                       class="track-input"
                       placeholder="e.g. SRP-2026-0001 or INQ-2026-0001"
                       value="{{ $refId ?? old('reference_id') }}"
                       required>
                @error('reference_id')
                    <p style="font-size:11px; font-weight:700; color:#DC2626; margin-top:6px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit" class="gold-btn"
                    style="width:100%; padding:15px; border-radius:14px;
                           font-size:10px; letter-spacing:0.3em; text-transform:uppercase;">
                Check Status
            </button>
        </form>
    </div>

    {{-- ═══════════════ RESULT ═══════════════ --}}
    @if(isset($status))
    @php
        $sc = in_array($status, ['approved','accepted']) ? 'status-approved'
            : (in_array($status, ['rejected', 'declined']) ? 'status-rejected' : 'status-pending');
    @endphp

    <div class="card-sacred result-in {{ $sc }}"
         style="overflow:hidden; box-shadow:0 12px 50px rgba(13,42,82,0.1);">

        {{-- Status banner --}}
        <div style="padding:1.5rem;
                    background:var(--s-bg);
                    display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:1rem;">
            <div>
                <p class="font-cinzel"
                   style="font-size:9px; letter-spacing:0.28em; text-transform:uppercase;
                          color:rgba(255,255,255,0.6); margin-bottom:4px;">
                    Tracking Result
                </p>
                <h2 class="font-heading"
                    style="font-size:1.75rem; font-weight:700; font-style:italic;
                           color:#fff; line-height:1;">
                    {{ $type }}
                </h2>
                <p style="font-family:monospace; font-size:10px; color:rgba(255,255,255,0.5); margin-top:4px;">
                    Ref: {{ $refId ?? $item->reference_id ?? substr($item->id, 0, 8) }}
                </p>
            </div>
            <div style="padding:6px 16px; border-radius:100px;
                        background:rgba(255,255,255,0.18); backdrop-filter:blur(8px);
                        font-size:9px; font-weight:700; letter-spacing:0.25em;
                        text-transform:uppercase; color:#fff; white-space:nowrap;">
                {{ $status }}
            </div>
        </div>

        {{-- Detail grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-6 sm:p-8">

            <div>
                <p class="font-cinzel"
                   style="font-size:9px; letter-spacing:0.25em; text-transform:uppercase;
                          color:rgba(13,42,82,0.35); margin-bottom:5px;">Name</p>
                <p style="font-weight:700; color:var(--blue-deep); word-break:break-word;">
                    {{ $item->full_name ?? $item->name ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="font-cinzel"
                   style="font-size:9px; letter-spacing:0.25em; text-transform:uppercase;
                          color:rgba(13,42,82,0.35); margin-bottom:5px;">Submitted On</p>
                <p style="font-weight:700; color:var(--blue-deep);">
                    {{ $item->created_at->format('M d, Y') }}
                </p>
            </div>

            @if($date)
            <div>
                <p class="font-cinzel"
                   style="font-size:9px; letter-spacing:0.25em; text-transform:uppercase;
                          color:rgba(13,42,82,0.35); margin-bottom:5px;">Preferred Date</p>
                <p style="font-weight:700; color:var(--blue-deep);">
                    {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                </p>
            </div>
            @endif

            <div>
                <p class="font-cinzel"
                   style="font-size:9px; letter-spacing:0.25em; text-transform:uppercase;
                          color:rgba(13,42,82,0.35); margin-bottom:5px;">Current Status</p>
                <div style="display:flex; align-items:center; gap:8px;">
                    <span style="width:8px; height:8px; border-radius:50%;
                                 background:var(--s-dot);
                                 {{ $status === 'pending' ? 'animation:pulse 1.8s ease-in-out infinite;' : '' }}
                                 display:inline-block; flex-shrink:0;"></span>
                    <p style="font-weight:700; font-size:0.875rem; text-transform:uppercase;
                              letter-spacing:0.1em; color:var(--s-bg);">
                        {{ $status }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Rejection reason --}}
                        @if(in_array($status, ['rejected', 'declined']) && !empty($item->rejection_reason))
        <div style="margin:0 2rem; padding:1rem 1.25rem;
                    background:var(--s-light); border:1px solid var(--s-border);
                    border-radius:12px; margin-bottom:0;">
            <p class="font-cinzel"
               style="font-size:9px; letter-spacing:0.25em; text-transform:uppercase;
                      color:var(--s-bg); margin-bottom:6px;">
                Rejection Reason
            </p>
            <p style="font-size:0.875rem; font-style:italic; color:var(--s-text); line-height:1.6;">
                "{{ $item->rejection_reason }}"
            </p>
        </div>
        @endif

        {{-- Status message --}}
        <div style="padding:1.5rem 2rem; border-top:1px solid rgba(26,64,128,0.08); margin-top:1.5rem;">
            @if($status === 'pending')
                <p style="font-size:0.875rem; font-style:italic; color:rgba(13,42,82,0.45); line-height:1.6;">
                    Team currently reviewing request. Check back later or await email/SMS notification.
                </p>
            @elseif(in_array($status, ['approved','accepted']))
                <p style="font-size:0.875rem; font-weight:700; color:var(--s-text); line-height:1.6;">
                    Request approved. We look forward to seeing you — check email for final instructions.
                </p>
            @elseif(in_array($status, ['rejected', 'declined']))
                <p style="font-size:0.875rem; font-weight:700; color:var(--s-text); line-height:1.6;">
                    Request could not be approved at this time. See reason above or contact parish office.
                </p>
            @else
                <p style="font-size:0.875rem; font-style:italic; color:rgba(13,42,82,0.45); line-height:1.6;">
                    Status updated. Check email for details.
                </p>
            @endif
        </div>
    </div>
    @endif

    {{-- Footer stamp --}}
    <p class="font-cinzel"
       style="text-align:center; margin-top:3.5rem; font-size:9px; letter-spacing:0.4em;
              text-transform:uppercase; color:rgba(13,42,82,0.18);">
        Paroisse Saint-Joseph Artisan · Riviera Bonoumin, Abidjan, Côte d'Ivoire
    </p>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const obs = new IntersectionObserver(
        entries => entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); obs.unobserve(e.target); } }),
        { threshold: 0.1 }
    );
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
});
</script>

</x-public-layout>