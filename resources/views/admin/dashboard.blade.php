<x-admin-layout>
    <script>
        function pptAutomation() {
            return {
                showPreview: false,
                editMode: false,
                currentSlide: 0,
                previewData: { date: '', slides: [] },
                
                async fetchPreview() {
                    try {
                        const response = await fetch('{{ route('admin.preview-ppt') }}');
                        if (!response.ok) throw new Error('Failed to fetch');
                        const data = await response.json();
                        
                        if (!data.slides || data.slides.length === 0) {
                            this.showNotification('No approved intentions found for ' + data.date + '. Please ensure you have approved pending intentions first.', 'error');
                            return;
                        }

                        this.previewData = data;
                        this.editMode = false;
                        this.currentSlide = 0;
                        this.showPreview = true;
                    } catch (error) {
                        this.showNotification('Error loading preview. Please ensure there are approved intentions.', 'error');
                    }
                },
                async generateFinal() {
                    try {
                        const response = await fetch('{{ route('admin.generate-ppt') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ slides: this.previewData.slides })
                        });

                        if (!response.ok) throw new Error('Generation failed');

                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `Mass_Intentions_${new Date().toISOString().split('T')[0]}.pptx`;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        this.showNotification('PowerPoint presentation generated successfully!', 'success');
                    } catch (error) {
                        this.showNotification('Error generating PPT. Please try again.', 'error');
                    }
                },
                handleKeyDown(e) {
                    if (!this.editMode || !this.showPreview) return;

                    const slide = this.previewData.slides[this.currentSlide];
                    if (!slide) return;

                    const step = e.shiftKey ? 10 : 2;

                    if (e.key === 'ArrowUp') {
                        slide.offsetY -= step;
                        e.preventDefault();
                    } else if (e.key === 'ArrowDown') {
                        slide.offsetY += step;
                        e.preventDefault();
                    } else if (e.key === 'ArrowLeft') {
                        slide.offsetX -= step;
                        e.preventDefault();
                    } else if (e.key === 'ArrowRight') {
                        slide.offsetX += step;
                        e.preventDefault();
                    }
                },
                dragging: false,
                startX: 0,
                startY: 0,
                initialOffsetX: 0,
                initialOffsetY: 0,
                startDrag(e) {
                    if (!this.editMode) return;
                    this.dragging = true;
                    this.startX = e.clientX;
                    this.startY = e.clientY;
                    this.initialOffsetX = this.previewData.slides[this.currentSlide].offsetX;
                    this.initialOffsetY = this.previewData.slides[this.currentSlide].offsetY;
                },
                onDrag(e) {
                    if (!this.dragging) return;
                    const dx = e.clientX - this.startX;
                    const dy = e.clientY - this.startY;
                    this.previewData.slides[this.currentSlide].offsetX = this.initialOffsetX + (dx * 2);
                    this.previewData.slides[this.currentSlide].offsetY = this.initialOffsetY + (dy * 2);
                },
                stopDrag() {
                    this.dragging = false;
                },
                creatingSlides: false,
                async createGoogleSlides() {
                    if (this.creatingSlides) return;
                    this.creatingSlides = true;

                    try {
                        const response = await fetch('{{ route('admin.create-google-slides') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                slides: this.previewData.slides,
                                date: this.previewData.date
                            })
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            this.showNotification(data.message || 'Failed to create slides', 'error');
                            return;
                        }

                        if (data.success && data.url) {
                            this.showNotification('Google Slide has been successfully created/updated!', 'success');
                            window.open(data.url, '_blank');
                        }
                    } catch (error) {
                        this.showNotification('Error: ' + error.message, 'error');
                    } finally {
                        this.creatingSlides = false;
                    }
                }
            }
        }
    </script>
    <div x-data="pptAutomation()" @keydown.window="handleKeyDown($event)" @mousemove.window="onDrag($event)"
        @mouseup.window="stopDrag()">
        {{-- Page Header --}}
        <div class="mb-10">
            <div class="flex items-center gap-2 mb-1">
                <p class="text-[10px] font-black uppercase tracking-[.2em] text-primary/30">Paroisse Saint-Joseph Artisan</p>
            </div>
            <h1 class="font-heading text-3xl font-bold text-primary italic">Dashboard</h1>
            <p class="text-sm text-muted-foreground mt-1">Overview of parish activities and statistics.</p>
        </div>

        @php $role = Auth::user()->role; @endphp

        {{-- Live Mass Control --}}
        @if(in_array($role, ['super_admin', 'staff', 'soccom'], true))
            @php
                $liveIsActive = \Illuminate\Support\Facades\Cache::get('manual_live_override', false) === true;
                $savedLiveUrl = \Illuminate\Support\Facades\Cache::get('live_mass_url', '');
                $savedLivePlatform = \Illuminate\Support\Facades\Cache::get('live_mass_platform', 'youtube');
            @endphp
            <section class="bg-white rounded-2xl border border-black/[.06] shadow-sm p-6 lg:p-8 mb-10">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="h-3 w-3 rounded-full {{ $liveIsActive ? 'bg-red-600 animate-pulse' : 'bg-gray-300' }}"></span>
                            <h2 class="text-lg font-bold text-primary">Contrôle de la messe en direct</h2>
                        </div>
                        <p class="text-sm text-muted-foreground">{{ $liveIsActive ? 'Le direct est signalé comme actif sur le site public.' : 'Inactif. Enregistrez le lien, puis cliquez sur « Diffuser » au début de la messe.' }}</p>
                        @if(session('success'))
                            <p class="mt-3 text-sm font-semibold text-green-700">{{ session('success') }}</p>
                        @endif
                        @if($errors->has('live_url'))
                            <p class="mt-3 text-sm font-semibold text-red-700">{{ $errors->first('live_url') }}</p>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('admin.live-mass.toggle') }}">
                        @csrf
                        <input type="hidden" name="state" value="{{ $liveIsActive ? 'off' : 'on' }}">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 font-bold text-white shadow-lg transition hover:brightness-110 {{ $liveIsActive ? 'bg-gray-700' : 'bg-red-600' }}">
                            <span class="h-2 w-2 rounded-full bg-white {{ $liveIsActive ? '' : 'animate-pulse' }}"></span>
                            {{ $liveIsActive ? 'Arrêter le direct' : '🔴 Diffuser' }}
                        </button>
                    </form>
                </div>
                <form method="POST" action="{{ route('admin.live-mass.link') }}" class="mt-6 grid gap-4 md:grid-cols-[180px_minmax(0,1fr)_auto] items-end">
                    @csrf
                    <label class="block text-sm font-semibold text-primary">
                        Plateforme
                        <select name="platform" required class="mt-2 w-full rounded-xl border-gray-200">
                            <option value="youtube" {{ $savedLivePlatform === 'youtube' ? 'selected' : '' }}>YouTube Live</option>
                            <option value="facebook" {{ $savedLivePlatform === 'facebook' ? 'selected' : '' }}>Facebook Live</option>
                        </select>
                    </label>
                    <label class="block text-sm font-semibold text-primary">
                        Lien de la diffusion
                        <input name="live_url" type="url" required value="{{ old('live_url', $savedLiveUrl) }}" placeholder="https://www.youtube.com/watch?v=..." class="mt-2 w-full rounded-xl border-gray-200" />
                    </label>
                    <button type="submit" class="rounded-xl border border-primary/10 bg-[#F5F7FA] px-5 py-3 font-bold text-primary hover:bg-gray-100">Enregistrer le lien</button>
                </form>
                <p class="mt-3 text-xs text-muted-foreground">Le bouton LIVE et la notification du site public se mettent à jour automatiquement. Le direct vidéo doit être lancé sur YouTube ou Facebook séparément.</p>
            </section>
        @endif

        {{-- Stats Grid --}}
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-10">
            @if($role === 'super_admin' || $role === 'staff')
                <div class="bg-white rounded-2xl border border-black/[.04] p-6 relative overflow-hidden group hover:shadow-md hover:shadow-black/[.04] transition-all duration-300">
                    <div class="stat-shine"></div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black text-muted-foreground/50 uppercase tracking-[.15em]">Total Intentions</span>
                        <div class="w-9 h-9 rounded-xl bg-primary/5 flex items-center justify-center text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.505 4.04 3 5.5L12 21l7-7Z"/></svg>
                        </div>
                    </div>
                    <p class="text-[2.5rem] font-black text-primary tracking-tighter leading-none">{{ $stats['total_intentions'] ?? 0 }}</p>
                    <div class="mt-3 pt-3 border-t border-black/[.03]">
                        <div class="h-1.5 rounded-full bg-primary/5 overflow-hidden"><div class="h-full rounded-full bg-gradient-to-r from-primary to-primary/60" style="width: min({{ ($stats['total_intentions'] ?? 0) * 2 }}%, 100%)"></div></div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-black/[.04] p-6 relative overflow-hidden group hover:shadow-md hover:shadow-black/[.04] transition-all duration-300">
                    <div class="stat-shine"></div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black text-muted-foreground/50 uppercase tracking-[.15em]">Pending Intentions</span>
                        <div class="w-9 h-9 rounded-xl bg-accent/10 flex items-center justify-center text-accent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                    </div>
                    <p class="text-[2.5rem] font-black text-accent tracking-tighter leading-none">{{ $stats['pending_intentions'] ?? 0 }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-black/[.04] p-6 relative overflow-hidden group hover:shadow-md hover:shadow-black/[.04] transition-all duration-300">
                    <div class="stat-shine"></div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black text-muted-foreground/50 uppercase tracking-[.15em]">Inquiry Requests</span>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                        </div>
                    </div>
                    <p class="text-[2.5rem] font-black text-blue-500 tracking-tighter leading-none">{{ $stats['pending_inquiries'] ?? 0 }}</p>
                </div>
            @endif

            @if($role === 'super_admin' || $role === 'soccom')
                <div class="bg-white rounded-2xl border border-black/[.04] p-6 relative overflow-hidden group hover:shadow-md hover:shadow-black/[.04] transition-all duration-300">
                    <div class="stat-shine"></div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black text-muted-foreground/50 uppercase tracking-[.15em]">Active Schedules</span>
                        <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center text-purple-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        </div>
                    </div>
                    <p class="text-[2.5rem] font-black text-purple-500 tracking-tighter leading-none">{{ $stats['active_schedules'] ?? 0 }}</p>
                </div>
            @endif

            @if($role === 'super_admin' || $role === 'staff')
                <div class="bg-white rounded-2xl border border-black/[.04] p-6 relative overflow-hidden group hover:shadow-md hover:shadow-black/[.04] transition-all duration-300">
                    <div class="stat-shine"></div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black text-muted-foreground/50 uppercase tracking-[.15em]">Total Donations</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                    </div>
                    <p class="text-[2.5rem] font-black text-emerald-500 tracking-tighter leading-none">₱{{ number_format(($stats['total_donations_amount'] ?? 0) / 100, 2) }}</p>
                    <p class="text-[10px] font-bold text-muted-foreground/50 mt-2">{{ $stats['total_donation_count'] ?? 0 }} completed</p>
                </div>
            @endif
        </div>

        {{-- PPT Automation --}}
        <div class="bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] p-6 lg:p-8 mb-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary/10 to-primary/5 flex items-center justify-center text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                    </div>
                    <div>
                        <h3 class="font-heading text-lg font-bold text-primary italic">Mass Presentation Automation</h3>
                        <p class="text-sm text-muted-foreground">Generate PowerPoint or Google Slides for upcoming mass intentions.</p>
                    </div>
                </div>

                <div class="flex gap-3 shrink-0">
                    <button @click="fetchPreview()"
                        class="inline-flex items-center gap-2 bg-[#F5F7FA] border border-black/[.06] px-5 py-2.5 rounded-xl font-bold text-sm text-primary/70 hover:bg-black/[.04] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0z" /><circle cx="12" cy="12" r="3" />
                        </svg>
                        Quick Preview
                    </button>
                    <button @click="generateFinal()"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-accent to-gold-dark text-primary px-5 py-2.5 rounded-xl font-black uppercase tracking-[.1em] text-[10px] shadow-lg shadow-accent/20 hover:shadow-xl hover:shadow-accent/30 hover:scale-[1.02] active:scale-[0.97] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M2 3h20" /><path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3" /><path d="m7 21 5-5 5 5" /></svg>
                        Generate PPT
                    </button>
                </div>
            </div>

            {{-- Slide Preview Modal --}}
            <div x-show="showPreview"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                style="display: none;" x-transition x-cloak>
                <div class="bg-white w-full max-w-5xl rounded-2xl border border-black/[.06] shadow-2xl flex flex-col h-[85vh] overflow-hidden">
                    <div class="px-6 py-5 border-b border-black/[.04] flex items-center justify-between bg-gradient-to-r from-[#F5F7FA] to-white">
                        <div>
                            <h3 class="text-lg font-bold text-primary font-heading italic">Draft Presentation</h3>
                            <p class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50 mt-0.5" x-text="'Mass Date: ' + previewData.date"></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button @click="showPreview = false" class="p-2 hover:bg-black/[.04] rounded-xl transition-colors text-muted-foreground">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18" /><path d="m6 6 12 12" /></svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 overflow-hidden relative group p-8 flex items-center justify-center bg-[#F5F7FA]">
                        <template x-for="(slide, index) in previewData.slides" :key="index">
                            <div x-show="currentSlide === index"
                                class="w-[800px] h-[500px] border-2 border-black bg-white shadow-2xl relative flex flex-col overflow-hidden"
                                x-transition:enter="transition ease-out duration-300 transform"
                                x-transition:enter-start="opacity-0 scale-95 translate-x-8"
                                x-transition:enter-end="opacity-100 scale-100 translate-x-0">

                                <template x-if="slide.type === 'intro'">
                                    <div class="flex-1 flex flex-col items-center justify-center text-center p-12 space-y-4 transition-all"
                                        :style="`transform: translate(${(slide.offsetX - 75)/2}px, ${(slide.offsetY - 100)/2}px)`">
                                        <textarea x-model="slide.mainText" :disabled="!editMode"
                                            :class="!editMode && 'cursor-default'"
                                            class="w-full text-xl font-bold text-black border-none focus:ring-0 bg-transparent text-center resize-none p-0"
                                            rows="2"></textarea>
                                        <textarea x-model="slide.boldText" :disabled="!editMode"
                                            :class="!editMode && 'cursor-default'"
                                            class="w-full text-5xl font-black text-red-600 border-none focus:ring-0 bg-transparent text-center resize-none p-0 uppercase leading-tight"
                                            rows="1"></textarea>
                                        <textarea x-model="slide.footerText" :disabled="!editMode"
                                            :class="!editMode && 'cursor-default'"
                                            class="w-full text-xl font-bold text-black border-none focus:ring-0 bg-transparent text-center resize-none p-0"
                                            rows="2"></textarea>
                                    </div>
                                </template>

                                <template x-if="slide.type === 'list'">
                                    <div class="flex-1 flex flex-col">
                                        <div class="h-12 bg-black flex items-center justify-center px-4">
                                            <input type="text" x-model="slide.category" :disabled="!editMode"
                                                :class="!editMode && 'cursor-default'"
                                                class="bg-transparent border-none text-white text-center font-bold uppercase tracking-wider focus:ring-0 w-full text-sm">
                                        </div>
                                        <div class="flex-1 p-8 overflow-y-auto transition-all text-black"
                                            :style="`padding-left: ${slide.offsetX}px; padding-top: ${slide.offsetY - 60}px`">
                                            <div class="space-y-4">
                                                <template x-for="(item, iIndex) in slide.items" :key="iIndex">
                                                    <div class="flex gap-3 items-start">
                                                        <span class="text-xl font-bold text-black pt-1" x-text="slide.isRepose ? '+' : '•'"></span>
                                                        <div class="flex-1 space-y-1">
                                                            <input type="text" x-model="item.name" :disabled="!editMode"
                                                                :class="!editMode && 'cursor-default'"
                                                                class="w-full text-lg font-black text-blue-800 border-none focus:ring-0 bg-transparent p-0 uppercase placeholder:text-gray-200">
                                                            <input type="text" x-model="item.description" :disabled="!editMode"
                                                                :class="!editMode && 'cursor-default'"
                                                                class="w-full text-sm font-medium text-gray-500 border-none focus:ring-0 bg-transparent p-0 italic placeholder:text-gray-200">
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <div class="absolute inset-y-0 left-4 flex items-center">
                            <button x-show="currentSlide > 0" @click="currentSlide--"
                                class="p-3 bg-white hover:bg-[#F5F7FA] border border-black/[.06] rounded-xl shadow-lg transition-all text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6" /></svg>
                            </button>
                        </div>
                        <div class="absolute inset-y-0 right-4 flex items-center">
                            <button x-show="currentSlide < previewData.slides.length - 1" @click="currentSlide++"
                                class="p-3 bg-white hover:bg-[#F5F7FA] border border-black/[.06] rounded-xl shadow-lg transition-all text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6" /></svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-black/[.04] flex items-center justify-between bg-[#FAFBFC]">
                        <div class="flex items-center gap-6 text-primary">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black text-muted-foreground/50 uppercase tracking-[.15em]">Slide</span>
                                <span class="text-sm font-bold" x-text="(currentSlide + 1) + ' / ' + previewData.slides.length"></span>
                            </div>
                            <div x-show="editMode" class="flex items-center gap-4">
                                <div class="h-4 w-px bg-black/[.08]"></div>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1.5">
                                        <kbd class="px-2 py-0.5 rounded-lg border border-black/[.08] bg-white text-[10px] font-bold shadow-sm">↑ ↓ ← →</kbd>
                                        <span class="text-[10px] font-bold text-muted-foreground/50 uppercase tracking-widest">Move</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <kbd class="px-2 py-0.5 rounded-lg border border-black/[.08] bg-white text-[10px] font-bold shadow-sm">Shift</kbd>
                                        <span class="text-[10px] font-bold text-muted-foreground/50 uppercase tracking-widest">Fast</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2.5">
                            <button @click="showPreview = false" class="px-5 py-2 rounded-xl font-bold text-sm bg-[#F5F7FA] hover:bg-black/[.04] border border-black/[.06] transition-all text-muted-foreground">Cancel</button>
                            <button @click="editMode = !editMode"
                                :class="editMode ? 'bg-primary text-primary-foreground' : 'bg-[#F5F7FA] text-primary border border-black/[.06]'"
                                class="px-5 py-2 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" /></svg>
                                <span x-text="editMode ? 'Finish Editing' : 'Edit Layout'"></span>
                            </button>
                            <button @click="createGoogleSlides()" :disabled="creatingSlides"
                                class="px-5 py-2 rounded-xl font-black text-xs uppercase tracking-[.1em] shadow-lg hover:scale-[1.02] transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed bg-amber-500 text-white shadow-amber-500/20">
                                <template x-if="!creatingSlides">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><polyline points="14 2 14 8 20 8" /></svg>
                                </template>
                                <template x-if="creatingSlides">
                                    <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 1 1-6.219-8.56" /></svg>
                                </template>
                                <span x-text="creatingSlides ? 'Creating...' : 'Google Slides'"></span>
                            </button>
                            <button @click="generateFinal()"
                                class="px-5 py-2 bg-gradient-to-r from-accent to-gold-dark text-primary rounded-xl font-black text-xs uppercase tracking-[.1em] shadow-lg shadow-accent/20 hover:shadow-xl transition-all flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" /><polyline points="7 10 12 15 17 10" /><line x1="12" x2="12" y1="15" y2="3" /></svg>
                                Download
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid gap-5 md:grid-cols-2">
            <div class="bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-[.15em]">Intentions Trend</h3>
                    <span class="text-[10px] font-bold text-muted-foreground/50 uppercase tracking-wider bg-[#F5F7FA] px-2.5 py-1 rounded-lg">Last 8 Weeks</span>
                </div>
                <div class="h-[240px]">
                    <canvas id="intentionsChart"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-[.15em]">Inquiry Distribution</h3>
                    <span class="text-[10px] font-bold text-muted-foreground/50 uppercase tracking-wider bg-[#F5F7FA] px-2.5 py-1 rounded-lg">By Category</span>
                </div>
                <div class="h-[240px]">
                    <canvas id="inquiriesChart"></canvas>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const intentionsCtx = document.getElementById('intentionsChart').getContext('2d');
                new Chart(intentionsCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($intentionsTrend->map(fn($t) => 'W' . substr($t->week, 4))) !!},
                        datasets: [{
                            label: 'Intentions',
                            data: {!! json_encode($intentionsTrend->pluck('total')) !!},
                            borderColor: '#0D2A52',
                            backgroundColor: 'rgba(13,42,82,0.03)',
                            borderWidth: 2.5,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#0D2A52',
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10, family: 'system-ui' } }, grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false } },
                            x: { grid: { display: false }, ticks: { font: { size: 10, family: 'system-ui' } } }
                        }
                    }
                });

                const inquiriesCtx = document.getElementById('inquiriesChart').getContext('2d');
                new Chart(inquiriesCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($inquiryTypes->pluck('type')) !!},
                        datasets: [{
                            label: 'Inquiries',
                            data: {!! json_encode($inquiryTypes->pluck('total')) !!},
                            backgroundColor: [
                                'rgba(13,42,82,0.8)', 'rgba(245,197,24,0.8)', 'rgba(16,185,129,0.8)', 'rgba(139,92,246,0.8)', 'rgba(239,68,68,0.8)'
                            ],
                            borderRadius: 8,
                            barThickness: 24
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10, family: 'system-ui' } }, grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false } },
                            x: { grid: { display: false }, ticks: { font: { size: 10, family: 'system-ui' } } }
                        }
                    }
                });
            });
        </script>

        {{-- Quick Actions --}}
        <div class="mt-10">
            <h2 class="text-xs font-bold text-primary uppercase tracking-[.15em] mb-5">Quick Actions</h2>
            <div class="grid gap-4 md:grid-cols-3">
                @if($role === 'super_admin' || $role === 'staff')
                    <a href="{{ route('admin.intentions') }}"
                        class="bg-white border border-black/[.04] rounded-2xl p-5 hover:shadow-md hover:shadow-black/[.04] hover:border-accent/30 transition-all duration-300 flex items-center gap-4 group">
                        <div class="w-11 h-11 rounded-xl bg-accent/10 text-accent flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.505 4.04 3 5.5L12 21l7-7Z" /></svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-primary">Review Intentions</div>
                            <div class="text-[10px] text-muted-foreground/50 font-medium">Approve or reject submissions</div>
                        </div>
                    </a>
                    <a href="{{ route('admin.donations') }}"
                        class="bg-white border border-black/[.04] rounded-2xl p-5 hover:shadow-md hover:shadow-black/[.04] hover:border-emerald-300/40 transition-all duration-300 flex items-center gap-4 group">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-primary">View Donations</div>
                            <div class="text-[10px] text-muted-foreground/50 font-medium">Track parish contributions</div>
                        </div>
                    </a>
                @endif

                @if($role === 'super_admin' || $role === 'soccom')
                    <a href="{{ route('admin.schedules.index') }}"
                        class="bg-white border border-black/[.04] rounded-2xl p-5 hover:shadow-md hover:shadow-black/[.04] hover:border-purple-300/40 transition-all duration-300 flex items-center gap-4 group">
                        <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="18" height="18" x="3" y="4" rx="2" ry="2" /><line x1="16" x2="16" y1="2" y2="6" /><line x1="8" x2="8" y1="2" y2="6" /><line x1="3" x2="21" y1="10" y2="10" /></svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-primary">Manage Schedules</div>
                            <div class="text-[10px] text-muted-foreground/50 font-medium">Mass service times</div>
                        </div>
                    </a>
                    <a href="{{ route('admin.gallery.index') }}"
                        class="bg-white border border-black/[.04] rounded-2xl p-5 hover:shadow-md hover:shadow-black/[.04] hover:border-blue-300/40 transition-all duration-300 flex items-center gap-4 group">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2" ry="2" /><circle cx="9" cy="9" r="2" /><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" /></svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-primary">Parish Gallery</div>
                            <div class="text-[10px] text-muted-foreground/50 font-medium">Photo albums & media</div>
                        </div>
                    </a>
                    <a href="{{ route('admin.events.index') }}"
                        class="bg-white border border-black/[.04] rounded-2xl p-5 hover:shadow-md hover:shadow-black/[.04] hover:border-amber-300/40 transition-all duration-300 flex items-center gap-4 group">
                        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.937A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .962 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.582a.5.5 0 0 1 0 .962L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.962 0z" /><path d="M20 3v4" /><path d="M22 5h-4" /><path d="M4 17v2" /><path d="M5 18H3" /></svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-primary">Events Manager</div>
                            <div class="text-[10px] text-muted-foreground/50 font-medium">Community celebrations</div>
                        </div>
                    </a>
                @endif

                <a href="{{ route('home') }}" target="_blank"
                    class="bg-white border border-black/[.04] rounded-2xl p-5 hover:shadow-md hover:shadow-black/[.04] hover:border-primary/20 transition-all duration-300 flex items-center gap-4 group">
                    <div class="w-11 h-11 rounded-xl bg-[#F5F7FA] text-muted-foreground/40 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15 3h6v6" /><path d="M10 14 21 3" /><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" /></svg>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-primary">View Website</div>
                        <div class="text-[10px] text-muted-foreground/50 font-medium">Opens in new tab</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
