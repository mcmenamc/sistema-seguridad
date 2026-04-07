<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceEvent;
use App\Models\Incident;
use App\Models\AuditLog;
use App\Models\IncidentType;
use App\Events\SignalReceived;
use App\Http\Requests\SaveEventRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = DeviceEvent::with('device');

        if (Auth::user()->hasRole('Cliente')) {
            $query->whereHas('device', function($q) {
                $q->where('client_id', Auth::id());
            });
        }

        if ($request->search) {
            $query->whereHas('device', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            })->orWhere('type', 'like', "%{$request->search}%");
        }

        if ($request->device_id) {
            $query->where('device_id', $request->device_id);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        return Inertia::render('Events/Index', [
            'events' => $query->latest()
                            ->paginate(15)
                            ->withQueryString(),
            'devices' => Device::orderBy('name')->get(['id', 'name']),
            'eventTypes' => IncidentType::orderBy('name')->get()->map(fn($t) => ['id' => $t->name, 'name' => $t->name]),
            'filters' => $request->only(['search', 'device_id', 'type']),
        ]);
    }

    public function store(SaveEventRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $device = Device::findOrFail($request->device_id);

            $event = DeviceEvent::create([
                'device_id' => $device->id,
                'type' => $request->type,
                'timestamp' => now(),
                'processed' => false,
            ]);

            $incidentCreated = false;

            $criticalTypes = [
                'Desconexión de Red',
                'Anomalía de Voltaje',
                'Alerta Térmica',
                'Actividad Sospechosa',
                'desconexion',
                'anomalia',
                'actividad sospechosa'
            ];

            if (in_array($request->type, $criticalTypes)) {
                $incident = Incident::create([
                    'device_id' => $device->id,
                    'type' => $request->type === 'desconexion' ? 'Desconexión de Red' : $request->type,
                    'status' => 'pendiente',
                    'description' => "REPORTE AUTOMÁTICO: Señal crítica [" . strtoupper($request->type) . "] detectada en el sensor.",
                ]);

                $device->update(['status' => 'alerta']);
                
                $event->update([
                    'incident_id' => $incident->id,
                    'processed' => true
                ]);
                $incidentCreated = true;
            }

            AuditLog::create([
                'user_id' => Auth::id() ?? 1,
                'action' => 'Simulación de Señal',
                'description' => "Telemetría forzada para [{$device->name}]: {$request->type}",
            ]);

            event(new SignalReceived($event->load('device')));

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Señal procesada por el núcleo.',
                    'incident_created' => $incidentCreated
                ]);
            }

            return redirect()->back()->with('message', 'Señal inyectada y procesada por el motor de reglas.');
        });
    }

    public function destroy(DeviceEvent $event)
    {
        $event->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Log de evento eliminado',
            'description' => "Se ha purgado un registro de telemetría del historial.",
        ]);

        return redirect()->back()->with('message', 'Registro de evento eliminado del historial.');
    }
}
