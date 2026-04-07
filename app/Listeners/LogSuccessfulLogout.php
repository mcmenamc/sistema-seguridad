<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\AuditLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        if ($event->user) {
            AuditLog::create([
                'user_id' => $event->user->getAuthIdentifier(),
                'action' => 'Cierre de Sesión',
                'description' => "El usuario ha abandonado la terminal operativa activamente.",
            ]);
        }
    }
}
