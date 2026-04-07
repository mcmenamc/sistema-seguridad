<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceEvent;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $queryIncidents = Incident::query();
        $queryEvents = DeviceEvent::query();
        $queryDevices = Device::query();

        if ($request->date_filter === 'today') {
            $queryEvents->whereDate('timestamp', Carbon::today());
            $queryIncidents->whereDate('created_at', Carbon::today());
        } elseif ($request->date_filter === 'week') {
            $queryEvents->whereBetween('timestamp', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            $queryIncidents->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }

        if ($request->client_id) {
            $queryDevices->where('client_id', $request->client_id);
            $deviceIds = Device::where('client_id', $request->client_id)->pluck('id');
            $queryIncidents->whereIn('device_id', $deviceIds);
            $queryEvents->whereIn('device_id', $deviceIds);
        }

        $stats = [
            'incidencias_pendientes' => (clone $queryIncidents)->where('status', 'pendiente')->count(),
            'incidencias_proceso' => (clone $queryIncidents)->where('status', 'en proceso')->count(),
            'dispositivos_alerta' => (clone $queryDevices)->where('status', 'alerta')->count(),
            'dispositivos_activos' => (clone $queryDevices)->where('status', 'activo')->count(),
            'total_eventos' => (clone $queryEvents)->count(),
        ];

        $deviceStatusCounts = (clone $queryDevices)->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'charts' => [
                'device_status' => $deviceStatusCounts,
            ],
            'recent_events' => (clone $queryEvents)->with('device')->latest('timestamp')->take(10)->get(),
            'recent_incidents' => (clone $queryIncidents)->with('device')->latest()->take(10)->get(),
            'filters' => $request->only(['date_filter', 'client_id']),
            'clients' => User::role('Cliente')->get(['id', 'name']),
        ]);
    }
}
