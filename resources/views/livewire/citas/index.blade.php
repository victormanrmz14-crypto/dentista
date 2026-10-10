@php
    $stats = [
        ['label' => 'Citas hoy', 'value' => $citasHoy, 'icon' => 'calendar-days', 'gradient' => 'from-teal-500 to-teal-700', 'shadow' => 'shadow-teal-600/20'],
        ['label' => 'Pendientes', 'value' => $pendientes, 'icon' => 'clock', 'gradient' => 'from-amber-400 to-amber-600', 'shadow' => 'shadow-amber-500/20'],
        ['label' => 'Completadas', 'value' => $completadas, 'icon' => 'check-circle', 'gradient' => 'from-emerald-500 to-emerald-700', 'shadow' => 'shadow-emerald-600/20'],
        ['label' => 'Próximas', 'value' => $proximas, 'icon' => 'rocket-launch', 'gradient' => 'from-sky-500 to-blue-700', 'shadow' => 'shadow-sky-600/20'],
    ];

    $pill = [
        'pendiente'  => ['bg-amber-50 text-amber-700 ring-amber-600/20', 'bg-amber-500'],
        'confirmada' => ['bg-blue-50 text-blue-700 ring-blue-600/20', 'bg-blue-500'],
        'completada' => ['bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'bg-emerald-500'],
        'cancelada'  => ['bg-red-50 text-red-700 ring-red-600/20', 'bg-red-500'],
    ];

    $estadoClases = fn (string $e): array => $pill[$e] ?? $pill['pendiente'];

    $iniciales = function (string $nombre): string {
        return collect(explode(' ', trim($nombre)))
            ->filter()
            ->take(2)
            ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))
            ->join('');
    };
@endphp

