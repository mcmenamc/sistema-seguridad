<?php

namespace App\Notifications;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IncidentNotification extends Notification
{
    use Queueable;

    protected $incident;
    protected $title;
    protected $message;
    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct(Incident $incident, $title, $message, $type = 'info')
    {
        $this->incident = $incident;
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'incident_id' => $this->incident->id,
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type, // info, success, warning, error
            'action_url' => route('incidents.index'),
        ];
    }
}
