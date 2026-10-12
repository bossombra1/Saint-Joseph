<x-admin-layout>
    <div class="p-6" x-data>
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="font-heading text-3xl font-bold text-primary italic">Gallery</h1>
                <p class="text-muted-foreground mt-1 text-sm">Manage your parish albums, memories and video highlights</p>
            </div>
            <a href="{{ route('admin.gallery.create') }}" class="inline-flex items-center gap-2 bg-primary text-primary-foreground px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-primary/15 hover:shadow-xl hover:shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                Create Album
            </a>
        </div>

        @if($albums->isEmpty())
            <x-admin-empty
                title="No albums yet"
                description="Start capturing your parish moments by creating your first photo album."
                icon="empty"
                actionRoute="{{ route('admin.gallery.create') }}"
                actionText="Create first album"
            />
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($albums as $album)
                    <div class="gallery-card bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] group overflow-hidden hover:shadow-lg hover:shadow-black/[.06] hover:border-black/[.08] transition-all duration-300 flex flex-col">
                        <div class="aspect-video bg-[#F5F7FA] relative overflow-hidden shrink-0">
                            @if($album->images->first())
                                <img src="{{ $album->images->first()->url }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-muted-foreground/20 gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="2" x2="22" y1="2" y2="22"/><path d="M10.41 10.41 2 18"/><path d="M8.13 8.13 11 5l9 9"/><path d="m16 16 4 4V8a2 2 0 0 0-2-2"/></svg>
                                    <span class="text-[9px] uppercase font-black tracking-[.2em]">Empty</span>
                                </div>
                            @endif
                            <div class="absolute top-3 right-3">
                                <x-admin-badge :status="$album->is_published ? 'published' : 'draft'" />
                            </div>
                            <div class="absolute bottom-3 left-3">
                                <span class="bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-lg flex items-center gap-1.5 border border-white/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                    {{ $album->images_count ?? $album->images->count() }}
                                </span>
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-1">
                            <h3 class="text-lg font-heading font-bold text-primary truncate" title="{{ $album->title }}">{{ $album->title }}</h3>
                            <div class="mt-1.5 text-xs text-muted-foreground/60 line-clamp-2 leading-relaxed flex-1">
                                {{ $album->description ?: 'No description provided.' }}
                            </div>
                            <div class="flex items-center justify-between pt-4 mt-4 border-t border-black/[.04] group-hover:border-primary/10 transition-colors">
                                <div class="flex gap-1.5">
                                    <a href="{{ route('admin.gallery.edit', $album) }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#F5F7FA] text-primary text-[11px] font-bold hover:bg-primary hover:text-white transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" /><path d="m15 5 4 4" /></svg>
                                        Edit
                                    </a>
<button type="button" x-data
                                        @click="$store.confirm.open({ title: 'Delete Album', message: 'Are you sure you want to permanently remove this album and all its images? This action cannot be undone.', onConfirm: () => deleteAlbumFromList('{{ route('admin.gallery.destroy', $album) }}', $el) })"
                                        class="p-2 rounded-lg bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18" /><path d="M19 6v14c0 1-1 2-2 2H7c-0 1-2-1-2-2V6" /><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" /><line x1="10" x2="10" y1="11" y2="17" /><line x1="14" x2="14" y1="11" y2="17" /></svg>
                                    </button>
                                </div>
                                <span class="text-[9px] text-muted-foreground/30 font-bold uppercase tracking-wider">{{ $album->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
    function deleteAlbumFromList(url, el) {
        const card = el.closest('.gallery-card');
        if (!card || card.dataset.deleting) return;
        card.dataset.deleting = '1';

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        })
        .then(r => r.text().then(text => {
            let data;
            try { data = JSON.parse(text); } catch (e) { data = { message: 'Server returned an unexpected response. Please try again.' }; }
            return { ok: r.ok, data };
        }))
        .then(({ ok, data }) => {
            if (!ok) {
                delete card.dataset.deleting;
                Alpine.store('toast').trigger(data.message || 'Failed to delete album.', 'error');
                return;
            }
            Alpine.store('toast').trigger(data.message || 'Album removed.', 'success');
            card.style.transition = 'opacity .3s ease, transform .3s ease, height .3s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(.95)';
            setTimeout(() => card.remove(), 320);
            setTimeout(() => { if (!document.querySelector('.gallery-card')) window.location.reload(); }, 360);
        })
        .catch(() => {
            delete card.dataset.deleting;
            Alpine.store('toast').trigger('Network error. Please try again.', 'error');
        });
    }
    </script>
    @endpush
</x-admin-layout>
