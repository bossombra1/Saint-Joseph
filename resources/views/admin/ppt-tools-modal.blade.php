{{-- Slide Preview Modal (included inside pptAutomation x-data scope) --}}
<div x-show="showPreview"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-background/80 backdrop-blur-sm"
    style="display: none;" x-transition x-cloak>

    <div class="bg-card w-full max-w-5xl rounded-2xl border shadow-2xl flex flex-col h-[85vh] overflow-hidden">
        {{-- Modal Header --}}
        <div class="p-6 border-b flex items-center justify-between bg-muted/30">
            <div>
                <h3 class="text-xl font-black text-primary uppercase tracking-tighter italic font-heading">Draft Presentation</h3>
                <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mt-1" x-text="'Mass Date: ' + previewData.date"></p>
            </div>
            <div class="flex items-center gap-3">
                <span x-show="hasUnsavedChanges" class="text-[10px] font-black uppercase tracking-widest text-amber-600 animate-pulse">Unsaved edits</span>
                <button @click="showPreview = false" class="p-2 hover:bg-muted rounded-full transition-colors text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" /><path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Slide Viewer --}}
        <div class="flex-1 overflow-hidden relative group p-8 flex items-center justify-center bg-muted/10">
            <template x-for="(slide, index) in previewData.slides" :key="index">
                <div x-show="currentSlide === index"
                    class="w-[800px] h-[500px] border-2 border-black bg-white shadow-2xl relative flex flex-col overflow-hidden"
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 scale-95 translate-x-8"
                    x-transition:enter-end="opacity-100 scale-100 translate-x-0">

                    {{-- Intro Slide --}}
                    <template x-if="slide.type === 'intro'">
                        <div class="flex-1 flex flex-col items-center justify-center text-center p-12 space-y-4 transition-all"
                            :style="`transform: translate(${(slide.offsetX - 75)/2}px, ${(slide.offsetY - 100)/2}px)`">
                            <textarea x-model="slide.mainText" :disabled="!editMode" :class="!editMode && 'cursor-default'"
                                class="w-full text-xl font-bold text-black border-none focus:ring-0 bg-transparent text-center resize-none p-0"
                                rows="2"></textarea>
                            <textarea x-model="slide.boldText" :disabled="!editMode" :class="!editMode && 'cursor-default'"
                                class="w-full text-5xl font-black text-red-600 border-none focus:ring-0 bg-transparent text-center resize-none p-0 uppercase leading-tight"
                                rows="1"></textarea>
                            <textarea x-model="slide.footerText" :disabled="!editMode" :class="!editMode && 'cursor-default'"
                                class="w-full text-xl font-bold text-black border-none focus:ring-0 bg-transparent text-center resize-none p-0"
                                rows="2"></textarea>
                        </div>
                    </template>

                    {{-- List Slide --}}
                    <template x-if="slide.type === 'list'">
                        <div class="flex-1 flex flex-col">
                            <div class="h-12 bg-black flex items-center justify-center px-4">
                                <input type="text" x-model="slide.category" :disabled="!editMode" :class="!editMode && 'cursor-default'"
                                    class="bg-transparent border-none text-white text-center font-bold uppercase tracking-wider focus:ring-0 w-full text-sm">
                            </div>
                            <div class="flex-1 p-8 overflow-y-auto transition-all text-black"
                                :style="`padding-left: ${slide.offsetX}px; padding-top: ${slide.offsetY - 60}px`">
                                <div class="space-y-4">
                                    <template x-for="(item, iIndex) in slide.items" :key="iIndex">
                                        <div class="flex gap-3 items-start group/item">
                                            <span class="text-xl font-bold text-black pt-1" x-text="slide.isRepose ? '+' : '•'"></span>
                                            <div class="flex-1 space-y-1">
                                                <input type="text" x-model="item.name" :disabled="!editMode" :class="!editMode && 'cursor-default'"
                                                    class="w-full text-lg font-black text-blue-800 border-none focus:ring-0 bg-transparent p-0 uppercase placeholder:text-gray-200">
                                                <input type="text" x-model="item.description" :disabled="!editMode" :class="!editMode && 'cursor-default'"
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

            {{-- Nav Buttons --}}
            <div class="absolute inset-y-0 left-4 flex items-center">
                <button x-show="currentSlide > 0" @click="currentSlide--"
                    class="p-3 bg-white hover:bg-muted border rounded-xl shadow-lg transition-all text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                </button>
            </div>
            <div class="absolute inset-y-0 right-4 flex items-center">
                <button x-show="currentSlide < previewData.slides.length - 1" @click="currentSlide++"
                    class="p-3 bg-white hover:bg-muted border rounded-xl shadow-lg transition-all text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-6 border-t flex items-center justify-between bg-muted/20">
            <div class="flex items-center gap-6 text-primary">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black text-muted-foreground uppercase tracking-widest">Slide</span>
                    <span class="text-sm font-bold" x-text="(currentSlide + 1) + ' / ' + previewData.slides.length"></span>
                </div>
                <div x-show="editMode" class="flex items-center gap-4 animate-in fade-in slide-in-from-left-4 duration-300">
                    <div class="h-6 w-px bg-border"></div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5">
                            <kbd class="px-2 py-0.5 rounded border border-muted bg-white text-[10px] font-black shadow-sm">↑ ↓ ← →</kbd>
                            <span class="text-[10px] font-black text-muted-foreground uppercase">Move Text</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <kbd class="px-2 py-0.5 rounded border border-muted bg-white text-[10px] font-black shadow-sm">Shift</kbd>
                            <span class="text-[10px] font-black text-muted-foreground uppercase">Fast Move</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <button @click="showPreview = false"
                    class="px-6 py-2 rounded-xl font-bold text-sm bg-muted hover:bg-muted/80 border border-border transition-all text-primary">Cancel</button>

                <button @click="editMode = !editMode"
                    :class="editMode ? 'bg-primary text-primary-foreground' : 'bg-muted text-primary'"
                    class="px-6 py-2 rounded-xl font-bold text-sm border transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                    </svg>
                    <span x-text="editMode ? 'Finish Editing' : 'Edit Layout'"></span>
                </button>

                <button @click="createGoogleSlides()" :disabled="creatingSlides"
                    class="px-6 py-2 rounded-xl font-black text-sm shadow-md hover:scale-[1.02] transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed bg-amber-500 text-white">
                    <template x-if="!creatingSlides">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" x2="8" y1="13" y2="13" />
                            <line x1="16" x2="8" y1="17" y2="17" />
                            <line x1="10" x2="8" y1="9" y2="9" />
                        </svg>
                    </template>
                    <template x-if="creatingSlides">
                        <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                        </svg>
                    </template>
                    <span x-text="creatingSlides ? 'Creating Slides...' : 'Create in Google Slides'"></span>
                </button>
                <button @click="generateFinal()" :disabled="generatingPpt"
                    class="px-6 py-2 bg-accent text-accent-foreground rounded-xl font-black text-sm shadow-md hover:scale-[1.02] transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <template x-if="!generatingPpt">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" x2="12" y1="15" y2="3" />
                        </svg>
                    </template>
                    <template x-if="generatingPpt">
                        <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                        </svg>
                    </template>
                    <span x-text="generatingPpt ? 'Generating...' : 'Download Final PPT'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

