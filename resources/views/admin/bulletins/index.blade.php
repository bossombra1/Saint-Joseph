<x-admin-layout>
    <div class="p-6 lg:p-10" x-data>
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="font-heading text-3xl font-bold text-primary italic">Weekly Bulletins</h1>
                <p class="text-muted-foreground mt-1 text-sm">Manage and upload weekly parish bulletins.</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Upload Form --}}
            <div class="bg-white border border-black/[.04] rounded-2xl p-6 shadow-sm shadow-black/[.02] h-fit">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" /><polyline points="17 8 12 3 7 8" /><line x1="12" x2="12" y1="3" y2="15" /></svg>
                    </div>
                    <h2 class="text-sm font-bold text-primary uppercase tracking-[.1em]">Upload New</h2>
                </div>
                <form action="{{ route('admin.bulletins.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div class="space-y-1.5">
                        <label for="bulletin-title" class="block text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50">Bulletin Title</label>
                        <input id="bulletin-title" type="text" name="title" required 
                            class="w-full px-4 py-2.5 rounded-xl bg-[#F5F7FA] border border-black/[.06] focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all text-sm font-bold"
                            placeholder="e.g. Easter Sunday 2026">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label for="bulletin-published-date" class="block text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50">Publish Date</label>
                        <input id="bulletin-published-date" type="date" name="published_date" required 
                            class="w-full px-4 py-2.5 rounded-xl bg-[#F5F7FA] border border-black/[.06] focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all text-sm font-bold"
                            value="{{ date('Y-m-d') }}">
                        @error('published_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label for="bulletin-file" class="block text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50">Bulletin PDF or image</label>
                        <input id="bulletin-file" type="file" name="file" accept="application/pdf,image/*" required 
                            class="w-full text-xs text-muted-foreground file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary/90 cursor-pointer">
                        @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded-xl shadow-lg shadow-primary/15 transition-all active:scale-[0.97] text-xs uppercase tracking-[.1em]">
                        Upload Bulletin
                    </button>
                </form>
            </div>

            {{-- List --}}
            <div class="lg:col-span-2 space-y-3">
                @forelse($bulletins as $bulletin)
                    <div class="bg-white border border-black/[.04] rounded-2xl p-5 shadow-sm shadow-black/[.02] flex items-center justify-between group hover:shadow-md hover:shadow-black/[.04] transition-all duration-300">
                        <div class="flex items-center gap-4">
                            @php
                                $isPdf = str_ends_with($bulletin->file_path, '.pdf');
                            @endphp
                            <div class="h-14 w-10 rounded-xl overflow-hidden border border-black/[.06] bg-[#F5F7FA] flex items-center justify-center text-primary/40 shrink-0">
                                @if($isPdf)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" /><polyline points="14 2 14 8 20 8" /></svg>
                                @else
                                    <img src="{{ Storage::url($bulletin->file_path) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div>
                                <h3 class="font-bold text-primary text-[13px]">{{ $bulletin->title }}</h3>
                                <p class="text-[10px] text-muted-foreground/50 font-bold tracking-wider">{{ $bulletin->published_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                             <a href="{{ $isPdf ? route('admin.bulletins.preview', $bulletin) : Storage::url($bulletin->file_path) }}" target="_blank" rel="noopener" aria-label="{{ $isPdf ? 'Preview bulletin '.$bulletin->title : 'Open bulletin image '.$bulletin->title }}" class="p-2 rounded-xl text-muted-foreground/40 hover:text-primary hover:bg-[#F5F7FA] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12c-1.8 0-4.5 2.7-4.5 4.5V20" /><path d="M3 12c1.8 0 4.5-2.7 4.5-4.5V4" /><path d="M12 3v18" /><path d="M3 12h18" /></svg>
                            </a>
                            <form :id="'delete-bulletin-{{ $bulletin->id }}'" action="{{ route('admin.bulletins.destroy', $bulletin) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    @click="$store.confirm.open({ title: 'Delete Bulletin', message: 'Are you sure you want to permanently remove this bulletin? This action cannot be undone.', onConfirm: () => document.getElementById('delete-bulletin-{{ $bulletin->id }}').submit() })"
                                    class="p-2 rounded-xl text-muted-foreground/40 hover:text-red-500 hover:bg-red-50 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18" /><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" /><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" /><line x1="10" x2="10" y1="11" y2="17" /><line x1="14" x2="14" y1="11" y2="17" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <x-admin-empty
                        title="No bulletins uploaded"
                        description="Upload your first weekly bulletin using the form on the left."
                        icon="empty"
                    />
                @endforelse
                {{ $bulletins->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
