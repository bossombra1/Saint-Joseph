<x-mail::message>
# Sacramental Inquiry Update

Dear {{ $inquiry->full_name ?? 'Parishioner' }},

Your sacramental inquiry submission (Ref: **{{ $refId }}**) for **{{ $inquiry->inquiry_type }}** has been updated to **{{ strtoupper($inquiry->status) }}**.

@if($inquiry->status === 'declined' && $inquiry->rejection_reason)
**Reason:**
> {{ $inquiry->rejection_reason }}
@endif

---

### Final Instructions
@if($inquiry->status === 'accepted')
Your inquiry has been accepted and forwarded to the parish office. Please prepare the following documents and wait for our staff to contact you:

@if(!empty($requirements))
**Documents to Prepare:**
@foreach($requirements as $req)
- {{ $req }}
@endforeach
@endif

@elseif($inquiry->status === 'declined')
We are unable to process your inquiry at this time. Please refer to the reason provided above. You may submit a new inquiry or visit the parish office for assistance.
@else
Your inquiry is currently under review.
@endif

<x-mail::button :url="route('track.status', ['refId' => $refId])">
Track Status Online
</x-mail::button>

{{ \App\Models\Setting::getValue('email_closing', 'Thank you for using Parish Portal!') }}<br>
God bless,<br>
**{{ \App\Models\Setting::getValue('email_signoff', 'Paroisse Saint-Joseph Artisan') }}**
</x-mail::message>
