<x-public-layout>
<x-slot name="meta">
    <meta name="description" content="Submit a Mass Intention at Paroisse Saint-Joseph Artisan – Riviera Bonoumin, Abidjan, Côte d'Ivoire.">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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

        .reveal {
            opacity: 0; transform: translateY(40px);
            transition: all 1.2s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .reveal.active { opacity: 1; transform: translateY(0); }

        /* ── Page hero ── */
        .page-hero {
            background: linear-gradient(160deg, var(--blue-deep) 0%, var(--blue-mid) 60%, #0f3060 100%);
            position: relative; overflow: hidden;
        }
        .page-hero::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse 70% 60% at 50% 0%, rgba(245,197,24,0.10) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ── Form card ── */
        .form-card {
            background: #FFFFFF;
            border: 1px solid rgba(26,64,128,0.12);
            border-radius: 24px;
            box-shadow: 0 12px 50px rgba(13,42,82,0.09);
            overflow: hidden;
        }

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
            outline: none;
            appearance: none;
        }
        .sacred-input:focus {
            border-color: var(--color-blue-deep);
            background: #FFFFFF;
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
            font-size: 9.5px; font-weight: 700;
            letter-spacing: 0.28em; text-transform: uppercase;
            color: rgba(13,42,82,0.45);
            margin-bottom: 7px; margin-left: 2px;
        }

        /* ── Payment toggle ── */
        .pay-btn {
            flex: 1; padding: 11px 8px;
            border: 1px solid rgba(26,64,128,0.14);
            border-radius: 12px;
            font-size: 10px; font-weight: 700;
            letter-spacing: 0.18em; text-transform: uppercase;
            color: rgba(13,42,82,0.45);
            background: var(--cream);
            cursor: pointer; transition: all 0.2s ease;
        }
        .pay-btn.active {
            background: var(--blue-deep);
            color: #FFFFFF;
            border-color: var(--blue-deep);
            box-shadow: 0 4px 16px rgba(13,42,82,0.22);
        }

        /* ── Payment info box ── */
        .pay-info-box {
            background: var(--blue-pale);
            border: 1px dashed rgba(26,64,128,0.20);
            border-radius: 14px;
            padding: 20px; text-align: center;
        }

        /* ── Gold btn ── */
        .gold-btn {
            background: linear-gradient(135deg, #FFD740 0%, #F5C518 55%, #E0A800 100%);
            color: var(--blue-deep);
            box-shadow: 0 4px 20px rgba(245,197,24,0.40), 0 1px 0 rgba(255,255,255,0.35) inset;
            transition: all 0.25s ease; font-weight: 700;
            border: none; cursor: pointer;
        }
        .gold-btn:hover:not(:disabled) {
            box-shadow: 0 8px 32px rgba(245,197,24,0.55), 0 1px 0 rgba(255,255,255,0.35) inset;
            transform: translateY(-2px);
        }
        .gold-btn:disabled { opacity: 0.65; cursor: not-allowed; }

        /* ── Success card ── */
        .ref-box {
            background: var(--blue-pale);
            border: 1.5px dashed rgba(26,64,128,0.22);
            border-radius: 18px; padding: 24px;
            text-align: center;
        }

        /* ── Donation accordion trigger ── */
        .donation-trigger {
            width: 100%; display: flex; align-items: center; justify-content: space-between;
            padding: 14px 18px; border-radius: 14px;
            border: 1px dashed rgba(245,197,24,0.40);
            background: rgba(245,197,24,0.04);
            cursor: pointer; transition: all 0.2s ease;
        }
        .donation-trigger:hover {
            background: rgba(245,197,24,0.08);
            border-color: rgba(245,197,24,0.60);
        }

        /* ── Flatpickr overrides ── */
        .flatpickr-calendar {
            border: 1px solid rgba(26,64,128,0.14) !important;
            border-radius: 16px !important;
            box-shadow: 0 12px 40px rgba(13,42,82,0.14) !important;
            font-family: 'Jost', sans-serif !important;
        }
        .flatpickr-day.selected, .flatpickr-day.selected:hover {
            background: var(--blue-deep) !important;
            border-color: var(--blue-deep) !important;
        }
        .flatpickr-day:hover { background: var(--blue-pale) !important; }
        .flatpickr-months .flatpickr-month { background: var(--blue-deep) !important; color: #FFF !important; border-radius: 16px 16px 0 0; }
        .flatpickr-current-month { color: #FFF !important; }
        .flatpickr-weekday { color: rgba(13,42,82,0.45) !important; font-weight: 700 !important; }
        .numInputWrapper:hover { background: transparent !important; }

        .flatpickr-monthDropdown-months {
            appearance: none !important;
            -webkit-appearance: none !important;
            background: var(--blue-deep) !important;
            color: #FFF !important;
            border: none !important;
            outline: none !important;
            font-family: 'Jost', sans-serif !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            padding: 0 4px !important;
            cursor: pointer !important;
        }

        .flatpickr-monthDropdown-months option {
            background: #0D2A52 !important;
            color: #FFFFFF !important;
        }

        .flatpickr-monthDropdown-months option:hover,
        .flatpickr-monthDropdown-months option:checked {
            background: #1A4080 !important;
            color: #FFFFFF !important;
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
            <span class="eyebrow" style="color:rgba(245,197,24,0.70);">Holy Sacrifice of the Mass</span>
        </div>

        <h1 class="font-heading animate-fade-up delay-1"
            style="font-size:clamp(2.8rem,6vw,5.5rem); font-weight:700; font-style:italic;
                   color:#FFFFFF; line-height:1.05; letter-spacing:-0.01em; margin-bottom:16px;
                   text-shadow:0 4px 32px rgba(0,0,0,0.45);">
            Submit Mass Intention
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
{{-- FORM                                               --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-20 max-w-[680px] mx-auto px-6" x-data="intentionForm()">

    {{-- ── SUCCESS STATE ── --}}
    <div x-show="submitted" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="reveal active text-center">

        {{-- Check circle --}}
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 rounded-full flex items-center justify-center"
                 style="background:rgba(245,197,24,0.10); border:2px solid rgba(245,197,24,0.40);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                     stroke="#C9A200" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 6 9 17l-5-5"/>
                </svg>
            </div>
        </div>

        <div class="divider-ornament mb-4"><span class="eyebrow">Request Received</span></div>
        <h2 class="font-heading font-bold italic mb-3"
            style="font-size:clamp(2rem,4vw,3rem); color:var(--blue-deep); line-height:1.1;">
            Submission Received
        </h2>
        <p style="color:rgba(13,42,82,0.48); font-size:14px; line-height:1.8; margin-bottom:28px;">
            Your mass intention has been submitted and is awaiting review by our parish staff.
        </p>

        {{-- Reference ID --}}
        <div x-show="refId" class="ref-box mb-8">
            <p class="eyebrow mb-3" style="color:rgba(13,42,82,0.40);">Reference ID</p>
            <div class="flex items-center justify-center gap-3">
                <span class="font-cinzel font-bold"
                      style="font-size:1.6rem; color:var(--blue-deep); letter-spacing:0.12em;"
                      x-text="refId"></span>
                <button @click="copyRefId()" type="button"
                        class="w-9 h-9 rounded-xl flex items-center justify-center transition-all"
                        style="border:1px solid rgba(26,64,128,0.16); background:var(--cream);"
                        title="Copy ID">
                    <span x-show="!refCopied">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,0.45)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="14" height="14" x="8" y="8" rx="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                    </span>
                    <span x-show="refCopied" x-cloak
                          style="font-size:8px; font-weight:700; letter-spacing:0.15em; color:#C9A200;">✓</span>
                </button>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <a :href="'/track-intention/' + refId"
               class="gold-btn w-full h-14 rounded-2xl inline-flex items-center justify-center gap-2.5
                      text-[11px] font-bold uppercase tracking-widest"
               style="text-decoration:none;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Track My Request
            </a>
            <button @click="reset()" type="button"
                    class="w-full h-12 rounded-2xl text-[11px] font-bold uppercase tracking-widest transition-all duration-200"
                    style="border:1.5px solid rgba(26,64,128,0.18); color:rgba(13,42,82,0.50); background:transparent;"
                    onmouseover="this.style.background='rgba(26,64,128,0.05)'; this.style.borderColor='rgba(26,64,128,0.30)';"
                    onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(26,64,128,0.18)';">
                Submit Another
            </button>
        </div>
    </div>

    {{-- ── FORM STATE ── --}}
    <div x-show="!submitted" class="reveal active">

        {{-- Duplicate Warning Modal --}}
        <div x-show="dupWarning" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-6"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="dupWarning = false"></div>
            <div class="relative bg-white max-w-md w-full rounded-3xl shadow-2xl p-8 text-center"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center"
                     style="background:rgba(245,197,24,0.10); border:2px solid rgba(245,197,24,0.30);">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <h2 class="font-heading text-2xl font-bold italic mb-2" style="color:var(--blue-deep);">Duplicate Intention Detected</h2>
                <p class="text-sm" style="color:rgba(13,42,82,0.55);">
                    You already have a pending <strong x-text="dupType"></strong> intention (Ref: <strong x-text="dupRef"></strong>).
                </p>
                <p class="text-xs mt-3 mb-6" style="color:rgba(13,42,82,0.40);">
                    Submitting a duplicate may cause delays. You may track your existing intention instead.
                </p>
                <div class="flex flex-col gap-3">
                    <a :href="'/track-intention/' + dupRef"
                       class="gold-btn w-full h-12 rounded-2xl inline-flex items-center justify-center gap-2 text-[11px] font-bold uppercase tracking-widest"
                       style="text-decoration:none;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Track Existing Intention
                    </a>
                    <button @click="forceSubmit()" type="button"
                            class="w-full h-12 rounded-2xl text-[11px] font-bold uppercase tracking-widest transition-all duration-200"
                            style="border:1.5px solid rgba(26,64,128,0.18); color:rgba(13,42,82,0.50); background:transparent;">
                        Submit Anyway
                    </button>
                    <button @click="dupWarning = false" type="button"
                            class="text-[10px] font-bold uppercase tracking-widest transition-all"
                            style="color:rgba(13,42,82,0.30);">Cancel</button>
                </div>
            </div>
        </div>

        <div class="form-card">
            {{-- Card header --}}
            <div class="px-8 py-6" style="border-bottom:1px solid rgba(26,64,128,0.08); background:var(--cream-deep);">
                <p class="eyebrow mb-1">Intention Details</p>
                <h2 class="font-heading font-bold italic"
                    style="font-size:1.5rem; color:var(--blue-deep); line-height:1.2;">
                    Fill Out Your Request
                </h2>
            </div>

            <div class="p-8">
                <form @submit.prevent="submitForm()" class="space-y-6">
                    @csrf

                    {{-- Name + Email --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="field-label" for="fullName">Requester Full Name</label>
                            <input x-model="formData.fullName"
                                   id="fullName" name="fullName"
                                   placeholder="Juan Dela Cruz" required
                                   class="sacred-input"
                                   :class="{ error: fieldErrors.fullName }"
                                   :aria-invalid="!!fieldErrors.fullName"
                                   aria-describedby="fullName-error">
                            <p x-show="fieldErrors.fullName" id="fullName-error" class="text-red-600 text-sm mt-1" role="alert"
                               x-text="fieldErrors.fullName[0]"></p>
                        </div>
                        <div>
                            <label class="field-label" for="email">Email Address</label>
                            <input x-model="formData.email"
                                   id="email" name="email" type="email"
                                   placeholder="juan@example.com" required
                                   class="sacred-input"
                                   :class="{ error: fieldErrors.email }"
                                   :aria-invalid="!!fieldErrors.email"
                                   aria-describedby="email-error">
                            <p x-show="fieldErrors.email" id="email-error" class="text-red-600 text-sm mt-1" role="alert"
                               x-text="fieldErrors.email[0]"></p>
                        </div>
                    </div>

                    {{-- Intention Category --}}
                    <div>
                        <label class="field-label" for="intentionType">Intention Category</label>
                        <div class="relative">
                            <select x-model="formData.intentionType"
                                    id="intentionType" name="intentionType" required
                                    class="sacred-input" style="padding-right:42px;"
                                    :class="{ error: fieldErrors.intentionType }"
                                    :aria-invalid="!!fieldErrors.intentionType"
                                    aria-describedby="intentionType-error">
                                <option value="" disabled selected>Select category…</option>
                                <template x-for="type in intentionTypes" :key="type">
                                    <option :value="type" x-text="type"></option>
                                </template>
                            </select>
                            <p x-show="fieldErrors.intentionType" id="intentionType-error" class="text-red-600 text-sm mt-1" role="alert"
                               x-text="fieldErrors.intentionType[0]"></p>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,0.38)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Date + Time --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="field-label" for="preferredDate">Mass Date</label>
                            <div class="relative">
                                <input x-ref="datePicker"
                                       x-model="formData.preferredDate"
                                       id="preferredDate" name="preferredDate"
                                       placeholder="Select date" required readonly
                                       class="sacred-input"
                                       :class="{ error: fieldErrors.preferredDate }"
                                       :aria-invalid="!!fieldErrors.preferredDate"
                                       aria-describedby="preferredDate-error"
                                       style="cursor:pointer; padding-right:42px;">
                                <p x-show="fieldErrors.preferredDate" id="preferredDate-error" class="text-red-600 text-sm mt-1" role="alert"
                                   x-text="fieldErrors.preferredDate[0]"></p>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,0.38)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="field-label" for="massTime">Service Time</label>
                            <div class="relative">
                                <select x-model="formData.massTime"
                                        id="massTime" name="massTime" required
                                        class="sacred-input" style="padding-right:42px;"
                                        :class="{ error: fieldErrors.massTime }"
                                        :aria-invalid="!!fieldErrors.massTime"
                                        aria-describedby="massTime-error">
                                    <option value="" disabled selected>Select time…</option>
                                    <template x-for="time in massTimes" :key="time">
                                        <option :value="time" x-text="time"></option>
                                    </template>
                                </select>
                                <p x-show="fieldErrors.massTime" id="massTime-error" class="text-red-600 text-sm mt-1" role="alert"
                                   x-text="fieldErrors.massTime[0]"></p>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,0.38)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="field-label" for="description">Intention Detail / Names</label>
                        <textarea x-model="formData.description"
                                  id="description" name="description"
                                  rows="4" required
                                  placeholder="Enter names or specific prayer requests…"
                                  class="sacred-input"
                                  :class="{ error: fieldErrors.description }"
                                  :aria-invalid="!!fieldErrors.description"
                                  aria-describedby="description-error"></textarea>
                        <p x-show="fieldErrors.description" id="description-error" class="text-red-600 text-sm mt-1" role="alert"
                           x-text="fieldErrors.description[0]"></p>
                    </div>

                    {{-- Donation accordion --}}
                    <div x-data="{ open: false }">
                        <button type="button" @click="open = !open" class="donation-trigger">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center"
                                     style="background:rgba(245,197,24,0.12); border:1px solid rgba(245,197,24,0.30);">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                         stroke="#C9A200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M11 15h2m-2-4h2m-2-4h2M9 22h6a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2Z"/>
                                    </svg>
                                </div>
                                <span class="eyebrow" style="color:var(--blue-deep);">Donation Details</span>
                            </div>
                            <svg :style="open ? 'transform:rotate(180deg)' : ''"
                                 style="transition:transform 0.2s ease; color:rgba(13,42,82,0.38);"
                                 width="15" height="15" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak x-transition class="mt-3 space-y-4 px-1">
                            {{-- Toggle --}}
                            <div class="flex gap-2">
                                <button type="button"
                                        @click="formData.paymentMethod = 'GCash'"
                                        :class="formData.paymentMethod === 'GCash' ? 'active' : ''"
                                        class="pay-btn">GCash</button>
                                <button type="button"
                                        @click="formData.paymentMethod = 'Bank'"
                                        :class="formData.paymentMethod === 'Bank' ? 'active' : ''"
                                        class="pay-btn">Bank Transfer</button>
                            </div>

                            {{-- GCash --}}
                            <template x-if="formData.paymentMethod === 'GCash'">
                                <div class="pay-info-box">
                                    <p class="eyebrow mb-3" style="color:rgba(13,42,82,0.40);">Send to GCash</p>

                                    @if (!empty($global_settings['qr_code']))
                                        <div class="flex justify-center mb-4">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('supabase')->url($global_settings['qr_code']) }}"
                                                 alt="GCash QR Code"
                                                 style="width:180px; height:180px; object-fit:contain; border-radius:12px; border:1px solid rgba(26,64,128,0.10); background:#FFF;">
                                        </div>
                                    @endif

                                    <p class="font-cinzel font-bold"
                                        style="font-size:1.35rem; color:var(--blue-deep); letter-spacing:0.12em;">
                                        {{ $global_settings['gcash_number'] ?? '09123456789' }}
                                    </p>
                                    <p style="font-size:11px; color:rgba(13,42,82,0.35); margin-bottom:12px;">
                                        {{ $global_settings['gcash_name'] ?? '' }}
                                    </p>
                                    <button @click="copyGCash()" type="button"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-[10px] font-bold uppercase tracking-widest transition-all"
                                            style="border:1px solid rgba(245,197,24,0.40); color:#8a6800; background:rgba(245,197,24,0.08);"
                                            onmouseover="this.style.background='rgba(245,197,24,0.16)';"
                                            onmouseout="this.style.background='rgba(245,197,24,0.08)';">
                                        <span x-show="!gcashCopied">Copy Number</span>
                                        <span x-show="gcashCopied" x-cloak>Copied ✓</span>
                                    </button>
                                </div>
                            </template>

                            {{-- Bank --}}
                            <template x-if="formData.paymentMethod === 'Bank'">
                                <div class="pay-info-box">
                                    <p class="eyebrow mb-3" style="color:rgba(13,42,82,0.40);">Bank Details</p>
                                    <p style="font-size:13px; color:var(--blue-deep); font-weight:600; line-height:1.8;">
                                        {{ $global_settings['bank_details'] ?? 'Paroisse Saint-Joseph Artisan' }}
                                    </p>
                                </div>
                            </template>

                            <p style="font-size:10px; color:rgba(13,42,82,0.32); text-align:center; font-style:italic;">
                                * Donation is voluntary. Thank you for your support.
                            </p>
                        </div>
                    </div>

                    {{-- Consent --}}
                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="consent" x-model="formData.consent"
                               class="mt-0.5 h-4 w-4 shrink-0 rounded accent-[#0D2A52]">
                        <label for="consent" class="text-xs leading-relaxed" style="color:rgba(13,42,82,.62);">
                            I have read and agree to the
                            <a href="{{ route('privacy-policy') }}" target="_blank" rel="noopener" class="underline font-semibold" style="color:var(--blue-deep);">Privacy Policy</a>
                            and the
                            <a href="{{ route('terms') }}" target="_blank" rel="noopener" class="underline font-semibold" style="color:var(--blue-deep);">Terms &amp; Conditions</a>.
                        </label>
                    </div>
                    <p x-show="fieldErrors.consent" id="consent-error" class="text-red-600 text-sm mt-1" role="alert"
                       x-text="fieldErrors.consent ? (fieldErrors.consent[0] || '') : ''"></p>

                    {{-- Submit --}}
                    <button type="submit" :disabled="loading"
                            class="gold-btn w-full h-14 rounded-2xl relative overflow-hidden
                                   inline-flex items-center justify-center gap-2.5
                                   text-[11px] font-bold uppercase tracking-widest">
                        <span :class="loading ? 'opacity-0' : 'opacity-100'" class="transition-opacity flex items-center gap-2.5">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                            Submit Intention
                        </span>
                        <div x-show="loading" x-cloak class="absolute inset-0 flex items-center justify-center">
                            <svg class="animate-spin w-6 h-6" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </div>
                    </button>
                </form>
            </div>
        </div>

        {{-- Note --}}
        <div class="mt-6 rounded-2xl flex items-start gap-4 p-5"
             style="background:#FFFFFF; border:1px solid rgba(245,197,24,0.28); box-shadow:0 4px 20px rgba(13,42,82,0.05);">
            <div class="shrink-0 w-9 h-9 rounded-xl flex items-center justify-center"
                 style="background:rgba(245,197,24,0.10); border:1px solid rgba(245,197,24,0.28);">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#C9A200"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                </svg>
            </div>
            <div>
                <p class="eyebrow mb-1">Parish Note</p>
                <p style="font-size:12.5px; color:rgba(13,42,82,0.48); line-height:1.8;">
                    Our staff will review your submission. You will receive a confirmation via email once your intention is approved and scheduled.
                </p>
            </div>
        </div>
    </div>
</section>

<script>
function intentionForm() {
    return {
        loading: false,
        submitted: false,
        gcashCopied: false,
        refCopied: false,
        refId: '',
        dupWarning: false,
        dupRef: '',
        dupType: '',
        fieldErrors: {},
        intentionTypes: [
            'Thanksgiving','Birthday','Wedding Anniversary','Healing',
            'Repose of the Soul','Special Intention','Other'
        ],
        massTimes: ['6:00 AM','8:30 AM','10:00 AM','3:00 PM','4:30 PM','6:00 PM'],
        formData: {
            fullName: '', email: '', intentionType: '',
            preferredDate: '', massTime: '', description: '',
            paymentMethod: '', consent: false
        },

        init() {
            flatpickr(this.$refs.datePicker, {
                minDate: 'today',
                dateFormat: 'Y-m-d',
                disableMobile: true,
                onChange: (dates, dateStr) => {
                    this.formData.preferredDate = dateStr;
                    this.updateMassTimes(dates[0]);
                }
            });
        },

        updateMassTimes(date) {
            if (!date) return;
            const day = date.getDay();
            if (day === 0)      this.massTimes = ['6:30 AM','8:30 AM','10:00 AM','3:00 PM','4:30 PM','6:00 PM'];
            else if (day === 6) this.massTimes = ['6:30 AM','6:00 PM'];
            else                this.massTimes = ['6:00 PM'];

            if (this.massTimes.length === 1) this.formData.massTime = this.massTimes[0];
            else if (!this.massTimes.includes(this.formData.massTime)) this.formData.massTime = '';
        },

        async submitForm(force = false) {
            if (this.loading) return;
            this.loading = true;
            this.fieldErrors = {}; // clear
            if (!this.formData.consent) {
                this.fieldErrors = { consent: ['Please agree to the Privacy Policy and Terms & Conditions to continue.'] };
                this.loading = false;
                return;
            }
            try {
                const body = { ...this.formData };
                if (force) body.force_submit = true;

                const res = await fetch('{{ route('submit-intention') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(body)
                });
                const data = await res.json();

                if (data.duplicate_warning) {
                    this.dupWarning = true;
                    this.dupRef = data.duplicate_ref;
                    this.dupType = data.duplicate_type;
                    this.loading = false;
                    return;
                }

                if (res.status === 422) {
                    this.fieldErrors = data.errors || {};
                    this.loading = false;
                    return;
                }

                if (!res.ok) throw new Error('Submission failed');
                this.fieldErrors = {};
                this.refId = data.refId || '';
                this.submitted = true;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } catch {
                if (window.showToast) window.showToast('Submission failed. Please try again.', 'error');
            } finally {
                this.loading = false;
            }
        },

        forceSubmit() {
            this.dupWarning = false;
            this.submitForm(true);
        },

        copyGCash() {
            navigator.clipboard.writeText('{{ $global_settings['gcash_number'] ?? '09123456789' }}');
            this.gcashCopied = true;
            setTimeout(() => this.gcashCopied = false, 2000);
        },

        copyRefId() {
            navigator.clipboard.writeText(this.refId);
            this.refCopied = true;
            setTimeout(() => this.refCopied = false, 2000);
        },

        reset() {
            this.submitted = false;
            this.formData = {
                fullName:'', email:'', intentionType:'',
                preferredDate:'', massTime:'', description:'',
                paymentMethod:'', consent: false
            };
        }
    }
}
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver(
        (entries) => entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); observer.unobserve(e.target); } }),
        { threshold: 0.12, rootMargin: '0px 0px -50px 0px' }
    );
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
});
</script>

</x-public-layout>