<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <flux:heading size="xl">Citas</flux:heading>
            <flux:text>Administra la agenda del consultorio.</flux:text>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="crear">Nueva cita</flux:button>
    </div>

    {{-- Filtros --}}
    <div class="flex flex-wrap gap-3">
        <div class="w-full sm:w-80">
            <flux:input wire:model.live.debounce.300ms="buscar" icon="magnifying-glass"
                        placeholder="Buscar por paciente o teléfono" />
        </div>
        <div class="w-full sm:w-52">
            <flux:select wire:model.live="filtroEstado">
                <flux:select.option value="">Todos los estados</flux:select.option>
                @foreach ($estados as $e)
                    <flux:select.option value="{{ $e->value }}">{{ $e->label() }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-700">
            <thead class="bg-zinc-50 text-left dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 font-medium">Fecha y hora</th>
                    <th class="px-4 py-3 font-medium">Paciente</th>
                    <th class="px-4 py-3 font-medium">Servicio</th>
                    <th class="px-4 py-3 font-medium">Estado</th>
                    <th class="px-4 py-3 text-right font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($citas as $cita)
                    <tr wire:key="cita-{{ $cita->id }}">
                        <td class="whitespace-nowrap px-4 py-3">
                            {{ $cita->fecha_hora->translatedFormat('d M Y, H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $cita->paciente }}</div>
                            <div class="text-zinc-500">{{ $cita->telefono }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $cita->servicio }}</td>
                        <td class="px-4 py-3">
                            <flux:badge size="sm" :color="$cita->estado->color()">
                                {{ $cita->estado->label() }}
                            </flux:badge>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            @if ($cita->estado->value === 'pendiente')
                                <flux:button size="sm" variant="ghost" icon="check"
                                    wire:click="cambiarEstado({{ $cita->id }}, 'confirmada')"
                                    title="Confirmar" />
                            @endif
                            @if (in_array($cita->estado->value, ['pendiente', 'confirmada']))
                                <flux:button size="sm" variant="ghost" icon="check-circle"
                                    wire:click="cambiarEstado({{ $cita->id }}, 'completada')"
                                    title="Marcar completada" />
                            @endif
                            <flux:button size="sm" variant="ghost" icon="pencil-square"
                                wire:click="editar({{ $cita->id }})" title="Editar" />
                            <flux:button size="sm" variant="ghost" icon="trash"
                                wire:click="confirmarEliminar({{ $cita->id }})" title="Eliminar" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-zinc-500">
                            No hay citas que mostrar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $citas->links() }}

    {{-- Modal crear / editar --}}
    <flux:modal name="cita-form" class="w-full max-w-lg">
        <form wire:submit="guardar" class="space-y-4">
            <flux:heading size="lg">{{ $citaId ? 'Editar cita' : 'Nueva cita' }}</flux:heading>

            <flux:input label="Paciente" wire:model="paciente" />
            <div class="grid gap-4 sm:grid-cols-2">
                <flux:input label="Teléfono" wire:model="telefono" type="tel" />
                <flux:input label="Correo (opcional)" wire:model="email" type="email" />
            </div>

            <flux:input label="Fecha y hora" wire:model="fecha_hora" type="datetime-local" />

            <flux:select label="Servicio" wire:model="servicio" placeholder="Selecciona un servicio">
                @foreach ($servicios as $s)
                    <flux:select.option value="{{ $s }}">{{ $s }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select label="Estado" wire:model="estado">
                @foreach ($estados as $e)
                    <flux:select.option value="{{ $e->value }}">{{ $e->label() }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:textarea label="Notas (opcional)" wire:model="notas" rows="3" />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Guardar</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Modal eliminar --}}
    <flux:modal name="confirmar-eliminacion" class="max-w-sm">
        <div class="space-y-4">
            <flux:heading size="lg">¿Eliminar cita?</flux:heading>
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