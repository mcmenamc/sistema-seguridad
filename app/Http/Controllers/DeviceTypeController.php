<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\DeviceType;
use App\Http\Requests\SaveDeviceTypeRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DeviceTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = DeviceType::withCount('devices');

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return Inertia::render('DeviceTypes/Index', [
            'types' => $query->latest()
                            ->paginate(10)
                            ->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(SaveDeviceTypeRequest $request)
    {
        $type = DeviceType::create($request->validated());

    
       AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tipo de dispositivo creado',
            'description' => "Se ha registrado una nueva categoría de hardware: {$type->name}",
        ]);

        return redirect()->back()->with('message', 'Tipo de hardware registrado con éxito.');
    }

    public function update(SaveDeviceTypeRequest $request, DeviceType $deviceType)
    {
        $deviceType->update($request->validated());

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tipo de dispositivo actualizado',
            'description' => "Se ha modificado la categoría de hardware: {$deviceType->name}",
        ]);

        return redirect()->back()->with('message', 'Tipo de hardware actualizado con éxito.');
    }

    public function destroy(DeviceType $deviceType)
    {
        if ($deviceType->devices()->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'No se puede eliminar un tipo que tiene dispositivos asociados.']);
        }

        $deviceType->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tipo de dispositivo eliminado',
            'description' => "Se ha eliminado del catálogo: {$deviceType->name}",
        ]);

        return redirect()->back()->with('message', 'Tipo de hardware eliminado.');
    }
}
