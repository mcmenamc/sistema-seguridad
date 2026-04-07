<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\DeviceEvent;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $devices = Device::all();
        
        if ($devices->isEmpty()) return;

        $eventTypes = ['telemetria', 'alerta', 'sistema'];

        for ($i = 1; $i <= 150; $i++) {
            DeviceEvent::create([
                'device_id' => $devices->random()->id,
                'type' => $eventTypes[rand(0, 2)],
                'timestamp' => now()->subMinutes(rand(1, 10000)),
                'processed' => rand(0, 1),
                'incident_id' => null,
            ]);
        }
    }
}
