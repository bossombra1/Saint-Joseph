<x-admin-layout>
    <div class="p-6 lg:p-10">
        <div class="mx-auto max-w-6xl">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <a href="{{ route('admin.bulletins.index') }}" class="mb-3 inline-flex items-center gap-2 text-sm font-semibold text-muted-foreground hover:text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/><path d="M20 12H9"/></svg>
                        Retour aux bulletins
                    </a>
                    <h1 class="font-heading text-3xl font-bold text-primary">{{ $bulletin->title }}</h1>
                    <p class="mt-1 text-sm text-muted-foreground">Aperçu du bulletin · {{ $bulletin->published_date->format('d/m/Y') }}</p>
                </div>
                <a href="{{ route('bulletins.download', $bulletin) }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-primary px-5 py-3 text-sm font-bold text-white transition-colors hover:bg-primary/90">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></svg>
                    Télécharger le PDF
                </a>
            </div>

            <div class="overflow-hidden rounded-lg border border-black/10 bg-[#e7e9ec] shadow-sm">
                <iframe
                    src="{{ route('admin.bulletins.file', $bulletin) }}#toolbar=1&navpanes=0&view=FitH"
                    title="Aperçu PDF : {{ $bulletin->title }}"
                    class="h-[calc(100vh-13rem)] min-h-[32rem] w-full"
                    loading="eager">
                </iframe>
            </div>
        </div>
    </div>
</x-admin-layout>