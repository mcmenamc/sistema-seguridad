<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Device;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\IncidentHistory;
use App\Models\IncidentType;
use App\Http\Requests\SaveIncidentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Incident::with(['device.client', 'device.deviceType', 'assignedUser']);

        if (Auth::user()->hasRole('Cliente')) {
            $query->whereHas('device', function($q) {
                $q->where('client_id', Auth::id());
            });
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('type', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->device_id) {
            $query->where('device_id', $request->device_id);
        }

        if ($request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        return Inertia::render('Incidents/Index', [
            'incidents' => $query->latest()->paginate(15)->withQueryString(),
            'devices' => Device::orderBy('name')->get(['id', 'name']),
            'users' => User::all(['id', 'name']),
            'eventTypes' => IncidentType::orderBy('name')->get()->map(fn($t) => ['id' => $t->name, 'name' => $t->name]),
            'filters' => $request->only(['search', 'status', 'device_id', 'type', 'from', 'to']),
        ]);
    }

    public function store(SaveIncidentRequest $request)
    {
        Incident::create($request->validated());
        return back()->with('success', 'Incidencia registrada en el protocolo.');
    }

    public function update(SaveIncidentRequest $request, Incident $incident)
    {
        $incident->update($request->validated());
        return back()->with('success', 'Bitácora de incidencia actualizada.');
    }
}
