@component('mail::message')

{{-- Header Logo / Firm Name --}}
<div style="text-align: center; margin-bottom: 24px;">
    <h1 style="font-size: 22px; font-weight: 700; color: #1e293b; margin: 0; letter-spacing: -0.5px;">
        LexLanka
    </h1>
    <p style="font-size: 12px; color: #64748b; margin: 4px 0 0; letter-spacing: 1px; text-transform: uppercase;">
        Legal Practice Management
    </p>
</div>

---

Dear **{{ $clientName }}**,

We are writing to inform you that the status of your legal case has been officially updated in our system.

@component('mail::panel')
**Case Reference:** #{{ $caseId }}
**New Status:** {{ $statusLabel }}
**Assigned Attorney:** {{ $case->assignedAttorney->name ?? 'To be assigned' }}
@endcomponent

If you have any questions regarding this update or your case proceedings, please do not hesitate to contact your assigned attorney directly or reach out to our office.

@component('mail::button', ['url' => config('app.url'), 'color' => 'primary'])
Visit Client Portal
@endcomponent

We appreciate your continued trust in LexLanka.

Yours sincerely,

**The LexLanka Team**
*Legal Practice Management*

---

<p style="font-size: 11px; color: #94a3b8; text-align: center; margin-top: 16px;">
    This is an automated notification from LexLanka Legal Practice Management System.
    Please do not reply directly to this email.
</p>

@endcomponent
