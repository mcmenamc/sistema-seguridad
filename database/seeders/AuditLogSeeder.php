<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) return;

        $actions = [
            'login', 'logout', 'create_device', 'update_device', 
            'delete_device', 'create_incident', 'resolve_incident', 
            'update_user', 'view_logs'
        ];

        $descriptions = [
            'El usuario inició sesión en el sistema.',
            'Cierre de sesión exitoso.',
            'Se registró un nuevo dispositivo en el inventario.',
            'Actualización de parámetros técnicos del equipo.',
            'Eliminación de registro de hardware por obsolescencia.',
            'Apertura de nueva incidencia de seguridad crítica.',
            'Cierre de incidencia tras validación en campo.',
            'Modificación de permisos de perfil de usuario.',
            'Consulta de bitácora de auditoría forense.'
        ];

        for ($i = 1; $i <= 120; $i++) {
            $actionIndex = rand(0, 8);
            AuditLog::create([
                'user_id' => $users->random()->id,
                'action' => $actions[$actionIndex],
                'description' => $descriptions[$actionIndex],
                'metadata' => [
                    'ip' => '192.168.1.' . rand(10, 250),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)-Browser/1.0',
                    'timestamp_latam' => now()->subMinutes(rand(1, 50000))->toIso8601String()
                ],
                'created_at' => now()->subMinutes(rand(1, 50000)),
            ]);
        }
    }
}
