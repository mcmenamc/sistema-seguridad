<?php

namespace Database\Factories;

use App\Models\DeviceEvent;
use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceEventFactory extends Factory
{
    protected $model = DeviceEvent::class;

    public function definition(): array
    {
        return [
            'device_id' => Device::factory(),
            'type' => $this->faker->randomElement(['Desconexión de Red', 'Anomalía de Voltaje', 'Detección de Movimiento', 'Sabotaje de Cámara', 'Señal Normal']),
            'intensity' => $this->faker->randomElement(['baja', 'media', 'alta']),
            'processed' => $this->faker->boolean(80),
            'metadata' => [
                'signal_strength' => $this->faker->numberBetween(10, 100) . '%',
                'raw_code' => $this->faker->bothify('ERR-####-??'),
            ],
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
