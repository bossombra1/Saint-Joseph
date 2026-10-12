<x-public-layout>
<x-slot name="meta">
    <meta name="description" content="Parish inquiries – sacraments, blessings, and document requests at Paroisse Saint-Joseph Artisan, Riviera Bonoumin, Abidjan, Côte d'Ivoire.">
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

        /* ── Main card ── */
        .inquiry-card {
            background: #FFF;
            border: 1px solid rgba(26,64,128,0.12);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 12px 50px rgba(13,42,82,0.09);
        }

        /* ── Sidebar ── */
        .sidebar {
            background: linear-gradient(175deg, var(--blue-deep) 0%, var(--blue-mid) 100%);
            position: relative; overflow: hidden;
            padding: 40px 36px;
        }
        .sidebar::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse 80% 50% at 50% 0%, rgba(245,197,24,0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .sidebar-step {
            display: flex; align-items: flex-start; gap: 14px;
        }
        .step-num {
            flex-shrink: 0; width: 22px; height: 22px;
            border-radius: 50%;
            border: 1px solid rgba(245,197,24,0.40);
            background: rgba(245,197,24,0.08);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Cinzel', Georgia, serif;
            font-size:11px; font-weight: 600;
            color: rgba(245,197,24,0.80);
            margin-top: 2px; flex-shrink: 0;
        }
        .sidebar-contact {
            display: flex; align-items: center; gap: 12px;
            font-size: 14px; color: rgba(235,242,255,0.60);
        }
        .sidebar-contact svg { flex-shrink: 0; opacity: 0.55; }

        /* ── Inputs ── */
        .sacred-input {
            width: 100%; height: 48px;
            border: 1px solid rgba(26,64,128,0.16);
            border-radius: 12px;
            background: var(--cream);
            padding: 0 16px;
            font-family: 'Jost', sans-serif;
            font-size: 14px; font-weight: 500;
            color: var(--blue-deep);
            transition: all 0.2s ease;
            outline: none; appearance: none;
        }
        .sacred-input:focus {
            border-color: var(--color-blue-deep);
            background: #FFF;
            box-shadow: 
                0 0 0 2px var(--color-blue-deep),
                0 0 0 4px rgba(245,197,24,0.35);
        }

        /* Error state */
        .sacred-input.error {
            border-color: #DC2626;
        }
        .sacred-input.error:focus {
            box-shadow:
                0 0 0 2px #DC2626,
                0 0 0 4px rgba(220,38,38,0.25);
        }
        .sacred-input::placeholder { color: rgba(13,42,82,0.28); }
        textarea.sacred-input { height: auto; padding: 14px 16px; resize: none; }

        .field-label {
            display: block;
            font-size: 11px; font-weight: 700;
            letter-spacing: 0.28em; text-transform: uppercase;
            color: rgba(13,42,82,0.45);
            margin-bottom: 7px; margin-left: 2px;
        }

        /* ── Gold btn ── */
        .gold-btn {
            background: linear-gradient(135deg, #FFD740 0%, #F5C518 55%, #E0A800 100%);
            color: var(--blue-deep);
            box-shadow: 0 4px 20px rgba(245,197,24,0.40), 0 1px 0 rgba(255,255,255,0.35) inset;
            transition: all 0.25s ease; border: none; cursor: pointer;
            font-weight: 700;
        }
        .gold-btn:hover:not(:disabled) {
            box-shadow: 0 8px 32px rgba(245,197,24,0.55), 0 1px 0 rgba(255,255,255,0.35) inset;
            transform: translateY(-2px);
        }
        .gold-btn:disabled { opacity: 0.65; cursor: not-allowed; }

        /* ── Success alert ── */
        .success-alert {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 18px 22px; border-radius: 16px;
            background: rgba(16,185,129,0.06);
            border: 1px solid rgba(16,185,129,0.25);
            margin-bottom: 28px;
        }

        /* Sacred Luxury Components */
        .sacred-confirm-card {
            background: #FFFAF0;
            border: 1px solid #E6D5B8;
            border-radius: 0.75rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px -4px rgba(13, 42, 82, 0.08);
        }
        .sacred-church-watermark {
            display: none;
        }
        .sacred-step-badge {
            color: #B08D00;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }
        .sacred-divider-star {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin: 24px 0;
            position: relative;
        }
        .sacred-divider-star::before, .sacred-divider-star::after {
            content: "";
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, #E6D5B8, transparent);
        }
        .sacred-option-card {
            background: white;
            border: 1px solid #E6D5B8;
            border-radius: 0.75rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            gap: 10px;
            height: 44px;
            min-width: 100px;
        }
        .sacred-radio-custom {
            width: 18px;
            height: 18px;
            border: 2px solid #E6D5B8;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        input:checked + .sacred-option-card {
            border-width: 2px;
            box-shadow: 0 5px 15px -5px rgba(0, 0, 0, 0.1);
        }
        /* YES specific */
        label:first-of-type input:checked + .sacred-option-card {
            border-color: #198754;
            background: #f0fff4;
            color: #198754;
        }
        label:first-of-type input:checked + .sacred-option-card .sacred-radio-custom { border-color: #198754; }
        label:first-of-type input:checked + .sacred-option-card .sacred-radio-inner { background: #198754; }

        /* NO specific */
        label:last-of-type input:checked + .sacred-option-card {
            border-color: #dc3545;
            background: #fff5f5;
            color: #dc3545;
        }
        label:last-of-type input:checked + .sacred-option-card .sacred-radio-custom { border-color: #dc3545; }
        label:last-of-type input:checked + .sacred-option-card .sacred-radio-inner { background: #dc3545; }

        .sacred-radio-inner {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            transition: all 0.2s;
        }
        .sacred-processing-bar {
            background: #F0F4FA;
            border-radius: 0.25rem;
            padding: 4px 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border: 1px solid #E1E8F0;
        }
        .sr-only {
            position: absolute; width: 1px; height: 1px;
            padding: 0; margin: -1px; overflow: hidden;
            clip: rect(0, 0, 0, 0); white-space: nowrap; border-width: 0;
        }
        .success-icon {
            width: 34px; height: 34px; flex-shrink: 0;
            border-radius: 50%;
            background: rgba(16,185,129,0.12);
            border: 1px solid rgba(16,185,129,0.30);
            display: flex; align-items: center; justify-content: center;
            margin-top: 1px;
        }
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
        {{-- Cross icon --}}
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
                <span class="font-cinzel relative z-10" style="color:var(--gold);font-size:1.5rem;" aria-hidden="true">✝</span>
            </div>
        </div>

        <div class="divider-ornament mb-4 animate-fade-in delay-1">
            <span class="eyebrow" style="color:rgba(245,197,24,0.70);">Sacraments & Services</span>
        </div>

        <h1 class="font-heading animate-fade-up delay-1"
            style="font-size:clamp(2.8rem,6vw,5.5rem); font-weight:700; font-style:italic;
                   color:#FFF; line-height:1.05; letter-spacing:-0.01em; margin-bottom:16px;
                   text-shadow:0 4px 32px rgba(0,0,0,0.45);">
            Parish Inquiries
        </h1>

        <p class="font-heading animate-fade-up delay-2"
           style="font-style:italic; color:rgba(245,197,24,0.75);
                  font-size:clamp(0.9rem,1.8vw,1.1rem); font-weight:300;">
            Riviera Bonoumin Cocody, Abidjan, Côte d'Ivoire
        </p>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-px"
         style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.25),transparent);"></div>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- CONTENT                                            --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-20 max-w-[960px] mx-auto px-6 pb-32 md:pb-20">

    {{-- ── Duplicate Warning Modal ── --}}
    @if(session('duplicate_warning'))
    <div x-data="{ showModal: true }" x-show="showModal"
         class="fixed inset-0 z-[100] flex items-center justify-center p-6"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        <div class="absolute inset-0 bg-amber-900/60 backdrop-blur-sm" @click="showModal = false"></div>
        <div class="relative bg-white w-full max-w-md rounded-3xl overflow-hidden shadow-2xl border border-amber-100 animate-fade-up">
            <div class="h-2 bg-gradient-to-r from-amber-400 via-amber-500 to-amber-400"></div>
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-amber-50 border border-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <h2 class="font-heading text-2xl font-bold text-amber-900 italic mb-2">Duplicate Inquiry Detected</h2>
                <p class="text-amber-700/70 text-sm mb-4">You already have a pending <strong>{{ session('duplicate_type') }}</strong> inquiry (Ref: {{ session('duplicate_ref') }}).</p>
                <p class="text-amber-600/60 text-xs mb-6">Submitting a duplicate may cause delays. Would you like to track your existing inquiry instead?</p>
                <div class="flex flex-col gap-3">
                    <a href="{{ route('track') }}" class="gold-btn w-full h-12 rounded-2xl font-bold uppercase tracking-widest text-[11px] flex items-center justify-center gap-2" style="text-decoration:none;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Track Existing Inquiry
                    </a>
                    <button @click="showModal = false" class="w-full h-12 rounded-2xl font-bold uppercase tracking-widest text-[11px] border-2 border-amber-300 text-amber-700 hover:bg-amber-50 transition-all">
                        Submit Anyway
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Success Modal ── --}}
    @if(session('reference_id'))
    <div x-data="{ 
            showModal: true, 
            copied: false,
            copyId() {
                navigator.clipboard.writeText('{{ session('reference_id') }}');
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            }
         }"
         x-show="showModal"
         class="fixed inset-0 z-[100] flex items-center justify-center p-6"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-blue-900/60 backdrop-blur-sm" @click="showModal = false"></div>

        {{-- Modal Card --}}
        <div class="relative bg-white w-full max-w-md rounded-3xl overflow-hidden shadow-2xl border border-blue-100 animate-fade-up">
            {{-- Top Accent --}}
            <div class="h-2 bg-gradient-to-r from-gold via-gold-light to-gold"></div>
            
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-green-50 border border-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgb(16,185,129)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </div>

                <h2 class="font-heading text-3xl font-bold text-blue-900 italic mb-2">Inquiry Submitted!</h2>
                <p class="text-blue-600/70 text-sm mb-8">Please keep your reference ID to track the status of your request.</p>

                <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-6 mb-8">
                    <p class="eyebrow mb-3" style="color: var(--blue-mid); opacity: 0.6;">Reference ID</p>
                    <div class="flex items-center justify-center gap-3">
                        <span class="font-cinzel text-2xl font-bold tracking-wider text-blue-900">
                            {{ session('reference_id') }}
                        </span>
                        <button @click="copyId()" 
                                class="p-2.5 rounded-xl transition-all active:scale-95"
                                :class="copied ? 'bg-green-100 text-green-600' : 'bg-white text-blue-400 hover:text-blue-600 shadow-sm border border-blue-100'">
                            <svg x-show="!copied" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>
                            </svg>
                            <svg x-show="copied" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" x-cloak>
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        </button>
                    </div>
                    <p x-show="copied" x-transition class="text-[11px] font-bold text-green-600 uppercase tracking-widest mt-3" x-cloak>Copied to clipboard</p>
                </div>

                <button @click="showModal = false" 
                        class="gold-btn w-full h-14 rounded-2xl font-bold uppercase tracking-widest text-[11px]">
                    Continue
                </button>
                <a href="{{ route('track') }}"
                   class="mt-3 w-full h-12 rounded-2xl font-bold uppercase tracking-widest text-[11px] flex items-center justify-center gap-2 border-2 transition-all duration-200 hover:-translate-y-0.5"
                   style="border-color:rgba(26,64,128,0.25);color:var(--blue-deep);background:transparent;text-decoration:none;"
                   onmouseover="this.style.borderColor='var(--blue-mid)';this.style.background='rgba(26,64,128,0.05)';"
                   onmouseout="this.style.borderColor='rgba(26,64,128,0.25)';this.style.background='transparent';">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Check Track Status
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Card --}}
    <div class="inquiry-card">
        <div class="grid md:grid-cols-5">

            {{-- ── SIDEBAR ── --}}
            <aside class="sidebar md:col-span-2">
                <div class="relative z-10">
                    <p class="eyebrow mb-2" style="color:rgba(245,197,24,0.65);">How It Works</p>
                    <h2 class="font-heading font-bold italic mb-8"
                        style="font-size:2.15rem; color:#FFF; line-height:1.15;">
                        Sacramental Services
                    </h2>

                    <div class="space-y-6 mb-12">
                        @foreach([
                            'Fill out the inquiry form with your contact details and specific needs.',
                            'Our SocCom or Admin team will validate your request.',
                            'Once accepted, forwarded to the Parish Office for processing.',
                            'Document Requests (Baptismal, etc.) — allow 3–5 working days for verification.',
                        ] as $i => $step)
                        <div class="sidebar-step">
                            <div class="step-num">{{ $i + 1 }}</div>
                            <p style="font-size:13px; color:rgba(235,242,255,0.65); line-height:1.75;">{{ $step }}</p>
                        </div>
                        @endforeach
                    </div>

                    {{-- Gold rule --}}
                    <div style="height:1px; background:linear-gradient(90deg,rgba(245,197,24,0.25),transparent); margin-bottom:28px;"></div>

                    <div class="space-y-4">
                        @php
                            $contactRaw = $global_settings['parish_contact'] ?? '';
                            $contactNumbers = is_string($contactRaw) && $contactRaw !== ''
                                ? (json_decode($contactRaw, true) ?: [$contactRaw])
                                : (is_array($contactRaw) ? $contactRaw : ['+63 2 8869 2742']);
                        @endphp
                        @foreach($contactNumbers as $number)
                        <div class="sidebar-contact">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            {{ $number }}
                        </div>
                        @endforeach
                        <div class="sidebar-contact">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h9"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/><path d="M19 16v6"/><path d="M16 19h6"/></svg>
                            <span style="word-break:break-all;">{{ config('services.parish.office_email') }}</span>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- ── FORM ── --}}
            <div class="md:col-span-3 p-8 md:p-10" style="background:#FFF;"
                 x-data="{ 
            loading: false, 
            inquiryType: '{{ old('inquiryType', '') }}' || new URLSearchParams(window.location.search).get('type') || '',
            serviceRequirements: {
                'Baptism': [
                    'Birth Certificate with Registry Number (Original & Photocopy)',
                    'Photocopy of Marriage Contract (If parents are married)',
                    'Original Copy of Baptismal Permit (If not Riviera Bonoumin residents)',
                    'Registration Fee: Php 500.00 (Fixed Amount)'
                ],
                'First Communion': ['Photocopy of Baptismal Certificate'],
                'Confirmation': ['Photocopy of Baptismal Certificate'],
                'Wedding': [
                    'Baptismal & Confirmation Certificate (New copy, for Marriage Purpose)',
                    'PSA Birth Certificate & CENOMAR',
                    'Permit from bride\'s parish (if non-parishioner)',
                    'Marriage License or Civil Marriage Contract (if applicable)',
                    'Completion: 2 months before preferred date'
                ],
                'Funeral Mass': ['Photocopy of Death Certificate'],
                'Baptismal Certificate': ['Full Name of the person', 'Date of Baptism', 'Minimal fee of Php 100.00'],
                'Confirmation Certificate': ['Full Name of the person', 'Date of Confirmation'],
                'Marriage Certificate': ['Names of Couple', 'Date of Wedding'],
                'Car Blessing': ['Vehicle Type & Plate Number'],
                'House Blessing': ['Exact Address/Location'],
                'Other': ['Please specify your request in the message details']
            },
            getRequirements() {
                return this.serviceRequirements[this.inquiryType] || [];
            },
            certData: {
                name: '', birthdate: '', baptism: '', birthplace: '', contact: '',
                father: '', mother: '', purpose: '', 
                partner: '', weddingDate: '', weddingPlace: ''
            },
            getCombinedMessage() {
                if (!['Baptism', 'Baptismal Certificate'].includes(this.inquiryType)) return '';
                let msg = 'NAME: ' + this.certData.name + '\n';
                msg += 'BIRTHDATE: ' + this.certData.birthdate + '\n';
                msg += 'BAPTISM: ' + this.certData.baptism + '\n';
                msg += 'PLACE OF BIRTH: ' + this.certData.birthplace + '\n';
                msg += 'CONTACT: ' + this.certData.contact + '\n\n';
                msg += 'FATHER: ' + this.certData.father + '\n';
                msg += 'MOTHER: ' + this.certData.mother + '\n\n';
                msg += 'PURPOSE: ' + this.certData.purpose + '\n';
                if (this.certData.purpose.toLowerCase().includes('marriage')) {
                    msg += '\n(FOR MARRIAGE)\n';
                    msg += 'NAME OF PARTNER: ' + this.certData.partner + '\n';
                    msg += 'DATE OF WEDDING: ' + this.certData.weddingDate + '\n';
                    msg += 'PLACE OF WEDDING: ' + this.certData.weddingPlace + '\n';
                }
                return msg;
            },
            // FIX: validate before allowing submit
            validateAndSubmit(e) {
                if (['Baptism', 'Baptismal Certificate'].includes(this.inquiryType)) {
                    const msg = this.getCombinedMessage().trim();
                    if (!this.certData.name || !this.certData.father || !this.certData.mother || !this.certData.purpose) {
                        e.preventDefault();
                        alert('Please fill in all required fields (Name, Father, Mother, Purpose).');
                        return;
                    }
                }
                this.loading = true;
            }
        }">

                <p class="eyebrow mb-1">Contact Form</p>
                <h3 class="font-heading font-bold italic mb-8"
                    style="font-size:1.6rem; color:var(--blue-deep); line-height:1.2;">
                    Send Your Inquiry
                </h3>

                <form action="{{ route('inquiry.store') }}" method="POST"
                      class="space-y-5" @submit="validateAndSubmit($event)">
                    @csrf

                    @if(session('duplicate_warning'))
                    <div class="p-4 rounded-2xl border-2 border-amber-200 bg-amber-50/80 flex items-start gap-3" x-data="{ showDup: true }" x-show="showDup">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2" class="shrink-0 mt-0.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-amber-800">Duplicate inquiry detected</p>
                            <p class="text-xs text-amber-600/70 mt-1">You already have a pending <strong>{{ session('duplicate_type') }}</strong> inquiry (Ref: {{ session('duplicate_ref') }}). You may submit again, but consider tracking your existing one first.</p>
                            <div class="flex gap-2 mt-3">
                                <a href="{{ route('track') }}" class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest border border-amber-300 text-amber-700 hover:bg-amber-100 transition-all" style="text-decoration:none;">Track Existing</a>
                                <button type="button" @click="showDup = false" class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest text-amber-600 hover:text-amber-800 transition-all">Dismiss</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="force_submit" value="1">
                    @endif

