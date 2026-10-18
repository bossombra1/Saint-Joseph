<x-admin-index 
    title="Service Inquiries" 
    description="Examiner et valider les demandes relatives aux sacrements émanant des paroissiens."
    :headers="['Name', 'Type', 'Status', 'Date']"
>
    @forelse($inquiries as $i)
        <tr class="hover:bg-[#F5F7FA]/60 transition-colors group">
            <td class="px-6 py-4 font-bold text-primary text-[13px]">{{ $i->full_name }}</td>
            <td class="px-6 py-4 capitalize text-xs font-medium text-muted-foreground/60">{{ $i->inquiry_type }}</td>
            <td class="px-6 py-4">
                <x-admin-badge :status="$i->status" />
            </td>
            <td class="px-6 py-4 text-xs text-muted-foreground/50">{{ $i->created_at->format('M d, Y h:i A') }}</td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                    <a href="{{ route('admin.inquiries.show', $i->id) }}" class="p-2 rounded-xl hover:bg-[#F5F7FA] text-muted-foreground/40 hover:text-primary transition-all" title="View">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0z" /><circle cx="12" cy="12" r="3" /></svg>
                    </a>
                </div>
            </td>
        </tr>
    @empty
        <x-admin-empty title="No inquiries yet" description="Service inquiries from parishioners will appear here." icon="inbox" />
    @endforelse
</x-admin-index>
