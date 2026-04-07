<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Database\Seeder;

class IncidentSeeder extends Seeder
{
    public function run(): void
    {
        $devices = Device::all();
        $operators = User::role('Operador')->get();
        
        if ($devices->isEmpty()) return;

        $statuses = ['pendiente', 'en proceso', 'resuelto'];
        $incidentTypes = [
            'Desconexión detectada', 
            'Alerta de temperatura', 
            'Anomalía de voltaje', 
            'Falla de enlace'
        ];

        // Crear 80 incidencias manuales para asegurar paginación
        for ($i = 1; $i <= 80; $i++) {
            Incident::create([
                'device_id' => $devices->random()->id,
                'type' => $incidentTypes[rand(0, 3)],
                'status' => $statuses[rand(0, 2)],
                'description' => 'Incidencia de seguridad reportada automáticamente por monitoreo preventivo #' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'assigned_to' => rand(0, 1) ? $operators->random()->id : null,
                'created_at' => now()->subHours(rand(1, 500)),
            ]);
        }
    }
}
