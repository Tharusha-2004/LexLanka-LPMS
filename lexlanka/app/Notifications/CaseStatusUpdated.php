<?php

namespace App\Notifications;

use App\Models\LegalCase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CaseStatusUpdated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly LegalCase $case
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusLabels = [
            'pending'            => 'Pending',
            'active'             => 'Active',
            'trial_scheduled'    => 'Trial Scheduled',
            'judgment_delivered' => 'Judgment Delivered',
            'case_closed'        => 'Case Closed',
        ];

        $statusLabel = $statusLabels[$this->case->status] ?? ucfirst($this->case->status);
        $clientName  = $this->case->client->name ?? 'Client';
        $caseId      = str_pad($this->case->id, 5, '0', STR_PAD_LEFT);

        return (new MailMessage)
            ->subject("Update on your Legal Case: #{$caseId}")
            ->markdown('emails.cases.status_updated', [
                'case'        => $this->case,
                'statusLabel' => $statusLabel,
                'clientName'  => $clientName,
                'caseId'      => $caseId,
            ]);
    }
}
