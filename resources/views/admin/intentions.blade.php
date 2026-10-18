<x-admin-layout>
    <div x-data="pptAutomation()">
    <div x-data="intentionList"
         data-ids='@json($intentions->pluck("id")->toArray())'
         data-batch-url="{{ route('admin.intentions.batch') }}"
         data-status-url="{{ route('admin.intentions.status', ':id') }}">

    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4 mb-8">
        <div class="min-w-0">
            <h1 class="font-heading text-3xl font-bold text-primary italic">Intentions de messe</h1>
            <p class="text-sm text-muted-foreground mt-1">Consultez les intentions approuvées et téléchargez un document Word modifiable.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 xl:justify-end">
            @include('admin.ppt-tools-buttons')
            <a href="{{ route('admin.intentions.create') }}"
               class="inline-flex items-center justify-center gap-2 bg-primary text-primary-foreground px-4 py-2 rounded-lg font-bold text-xs shadow-sm hover:opacity-90 transition-opacity whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                Créer
            </a>
        </div>
    </div>

    {{-- Status filter tabs --}}
    @php $currentFilter = request('status', 'all'); @endphp
    <div class="flex items-center gap-2 mb-6">
        @foreach(['all', 'pending', 'approved', 'rejected'] as $f)
            <a href="{{ route('admin.intentions', ['status' => $f]) }}"
                class="px-4 py-1.5 rounded-md text-xs font-bold transition-all capitalize border {{ $currentFilter === $f ? 'bg-primary text-primary-foreground border-primary' : 'bg-white text-muted-foreground border-border hover:border-primary hover:text-primary' }}">
                {{ $f }}
            </a>
        @endforeach
    </div>

    {{-- Batch Actions Bar --}}
    <div x-show="selected.length > 0" x-transition x-cloak
         class="mb-4 p-4 bg-accent/5 border border-accent/20 rounded-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-in slide-in-from-top-4 duration-300">
        <div class="flex items-center gap-3">
            <div class="h-8 w-8 bg-accent/20 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-accent"><path d="m9 11 3 3L22 4"/><path d="M21 12a9 9 0 1 1-9-9c.456 0 .9.053 1.323.155"/></svg>
            </div>
            <span class="text-sm font-bold text-accent italic">
                <span x-text="selected.length"></span> selected
            </span>
        </div>
        <div class="flex items-center gap-2">
            <form id="batch-form" action="{{ route('admin.intentions.batch') }}" method="POST" class="hidden">
                @csrf
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <input type="hidden" name="status" id="batch-action-type">
                <input type="hidden" name="rejection_reason" id="batch-rejection-reason">
            </form>

            <button @click="batchApprove()" :disabled="batchProcessing"
                    class="px-5 py-2 bg-accent text-accent-foreground rounded-lg text-xs font-black uppercase tracking-wider shadow-md hover:brightness-110 active:scale-95 transition-all flex items-center gap-2 disabled:opacity-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Approve
            </button>

            <button @click="openReject()" :disabled="batchProcessing"
                    class="px-5 py-2 bg-destructive text-white rounded-lg text-xs font-black uppercase tracking-wider shadow-md hover:bg-destructive/90 active:scale-95 transition-all flex items-center gap-2 disabled:opacity-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                Reject
            </button>

            <div class="h-6 w-px bg-accent/20 mx-1"></div>
            <button @click="selected = []" class="text-xs text-muted-foreground hover:text-accent font-bold px-2 transition-colors">Clear</button>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-card rounded-xl border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted/50 border-b">
                    <tr>
                        <th class="px-6 py-4 w-10">
                            <input type="checkbox" @click="toggleAll()"
                                :checked="selected.length > 0 && selected.length === allIds.length"
                                class="rounded border-gray-300 text-primary focus:ring-primary">
                        </th>
                        <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Donation</th>
                        <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Preferred Date</th>
                        <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($intentions as $i => $intention)
                        <tr class="hover:bg-muted/20 transition-colors cursor-pointer"
                            :class="selected.includes('{{ $intention->id }}') ? 'bg-primary/5' : ''"
                            data-row-id="{{ $intention->id }}"
                            @click="handleRowClick({{ $i }}, '{{ $intention->id }}', $event)">
                            <td class="px-6 py-4">
                                <input type="checkbox" x-model="selected" value="{{ $intention->id }}"
                                    @click.stop
                                    class="rounded border-gray-300 text-primary focus:ring-primary">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-primary">{{ $intention->full_name ?? '—' }}</div>
                                <div class="text-[10px] text-muted-foreground font-mono uppercase">{{ (string) ($intention->reference_number ?? substr($intention->id, 0, 8)) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="block font-medium">{{ $intention->intention_type ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($intention->payment_method)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md border text-[10px] font-bold uppercase tracking-wider {{ $intention->payment_method === 'GCash' ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-primary/5 text-primary border-primary/20' }}">
                                        {{ $intention->payment_method }}
                                    </span>
                                @else
                                    <span class="text-muted-foreground italic text-xs">Cash/None</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-muted-foreground whitespace-nowrap">
                                <span class="block font-bold text-primary">{{ (isset($intention->preferred_date) && $intention->preferred_date) ? $intention->preferred_date->format('M d, Y') : '—' }}</span>
                                <span class="text-[10px] font-medium uppercase tracking-widest">{{ $intention->formatted_mass_time ?: 'Heure non définie' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <x-admin-badge :status="$intention->status ?? 'pending'" data-status />
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2" @click.stop>
                                    <a href="{{ route('admin.intentions.show', $intention->id) }}"
                                       class="p-2 rounded-lg border border-border text-muted-foreground hover:border-primary hover:text-primary hover:bg-primary/5 transition-all group"
                                       title="View Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:scale-110 transition-transform"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    @if(($intention->status ?? 'pending') === 'pending')
                                        <form id="reject-form-{{ $intention->id }}" action="{{ route('admin.intentions.status', $intention->id) }}" method="POST" class="hidden">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                        </form>
                                        <button @click="openReject('{{ $intention->id }}')"
                                            class="p-2 rounded-lg border border-border text-muted-foreground hover:border-destructive hover:text-destructive hover:bg-destructive/5 transition-all group"
                                            title="Reject">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:scale-110 transition-transform"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-admin-empty
                            title="No intentions found"
                            description="No mass intentions match the current filter. Try selecting a different status."
                            icon="inbox"
                        />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Rejection Reason Modal --}}
    <div x-show="showRejectionModal" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-primary/20 backdrop-blur-sm animate-in fade-in duration-200">
        <div @click.away="showRejectionModal = false"
             class="bg-white max-w-md w-full rounded-2xl shadow-2xl border p-6 animate-in zoom-in-95 duration-200">
            <h3 class="text-xl font-bold text-primary italic font-heading mb-2">Rejection Reason</h3>
            <p class="text-sm text-muted-foreground mb-4">Provide a reason for rejecting the selected intention(s). This will be recorded for reference.</p>

            <textarea x-model="rejectionReason"
                      class="w-full min-h-[100px] rounded-lg border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring mb-6"
                      placeholder="e.g. Offensive language, duplicated entry, invalid date..."></textarea>

            <div class="flex items-center justify-end gap-3">
                <button @click="showRejectionModal = false; rejectionReason = ''; singleRejectId = null"
                        class="px-5 py-2 rounded-md text-sm font-bold text-muted-foreground hover:bg-muted transition-colors">
                    Cancel
                </button>
                <button @click="submitReject()"
                        class="px-5 py-2 bg-destructive text-white rounded-md text-sm font-bold shadow-md hover:bg-destructive/90 transition-all">
                    Reject Now
                </button>
            </div>
        </div>
    </div>
    </div>

    @include('admin.ppt-tools-modal')
    </div>
</x-admin-layout>
