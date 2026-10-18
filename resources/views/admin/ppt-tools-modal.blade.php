{{-- Aperçu et édition du document Word --}}
<div x-show="showPreview" x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-background/80 backdrop-blur-sm"
     style="display: none;" x-transition>
    <div @click.away="showPreview = false"
         class="bg-card w-full max-w-4xl max-h-[90vh] rounded-2xl border shadow-2xl flex flex-col overflow-hidden">
        <div class="p-5 sm:p-6 border-b flex items-center justify-between gap-4 bg-muted/20">
            <div>
                <h2 class="text-xl font-bold text-primary">Aperçu du document Word</h2>
                <p class="text-sm text-muted-foreground mt-1">Date : <span x-text="previewData.date"></span></p>
            </div>
            <button type="button" @click="showPreview = false"
                    class="p-2 rounded-lg hover:bg-muted text-muted-foreground" aria-label="Fermer l’aperçu">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 6-12 12M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-5 sm:p-8 overflow-y-auto space-y-6">
            <div class="text-center">
                <p class="text-xs font-bold tracking-widest text-muted-foreground">SAINT-JOSEPH ARTISAN</p>
                <h3 class="text-2xl font-bold text-primary mt-2">INTENTIONS DE MESSE</h3>
                <p class="text-sm mt-2">Date : <span x-text="previewData.date"></span></p>
            </div>

            <template x-if="!previewData.sections.length">
                <p class="text-center text-muted-foreground py-8">Aucune intention approuvée pour cette date.</p>
            </template>

            <template x-for="(section, sectionIndex) in previewData.sections" :key="sectionIndex">
                <section class="border rounded-xl p-4 sm:p-5 space-y-3">
                    <input type="text" x-model="section.category"
                           class="w-full font-bold text-primary bg-transparent border-0 border-b border-border focus:ring-0 px-0 pb-2">
                    <textarea x-model="section.introduction" rows="2"
                              placeholder="Introduction (facultative)"
                              class="w-full text-sm rounded-lg border border-border bg-background px-3 py-2 focus:ring-2 focus:ring-primary"></textarea>

                    <template x-for="(item, itemIndex) in section.items" :key="itemIndex">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 border-t pt-3">
                            <input type="text" x-model="item.name" aria-label="Nom ou intention"
                                   class="sm:col-span-1 text-sm rounded-lg border border-border bg-background px-3 py-2">
                            <input type="text" x-model="item.type" aria-label="Type d’intention"
                                   class="text-sm rounded-lg border border-border bg-background px-3 py-2">
                            <input type="text" x-model="item.time" aria-label="Heure"
                                   placeholder="Heure (facultative)"
                                   class="text-sm rounded-lg border border-border bg-background px-3 py-2">
                        </div>
                    </template>
                </section>
            </template>
        </div>

        <div class="p-4 sm:p-5 border-t bg-muted/20 flex flex-wrap items-center justify-end gap-3">
            <button type="button" @click="showPreview = false"
                    class="px-4 py-2 rounded-lg border border-border text-sm font-bold hover:bg-muted">
                Fermer
            </button>
            <button type="button" @click="downloadWord()" :disabled="downloadingWord"
                    class="px-5 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-bold disabled:opacity-50">
                <span x-text="downloadingWord ? 'Préparation…' : 'Télécharger le document Word'"></span>
            </button>
        </div>
    </div>
</div>

@once
<script>
    function pptAutomation() {
        return {
            showPreview: false,
            downloadingWord: false,
            previewData: { date: '', sections: [] },

            async fetchPreview() {
                try {
                    const response = await fetch('{{ route('admin.preview-ppt') }}', {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        throw new Error(data.message || 'Impossible de charger l’aperçu.');
                    }
                    this.previewData = {
                        date: data.date || '',
                        sections: Array.isArray(data.sections) ? data.sections : []
                    };
                    this.showPreview = true;
                    if (!this.previewData.sections.length) {
                        this.$store.toast.trigger('Aucune intention approuvée disponible.', 'error');
                    }
                } catch (error) {
                    this.$store.toast.trigger(error.message || 'Erreur lors du chargement de l’aperçu.', 'error');
                }
            },

            async downloadWord() {
                if (this.downloadingWord) return;
                this.downloadingWord = true;
                try {
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                    const response = await fetch('{{ route('admin.generate-ppt') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/json',
                            'Content-Type': 'application/json',
                            ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
                        },
                        body: JSON.stringify({
                            date: this.previewData.date || null,
                            sections: this.previewData.sections
                        })
                    });

                    const contentType = response.headers.get('content-type') || '';
                    if (!response.ok || contentType.includes('application/json')) {
                        const data = await response.json().catch(() => ({}));
                        throw new Error(data.message || 'La génération du document Word a échoué.');
                    }

                    const blob = await response.blob();
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = 'Intentions_de_messe_' + new Date().toISOString().slice(0, 10) + '.docx';
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                    URL.revokeObjectURL(url);
                    this.$store.toast.trigger('Document Word téléchargé.', 'success');
                } catch (error) {
                    this.$store.toast.trigger(error.message || 'Erreur lors du téléchargement Word.', 'error');
                } finally {
                    this.downloadingWord = false;
                }
            }
        };
    }
</script>
@endonce