@once
<script>
    function pptAutomation() {
        const STORAGE_KEY = 'ppt_draft_slides';
        return {
            showPreview: false,
            editMode: false,
            currentSlide: 0,
            previewData: { date: '', slides: [] },
            hasUnsavedChanges: false,
            generatingPpt: false,
            creatingSlides: false,

            init() {
                this.restoreDraft();
                this.$watch('previewData', () => {
                    this.hasUnsavedChanges = true;
                    this.autoSaveDraft();
                }, { deep: true });
            },

            autoSaveDraft() {
                try {
                    const payload = JSON.stringify(this.previewData);
                    localStorage.setItem(STORAGE_KEY, payload);
                } catch (e) { /* quota exceeded, ignore */ }
            },

            restoreDraft() {
                try {
                    const raw = localStorage.getItem(STORAGE_KEY);
                    if (!raw) return;
                    const saved = JSON.parse(raw);
                    if (saved && saved.slides && saved.slides.length > 0) {
                        this.previewData = saved;
                        this.currentSlide = 0;
                        // Restore the saved draft silently; the user opens it with Preview Content.
                        this.hasUnsavedChanges = false;
                    }
                } catch (e) { /* corrupted data, ignore */ }
            },

            clearDraft() {
                localStorage.removeItem(STORAGE_KEY);
                this.hasUnsavedChanges = false;
            },

            async fetchPreview() {
                try {
                    const response = await fetch('{{ route('admin.preview-ppt') }}', {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        throw new Error(data.message || 'Impossible de charger l’aperçu.');
                    }

                    if (!Array.isArray(data.slides) || data.slides.length === 0) {
                        this.$store.toast.trigger(
                            'Aucune intention approuvée trouvée pour ' + (data.date || 'la date demandée') + '.',
                            'error'
                        );
                        return;
                    }

                    this.previewData = data;
                    this.editMode = false;
                    this.currentSlide = 0;
                    this.showPreview = true;
                    this.hasUnsavedChanges = false;
                    localStorage.removeItem(STORAGE_KEY);
                } catch (error) {
                    this.$store.toast.trigger(error.message || 'Erreur lors du chargement de l’aperçu.', 'error');
                }
            },

            async generateFinal() {
                if (this.generatingPpt) return;
                if (!Array.isArray(this.previewData.slides) || this.previewData.slides.length === 0) {
                    this.$store.toast.trigger('Chargez d’abord un aperçu contenant des intentions approuvées.', 'error');
                    return;
                }

                this.generatingPpt = true;
                try {
                    const response = await fetch('{{ route('admin.generate-ppt') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ slides: this.previewData.slides })
                    });

                    if (!response.ok) {
                        const data = await response.json().catch(() => ({}));
                        throw new Error(data.message || 'La génération du PowerPoint a échoué.');
                    }

                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `Mass_Intentions_${new Date().toISOString().split('T')[0]}.pptx`;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                    this.clearDraft();
                    this.$store.toast.trigger('PowerPoint généré avec succès.', 'success');
                } catch (error) {
                    this.$store.toast.trigger(error.message || 'Erreur lors de la génération du PowerPoint.', 'error');
                } finally {
                    this.generatingPpt = false;
                }
            },

            handleKeyDown(e) {
                if (!this.editMode || !this.showPreview) return;

                const slide = this.previewData.slides[this.currentSlide];
                if (!slide) return;

                const step = e.shiftKey ? 10 : 2;

                if (e.key === 'ArrowUp') { slide.offsetY -= step; e.preventDefault(); }
                else if (e.key === 'ArrowDown') { slide.offsetY += step; e.preventDefault(); }
                else if (e.key === 'ArrowLeft') { slide.offsetX -= step; e.preventDefault(); }
                else if (e.key === 'ArrowRight') { slide.offsetX += step; e.preventDefault(); }
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
            stopDrag() { this.dragging = false; },

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
                        this.$store.toast.trigger(data.message || 'Failed to create slides', 'error');
                        return;
                    }

                    if (data.success && data.url) {
                        this.clearDraft();
                        this.$store.toast.trigger('Google Slide has been successfully created/updated!', 'success');
                        window.open(data.url, '_blank');
                    }
                } catch (error) {
                    this.$store.toast.trigger('Error: ' + error.message, 'error');
                } finally {
                    this.creatingSlides = false;
                }
            }
        }
    }
</script>
@endonce
