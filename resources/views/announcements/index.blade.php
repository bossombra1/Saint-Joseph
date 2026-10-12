<x-public-layout>
    @php
        $isFrench = app()->getLocale() === 'fr';
        $categoryLabels = $isFrench ? [
            'Parish Life' => 'Vie paroissiale',
            'Liturgical' => 'Liturgie',
            'Sacraments' => 'Sacrements',
            'Formation' => 'Formation',
            'Recruitment' => 'Recrutement',
        ] : [];
    @endphp
    <x-slot name="meta">
        <meta name="description" content="{{ $isFrench ? 'Suivez les dernières annonces et actualités de la paroisse Saint-Joseph Artisan.' : 'Stay updated with the latest announcements and parish news from Paroisse Saint-Joseph Artisan.' }}">
        <meta property="og:title" content="{{ $isFrench ? 'Annonces paroissiales | Paroisse Saint-Joseph Artisan' : 'Parish Announcements | Paroisse Saint-Joseph Artisan' }}">
        <meta property="og:description" content="{{ $isFrench ? 'Actualités, neuvaines, nouvelles de la communauté et événements de la paroisse Saint-Joseph Artisan.' : 'Latest news, novenas, community updates, and event announcements from Paroisse Saint-Joseph Artisan.' }}">
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

            .page-hero {
                background: linear-gradient(160deg, var(--blue-deep) 0%, var(--blue-mid) 60%, #0f3060 100%);
                position: relative; overflow: hidden;
            }
            .page-hero::before {
                content: ''; position: absolute; inset: 0;
                background: radial-gradient(ellipse 70% 60% at 50% 0%, rgba(245,197,24,0.10) 0%, transparent 70%);
                pointer-events: none;
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
                    <span class="font-cinzel relative z-10" style="color:var(--color-gold);font-size:1.5rem;" aria-hidden="true">✝</span>
                </div>
            </div>

            <div class="divider-ornament mb-4 animate-fade-in delay-1">
                <span class="eyebrow" style="color:rgba(245,197,24,0.70);">{{ $isFrench ? 'Restez informés' : 'Stay Informed' }}</span>
            </div>

            <h1 class="font-heading animate-fade-up delay-1"
                style="font-size:clamp(2.8rem,6vw,5.5rem); font-weight:700; font-style:italic;
                       color:#FFF; line-height:1.05; letter-spacing:-0.01em; margin-bottom:16px;
                       text-shadow:0 4px 32px rgba(0,0,0,0.45);">
                {{ $isFrench ? 'Annonces paroissiales' : 'Parish Announcements' }}
            </h1>

            <p class="font-heading animate-fade-up delay-2"
               style="font-style:italic; color:rgba(245,197,24,0.75);
                      font-size:clamp(0.9rem,1.8vw,1.1rem); font-weight:300;">
                {{ $isFrench ? 'Actualités, neuvaines et nouvelles de la communauté de la paroisse Saint-Joseph Artisan.' : 'Latest news, novenas, and community updates from Paroisse Saint-Joseph Artisan.' }}
            </p>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-px"
             style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.25),transparent);"></div>
    </section>

    <section id="announcements-grid" class="max-w-6xl mx-auto px-6 pt-16 pb-16" x-data="announcementTabs()" x-init="init()">
        {{-- Tab Bar --}}
        <div class="relative mb-10">
            <div class="flex items-center justify-center gap-0 border-b-2 border-muted/50 overflow-x-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]" x-ref="tabBar">
                <button @click="switchTab('all')"
                    :class="activeTab === 'all' ? 'text-primary border-primary' : 'text-muted-foreground border-transparent hover:text-primary/60'"
                    class="relative px-5 py-3 text-xs font-bold uppercase tracking-widest whitespace-nowrap transition-colors duration-200 border-b-2 -mb-[2px]">
                    {{ $isFrench ? 'Toutes' : 'All' }}
                    <span class="ml-1.5 inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-[10px] font-black transition-colors duration-200"
                        :class="activeTab === 'all' ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground'">
                        <span x-text="counts['all']"></span>
                    </span>
                </button>
                @foreach($categories as $cat)
                    <button @click="switchTab('{{ $cat }}')"
                        :class="activeTab === '{{ $cat }}' ? 'text-primary border-primary' : 'text-muted-foreground border-transparent hover:text-primary/60'"
                        class="relative px-5 py-3 text-xs font-bold uppercase tracking-widest whitespace-nowrap transition-colors duration-200 border-b-2 -mb-[2px]">
                        {{ $categoryLabels[$cat] ?? $cat }}
                        <span class="ml-1.5 inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-[10px] font-black transition-colors duration-200"
                            :class="activeTab === '{{ $cat }}' ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground'">
                            <span x-text="counts['{{ $cat }}']"></span>
                        </span>
                    </button>
                @endforeach
            </div>
            {{-- Sliding underline indicator --}}
            <div class="absolute bottom-0 h-[2px] bg-primary rounded-full transition-all duration-300 ease-out"
                x-ref="underline"
                :style="underlineStyle"></div>
        </div>

        {{-- Announcements Grid (fades during tab switch) --}}
        <div class="transition-all duration-300 ease-out" :class="loading ? 'opacity-30 pointer-events-none scale-[0.99]' : 'opacity-100 scale-100'">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="ann in announcements" :key="ann.id">
                    <article class="bg-card border border-muted rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group relative h-[268px]"
                        :style="'border-top-width:4px;border-top-color:' + getCategoryColor(ann.category)">
                        <div class="p-5 flex flex-col h-full">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <template x-if="ann.is_recruitment">
                                        <span class="inline-flex items-center gap-1 text-[8px] font-black uppercase tracking-widest text-accent">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                            <span>{{ $isFrench ? 'Recrutement' : 'Recruitment' }}</span>
                                        </span>
                                    </template>
                                </div>
                                <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center" :style="'background:' + getCategoryTint(ann.category)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" :stroke="getCategoryColor(ann.category)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 19V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v13"/><path d="M4 19l8-8 8 8"/><path d="M8 7v4"/><path d="M16 7v2"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="font-heading font-bold italic text-base text-primary leading-tight mb-2 line-clamp-2 min-h-[2.7em] flex-1 group-hover:text-accent transition-colors">
                                <a :href="ann.url" class="focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded" x-text="ann.title"></a>
                            </h3>
                            <p class="text-sm text-muted-foreground leading-relaxed line-clamp-2 min-h-[2.6em] mb-4" x-text="ann.content"></p>
                            <template x-if="ann.date_from">
                                <div class="flex items-center gap-1.5 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    <span class="text-[11px] font-bold text-primary" x-text="new Date(ann.date_from + 'T00:00:00').toLocaleDateString(document.documentElement.lang, { month: 'short', day: 'numeric' }) + (ann.date_to ? ' – ' + new Date(ann.date_to + 'T00:00:00').toLocaleDateString(document.documentElement.lang, { month: 'short', day: 'numeric' }) : '')"></span>
                                </div>
                            </template>
                            <div class="mt-auto pt-4 border-t border-muted flex items-center justify-between">
                                <a :href="ann.url" class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-primary hover:text-accent transition-colors">
                                    {{ $isFrench ? 'Lire la suite' : 'Read more' }} <span aria-hidden="true">&rarr;</span>
                                </a>
                                <template x-if="ann.is_recruitment && ann.registration_link">
                                    <a :href="ann.registration_link" target="_blank" rel="noopener" class="gold-btn text-[9.5px] px-2.5 py-1 rounded-lg font-bold uppercase tracking-wider">{{ $isFrench ? 'S’inscrire maintenant' : 'Register Now' }}</a>
                                </template>
                            </div>
                        </div>
                    </article>
                </template>
            </div>

            {{-- Empty state --}}
            <div x-show="announcements.length === 0" class="text-center py-20 bg-muted/30 rounded-[3rem] border-2 border-dashed">
                <div class="font-cinzel text-5xl mb-5 opacity-20 text-primary">✝</div>
                <h3 class="font-heading font-bold italic text-2xl mb-2 text-primary">{{ $isFrench ? 'Aucune annonce' : 'No Announcements' }}</h3>
                <p class="text-muted-foreground italic">{{ $isFrench ? 'Aucune annonce dans cette catégorie pour le moment. Revenez bientôt.' : 'There are currently no announcements in this category. Please check back soon.' }}</p>
            </div>
        </div>
    </section>

    <script>
        function announcementTabs() {
            return {
                activeTab: @js($category ?? 'all'),
                announcements: @js($initialAnnouncements),
                counts: @js($counts),
                loading: false,
                underlineWidth: 0,
                underlineLeft: 0,

                init() {
                    this.$nextTick(() => this.updateUnderline());
                    window.addEventListener('resize', () => this.updateUnderline());
                    window.addEventListener('popstate', () => {
                        const params = new URLSearchParams(window.location.search);
                        const cat = params.get('category') || 'all';
                        if (cat !== this.activeTab) {
                            this.fetchTab(cat, false);
                        }
                    });
                },

                get underlineStyle() {
                    return `width: ${this.underlineWidth}px; left: ${this.underlineLeft}px;`;
                },

                updateUnderline() {
                    this.$nextTick(() => {
                        const bar = this.$refs.tabBar;
                        if (!bar) return;
                        const activeBtn = bar.querySelector('button.text-primary') || bar.querySelector('button');
                        if (!activeBtn) return;
                        const barRect = bar.getBoundingClientRect();
                        const btnRect = activeBtn.getBoundingClientRect();
                        this.underlineWidth = btnRect.width;
                        this.underlineLeft = btnRect.left - barRect.left + bar.scrollLeft;
                    });
                },

                async switchTab(category) {
                    if (this.activeTab === category) return;
                    await this.fetchTab(category, true);
                },

                async fetchTab(category, pushState = true) {
                    this.loading = true;

                    if (pushState) {
                        const url = new URL(window.location);
                        if (category === 'all') {
                            url.searchParams.delete('category');
                        } else {
                            url.searchParams.set('category', category);
                        }
                        history.pushState({}, '', url);
                    }

                    try {
                        const res = await fetch(`{{ route('announcements.filter') }}?category=${encodeURIComponent(category)}`);
                        const data = await res.json();
                        this.announcements = data.announcements;
                        this.activeTab = category;
                        this.updateUnderline();
                    } catch (e) {
                        console.error('Failed to load announcements:', e);
                    } finally {
                        this.loading = false;
                    }
                },

                getCategoryColor(cat) {
                    const colors = {
                        'Parish Life': '#B5562F',
                        'Liturgical': '#6B3FA0',
                        'Sacraments': '#A87F22',
                        'Formation': '#1B2A4A',
                    };
                    return colors[cat] || '#2D6A4F';
                },

                getCategoryTint(cat) {
                    const tints = {
                        'Parish Life': '#FBEEE7',
                        'Liturgical': '#F3ECFA',
                        'Sacraments': '#FBF3DC',
                        'Formation': '#EEF1F6',
                    };
                    return tints[cat] || '#E8F4F0';
                },
            }
        }
    </script>
</x-public-layout>
