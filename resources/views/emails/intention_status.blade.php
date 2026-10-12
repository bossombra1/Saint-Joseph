<x-mail::message>
# Mass Intention Update

Dear {{ $intention->full_name ?? 'Parishioner' }},

Your mass intention submission (Ref: **{{ $refId }}**) has been updated to **{{ strtoupper($intention->status) }}**.

@if($intention->status === 'rejected' && $intention->rejection_reason)
**Reason:**
> {{ $intention->rejection_reason }}
@endif

---

### Final Instructions
@if($intention->status === 'approved')
Your mass intention has been scheduled. If you haven't yet, please proceed to the parish office to drop your donation envelope or complete your online donation prior to the mass schedule.
@elseif($intention->status === 'rejected')
Unfortunately, we cannot accommodate your intention at this time for the reason stated above. Please contact the parish office during office hours or ask for a live agent thru our parish chatbot if you need further clarification.
@else
Your intention is currently pending review.
@endif

<x-mail::button :url="route('track.status', ['refId' => $refId])">
Track Status Online
</x-mail::button>

{{ \App\Models\Setting::getValue('email_closing', 'Thank you for your faith and patience.') }}<br>
God bless,<br>
**{{ \App\Models\Setting::getValue('email_signoff', 'Paroisse Saint-Joseph Artisan') }}**
</x-mail::message>
