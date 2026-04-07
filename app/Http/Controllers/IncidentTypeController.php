<?php

namespace App\Http\Controllers;

use App\Models\IncidentType;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class IncidentTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = IncidentType::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return Inertia::render('IncidentTypes/Index', [
            'types' => $query->latest()->paginate(15)->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:incident_types,name',
        ]);

        $type = IncidentType::create($request->only('name'));

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Protocolo creado',
            'description' => "Se ha forjado el nuevo tipo de alerta: {$type->name}",
        ]);

        return redirect()->back()->with('message', 'Protocolo inyectado al catálogo maestro.');
    }

    public function update(Request $request, IncidentType $incidentType)
    {
        $request->validate([
            'name' => "required|string|max:50|unique:incident_types,name,{$incidentType->id}",
        ]);

        $incidentType->update($request->only('name'));

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Protocolo actualizado',
            'description' => "Se ha modificado la definición del protocolo CAT-{$incidentType->id}",
        ]);

        return redirect()->back()->with('message', 'Protocolo actualizado en el catálogo.');
    }

    public function destroy(Request $request, IncidentType $incidentType)
    {
        $name = $incidentType->name;
        $incidentType->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tipo de alerta purgado',
            'description' => "Se ha eliminado el protocolo: {$name}",
        ]);

        return redirect()->back()->with('message', 'Protocolo purgado del catálogo.');
    }
}
