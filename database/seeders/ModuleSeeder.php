<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            // Principal
            [
                'name' => 'Dashboard',
                'icon' => 'LayoutDashboard',
                'route' => 'dashboard',
                'permission' => 'ver_dashboard',
                'group_name' => 'Principal',
                'order' => 1
            ],
            
            // Operativa
            [
                'name' => 'Dispositivos',
                'icon' => 'Monitor',
                'route' => 'devices.index',
                'permission' => 'ver_dispositivos',
                'group_name' => 'Operativa',
                'order' => 10
            ],
            [
                'name' => 'Eventos',
                'icon' => 'Zap',
                'route' => 'events.index',
                'permission' => 'ver_eventos',
                'group_name' => 'Operativa',
                'order' => 11
            ],
            [
                'name' => 'Incidencias',
                'icon' => 'AlertTriangle',
                'route' => 'incidents.index',
                'permission' => 'ver_incidencias',
                'group_name' => 'Operativa',
                'order' => 12
            ],
            
            // Configuraciones
            [
                'name' => 'Tipos de Hardware',
                'icon' => 'Server',
                'route' => 'device-types.index',
                'permission' => 'ver_tipos_hardware',
                'group_name' => 'Configuraciones',
                'order' => 20
            ],
            [
                'name' => 'Tipos de Alerta',
                'icon' => 'Tag',
                'route' => 'incident-types.index',
                'permission' => 'ver_tipos_alerta',
                'group_name' => 'Configuraciones',
                'order' => 21
            ],
            
            // Administración
            [
                'name' => 'Bitácora',
                'icon' => 'ScrollText',
                'route' => 'logs.index',
                'permission' => 'ver_bitacora',
                'group_name' => 'Administración',
                'order' => 30
            ],
            [
                'name' => 'Usuarios',
                'icon' => 'Users',
                'route' => 'users.index',
                'permission' => 'ver_usuarios',
                'group_name' => 'Administración',
                'order' => 31
            ],
        ];

        foreach ($modules as $module) {
            Module::updateOrCreate(['route' => $module['route']], $module);
        }
    }
}
