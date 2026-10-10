<?php

namespace Database\Seeders;

use App\Enums\EstadoCita;
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicios = [
            'Limpieza dental',
            'Consulta / revisión',
            'Resinas (empastes)',
            'Extracción',
            'Blanqueamiento',
            'Ortodoncia (revisión)',
            'Urgencia dental',
        ];

        $estados = [
            EstadoCita::Pendiente,
            EstadoCita::Confirmada,
            EstadoCita::Completada,
            EstadoCita::Cancelada,
        ];

        $pacientes = [
            ['María González', '5512345678', 'maria.gonzalez@example.com'],
            ['Juan Martínez', '5598765432', 'juan.martinez@example.com'],
            ['Ana López', '5555123456', 'ana.lopez@example.com'],
            ['Carlos Hernández', '5544332211', 'carlos.hernandez@example.com'],
            ['Lucía Ramírez', '5566778899', null],
            ['Pedro Sánchez', '5533445566', 'pedro.sanchez@example.com'],
            ['Sofía Torres', '5577889900', 'sofia.torres@example.com'],
            ['Diego Flores', '5522334455', null],
            ['Valeria Cruz', '5511223344', 'valeria.cruz@example.com'],
            ['Miguel Reyes', '5599887766', 'miguel.reyes@example.com'],
        ];

        $fecha = Carbon::now()->startOfDay()->addHours(9);

        foreach ($pacientes as $index => [$paciente, $telefono, $email]) {
            $servicio = $servicios[$index % count($servicios)];
            $estado = $estados[$index % count($estados)];

            Cita::updateOrCreate(
                ['fecha_hora' => $fecha],
                [
                    'paciente' => $paciente,
                    'telefono' => $telefono,
                    'email' => $email,
                    'servicio' => $servicio,
                    'estado' => $estado,
                    'notas' => $index % 3 === 0 ? 'Paciente refiere sensibilidad dental.' : null,
                ],
            );

            $fecha = $fecha->copy()->addMinutes(45);
        }
    }
}
