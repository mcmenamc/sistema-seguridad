<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Módulos
            'ver_dashboard',
            'ver_dispositivos',
            'ver_eventos',
            'ver_incidencias',
            'ver_tipos_hardware',
            'ver_tipos_alerta',
            'ver_bitacora',
            'ver_usuarios',
            
            // Acciones Críticas
            'gestionar_usuarios',
            'gestionar_dispositivos',
            'gestionar_incidencias',
            'ver_auditoria_completa',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

         // Roles
        $admin = Role::firstOrCreate(['name' => 'Administrador']);
        $operador = Role::firstOrCreate(['name' => 'Operador']);
        $cliente = Role::firstOrCreate(['name' => 'Cliente']);

        // Administrador: Acceso total
        $admin->syncPermissions(Permission::all());

        // Operador
        $operador->syncPermissions([
            'ver_dashboard',
            'ver_dispositivos',
            'ver_eventos',
            'ver_incidencias',
            'ver_tipos_hardware',
            'ver_tipos_alerta',
            'gestionar_dispositivos',
            'gestionar_incidencias',
        ]);

        $cliente->syncPermissions([
            'ver_dashboard',
            'ver_dispositivos',
            'ver_eventos',
            'ver_incidencias',
        ]);
    }
}
