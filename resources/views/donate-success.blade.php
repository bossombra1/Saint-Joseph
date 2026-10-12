<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="Thank you for your generous donation to Paroisse Saint-Joseph Artisan.">
        <style>
            @keyframes confetti-fall {
                0% { transform: translateY(-10vh) rotate(0deg); opacity: 1; }
                100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
            }
            .confetti-piece {
                position: fixed; top: 0; width: 10px; height: 10px;
                animation: confetti-fall 3s ease-in forwards;
                z-index: 9999; pointer-events: none;
            }
            @keyframes success-pulse {
                0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(34,197,94,0.4); }
                50% { transform: scale(1.05); box-shadow: 0 0 0 20px rgba(34,197,94,0); }
            }
            .success-icon { animation: success-pulse 2s ease infinite; }
        </style>
    </x-slot>

    {{-- Confetti --}}
    <div x-data="confetti()" x-init="fire()">
        <template x-for="(piece, i) in pieces" :key="i">
            <div class="confetti-piece" :style="`left:${piece.x}%;background:${piece.color};animation-delay:${piece.delay}s;border-radius:${piece.round ? '50%' : '2px'};width:${piece.size}px;height:${piece.size}px;`"></div>
        </template>
    </div>

    {{-- Hero --}}
    <section style="background:var(--color-blue-deep);position:relative;overflow:hidden;padding:100px 24px 80px;text-align:center;">
        <div style="position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 50% 110%,rgba(34,197,94,0.10) 0%,transparent 70%);pointer-events:none;"></div>
        <div style="position:relative;z-index:10;max-width:600px;margin:0 auto;">
            <div style="font-size:10px;font-weight:600;letter-spacing:.32em;text-transform:uppercase;color:rgba(34,197,94,0.8);margin-bottom:18px;">Payment Confirmed</div>
            <h1 class="font-heading" style="font-weight:700;font-style:italic;font-size:clamp(2rem,5vw,3.5rem);line-height:1.1;color:#fff;margin-bottom:16px;">
                Thank You
            </h1>
            <div style="width:56px;height:1px;margin:0 auto 20px;background:linear-gradient(90deg,transparent,rgba(34,197,94,0.65),transparent);"></div>
            <p style="color:rgba(235,242,255,0.6);font-size:clamp(0.85rem,1.4vw,1rem);line-height:1.78;font-weight:300;">
                Your generous donation helps us continue our mission of faith and service.
            </p>
        </div>
    </section>

    {{-- Success Card --}}
    <div style="max-width:600px;margin:-40px auto 80px;padding:0 24px;position:relative;z-index:20;">
        <div style="background:#fff;border-radius:24px;border:1px solid rgba(26,64,128,0.1);box-shadow:0 8px 40px rgba(13,42,82,0.1);padding:48px 36px;text-align:center;">

            {{-- Check Icon --}}
            <div class="success-icon" style="width:80px;height:80px;border-radius:50%;background:rgba(34,197,94,0.08);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6 9 17l-5-5"/>
                </svg>
            </div>

            <h2 class="font-heading" style="font-size:1.5rem;font-weight:700;color:var(--color-blue-deep,#0D2A52);margin-bottom:8px;">Donation Received!</h2>
            <p style="font-size:.9rem;color:rgba(13,42,82,0.5);line-height:1.7;margin-bottom:32px;">
                We've received your generous contribution. A confirmation has been recorded in our system.
            </p>

            @if($donation)
                {{-- Donation Details --}}
                <div style="background:#F7F9FF;border-radius:16px;padding:24px;text-align:left;margin-bottom:28px;border:1px solid rgba(26,64,128,0.06);">
                    <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.2em;color:rgba(13,42,82,0.35);margin-bottom:16px;">Transaction Details</p>
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:13px;color:rgba(13,42,82,0.5);">Amount</span>
                            <span style="font-weight:700;font-size:15px;color:var(--color-blue-deep,#0D2A52);">{{ $donation->formatted_amount }}</span>
                        </div>
                        @if($donation->donor_name)
                            <div style="height:1px;background:rgba(26,64,128,0.08);"></div>
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:13px;color:rgba(13,42,82,0.5);">Donor</span>
                                <span style="font-weight:600;font-size:14px;color:var(--color-blue-deep,#0D2A52);">{{ $donation->donor_name }}</span>
                            </div>
                        @endif
                        @if($donation->purpose)
                            <div style="height:1px;background:rgba(26,64,128,0.08);"></div>
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:13px;color:rgba(13,42,82,0.5);">Purpose</span>
                                <span style="font-weight:600;font-size:14px;color:var(--color-blue-deep,#0D2A52);">{{ $donation->purpose }}</span>
                            </div>
                        @endif
                        <div style="height:1px;background:rgba(26,64,128,0.08);"></div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:13px;color:rgba(13,42,82,0.5);">Reference</span>
                            <span style="font-weight:600;font-size:13px;color:rgba(13,42,82,0.6);font-family:monospace;">DON-{{ strtoupper(substr($donation->id, 0, 8)) }}</span>
                        </div>
                        <div style="height:1px;background:rgba(26,64,128,0.08);"></div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:13px;color:rgba(13,42,82,0.5);">Status</span>
                            <span style="display:inline-flex;align-items:center;gap:6px;font-weight:700;font-size:12px;color:#22c55e;text-transform:uppercase;letter-spacing:.1em;">
                                <span style="width:6px;height:6px;border-radius:50%;background:#22c55e;"></span>
                                {{ $donation->status }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            <div style="display:flex;flex-direction:column;gap:12px;">
                @if($donation && $donation->status === 'paid')
                    <a href="{{ URL::signedRoute('donation.receipt', ['donation' => $donation]) }}"
                       style="display:flex;align-items:center;justify-content:center;gap:8px;padding:14px 24px;border-radius:14px;background:rgba(26,64,128,0.06);border:1.5px solid rgba(26,64,128,0.12);color:var(--color-blue-deep,#0D2A52);font-size:14px;font-weight:700;text-decoration:none;transition:all .2s;"
                       onmouseenter="this.style.background='rgba(26,64,128,0.1)'"
                       onmouseleave="this.style.background='rgba(26,64,128,0.06)'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M4 22h16a2 2 0 0 0 2-2V10H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/><path d="M14 2l6 6v8a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V8a2 2 0 0 0 2-2h6z"/></svg>
                        Download Receipt (PDF)
                    </a>
                @endif
                <a href="{{ route('donate') }}" style="display:block;padding:14px 24px;border-radius:14px;background:linear-gradient(135deg,#F5C518 0%,#FFD740 100%);color:#0D2A52;font-size:14px;font-weight:800;text-decoration:none;text-align:center;transition:all .3s;box-shadow:0 4px 20px rgba(245,197,24,0.25);"
                   onmouseenter="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 32px rgba(245,197,24,0.4)'"
                   onmouseleave="this.style.transform='none';this.style.boxShadow='0 4px 20px rgba(245,197,24,0.25)'">
                    Make Another Donation
                </a>
                <a href="{{ route('home') }}" style="display:block;padding:12px 24px;border-radius:12px;background:transparent;border:1.5px solid rgba(26,64,128,0.12);color:rgba(13,42,82,0.5);font-size:13px;font-weight:600;text-decoration:none;text-align:center;transition:all .2s;"
                   onmouseenter="this.style.borderColor='rgba(26,64,128,0.25)'"
                   onmouseleave="this.style.borderColor='rgba(26,64,128,0.12)'">
                    Return to Home
                </a>
            </div>
        </div>

        {{-- Scripture --}}
        <div style="margin-top:24px;padding:24px;background:var(--color-blue-deep,#0D2A52);border-radius:20px;text-align:center;position:relative;overflow:hidden;">
            <div style="position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(245,197,24,0.4),transparent);"></div>
            <p style="font-size:.85rem;font-style:italic;line-height:1.8;color:rgba(235,242,255,0.7);margin-bottom:8px;">
                "Each of you should give what you have decided in your heart to give, not reluctantly or under compulsion, for God loves a cheerful giver."
            </p>
            <p style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.25em;color:var(--color-gold,#F5C518);">– 2 Corinthians 9:7</p>
        </div>
    </div>

    @push('scripts')
    <script>
        function confetti() {
            return {
                pieces: [],
                fire() {
                    const colors = ['#F5C518','#22c55e','#3b82f6','#ef4444','#d946ef','#f97316'];
                    for (let i = 0; i < 50; i++) {
                        this.pieces.push({
                            x: Math.random() * 100,
                            color: colors[Math.floor(Math.random() * colors.length)],
                            delay: Math.random() * 1.5,
                            size: Math.random() * 8 + 6,
                            round: Math.random() > 0.5,
                        });
                    }
                }
            };
        }
    </script>
    @endpush
</x-public-layout>
