<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="Support Paroisse Saint-Joseph Artisan. Give your tithes, digital offerings, and donations to help our church maintenance and community programs.">
        <style>
            @keyframes shimmer { 0%{background-position:-200% center} 100%{background-position:200% center} }
            @keyframes spin-slow { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
            @keyframes pulse-glow { 0%,100%{box-shadow:0 0 20px rgba(245,197,24,0.15)} 50%{box-shadow:0 0 40px rgba(245,197,24,0.3)} }
            .hero-accent {
                background: linear-gradient(90deg,#FFD740 0%,#FFFFFF 40%,#FFD740 60%,#F5C518 100%);
                background-size: 200% auto; -webkit-background-clip: text; background-clip: text;
                -webkit-text-fill-color: transparent; animation: shimmer 4s linear infinite;
            }

            /* ── Tabs ── */
            .donate-tabs { display:flex; gap:4px; background:rgba(13,42,82,0.04); border-radius:16px; padding:4px; margin-bottom:32px; }
            .donate-tab {
                flex:1; padding:14px 16px; border:none; background:transparent; border-radius:12px;
                cursor:pointer; font-size:13px; font-weight:700; color:rgba(13,42,82,0.4);
                transition: all 0.3s cubic-bezier(0.16,1,0.3,1); display:flex; align-items:center; justify-content:center; gap:8px;
            }
            .donate-tab:hover { color:rgba(13,42,82,0.7); }
            .donate-tab.active {
                background:#fff; color:var(--color-blue-deep,#0D2A52);
                box-shadow:0 4px 16px rgba(13,42,82,0.08);
            }
            .donate-tab svg { width:18px; height:18px; }
            .tab-panel { display:none; animation: fadeSlide 0.4s ease; }
            .tab-panel.active { display:block; }
            @keyframes fadeSlide { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }

            /* ── Amount Buttons ── */
            .amount-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:8px; margin-bottom:16px; }
            .amount-btn {
                padding:14px 8px; border:2px solid rgba(26,64,128,0.1); border-radius:12px;
                background:#fff; cursor:pointer; font-size:15px; font-weight:700;
                color:var(--color-blue-deep,#0D2A52); transition:all .25s cubic-bezier(0.16,1,0.3,1);
                text-align:center;
            }
            .amount-btn:hover { border-color:rgba(245,197,24,0.5); background:rgba(245,197,24,0.04); transform:translateY(-2px); }
            .amount-btn.selected {
                border-color:var(--color-gold,#F5C518); background:rgba(245,197,24,0.08);
                box-shadow:0 4px 16px rgba(245,197,24,0.15);
            }

            /* ── Form Inputs ── */
            .donate-input {
                width:100%; padding:14px 16px; border:1.5px solid rgba(26,64,128,0.12);
                border-radius:12px; font-size:14px; color:#0D2A52; background:#fff;
                transition:border-color .2s, box-shadow .2s; outline:none;
            }
            .donate-input:focus { border-color:rgba(245,197,24,0.6); box-shadow:0 0 0 3px rgba(245,197,24,0.1); }
            .donate-input::placeholder { color:rgba(13,42,82,0.3); }
            .donate-label { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.2em; color:rgba(13,42,82,0.4); margin-bottom:6px; display:block; }

            /* ── Submit Button ── */
            .donate-submit {
                width:100%; padding:16px; border:none; border-radius:14px;
                background:linear-gradient(135deg,#F5C518 0%,#FFD740 100%);
                color:#0D2A52; font-size:15px; font-weight:800; letter-spacing:.02em;
                cursor:pointer; transition:all .3s cubic-bezier(0.16,1,0.3,1);
                box-shadow:0 4px 20px rgba(245,197,24,0.25);
            }
            .donate-submit:hover { transform:translateY(-2px); box-shadow:0 8px 32px rgba(245,197,24,0.4); }
            .donate-submit:disabled { opacity:0.5; cursor:not-allowed; transform:none; }

            /* ── Info Items ── */
            .info-item {
                display:flex; align-items:flex-start; gap:1rem; padding:16px;
                border-radius:14px; transition:all .3s ease;
            }
            .info-item:hover { background:rgba(13,42,82,0.02); transform:translateX(4px); }
            .info-icon-box {
                width:44px; height:44px; flex-shrink:0; border-radius:12px;
                display:flex; align-items:center; justify-content:center;
                transition:transform .3s ease;
            }
            .info-item:hover .info-icon-box { transform:scale(1.08); }

            /* ── Copy Row ── */
            .copy-row {
                display:flex; align-items:center; justify-content:space-between; padding:14px 16px;
                background:rgba(13,42,82,0.03); border-radius:14px;
                border:1.5px dashed rgba(26,64,128,0.12); cursor:pointer; transition:all .2s;
            }
            .copy-row:hover { border-color:rgba(245,197,24,0.5); background:rgba(245,197,24,0.04); }

            /* ── Coming Soon Badge ── */
            .coming-soon-badge {
                display:inline-flex; align-items:center; gap:6px; padding:6px 14px;
                background:rgba(245,197,24,0.1); border-radius:20px;
                font-size:11px; font-weight:700; color:var(--color-gold,#F5C518);
                letter-spacing:.05em;
            }

            @media(max-width:640px) {
                .amount-grid { grid-template-columns:repeat(2,1fr); }
                .donate-tabs { flex-direction:column; }
            }
        </style>
    </x-slot>

    {{-- ═══════════ HERO ═══════════ --}}
    <section style="background:var(--color-blue-deep);position:relative;overflow:hidden;padding:100px 24px 80px;text-align:center;">
        <div style="position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 50% 110%,rgba(245,197,24,0.10) 0%,transparent 70%);pointer-events:none;"></div>
        <div style="position:absolute;width:520px;height:520px;border-radius:50%;border:1px solid rgba(245,197,24,0.06);top:50%;left:50%;transform:translate(-50%,-50%);animation:spin-slow 60s linear infinite;pointer-events:none;"></div>
        <div style="position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(245,197,24,0.4),transparent);"></div>
        <div style="position:relative;z-index:10;max-width:600px;margin:0 auto;">
            <div class="animate-fade-in-down" style="font-size:10px;font-weight:600;letter-spacing:.32em;text-transform:uppercase;color:rgba(245,197,24,0.8);margin-bottom:18px;">Stewardship & Giving</div>
            <div class="font-cinzel" style="color:var(--color-gold);font-size:1.35rem;opacity:.65;margin-bottom:16px;text-shadow:0 0 20px rgba(245,197,24,0.5);">✝</div>
            <h1 class="font-heading animate-fade-in-up" style="font-weight:700;font-style:italic;font-size:clamp(2.4rem,6vw,4.5rem);line-height:1.05;color:#fff;letter-spacing:-0.02em;margin-bottom:16px; animation-delay: 0.2s;">
            <em class="hero-accent">Support Our Parish</em>
            </h1>
            <div style="width:56px;height:1px;margin:0 auto 20px;background:linear-gradient(90deg,transparent,rgba(245,197,24,0.65),transparent);"></div>
            <p class="animate-fade-in-up" style="color:rgba(235,242,255,0.6);font-size:clamp(0.85rem,1.4vw,1rem);line-height:1.78;font-weight:300; animation-delay: 0.3s;">
                Your tithes and donations help us maintain our sacred space, support our<br class="hidden md:block"> community outreach programs, and continue our mission of spiritual service.
            </p>
        </div>
        <div style="position:absolute;bottom:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(245,197,24,0.2),transparent);"></div>
    </section>

    {{-- ═══════════ MAIN ═══════════ --}}
    <div style="max-width:960px;margin:0 auto;padding:48px 24px 80px;" x-data="{
        tab: '{{ ($paymongoEnabled ?? false) ? 'online' : 'qr' }}',
        amount: 0,
        custom: false,
        customAmount: '',
        copiedG: false,
        copiedM: false,
        setAmount(val) {
            this.amount = val;
            this.custom = false;
            this.customAmount = '';
        },
        copyText(text) {
            if (text && navigator.clipboard) {
                navigator.clipboard.writeText(text);
            }
        }
    }">

        {{-- Flash Messages --}}
        @if(session('error'))
            <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);border-radius:12px;padding:14px 20px;margin-bottom:24px;color:#dc2626;font-size:14px;font-weight:600;">
                {{ session('error') }}
            </div>
        @endif

        <div style="display:grid;grid-template-columns:1fr;gap:32px;" class="md:grid-cols-[1fr_380px]">

            {{-- ── Left Column: Payment Methods ── --}}
            <div>
                {{-- Tabs --}}
                <div class="donate-tabs">
                    <button class="donate-tab" :class="tab === 'online' && 'active'" @click="tab = 'online'">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><path d="M2 10h20"/></svg>
                        Online Payment
                    </button>
                    <button class="donate-tab" :class="tab === 'qr' && 'active'" @click="tab = 'qr'">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/></svg>
                        Scan QR
                    </button>
                    <button class="donate-tab" :class="tab === 'bank' && 'active'" @click="tab = 'bank'">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 7 10-4 10 4"/><path d="M4 10v8"/><path d="M20 10v8"/><path d="M12 10v8"/><path d="M2 19h20"/></svg>
                        Bank Transfer
                    </button>
                </div>

                {{-- ═══════ TAB 1: Online Payment ═══════ --}}
                <div class="tab-panel" :class="tab === 'online' && 'active'">
                    <div style="background:#fff;border-radius:24px;border:1px solid rgba(26,64,128,0.1);box-shadow:0 8px 32px rgba(13,42,82,0.06);padding:32px;overflow:hidden;">

                        @if($paymongoEnabled ?? false)
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:28px;">
                                <div style="width:8px;height:8px;border-radius:50%;background:#22c55e;animation:pulse-glow 2s infinite;"></div>
                                <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:rgba(13,42,82,0.4);">Secure Payment via PayMongo</span>
                            </div>

                            @include('partials.donation-form-fields', [
                                'channel' => 'online',
                                'helperText' => 'Secured by PayMongo · GCash · Maya · Visa · Mastercard',
                            ])
                        @else
                            {{-- PayMongo Not Configured --}}
                            <div style="text-align:center;padding:48px 24px;">
                                <div style="width:72px;height:72px;border-radius:50%;background:rgba(245,197,24,0.08);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(245,197,24,0.6)" stroke-width="1.5"><rect width="20" height="14" x="2" y="5" rx="2"/><path d="M2 10h20"/></svg>
                                </div>
                                <div class="coming-soon-badge" style="margin:0 auto 16px;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                                    Coming Soon
                                </div>
                                <h3 class="font-heading" style="font-size:1.25rem;font-weight:700;color:var(--color-blue-deep,#0D2A52);margin-bottom:8px;">Online Payments</h3>
                                <p style="font-size:.875rem;color:rgba(13,42,82,0.5);line-height:1.7;max-width:320px;margin:0 auto;">
                                    We're setting up secure online payments via GCash, Maya, and credit/debit cards.
                                </p>
                                <button @click="tab = 'qr'" class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-full text-xs font-bold uppercase tracking-widest transition-all duration-300 hover:-translate-y-0.5" style="background:linear-gradient(135deg,#F5C518 0%,#FFD740 100%);color:#0D2A52;box-shadow:0 4px 16px rgba(245,197,24,0.3);cursor:pointer;border:none;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/></svg>
                                    In the meantime, try QR
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ═══════ TAB 2: Scan QR ═══════ --}}
                <div class="tab-panel" :class="tab === 'qr' && 'active'">
                    <div style="background:#fff;border-radius:24px;border:1px solid rgba(26,64,128,0.1);box-shadow:0 8px 32px rgba(13,42,82,0.06);padding:32px;overflow:hidden;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                            <div>
                                <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.25em;color:rgba(13,42,82,0.35);">Digital Offerings</p>
                                <h2 class="font-heading" style="font-size:1.4rem;font-weight:700;color:var(--color-blue-deep,#0D2A52);margin-top:2px;">GCash & Maya</h2>
                            </div>
                            <div style="height:48px;width:48px;border-radius:14px;background:var(--color-gold,#F5C518);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(245,197,24,0.35);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--color-blue-deep,#0D2A52)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/></svg>
                            </div>
                        </div>

                        @if($paymongoEnabled ?? false)
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:28px;">
                                <div style="width:8px;height:8px;border-radius:50%;background:#22c55e;animation:pulse-glow 2s infinite;"></div>
                                <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:rgba(13,42,82,0.4);">Auto-Verified QR via PayMongo</span>
                            </div>

                            @include('partials.donation-form-fields', [
                                'channel' => 'qr',
                                'helperText' => 'Secured by PayMongo · Scan with GCash, Maya, or your banking app',
                            ])

                            <div style="display:flex;align-items:center;gap:12px;margin:32px 0;">
                                <div style="flex:1;height:1px;background:rgba(26,64,128,0.08);"></div>
                                <span style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.15em;color:rgba(13,42,82,0.3);">or scan our QR directly</span>
                                <div style="flex:1;height:1px;background:rgba(26,64,128,0.08);"></div>
                            </div>
                        @endif

                        {{-- QR Code (manual — send directly, not tracked automatically) --}}
                        @if(!empty($global_settings['qr_code']))
                        <div style="padding:20px;background:#F7F9FF;border-radius:20px;border:1px solid rgba(26,64,128,0.08);margin-bottom:24px;">
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('supabase')->url($global_settings['qr_code']) }}"
                                 alt="Parish Donation QR"
                                 style="max-width:240px;width:100%;height:auto;border-radius:12px;display:block;margin:0 auto;">
                        </div>
                        @endif

                        {{-- Copy Details (manual fallback) --}}
                        <div style="display:flex;flex-direction:column;gap:10px;">
                            <div class="copy-row" @click="copyText('{{ $global_settings['gcash_number'] ?? '' }}'); copiedG = true; setTimeout(() => copiedG = false, 2000)">
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <div style="width:32px;height:32px;border-radius:50%;background:rgba(13,42,82,0.08);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:var(--color-blue-deep,#0D2A52);">G</div>
                                    <div>
                                        <p style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.2em;color:rgba(13,42,82,0.35);">GCash Number</p>
                                        <p style="font-weight:700;font-size:.875rem;color:var(--color-blue-deep,#0D2A52);">{{ $global_settings['gcash_number'] ?? 'Not set' }}</p>
                                    </div>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <span x-show="copiedG" x-cloak style="font-size:9px;font-weight:800;color:var(--color-gold,#F5C518);">COPIED!</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="rgba(245,197,24,0.7)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                </div>
                            </div>
                            <div class="copy-row" @click="copyText('{{ $global_settings['gcash_name'] ?? '' }}'); copiedM = true; setTimeout(() => copiedM = false, 2000)">
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <div style="width:32px;height:32px;border-radius:50%;background:rgba(26,64,128,0.06);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:rgba(13,42,82,0.5);">A</div>
                                    <div>
                                        <p style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.2em;color:rgba(13,42,82,0.35);">Account Name</p>
                                        <p style="font-weight:700;font-size:.875rem;color:var(--color-blue-deep,#0D2A52);">{{ $global_settings['gcash_name'] ?? 'Not set' }}</p>
                                    </div>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <span x-show="copiedM" x-cloak style="font-size:9px;font-weight:800;color:var(--color-gold,#F5C518);">COPIED!</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="rgba(245,197,24,0.7)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══════ TAB 3: Bank Transfer ═══════ --}}
                <div class="tab-panel" :class="tab === 'bank' && 'active'">
                    <div style="background:#fff;border-radius:24px;border:1px solid rgba(26,64,128,0.1);box-shadow:0 8px 32px rgba(13,42,82,0.06);padding:32px;overflow:hidden;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
                            <div>
                                <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.25em;color:rgba(13,42,82,0.35);">Direct Deposit</p>
                                <h2 class="font-heading" style="font-size:1.4rem;font-weight:700;color:var(--color-blue-deep,#0D2A52);margin-top:2px;">Bank Transfer</h2>
                            </div>
                            <div style="height:48px;width:48px;border-radius:14px;background:rgba(13,42,82,0.06);display:flex;align-items:center;justify-content:center;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--color-blue-deep,#0D2A52)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 7 10-4 10 4"/><path d="M4 10v8"/><path d="M20 10v8"/><path d="M12 10v8"/><path d="M2 19h20"/></svg>
                            </div>
                        </div>

                        @if($paymongoEnabled ?? false)
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:28px;">
                                <div style="width:8px;height:8px;border-radius:50%;background:#22c55e;animation:pulse-glow 2s infinite;"></div>
                                <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:rgba(13,42,82,0.4);">Pay via Online Banking (BPI / UnionBank)</span>
                            </div>

                            @include('partials.donation-form-fields', [
                                'channel' => 'bank',
                                'helperText' => 'Secured by PayMongo · Redirects to your bank login to authorize the transfer',
                            ])

                            <div style="display:flex;align-items:center;gap:12px;margin:32px 0;">
                                <div style="flex:1;height:1px;background:rgba(26,64,128,0.08);"></div>
                                <span style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.15em;color:rgba(13,42,82,0.3);">or transfer manually</span>
                                <div style="flex:1;height:1px;background:rgba(26,64,128,0.08);"></div>
                            </div>
                        @endif

                        <div style="background:#F7F9FF;border-radius:16px;padding:24px;border:1px solid rgba(26,64,128,0.06);margin-bottom:20px;">
                            <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.2em;color:rgba(13,42,82,0.35);margin-bottom:16px;">Account Details</p>
                            <div style="display:flex;flex-direction:column;gap:14px;">
                                @if(!empty($global_settings['bank_name']))
                                <div>
                                    <p style="font-size:11px;color:rgba(13,42,82,0.4);font-weight:600;">Bank Name</p>
                                    <p style="font-weight:700;color:var(--color-blue-deep,#0D2A52);font-size:15px;">{{ $global_settings['bank_name'] }}</p>
                                </div>
                                <div style="height:1px;background:rgba(26,64,128,0.08);"></div>
                                @endif
                                @if(!empty($global_settings['bank_account_name']))
                                <div>
                                    <p style="font-size:11px;color:rgba(13,42,82,0.4);font-weight:600;">Account Name</p>
                                    <p style="font-weight:700;color:var(--color-blue-deep,#0D2A52);font-size:15px;">{{ $global_settings['bank_account_name'] }}</p>
                                </div>
                                <div style="height:1px;background:rgba(26,64,128,0.08);"></div>
                                @endif
                                @if(!empty($global_settings['bank_account_number']))
                                <div>
                                    <p style="font-size:11px;color:rgba(13,42,82,0.4);font-weight:600;">Account Number</p>
                                    <p style="font-weight:700;color:var(--color-blue-deep,#0D2A52);font-size:15px;letter-spacing:.05em;">{{ $global_settings['bank_account_number'] }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div style="background:rgba(245,197,24,0.06);border-radius:12px;padding:14px 18px;display:flex;align-items:flex-start;gap:10px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(245,197,24,0.8)" stroke-width="2" style="flex-shrink:0;margin-top:2px;"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            <p style="font-size:12px;color:rgba(13,42,82,0.5);line-height:1.6;">Please send proof of transfer to <strong>{{ config('services.parish.office_email', 'paroisse.stjoseph.bonoumin@gmail.com') }}</strong> for acknowledgment.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Right Column: Info ── --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                {{-- Where It Goes --}}
                <div style="padding:28px;background:#fff;border-radius:24px;border:1px solid rgba(26,64,128,0.1);box-shadow:0 4px 20px rgba(13,42,82,0.05);">
                    <div style="font-size:10px;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:rgba(13,42,82,0.35);margin-bottom:6px;">Transparency</div>
                    <h3 class="font-heading" style="font-size:1.3rem;font-weight:700;color:var(--color-blue-deep,#0D2A52);margin-bottom:20px;">Where your donation goes</h3>
                    <div style="display:flex;flex-direction:column;gap:4px;">
                        <div class="info-item">
                            <div class="info-icon-box" style="background:rgba(13,42,82,0.06);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-blue-deep,#0D2A52)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/></svg>
                            </div>
                            <div>
                                <p style="font-weight:700;font-size:.9rem;color:var(--color-blue-deep,#0D2A52);">Church Maintenance</p>
                                <p style="font-size:.8rem;color:rgba(13,42,82,0.5);line-height:1.6;margin-top:3px;">Preserving the beauty and structural integrity of our parish church.</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon-box" style="background:rgba(245,197,24,0.1);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold,#F5C518)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.505 4.04 3 5.5L12 21l7-7Z"/></svg>
                            </div>
                            <div>
                                <p style="font-weight:700;font-size:.9rem;color:var(--color-blue-deep,#0D2A52);">Pantry Community Outreach</p>
                                <p style="font-size:.8rem;color:rgba(13,42,82,0.5);line-height:1.6;margin-top:3px;">Providing food supplies and support to under-privileged families in the parish.</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon-box" style="background:rgba(26,64,128,0.08);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-blue-mid,#1A4080)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div>
                                <p style="font-weight:700;font-size:.9rem;color:var(--color-blue-deep,#0D2A52);">Youth Ministry</p>
                                <p style="font-size:.8rem;color:rgba(13,42,82,0.5);line-height:1.6;margin-top:3px;">Empowering the next generation through spiritual retreats and skill-building workshops.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Scripture Quote --}}
                <div style="padding:28px;background:var(--color-blue-deep,#0D2A52);border-radius:24px;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-family:'Cinzel',serif;font-size:180px;color:rgba(255,255,255,0.03);pointer-events:none;line-height:1;">✝</div>
                    <div style="position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(245,197,24,0.4),transparent);"></div>
                    <div style="position:relative;z-index:10;">
                        <div style="color:rgba(245,197,24,0.5);font-size:2rem;margin-bottom:12px;font-family:'Cinzel',serif;">"</div>
                        <p style="font-size:.9rem;font-style:italic;line-height:1.8;color:rgba(235,242,255,0.8);margin-bottom:1rem;">
                            Each of you should give what you have decided in your heart to give, not reluctantly or under compulsion, for God loves a cheerful giver.
                        </p>
                        <p style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.25em;color:var(--color-gold,#F5C518);">– 2 Corinthians 9:7</p>
                    </div>
                </div>

                {{-- Impact Counter / Social Proof --}}
                @php
                    $totalDonations = \App\Models\Donation::where('status', 'paid')->count();
                @endphp
                @if($totalDonations > 0)
                <div style="padding:24px;background:#fff;border-radius:24px;border:1px solid rgba(26,64,128,0.1);box-shadow:0 4px 20px rgba(13,42,82,0.05);text-align:center;">
                    <p style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.25em;color:rgba(13,42,82,0.35);margin-bottom:12px;">Community Impact</p>
                    <div style="display:flex;align-items:center;justify-content:center;gap:24px;">
                        <div>
                            <p class="font-heading" style="font-size:1.8rem;font-weight:700;font-style:italic;color:var(--color-blue-deep,#0D2A52);line-height:1;">{{ number_format($totalDonations) }}</p>
                            <p style="font-size:10px;color:rgba(13,42,82,0.4);margin-top:4px;">Donations Made</p>
                        </div>
                        <div style="width:1px;height:36px;background:rgba(26,64,128,0.08);"></div>
                        <div>
                            <p class="font-heading" style="font-size:1.8rem;font-weight:700;font-style:italic;color:var(--color-gold,#F5C518);line-height:1;">142+</p>
                            <p style="font-size:10px;color:rgba(13,42,82,0.4);margin-top:4px;">Families Supported</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Mass Intention CTA --}}
                <a href="{{ route('submit-intention') }}" style="display:block;padding:20px 24px;background:#fff;border-radius:18px;border:1px solid rgba(26,64,128,0.1);text-decoration:none;transition:all .3s ease;box-shadow:0 2px 12px rgba(13,42,82,0.04);"
                   onmouseenter="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(13,42,82,0.08)'"
                   onmouseleave="this.style.transform='none';this.style.boxShadow='0 2px 12px rgba(13,42,82,0.04)'">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:14px;">
                            <div style="width:40px;height:40px;border-radius:12px;background:rgba(245,197,24,0.1);display:flex;align-items:center;justify-content:center;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold,#F5C518)" stroke-width="2"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/><path d="M12 6v6l4 2"/></svg>
                            </div>
                            <div>
                                <p style="font-weight:700;font-size:.9rem;color:var(--color-blue-deep,#0D2A52);">Submit a Mass Intention</p>
                                <p style="font-size:.75rem;color:rgba(13,42,82,0.4);">Offer a prayer request for your loved ones</p>
                            </div>
                        </div>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,0.3)" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    </div>
                </a>
            </div>
        </div>
    </div>

</x-public-layout>
