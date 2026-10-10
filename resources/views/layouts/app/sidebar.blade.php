<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body
        class="min-h-screen bg-slate-50 text-slate-800 antialiased"
        style="--color-accent: var(--color-teal-600); --color-accent-content: var(--color-teal-700); --color-accent-foreground: var(--color-white);"
    >
        <flux:sidebar sticky collapsible="mobile" class="border-e border-teal-100/70 bg-white/90 backdrop-blur-xl">
            <flux:sidebar.header class="border-b border-teal-100/70 px-2 py-1">
                <a href="{{ route('dashboard') }}" wire:navigate class="flex min-w-0 items-center gap-2.5">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-teal-500 to-teal-700 text-white shadow-md shadow-teal-600/20">
                        <x-app-logo-icon class="size-5 fill-current" />
                    </span>
                    <span class="grid min-w-0 text-start leading-tight">
                        <span class="truncate text-sm font-bold text-slate-800">Dra. Rocio Pérez</span>
                        <span class="truncate text-xs font-medium text-teal-600">Panel del consultorio</span>
                    </span>
                </a>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Panel')">
                    <flux:sidebar.item
                        icon="calendar-days"
                        :href="route('dashboard')"
                        :current="request()->routeIs('dashboard') || request()->routeIs('citas.*')"
                        wire:navigate
                    >
                        {{ __('Agenda de citas') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <a href="{{ route('home') }}" target="_blank" rel="noopener"
                   class="group flex h-9 w-full items-center gap-3 rounded-lg px-3 text-sm font-medium text-slate-600 transition-all duration-200 hover:bg-teal-50 hover:text-teal-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-teal-600 active:scale-[0.97]">
                    <flux:icon name="globe-alt" class="size-4 shrink-0 text-teal-600 transition-transform duration-200 group-hover:scale-110 group-active:translate-x-0.5" />
                    <span class="truncate">Ver página pública</span>
                </a>
            </flux:sidebar.nav>

            {{-- Usuario y acciones --}}
            <div class="space-y-2 border-t border-teal-100/70 pt-3">
                <div class="flex items-center gap-2.5 rounded-xl bg-slate-50 px-2.5 py-2 ring-1 ring-slate-900/5">
                    <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" size="sm" class="shrink-0 rounded-full" />
                    <div class="min-w-0 flex-1 leading-tight">
                        <p class="truncate text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('profile.edit') }}" wire:navigate
                       class="flex h-9 flex-1 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-600 transition-all duration-200 hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-teal-600 active:scale-[0.97]">
                        <flux:icon name="cog" class="size-4" />
                        Ajustes
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit" data-test="logout-button"
                            class="group flex h-9 w-full items-center justify-center gap-2 rounded-lg border border-red-100 bg-red-50/70 text-sm font-medium text-red-600 transition-all duration-200 hover:border-red-200 hover:bg-red-100 hover:text-red-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 active:scale-[0.97]">
                            <flux:icon name="arrow-right-start-on-rectangle" class="size-4 transition-transform duration-200 group-hover:translate-x-0.5" />
                            Salir
                        </button>
                    </form>
                </div>
            </div>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden border-b border-teal-100/70 bg-white/90 backdrop-blur-xl">
            <flux:sidebar.toggle class="lg:hidden text-teal-700" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Configuración') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Cerrar sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
