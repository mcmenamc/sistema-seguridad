<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\DeviceType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  CATEGORÍAS (TIPOS) DE DISPOSITIVOS
        $types = [
            ['name' => 'Cámara IP', 'icon' => 'Camera'],
            ['name' => 'Control de Acceso', 'icon' => 'ShieldCheck'],
            ['name' => 'Sensor Térmico', 'icon' => 'Zap'],
            ['name' => 'Panel de Alarma', 'icon' => 'Server'],
            ['name' => 'Botón de Pánico', 'icon' => 'AlertTriangle'],
        ];

        foreach ($types as $t) {
            DeviceType::updateOrCreate(['name' => $t['name']], ['icon' => $t['icon']]);
        }

        $typeModels = DeviceType::all();

         //CLIENTES REALES
        $laura = User::updateOrCreate(['email' => 'laura@ejemplo.com'], [
            'name' => 'Laura Cliente',
            'password' => Hash::make('password'),
        ]);
        $laura->assignRole('Cliente');

        $pedro = User::updateOrCreate(['email' => 'pedro@ejemplo.com'], [
            'name' => 'Pedro Cliente',
            'password' => Hash::make('password'),
        ]);
        $pedro->assignRole('Cliente');

        //  DISPOSITIVOS REALES VINCULADOS
        $dispositivosReales = [
            ['name' => 'Domo PTZ Acceso Principal', 'type_name' => 'Cámara IP', 'status' => 'activo', 'location' => 'Entrada Vehicular Norte', 'client_id' => $laura->id],
            ['name' => 'Barrera Vehicular Automatizada', 'type_name' => 'Control de Acceso', 'status' => 'activo', 'location' => 'Caseta de Vigilancia', 'client_id' => $laura->id],
            ['name' => 'Sensor Perimetral Láser A1', 'type_name' => 'Panel de Alarma', 'status' => 'alerta', 'location' => 'Barda Exterior Este', 'client_id' => $laura->id],
            ['name' => 'Cámara Térmica Almacén Frío', 'type_name' => 'Cámara IP', 'status' => 'activo', 'location' => 'Bodega de Perecederos', 'client_id' => $laura->id],
            ['name' => 'Detector de Intrusión Zona 4', 'type_name' => 'Sensor Térmico', 'status' => 'inactivo', 'location' => 'Patio de Maniobras', 'client_id' => $laura->id],
            ['name' => 'Lector Biométrico Huella/Facial', 'type_name' => 'Control de Acceso', 'status' => 'activo', 'location' => 'Lobby Principal Piso 1', 'client_id' => $pedro->id],
            ['name' => 'Cerradura Electromagnética 01', 'type_name' => 'Control de Acceso', 'status' => 'activo', 'location' => 'Puerta Emergencia Sótano', 'client_id' => $pedro->id],
            ['name' => 'Sensor de Inundación Sala TI', 'type_name' => 'Sensor Térmico', 'status' => 'alerta', 'location' => 'Cuarto de Racks', 'client_id' => $pedro->id],
            ['name' => 'Panel Contra Incendios Mircom', 'type_name' => 'Panel de Alarma', 'status' => 'activo', 'location' => 'Oficina Administrativa', 'client_id' => $pedro->id],
            ['name' => 'Cámara Bullet 360 Vision', 'type_name' => 'Cámara IP', 'status' => 'activo', 'location' => 'Pasillo Elevadores Piso 3', 'client_id' => $pedro->id],
            ['name' => 'Switch Industrial PoE 24P', 'type_name' => 'Panel de Alarma', 'status' => 'activo', 'location' => 'Gabinete Telecomunicaciones', 'client_id' => $laura->id],
            ['name' => 'Detector de Humo Fotoeléctrico', 'type_name' => 'Panel de Alarma', 'status' => 'activo', 'location' => 'Cocina de Empleados', 'client_id' => $laura->id],
            ['name' => 'Botón de Pánico Caja 01', 'type_name' => 'Botón de Pánico', 'status' => 'alerta', 'location' => 'Área de Cajas', 'client_id' => $pedro->id],
            ['name' => 'Detector Rayo Infrarrojo 100m', 'type_name' => 'Sensor Térmico', 'status' => 'inactivo', 'location' => 'Muro Perimetral Oeste', 'client_id' => $pedro->id],
        ];

        foreach ($dispositivosReales as $dev) {
            $typeId = $typeModels->where('name', $dev['type_name'])->first()->id;
            Device::create([
                'name' => $dev['name'],
                'device_type_id' => $typeId,
                'status' => $dev['status'],
                'location' => $dev['location'],
                'client_id' => $dev['client_id'],
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            Device::create([
                'name' => "Equipo Táctico - " . str_pad($i, 03, '0', STR_PAD_LEFT),
                'device_type_id' => $typeModels->random()->id,
                'status' => ['activo', 'inactivo', 'alerta'][rand(0, 2)],
                'location' => 'Sector Operativo ' . chr(rand(65, 90)) . '-' . rand(100, 999),
                'client_id' => User::role('Cliente')->get()->random()->id,
            ]);
        }
    }
}
