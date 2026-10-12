<x-public-layout>
    @php
        $isFrench = app()->getLocale() === 'fr';
    @endphp
    @push('styles')
        <style>
            .bulletin-reader-page { background: #edf0f2; }
            .bulletin-book-stage { min-height: 630px; }
            .bulletin-book-page { display: grid; place-items: center; background: #fdfcf9; overflow: hidden; }
            .bulletin-book-page canvas { display: block; width: 100%; height: 100%; object-fit: contain; }
            .bulletin-reader-button:disabled { opacity: .38; cursor: not-allowed; }
            @media (max-width: 640px) {
                .bulletin-book-stage { min-height: 420px; }
            }
        </style>
    @endpush

    <section class="bulletin-reader-page min-h-[75vh] px-4 py-10 sm:px-6 sm:py-14">
        <div class="mx-auto max-w-6xl">
            <a href="{{ route('bulletins.index') }}" class="mb-6 inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-accent">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/><path d="M20 12H9"/></svg>
                {{ $isFrench ? 'Tous les bulletins' : 'All bulletins' }}
            </a>

            <div class="mb-8 flex flex-col gap-4 border-b border-primary/10 pb-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="mb-2 text-xs font-bold uppercase tracking-[.2em] text-accent">{{ $isFrench ? 'Bulletin paroissial' : 'Parish bulletin' }}</p>
                    <h1 class="font-heading text-3xl font-bold leading-tight text-primary sm:text-4xl">{{ $bulletin->title }}</h1>
                    <p class="mt-2 text-sm text-muted-foreground">{{ $bulletin->published_date->locale(app()->getLocale())->isoFormat('LL') }}</p>
                </div>
                <a href="{{ route('bulletins.download', $bulletin) }}" class="inline-flex min-h-11 shrink-0 items-center justify-center gap-2 rounded-lg bg-primary px-5 py-3 text-sm font-bold text-white transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></svg>
                    {{ $isFrench ? 'Télécharger le PDF' : 'Download PDF' }}
                </a>
            </div>

            <div data-bulletin-reader data-pdf-url="{{ route('bulletins.preview', $bulletin) }}" data-locale="{{ app()->getLocale() }}" tabindex="0" class="mx-auto max-w-[900px] outline-none focus-visible:ring-2 focus-visible:ring-primary">
                <div class="bulletin-book-stage flex items-center justify-center overflow-hidden rounded-lg bg-[#d7dadd] px-2 py-4 sm:px-6">
                    <div data-book-pages class="w-full" aria-label="{{ $isFrench ? 'Pages du bulletin' : 'Bulletin pages' }}"></div>
                </div>

                <p data-reader-status class="py-4 text-center text-sm text-muted-foreground" role="status" aria-live="polite">
                    {{ $isFrench ? 'Chargement du bulletin…' : 'Loading bulletin…' }}
                </p>

                <div class="flex flex-wrap items-center justify-center gap-4 py-4">
                    <button type="button" data-page-previous class="bulletin-reader-button inline-flex h-11 w-11 items-center justify-center rounded-full border border-primary/20 bg-white text-primary transition-colors hover:bg-primary/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary" aria-label="{{ $isFrench ? 'Page précédente' : 'Previous page' }}" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                    </button>
                    <span data-page-label class="min-w-32 text-center text-sm font-semibold tabular-nums text-primary" aria-live="polite">{{ $isFrench ? 'Pages 1–2' : 'Pages 1–2' }}</span>
                    <button type="button" data-page-next class="bulletin-reader-button inline-flex h-11 w-11 items-center justify-center rounded-full border border-primary/20 bg-white text-primary transition-colors hover:bg-primary/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary" aria-label="{{ $isFrench ? 'Page suivante' : 'Next page' }}" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                    </button>
                    <span class="hidden text-xs text-muted-foreground sm:inline">{{ $isFrench ? 'Utilisez les flèches du clavier ou touchez les coins des pages.' : 'Use the arrow keys or tap the page corners.' }}</span>
                </div>
            </div>
        </div>
    </section>
</x-public-layout>