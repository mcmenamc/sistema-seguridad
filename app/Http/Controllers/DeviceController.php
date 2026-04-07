<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Device;
use App\Models\User;
use App\Models\DeviceType;
use Illuminate\Http\Request;
use App\Http\Requests\SaveDeviceRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $query = Device::with(['client', 'deviceType']);

        if (Auth::user()->hasRole('Cliente')) {
            $query->where('client_id', Auth::id());
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('location', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->client_id) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->device_type_id) {
            $query->where('device_type_id', $request->device_type_id);
        }

        return Inertia::render('Devices/Index', [
            'devices' => $query->orderByRaw("FIELD(status, 'alerta', 'activo', 'inactivo')")
                            ->latest()
                            ->paginate(15)
                            ->withQueryString(),
            'filters' => $request->only(['search', 'status', 'client_id', 'device_type_id']),
            'clients' => User::role('Cliente')->get(['id', 'name']),
            'deviceTypes' => DeviceType::all(['id', 'name', 'icon']),
        ]);
    }

    public function store(SaveDeviceRequest $request)
    {
        $data = $request->validated();
        $images = [];

        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $path = $file->store('devices', 'public');
                $images[] = $path;
            }
        }

        $data['images'] = $images;
        $device = Device::create($data);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Dispositivo creado',
            'description' => "Dispositivo registrado: {$device->name} con " . count($images) . " evidencias fotográficas.",
        ]);

        return redirect()->back()->with('message', 'Dispositivo registrado con éxito.');
    }

    public function update(SaveDeviceRequest $request, Device $device)
    {
        $data = $request->validated();
        $currentImages = $device->images ?? [];

        if ($request->deleted_images) {
            foreach ($request->deleted_images as $path) {
                Storage::disk('public')->delete($path);
                $currentImages = array_filter($currentImages, fn($img) => $img !== $path);
            }
            $currentImages = array_values($currentImages); 
        }

        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $path = $file->store('devices', 'public');
                $currentImages[] = $path;
            }
        }

        $data['images'] = $currentImages;
        $device->update($data);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Dispositivo actualizado',
            'description' => "Dispositivo modificado: {$device->name}",
        ]);

        return redirect()->back()->with('message', 'Dispositivo actualizado con éxito.');
    }

    public function destroy(Device $device)
    {
        $deviceName = $device->name;
        $device->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Dispositivo eliminado',
            'description' => "Dispositivo retirado del sistema: {$deviceName}",
        ]);

        return redirect()->back()->with('message', 'Dispositivo eliminado con éxito.');
    }
}
