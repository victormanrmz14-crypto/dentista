<?php

namespace App\Models;

use App\Enums\EstadoCita;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'paciente', 'telefono', 'email', 'fecha_hora',
        'servicio', 'estado', 'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_hora' => 'datetime',
            'estado' => EstadoCita::class,
        ];
    }
}
