<?php

namespace App\Observers;

use App\Models\Incident;
use App\Models\IncidentHistory;
use App\Models\AuditLog;
use App\Models\User;
use App\Notifications\IncidentNotification;
use App\Events\IncidentUpdated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class IncidentObserver
{
    public function updated(Incident $incident): void
    {
        if ($incident->isDirty('status')) {
            $oldStatus = $incident->getOriginal('status');
            $newStatus = $incident->status;

            //  Historial de cambios de estado
            IncidentHistory::create([
                'incident_id' => $incident->id,
                'user_id' => Auth::id() ?? 1,
                'from_status' => $oldStatus,
                'to_status' => $newStatus,
                'comment' => "Cambio de estado táctico detectado.",
            ]);

            //  Bitácora
            AuditLog::create([
                'user_id' => Auth::id() ?? 1,
                'action' => 'Cambio de estado',
                'description' => "Incidencia #{$incident->id}: {$oldStatus} -> {$newStatus}",
            ]);

            // 3. Notificación
            $admins = User::role('Administrador')->get();
            Notification::send($admins, new IncidentNotification(
                $incident,
                'Actualización de Alerta',
                "La incidencia I-{$incident->id} ha cambiado a: " . strtoupper($newStatus),
                $newStatus === 'resuelto' ? 'success' : 'info'
            ));

            // BROADCAST REAL-TIME
            event(new IncidentUpdated($incident));
        }
    }

    public function created(Incident $incident): void
    {
        AuditLog::create([
            'user_id' => Auth::id() ?? 1,
            'action' => 'Incidencia creada',
            'description' => "Nueva incidencia operativa detectada: {$incident->type}",
        ]);

        $admins = User::role('Administrador')->get();
        Notification::send($admins, new IncidentNotification(
            $incident,
            'Nueva Alerta',
            "Se ha reportado una incidencia operativa: " . strtoupper($incident->type),
            'warning'
        ));

        event(new IncidentUpdated($incident));
    }
}
