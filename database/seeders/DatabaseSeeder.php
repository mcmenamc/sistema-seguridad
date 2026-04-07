<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(ModuleSeeder::class);
        $this->call(RoleSeeder::class);

        //  (ADMIN & OPERADORES)
        $admin = User::updateOrCreate(['email' => 'admin@test.com'], [
            'name' => 'Jesús Admin Director',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('Administrador');

        $ops = [
            ['name' => 'Carlos Operador Alpha', 'email' => 'carlos.ops@test.com'],
            ['name' => 'Beatriz Operador Beta', 'email' => 'beatriz.ops@test.com'],
            ['name' => 'Sara Operador Gamma', 'email' => 'sara.ops@test.com'],
            ['name' => 'Mario Operador Delta', 'email' => 'mario.ops@test.com'],
            ['name' => 'Lucia Operador Epsilon', 'email' => 'lucia.ops@test.com'],
        ];

        foreach ($ops as $op) {
            $user = User::updateOrCreate(['email' => $op['email']], [
                'name' => $op['name'],
                'password' => bcrypt('password'),
            ]);
            $user->assignRole('Operador');
        }

        $clientes = [
            ['name' => 'Juan Pérez (Lógistica Norte)', 'email' => 'juan.perez@test.com'],
            ['name' => 'María García (Almacenes Alfa)', 'email' => 'maria.garcia@test.com'],
            ['name' => 'Roberto Sánchez (Corporativo Sol)', 'email' => 'roberto.s@test.com'],
            ['name' => 'Ana Martínez (Tech Solutions)', 'email' => 'ana.mtz@test.com'],
            ['name' => 'Carlos Rodríguez (Industrias Delta)', 'email' => 'carlos.rod@test.com'],
            ['name' => 'Lucía Fernández (Seguridad Global)', 'email' => 'lucia.f@test.com'],
            ['name' => 'Diego Gómez (Transportes Prime)', 'email' => 'diego.g@test.com'],
            ['name' => 'Elena Ruiz (Retail X)', 'email' => 'elena.ruiz@test.com'],
            ['name' => 'Fernando López (Energía Pura)', 'email' => 'fer.lopez@test.com'],
            ['name' => 'Patricia Torres (Banca Segura)', 'email' => 'patricia.t@test.com'],
            ['name' => 'Miguel Angel (Sistemas Estela)', 'email' => 'miguel.a@test.com'],
            ['name' => 'Rosa Isela (Textiles Atlas)', 'email' => 'rosa.i@test.com'],
            ['name' => 'Gabriel Soto (Alimentos MX)', 'email' => 'gabriel.s@test.com'],
            ['name' => 'Sofia Vergara (Moda Express)', 'email' => 'sofia.v@test.com'],
            ['name' => 'Alejandro Sanz (Audio Pro)', 'email' => 'ale.sanz@test.com'],
            ['name' => 'Monica Naranjo (Estética Real)', 'email' => 'monica.n@test.com'],
            ['name' => 'David Bisbal (Marina Group)', 'email' => 'david.b@test.com'],
            ['name' => 'Shakira Mebarak (Fundación Sol)', 'email' => 'shakira.m@test.com'],
            ['name' => 'Luis Miguel (Hoteles Sol)', 'email' => 'luis.m@test.com'],
            ['name' => 'Paulina Rubio (Eventos Chic)', 'email' => 'pau.rubio@test.com'],
            ['name' => 'Enrique Iglesias (Vuelos V)', 'email' => 'enrique.i@test.com'],
            ['name' => 'Thalia Sodi (Belleza Total)', 'email' => 'thalia.s@test.com'],
            ['name' => 'Chayanne Figueroa (Baile Fit)', 'email' => 'chayanne.f@test.com'],
            ['name' => 'Ricky Martin (Vida Sana)', 'email' => 'ricky.m@test.com'],
            ['name' => 'Gloria Estefan (Ritmo Latam)', 'email' => 'gloria.e@test.com'],
            ['name' => 'Juanes Aristizabal (Paz Rock)', 'email' => 'juanes.a@test.com'],
            ['name' => 'Natalia Lafourcade (Arte MX)', 'email' => 'natalia.l@test.com'],
            ['name' => 'Ximena Sariñana (Musica Ind)', 'email' => 'ximena.s@test.com'],
            ['name' => 'Julieta Venegas (Libros Lib)', 'email' => 'julieta.v@test.com'],
            ['name' => 'Beto Cuevas (Ley Real)', 'email' => 'beto.c@test.com'],
            ['name' => 'Fher Olvera (Eco Planeta)', 'email' => 'fher.o@test.com'],
            ['name' => 'Vicente Fernandez (Rancho F)', 'email' => 'chente.f@test.com'],
            ['name' => 'Pepe Aguilar (Charro Pro)', 'email' => 'pepe.a@test.com'],
            ['name' => 'Christian Nodal (Mariacheño)', 'email' => 'nodal.c@test.com'],
            ['name' => 'Belinda Peregrin (Pop Star)', 'email' => 'belinda.p@test.com'],
            ['name' => 'Danna Paola (Elite Sec)', 'email' => 'danna.p@test.com'],
            ['name' => 'Eiza Gonzalez (Hollywood)', 'email' => 'eiza.g@test.com'],
            ['name' => 'Diego Luna (Cine Local)', 'email' => 'diego.l@test.com'],
            ['name' => 'Gael Garcia (Rudo Cursi)', 'email' => 'gael.g@test.com'],
            ['name' => 'Salma Hayek (Global Prod)', 'email' => 'salma.h@test.com'],
        ];

        foreach ($clientes as $c) {
            $user = User::updateOrCreate(['email' => $c['email']], [
                'name' => $c['name'],
                'password' => bcrypt('password'),
            ]);
            $user->assignRole('Cliente');
        }

        $this->call(IncidentTypeSeeder::class);
        $this->call(DeviceSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(IncidentSeeder::class);
        $this->call(AuditLogSeeder::class);
    }
}
