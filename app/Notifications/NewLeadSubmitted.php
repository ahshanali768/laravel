<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Lead;

class NewLeadSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'New lead submitted by ' . $this->lead->agent_name,
            'lead_id' => $this->lead->id,
            'created_at' => now(),
        ];
    }
}
