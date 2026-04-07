<?php

namespace App\Events;

use App\Models\DeviceEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SignalReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $signal;

    /**
     * Create a new event instance.
     */
    public function __construct(DeviceEvent $event)
    {
        $this->signal = $event->load('device');
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('secops-monitor'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'signal.received';
    }
}
