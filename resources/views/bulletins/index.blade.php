<x-public-layout>
    <div class="container py-12 mx-auto px-4 max-w-5xl">
        <div class="text-center mb-16 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <p class="text-[12px] font-black uppercase tracking-[0.3em] text-accent mb-4">{{ app()->getLocale() === 'fr' ? 'Restez informés' : 'Stay Informed' }}</p>
            <h1 class="font-heading text-4xl md:text-6xl font-black mb-6 text-primary italic uppercase leading-tight">{{ app()->getLocale() === 'fr' ? 'Bulletins paroissiaux' : 'Weekly Bulletins' }}</h1>
            <div class="h-1.5 w-32 bg-accent mx-auto rounded-full mb-8"></div>
            <p class="text-xl text-muted-foreground max-w-2xl mx-auto italic">
                {{ app()->getLocale() === 'fr' ? 'Consultez et téléchargez les bulletins paroissiaux pour suivre les actualités, les horaires des messes et la vie de la communauté.' : 'Read and download parish bulletins with the latest news, Mass schedules, and community announcements.' }}
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($bulletins as $bulletin)
                <div class="group bg-card border rounded-[2.5rem] p-8 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden">
                    @php
                        $isPdf = str_ends_with($bulletin->file_path, '.pdf');
                    @endphp
                    <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:scale-110 transition-transform z-0">
                        @if($isPdf)
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        @endif
                    </div>

                    @if(!$isPdf)
                        <div class="mb-6 rounded-2xl overflow-hidden border border-muted bg-muted/20 relative z-10 aspect-[3/4]">
                            <img src="{{ Storage::url($bulletin->file_path) }}" 
                                 alt="{{ $bulletin->title }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                 <a href="{{ Storage::url($bulletin->file_path) }}" target="_blank" class="p-3 rounded-full bg-white/20 backdrop-blur-md text-white hover:bg-white/40 transition-all">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                 </a>
                            </div>
                        </div>
                    @endif
                    
                    <div class="mb-6 relative z-10">
                        @if($isPdf)
                            <div class="h-12 w-12 rounded-2xl bg-accent/10 flex items-center justify-center text-accent mb-4 group-hover:bg-accent group-hover:text-white transition-colors shadow-lg shadow-accent/5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                            </div>
                        @endif
                        <h2 class="text-xl font-black text-primary uppercase leading-tight mb-2">{{ $bulletin->title }}</h2>
                        <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">{{ $bulletin->published_date->locale(app()->getLocale())->isoFormat('LL') }}</p>
                    </div>

                    @if($isPdf)
                        <a href="{{ route('bulletins.show', $bulletin) }}" class="inline-flex items-center gap-2 text-sm font-black text-primary hover:text-accent transition-colors uppercase tracking-widest mb-4">
                            {{ app()->getLocale() === 'fr' ? 'Lire le bulletin' : 'Read bulletin' }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>
                    @endif

                    <a href="{{ route('bulletins.download', $bulletin) }}" class="inline-flex items-center gap-2 text-sm font-black text-primary hover:text-accent transition-colors uppercase tracking-widest group/link">
                        {{ app()->getLocale() === 'fr' ? 'Télécharger' : 'Download' }} {{ $isPdf ? 'PDF' : (app()->getLocale() === 'fr' ? 'l’image' : 'Image') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="translate-x-0 group-hover/link:translate-x-1 transition-transform"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-20 bg-muted/30 rounded-[3rem] border-2 border-dashed">
                    <p class="text-muted-foreground font-medium italic">{{ app()->getLocale() === 'fr' ? 'Aucun bulletin disponible pour le moment.' : 'No bulletins available at this time.' }}</p>
                </div>
            @endforelse
        </div>

        <div class="mt-16">
            {{ $bulletins->links() }}
        </div>
    </div>
</x-public-layout>