{{-- Full Name --}}
                    <div>
                        <label class="field-label" for="fullName">Full Name</label>
                        <input name="fullName" id="fullName" required
                               placeholder="Juan Dela Cruz"
                               class="sacred-input @error('fullName') error @enderror"
                               aria-invalid="@error('fullName') true @else false @enderror"
                               aria-describedby="fullName-error"
                               value="{{ old('fullName') }}">
                        @error('fullName')
                            <p id="fullName-error" class="text-red-600 text-sm mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email + Phone --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="field-label" for="email">Email</label>
                            <input type="email" name="email" id="email" required
                                   placeholder="juan@example.com"
                                   class="sacred-input @error('email') error @enderror"
                                   aria-invalid="@error('email') true @else false @enderror"
                                   aria-describedby="email-error"
                                   value="{{ old('email') }}">
                            @error('email')
                                <p id="email-error" class="text-red-600 text-sm mt-1" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="field-label" for="phone">Phone <span style="opacity:0.45;">(Optional)</span></label>
                            <input name="phone" id="phone"
                                   placeholder="0912 345 6789"
                                   class="sacred-input @error('phone') error @enderror"
                                   aria-invalid="@error('phone') true @else false @enderror"
                                   aria-describedby="phone-error"
                                   value="{{ old('phone') }}">
                            @error('phone')
                                <p id="phone-error" class="text-red-600 text-sm mt-1" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Inquiry Type + Preferred Date --}}
                    <div :class="['Baptism','Wedding','Funeral Mass'].includes(inquiryType)
                                 ? 'grid grid-cols-1 md:grid-cols-2 gap-4'
                                 : ''">
                        <div>
                            <label class="field-label" for="inquiryType">Inquiry Type</label>
                            <div class="relative">
