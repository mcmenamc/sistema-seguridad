<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncidentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Desconexión de Red', 'icon' => 'Network', 'color' => '#ef4444'], // Red
            ['name' => 'Anomalía de Voltaje', 'icon' => 'Zap', 'color' => '#f59e0b'], // Amber
            ['name' => 'Alerta Térmica', 'icon' => 'Thermometer', 'color' => '#f97316'], // Orange
            ['name' => 'Reinicio Forzado', 'icon' => 'RefreshCw', 'color' => '#6366f1'], // Indigo
            ['name' => 'Actividad Sospechosa', 'icon' => 'Eye', 'color' => '#8b5cf6'], // Violet
        ];

        foreach ($types as $type) {
            \App\Models\IncidentType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