<div class="dash-fade-in space-y-6">
    {{-- Encabezado --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-teal-600">
                Panel de administración
            </p>
            <flux:heading size="xl" class="text-slate-900">Agenda de citas</flux:heading>
            <flux:text>Administra el consultorio, confirma y da seguimiento a tus pacientes.</flux:text>
        </div>
        <flux:button
            variant="primary"
            icon="plus"
            wire:click="crear"
            class="dash-pop shadow-lg shadow-teal-600/25"
        >
            Nueva cita
        </flux:button>
    </div>

    {{-- Tarjetas de resumen --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $index => $stat)
            <div
                class="dash-fade-up group rounded-2xl border border-teal-100/70 bg-white p-5 shadow-sm ring-1 ring-slate-900/5 transition duration-300 hover:-translate-y-1 hover:shadow-md"
                style="animation-delay: {{ $index * 90 }}ms"
            >
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</span>
                    <span class="flex size-11 items-center justify-center rounded-xl bg-gradient-to-br {{ $stat['gradient'] }} text-white shadow-md transition duration-300 group-hover:scale-110 {{ $stat['shadow'] }}">
                        <flux:icon name="{{ $stat['icon'] }}" variant="solid" class="size-5" />
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-800">
                    {{ $stat['value'] }}
                </p>
            </div>
        @endforeach
    </div>

    {{-- Filtros --}}
    <div class="dash-fade-up flex flex-wrap gap-3" style="animation-delay: 120ms">
        <div class="w-full sm:w-80">
            <flux:input wire:model.live.debounce.300ms="buscar" icon="magnifying-glass"
                        placeholder="Buscar por paciente o teléfono" />
        </div>
        <div class="w-full sm:w-56">
            <flux:select wire:model.live="filtroEstado">
                <flux:select.option value="">Todos los estados</flux:select.option>
                @foreach ($estados as $e)
                    <flux:select.option value="{{ $e->value }}">{{ $e->label() }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="dash-fade-up overflow-hidden rounded-2xl border border-slate-200/70 bg-white shadow-sm" style="animation-delay: 180ms">
        <div class="dash-scroll overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50/80 text-left">
                    <tr>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-slate-500">Fecha y hora</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-slate-500">Paciente</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-slate-500">Servicio</th>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-slate-500">Estado</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($citas as $cita)
                        @php
                            [$pillBg, $pillDot] = $estadoClases($cita->estado->value);
                        @endphp
                        <tr wire:key="cita-{{ $cita->id }}" class="group transition-colors duration-200 hover:bg-teal-50/40">
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="grid leading-tight">
                                        <span class="font-medium text-slate-800">
                                            {{ $cita->fecha_hora->translatedFormat('l d M') }}
                                        </span>
                                        <span class="text-xs text-slate-500">
                                            {{ $cita->fecha_hora->translatedFormat('H:i') }} h
                                        </span>
                                    </div>
                                    @if ($cita->fecha_hora->isToday())
                                        <span class="dash-pop rounded-full bg-teal-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-teal-700 ring-1 ring-teal-600/20">
                                            Hoy
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-teal-100 text-xs font-bold text-teal-700">
                                        {{ $iniciales($cita->paciente) }}
                                    </span>
                                    <div class="leading-tight">
                                        <div class="font-medium text-slate-800">{{ $cita->paciente }}</div>
                                        <div class="text-xs text-slate-500">{{ $cita->telefono }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $cita->servicio }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $pillBg }}">
                                    <span class="size-1.5 rounded-full {{ $pillDot }}"></span>
                                    {{ $cita->estado->label() }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <div class="inline-flex items-center justify-end gap-1 opacity-100 transition sm:opacity-0 sm:group-hover:opacity-100">
                                    @if ($cita->estado->value === 'pendiente')
                                        <flux:button size="sm" variant="ghost" icon="check"
                                            wire:click="cambiarEstado({{ $cita->id }}, 'confirmada')"
                                            title="Confirmar cita" class="text-teal-700 hover:bg-teal-50 hover:text-teal-800" />
                                    @endif
                                    @if (in_array($cita->estado->value, ['pendiente', 'confirmada']))
                                        <flux:button size="sm" variant="ghost" icon="check-circle"
                                            wire:click="cambiarEstado({{ $cita->id }}, 'completada')"
                                            title="Marcar como completada" class="text-emerald-600 hover:bg-emerald-50 hover:text-emerald-700" />
                                    @endif
                                    <flux:button size="sm" variant="ghost" icon="pencil-square"
                                        wire:click="editar({{ $cita->id }})" title="Editar" class="text-slate-500 hover:bg-teal-50 hover:text-teal-700" />
                                    <flux:button size="sm" variant="ghost" icon="trash"
                                        wire:click="confirmarEliminar({{ $cita->id }})" title="Eliminar" class="text-red-500 hover:bg-red-50 hover:text-red-600" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16">
                                <div class="mx-auto flex max-w-sm flex-col items-center text-center">
                                    <span class="flex size-14 items-center justify-center rounded-2xl bg-teal-50 text-teal-600">
                                        <flux:icon name="calendar-days" variant="solid" class="size-7" />
                                    </span>
                                    <h3 class="mt-4 text-base font-semibold text-slate-800">No hay citas que mostrar</h3>
                                    <p class="mt-1 text-sm text-slate-500">
                                        @if ($buscar || $filtroEstado)
                                            Ajusta la búsqueda o el filtro de estado para encontrar citas.
                                        @else
                                            Agenda la primera cita del día para empezar a gestionar tu consultorio.
                                        @endif
                                    </p>
                                    @unless ($buscar || $filtroEstado)
                                        <flux:button variant="primary" icon="plus" wire:click="crear" class="mt-5 shadow-lg shadow-teal-600/25">
                                            Nueva cita
                                        </flux:button>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($citas->hasPages())
        <div class="dash-fade-up" style="animation-delay: 220ms">
            {{ $citas->links() }}
        </div>
    @endif

    {{-- Modal crear / editar --}}
    <flux:modal name="cita-form" class="w-full max-w-xl">
        <form wire:submit="guardar" class="space-y-5">
            {{-- Encabezado --}}
            <div class="flex items-start gap-3">
                <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-teal-500 to-teal-700 text-white shadow-md shadow-teal-600/20">
                    <flux:icon :name="$citaId ? 'pencil-square' : 'calendar-days'" variant="solid" class="size-5" />
                </span>
                <div class="min-w-0">
                    <flux:heading size="lg">{{ $citaId ? 'Editar cita' : 'Nueva cita' }}</flux:heading>
                    <flux:subheading>
                        {{ $citaId ? 'Actualiza los datos de la cita seleccionada.' : 'Completa los datos para agendar la cita.' }}
                    </flux:subheading>
                </div>
            </div>

            <flux:separator />

            {{-- Datos del paciente --}}
            <fieldset class="space-y-4">
                <legend class="text-xs font-semibold uppercase tracking-wider text-teal-600">Datos del paciente</legend>
                <flux:input label="Nombre completo" icon="user" wire:model="paciente" placeholder="Ej. Ana López" required />
                <div class="grid gap-4 sm:grid-cols-2">
                    <flux:input label="Teléfono" icon="phone" type="tel" wire:model="telefono" placeholder="55 1234 5678" required />
                    <flux:input label="Correo (opcional)" icon="envelope" type="email" wire:model="email" placeholder="correo@ejemplo.com" />
                </div>
            </fieldset>

            <flux:separator />

            {{-- Detalle de la cita --}}
            <fieldset class="space-y-4">
                <legend class="text-xs font-semibold uppercase tracking-wider text-teal-600">Detalle de la cita</legend>
                <flux:input label="Fecha y hora" icon="calendar-days" type="datetime-local" wire:model="fecha_hora" required />
                <div class="grid gap-4 sm:grid-cols-2">
                    <flux:select label="Servicio" wire:model="servicio" placeholder="Selecciona un servicio" required>
                        @foreach ($servicios as $s)
                            <flux:select.option value="{{ $s }}">{{ $s }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:select label="Estado" wire:model="estado">
                        @foreach ($estados as $e)
                            <flux:select.option value="{{ $e->value }}">{{ $e->label() }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                <flux:textarea label="Notas (opcional)" wire:model="notas" rows="3" placeholder="Alergias, indicaciones u observaciones..." />
            </fieldset>

            <flux:separator />

            {{-- Acciones --}}
            <div class="flex flex-col-reverse items-stretch gap-2 sm:flex-row sm:items-center sm:justify-end">
                <flux:modal.close>
                    <flux:button variant="ghost" class="w-full sm:w-auto">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button
                    type="submit"
                    variant="primary"
                    icon="check"
                    wire:loading.attr="disabled"
                    wire:target="guardar"
                    class="w-full shadow-lg shadow-teal-600/25 sm:w-auto"
                >
                    <span wire:loading.remove wire:target="guardar">
                        {{ $citaId ? 'Guardar cambios' : 'Agendar cita' }}
                    </span>
                    <span wire:loading wire:target="guardar" class="flex items-center gap-2">
                        <flux:icon.arrow-path class="size-4 animate-spin" />
                        Guardando…
                    </span>
                </flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Modal eliminar --}}
    <flux:modal name="confirmar-eliminacion" class="max-w-sm">
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <span class="flex size-10 items-center justify-center rounded-xl bg-red-50 text-red-600">
                    <flux:icon name="trash" variant="solid" class="size-5" />
                </span>
                <flux:heading size="lg">¿Eliminar cita?</flux:heading>
            </div>
            <flux:text>Esta acción no se puede deshacer.</flux:text>
            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="eliminar">Eliminar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>