<?php

namespace App\Livewire\Citas;

use App\Enums\EstadoCita;
use App\Models\Cita;
use Carbon\Carbon;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $buscar = '';

    #[Url(as: 'estado')]
    public string $filtroEstado = '';

    public ?int $citaId = null;
    public string $paciente = '';
    public string $telefono = '';
    public string $email = '';
    public string $fecha_hora = '';
    public string $servicio = '';
    public string $estado = 'pendiente';
    public string $notas = '';

    public ?int $citaAEliminar = null;

    public array $servicios = [
        'Limpieza dental',
        'Consulta / revisión',
        'Resinas (empastes)',
        'Extracción',
        'Blanqueamiento',
        'Ortodoncia (revisión)',
        'Urgencia dental',
    ];

    protected function rules(): array
    {
        return [
            'paciente' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'fecha_hora' => array_filter([
                'required',
                'date',
                // Solo se exige fecha futura al crear
                $this->citaId ? null : 'after:now',
            ]),
            'servicio' => ['required', 'string', 'max:255'],
            'estado' => ['required', Rule::enum(EstadoCita::class)],
            'notas' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'paciente.required' => 'El nombre del paciente es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'fecha_hora.required' => 'Selecciona fecha y hora.',
            'fecha_hora.after' => 'La fecha debe ser posterior a este momento.',
            'servicio.required' => 'Selecciona un servicio.',
        ];
    }

    public function updatedBuscar(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroEstado(): void
    {
        $this->resetPage();
    }

    public function crear(): void
    {
        $this->resetFormulario();
        Flux::modal('cita-form')->show();
    }

    public function editar(int $id): void
    {
        $cita = Cita::findOrFail($id);

        $this->citaId = $cita->id;
        $this->paciente = $cita->paciente;
        $this->telefono = $cita->telefono;
        $this->email = $cita->email ?? '';
        $this->fecha_hora = $cita->fecha_hora->format('Y-m-d\TH:i');
        $this->servicio = $cita->servicio;
        $this->estado = $cita->estado->value;
        $this->notas = $cita->notas ?? '';

        $this->resetValidation();
        Flux::modal('cita-form')->show();
    }

    public function guardar(): void
    {
        $datos = $this->validate();
        $fecha = Carbon::parse($datos['fecha_hora']);

        // Evitar empalmes con citas activas
        $ocupado = Cita::where('fecha_hora', $fecha)
            ->where('estado', '!=', EstadoCita::Cancelada->value)
            ->when($this->citaId, fn ($q) => $q->whereKeyNot($this->citaId))
            ->exists();

        if ($ocupado) {
            $this->addError('fecha_hora', 'Ya existe una cita en ese horario.');
            return;
        }

        $payload = [
            'paciente' => $datos['paciente'],
            'telefono' => $datos['telefono'],
            'email' => $datos['email'] ?: null,
            'fecha_hora' => $fecha,
            'servicio' => $datos['servicio'],
            'estado' => $datos['estado'],
            'notas' => $datos['notas'] ?: null,
        ];

        if ($this->citaId) {
            Cita::findOrFail($this->citaId)->update($payload);
        } else {
            Cita::create($payload);
        }

        Flux::modal('cita-form')->close();
        $this->resetFormulario();
        Flux::toast($this->citaId ? 'Cita actualizada.' : 'Cita registrada.');
    }

    public function cambiarEstado(int $id, string $estado): void
    {
        Cita::findOrFail($id)->update([
            'estado' => EstadoCita::from($estado),
        ]);
    }

    public function confirmarEliminar(int $id): void
    {
        $this->citaAEliminar = $id;
        Flux::modal('confirmar-eliminacion')->show();
    }

    public function eliminar(): void
    {
        if ($this->citaAEliminar) {
            Cita::whereKey($this->citaAEliminar)->delete();
        }

        $this->citaAEliminar = null;
        Flux::modal('confirmar-eliminacion')->close();
        Flux::toast('Cita eliminada.');
    }

    private function resetFormulario(): void
    {
        $this->reset([
            'citaId', 'paciente', 'telefono', 'email',
            'fecha_hora', 'servicio', 'notas',
        ]);
        $this->estado = 'pendiente';
        $this->resetValidation();
    }

    public function render()
    {
        $citas = Cita::query()
            ->when($this->buscar, function ($q) {
                $q->where(function ($q) {
                    $q->where('paciente', 'like', "%{$this->buscar}%")
                      ->orWhere('telefono', 'like', "%{$this->buscar}%");
                });
            })
            ->when($this->filtroEstado, fn ($q) => $q->where('estado', $this->filtroEstado))
            ->orderBy('fecha_hora')
            ->paginate(10);

        return view('livewire.citas.index', [
            'citas' => $citas,
            'estados' => EstadoCita::cases(),
        ]);
    }
}