<select name="inquiryType" id="inquiryType" required
        x-model="inquiryType"
        class="sacred-input @error('inquiryType') error @enderror" style="padding-right:42px;"
        aria-invalid="@error('inquiryType') true @else false @enderror"
        aria-describedby="inquiryType-error">
    <option value="" disabled selected>Select a service…</option>
    <optgroup label="Sacramental Rites">
        <option value="Baptism">Baptism</option>
        <option value="First Communion">First Communion</option>
        <option value="Confirmation">Confirmation</option>
        <option value="Wedding">Wedding</option>
        <option value="Funeral Mass">Funeral Mass</option>
    </optgroup>
    <optgroup label="Document Requests">
        <option value="Baptismal Certificate">Baptismal Certificate</option>
        <option value="Confirmation Certificate">Confirmation Certificate</option>
        <option value="Marriage Certificate">Marriage Certificate</option>
    </optgroup>
    <optgroup label="Blessings & Others">
        <option value="Car Blessing">Car Blessing</option>
        <option value="House Blessing">House Blessing</option>
        <option value="Other">Others</option>
    </optgroup>
</select>
@error('inquiryType')
    <p id="inquiryType-error" class="text-red-600 text-sm mt-1" role="alert">{{ $message }}</p>
@enderror
<div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                         stroke="rgba(13,42,82,0.38)" stroke-width="2.5"
                                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Document Confirmation Banner --}}
                        <div x-show="['Baptismal Certificate', 'Confirmation Certificate', 'Marriage Certificate'].includes(inquiryType)" 
                             x-transition
                             class="mt-3 sacred-confirm-card">
                            
                            <div class="p-3">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded bg-[#F7F2E8] border border-[#E6D5B8] flex items-center justify-center">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#B08D00" stroke-width="2.5">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M9 15l2 2 4-4"/>
                                            </svg>
                                        </div>
                                        <p class="text-[11px] font-bold text-blue-900/80">Request a Document?</p>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        {{-- YES Option --}}
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="wants_document" value="yes" class="peer sr-only" checked>
                                            <div class="sacred-option-card">
                                                <div class="sacred-radio-custom"><div class="sacred-radio-inner"></div></div>
                                                <p class="font-cinzel font-black text-[13px] tracking-widest">YES</p>
                                            </div>
                                        </label>

                                        {{-- NO Option --}}
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="wants_document" value="no" class="peer sr-only">
                                            <div class="sacred-option-card">
                                                <div class="sacred-radio-custom"><div class="sacred-radio-inner"></div></div>
                                                <p class="font-cinzel font-black text-[13px] tracking-widest">NO</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="mt-3 flex items-center justify-between border-t border-[#E6D5B8]/30 pt-2">
                                    <div class="sacred-processing-bar">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#2255A4" stroke-width="2.5">
                                            <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                                        </svg>
                                        <p class="text-[7px] font-bold text-blue-800 uppercase tracking-widest">3–5 WORKING DAYS</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="['Baptism','Wedding','Funeral Mass'].includes(inquiryType)"
                             x-transition>
                            <label class="field-label" for="preferredDate">Preferred Date</label>
                            <div class="relative">
                                <input type="date" name="preferredDate" id="preferredDate"
                                       :required="['Baptism','Wedding','Funeral Mass'].includes(inquiryType)"
                                       min="{{ date('Y-m-d') }}"
                                       value="{{ old('preferredDate') }}"
                                       class="sacred-input @error('preferredDate') error @enderror" style="padding-right:42px;"
                                       aria-invalid="@error('preferredDate') true @else false @enderror"
                                       aria-describedby="preferredDate-error">
                                @error('preferredDate')
                                    <p id="preferredDate-error" class="text-red-600 text-sm mt-1" role="alert">{{ $message }}</p>
                                @enderror
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                         stroke="rgba(13,42,82,0.38)" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect width="18" height="18" x="3" y="4" rx="2"/>
                                        <path d="M16 2v4M8 2v4M3 10h18"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── MESSAGE AREA ──
                         FIX: Use x-if so only ONE input named "message" is ever in the DOM.
                         Previously both the textarea and hidden input coexisted, causing PHP
                         to receive an empty string (from the hidden input) instead of the
                         textarea's value, failing validation. --}}
                    <div class="pt-2">
                        <div class="flex items-center justify-between mb-4">
                            <label class="field-label mb-0" x-show="!inquiryType">Message / Details</label>
                            <label class="field-label mb-0" x-show="inquiryType" x-transition>
                                Include details for <span x-text="inquiryType === 'Baptism' ? 'Baptism Rites' : inquiryType"></span>
                            </label>
                        </div>

                        {{-- Standard textarea — rendered only when NOT a Baptism/Certificate type --}}
                        <template x-if="!['Baptism', 'Baptismal Certificate'].includes(inquiryType)">
                            <textarea name="message" id="message" rows="4"
                                      :required="true"
                                      placeholder="Provide details about your request (e.g. occasion, names)…"
                                      class="sacred-input @error('message') error @enderror"
                                      aria-invalid="@error('message') true @else false @enderror"
                                      aria-describedby="message-error"></textarea>
                            @error('message')
                                <p id="message-error" class="text-red-600 text-sm mt-1" role="alert">{{ $message }}</p>
                            @enderror
                        </template>

                        {{-- Structured inputs for Baptism / Baptismal Certificate --}}
                        <template x-if="['Baptism', 'Baptismal Certificate'].includes(inquiryType)">
                            <div class="space-y-4">
                                {{-- Single hidden input — only present in DOM for these types --}}
                                <input type="hidden" name="message" :value="getCombinedMessage()">

                                @error('message')
                                    <p id="message-error" class="text-red-600 text-sm mt-1 mb-4" role="alert">{{ $message }}</p>
                                @enderror

                                {{-- Baptism-specific guidelines --}}
                                <template x-if="inquiryType === 'Baptism'">
                                    <div class="p-4 bg-blue-50/30 border border-blue-100 rounded-2xl space-y-4 mb-4">
                                        <div>
                                            <p class="text-[11px] font-bold uppercase tracking-wider text-blue-900 mb-2 flex items-center gap-2">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 8h.01M12 12V16M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 4.03 9 9 9z"/>
                                                </svg>
                                                Baptism Rites Requirements
                                            </p>
                                            <ul class="space-y-1.5 text-left text-sm text-blue-800/70">
                                                <li>1. Birth Certificate with Registry Number (Original & Photocopy)</li>
                                                <li>2. Photocopy of Marriage Contract (If parents are married)</li>
                                                <li>3. Original Copy of Baptismal Permit (If not Riviera Bonoumin residents)</li>
                                                <li>4. Registration Fee of ₱500.00 (Fixed Amount)</li>
                                            </ul>
                                        </div>
                                        <div class="pt-3 border-t border-blue-100 space-y-4">
                                            <div class="space-y-1.5">
                                                <p class="text-[11px] font-bold text-blue-900/60 uppercase tracking-widest">Notes & Schedule</p>
                                                <ul class="text-sm text-blue-800/70 space-y-1">
                                                    <li>• Deadline: At least 1 week before the date.</li>
                                                    <li>• Schedule: Saturdays & Sundays only (No special baptism).</li>
                                                    <li>• Seminar: 10:00 AM (Parents & Sponsors must attend).</li>
                                                    <li>• Sponsors: Must be Catholic members.</li>
                                                </ul>
                                            </div>

                                            <div class="space-y-1.5">
                                                <p class="text-[11px] font-bold text-blue-900/60 uppercase tracking-widest">Dress Code</p>
                                                <p class="text-sm text-blue-800/70"><strong>Person to be baptized:</strong> White baptismal attire</p>
                                                <p class="text-sm text-blue-800/70"><strong>Parents & Godparents:</strong> Sunday attire; formal or casual</p>
                                            </div>

                                            <div class="bg-red-50 p-2 rounded-xl border border-red-100 text-center">
                                                <p class="text-[10px] font-black text-red-600 uppercase tracking-tighter">
                                                    NO CAPS, PLUNGING NECKLINES, SPAGHETTI STRAPS, SHORTS, MINI-SKIRTS OR REVEALING APPARELS
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        {{-- Wedding-specific guidelines --}}
                        <template x-if="inquiryType === 'Wedding'">
                            <div class="mt-4 space-y-4 animate-fade-down">
                                <div class="p-4 bg-[#FDFBF7] border border-[#E6D5B8]/50 rounded-2xl space-y-4">
                                    <div>
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-[#B08D00] mb-2 flex items-center gap-2">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 8h.01M12 12V16M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 4.03 9 9 9z"/>
                                            </svg>
                                            Wedding Requirements
                                        </p>
                                        <ul class="space-y-1.5 text-left text-sm text-blue-800/70">
                                            <li>1. Original Copy of Certificate of Baptism with "For Marriage Purpose" annotation</li>
                                            <li>2. Original Copy of Certificate of Confirmation with "For Marriage Purpose" annotation</li>
                                            <li>3. PSA-issued Birth Certificate</li>
                                            <li>4. PSA-issued Certificate of No Records of Marriage (Cenomar)</li>
                                            <li>5. Permit from the parish of the bride (if non-parishioner)</li>
                                            <li>6. Certificate of Legal Capacity to Marry (from Embassy, for non-Filipinos)</li>
                                            <li>7. Death Certificate & Marriage Contract of deceased spouse (for widows/widowers)</li>
                                            <li>8. Original Copy of Marriage License from respective municipalities</li>
                                            <li>9. NSO Certified True Copy of Civil Marriage Contract (if applicable)</li>
                                            <li>10. Affidavit of 5+ years cohabitation (if applicable)</li>
                                            <li>11. Wedding Fees to be settled one (1) week before the date</li>
                                        </ul>
                                    </div>
                                    <div class="pt-3 border-t border-[#E6D5B8]/20 space-y-4">
                                        <div class="space-y-1.5">
                                            <p class="text-[11px] font-bold text-[#B08D00] uppercase tracking-widest">Optional Donations & Fees</p>
                                            <div class="grid grid-cols-2 gap-2 text-sm text-blue-800/70">
                                                <div>• Wedding: ₱5k</div>
                                                <div>• Floral: ₱12k</div>
                                                <div>• Aircon: ₱8k</div>
                                                <div>• Lights: ₱3k-5k</div>
                                            </div>
                                        </div>
                                        <div class="space-y-1.5">
                                            <p class="text-[11px] font-bold text-[#B08D00] uppercase tracking-widest">Schedule & Deadlines</p>
                                            <ul class="text-sm text-blue-800/70 space-y-1">
                                                <li>• Completion: 2 months before preferred date</li>
                                                <li>• Interview: 1 month before preferred date</li>
                                            </ul>
                                        </div>
                                        <div class="space-y-1.5">
                                            <p class="text-[11px] font-bold text-[#B08D00] uppercase tracking-widest">Seminar Schedule</p>
                                            <ul class="text-sm text-blue-800/70 space-y-1">
                                                <li>• 1st Saturday: 1:00 - 6:00 PM</li>
                                                <li>• 2nd Saturday: 1:00 - 4:00 PM</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[9px] font-bold text-blue-900/40 uppercase mb-1 block ml-1">Name <span class="text-red-400">*</span></label>
                                        <input x-model="certData.name" placeholder="Full Name" class="sacred-input">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-bold text-blue-900/40 uppercase mb-1 block ml-1">Birthdate</label>
                                        <input x-model="certData.birthdate" type="date" class="sacred-input">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-bold text-blue-900/40 uppercase mb-1 block ml-1">Baptism Date</label>
                                        <input x-model="certData.baptism" type="date" class="sacred-input">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-bold text-blue-900/40 uppercase mb-1 block ml-1">Place of Birth</label>
                                        <input x-model="certData.birthplace" placeholder="City/Municipality" class="sacred-input">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[9px] font-bold text-blue-900/40 uppercase mb-1 block ml-1">Father's Name <span class="text-red-400">*</span></label>
                                        <input x-model="certData.father" placeholder="Full Name" class="sacred-input">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-bold text-blue-900/40 uppercase mb-1 block ml-1">Mother's Name <span class="text-red-400">*</span></label>
                                        <input x-model="certData.mother" placeholder="Full Name" class="sacred-input">
                                    </div>
                                </div>

                                <div>
                                    <label class="text-[9px] font-bold text-blue-900/40 uppercase mb-1 block ml-1">Purpose of Request <span class="text-red-400">*</span></label>
                                    <select x-model="certData.purpose" class="sacred-input">
                                        <option value="">Select purpose…</option>
                                        <option value="Personal Copy">Personal Copy</option>
                                        <option value="Marriage Purpose">For Marriage Purpose</option>
                                        <option value="School Requirement">School Requirement</option>
                                        <option value="Employment">Employment</option>
                                    </select>
                                </div>

                                {{-- Marriage Specific Fields --}}
                                <template x-if="certData.purpose === 'Marriage Purpose'">
                                    <div x-transition 
                                         class="p-5 bg-[#FDFBF7] border border-[#E6D5B8]/40 rounded-2xl space-y-4">
                                        <p class="text-[10px] font-bold text-[#B08D00] uppercase tracking-widest border-b border-[#E6D5B8]/20 pb-2">Marriage Information</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="md:col-span-2">
                                                <label class="text-[9px] font-bold text-[#B08D00]/60 uppercase mb-1 block ml-1">Name of Partner</label>
                                                <input x-model="certData.partner" placeholder="Full Name" class="sacred-input" style="background:#FFF;">
                                            </div>
                                            <div>
                                                <label class="text-[9px] font-bold text-[#B08D00]/60 uppercase mb-1 block ml-1">Date of Wedding</label>
                                                <input x-model="certData.weddingDate" type="date" class="sacred-input" style="background:#FFF;">
                                            </div>
                                            <div>
                                                <label class="text-[9px] font-bold text-[#B08D00]/60 uppercase mb-1 block ml-1">Place of Wedding</label>
                                                <input x-model="certData.weddingPlace" placeholder="Church & Location" class="sacred-input" style="background:#FFF;">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    {{-- Consent --}}
                    <div class="flex items-start gap-3 pt-2">
                        <input type="checkbox" name="consent" id="consent" required
                               class="mt-0.5 h-4 w-4 shrink-0 rounded accent-[#0D2A52]"
                               aria-describedby="consent-error">
                        <label for="consent" class="text-xs leading-relaxed" style="color:rgba(13,42,82,.62);">
                            I have read and agree to the
                            <a href="{{ route('privacy-policy') }}" target="_blank" rel="noopener" class="underline font-semibold" style="color:var(--blue-deep);">Privacy Policy</a>
                            and the
                            <a href="{{ route('terms') }}" target="_blank" rel="noopener" class="underline font-semibold" style="color:var(--blue-deep);">Terms &amp; Conditions</a>.
                        </label>
                    </div>
                    @error('consent')
                        <p id="consent-error" class="text-red-600 text-sm mt-1" role="alert">{{ $message }}</p>
                    @enderror

                    {{-- Submit --}}
                    <div style="text-align:right;">
                    <button type="submit" :disabled="loading"
                            class="gold-btn h-14 rounded-2xl relative overflow-hidden
                                   inline-flex items-center justify-center gap-2.5
                                   text-[11px] font-bold uppercase tracking-widest px-10">
                        <span :class="loading ? 'opacity-0' : 'opacity-100'"
                              class="transition-opacity flex items-center gap-2.5">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.5"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>
                            </svg>
                            Submit Inquiry
                        </span>
                        <div x-show="loading" x-cloak
                             class="absolute inset-0 flex items-center justify-center">
                            <svg class="animate-spin w-6 h-6" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </div>
                    </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

</x-public-layout>