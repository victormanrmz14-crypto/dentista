@php
    $consultorio = 'Consultorio Dental Dra. Rocio Pérez';
    $whatsapp = '525512345678'; // lada + número, sin + ni espacios
    $mensaje = urlencode('Hola, me gustaría agendar una cita.');
    $servicios = [
        ['Limpieza dental', 'Eliminamos sarro y placa para mantener encías y dientes sanos.'],
        ['Resinas', 'Restauramos caries con materiales estéticos del color de tu diente.'],
        ['Blanqueamiento', 'Recupera la blancura de tu sonrisa de forma segura.'],
        ['Extracciones', 'Procedimientos cuidadosos y con anestesia local.'],
        ['Ortodoncia', 'Valoración y seguimiento para alinear tu sonrisa.'],
        ['Urgencias dentales', 'Atención rápida para dolor, fracturas o inflamación.'],
    ];
    $razones = [
        ['Atención personalizada', 'Cada tratamiento se explica y se adapta a ti.'],
        ['Material esterilizado', 'Protocolos estrictos de higiene y bioseguridad.'],
        ['Precios claros', 'Te damos el presupuesto antes de comenzar.'],
    ];
@endphp
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $consultorio }} | Cuida tu sonrisa</title>
    <meta name="description" content="Limpieza, resinas, blanqueamiento, ortodoncia y más. Agenda tu cita con la Dra. Rocio Pérez.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans text-slate-800 antialiased">

    {{-- Navbar --}}
    <header class="sticky top-0 z-50 border-b border-slate-100 bg-white/90 backdrop-blur">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
            <a href="#inicio" class="text-lg font-bold text-teal-700">🦷 Dra. Rocio Pérez</a>
            <div class="hidden gap-6 text-sm font-medium md:flex">
                <a href="#servicios" class="hover:text-teal-700">Servicios</a>
                <a href="#nosotros" class="hover:text-teal-700">Por qué elegirme</a>
                <a href="#contacto" class="hover:text-teal-700">Contacto</a>
            </div>
            <a href="https://wa.me/{{ $whatsapp }}?text={{ $mensaje }}" target="_blank" rel="noopener"
               class="rounded-full bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-700">
                Agendar cita
            </a>
        </nav>
    </header>

    {{-- Hero --}}
    <section id="inicio" class="bg-gradient-to-b from-teal-50 to-white">
        <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 py-20 md:grid-cols-2">
            <div>
                <p class="mb-3 text-sm font-semibold uppercase tracking-wide text-teal-700">Odontología general y estética</p>
                <h1 class="text-4xl font-extrabold leading-tight sm:text-5xl">
                    Una sonrisa sana empieza con una buena consulta
                </h1>
                <p class="mt-5 text-lg text-slate-600">
                    Atención dental cercana y profesional para toda la familia. Agenda tu cita hoy mismo.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="https://wa.me/{{ $whatsapp }}?text={{ $mensaje }}" target="_blank" rel="noopener"
                       class="rounded-full bg-teal-600 px-6 py-3 font-semibold text-white shadow hover:bg-teal-700">
                        Agendar por WhatsApp
                    </a>
                    <a href="#servicios"
                       class="rounded-full border border-teal-600 px-6 py-3 font-semibold text-teal-700 hover:bg-teal-50">
                        Ver servicios
                    </a>
                </div>
            </div>
            <div class="flex h-72 items-center justify-center rounded-3xl bg-teal-100 text-8xl md:h-96">
                <img src="{{ asset('img/doctora.jpg') }}" class="h-full w-full rounded-3xl object-cover" alt="Dra. Rocio Perez"> 
                
            </div>
        </div>
    </section>

    {{-- Servicios --}}
    <section id="servicios" class="mx-auto max-w-6xl px-4 py-20">
        <h2 class="text-center text-3xl font-bold">Nuestros servicios</h2>
        <p class="mx-auto mt-3 max-w-2xl text-center text-slate-600">
            Tratamientos para cuidar tu salud bucal en cada etapa.
        </p>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($servicios as [$titulo, $descripcion])
                <article class="rounded-2xl border border-slate-100 p-6 shadow-sm transition hover:shadow-md">
                    <div class="mb-4 flex size-12 items-center justify-center rounded-xl bg-teal-100 text-2xl">🦷</div>
                    <h3 class="text-lg font-semibold">{{ $titulo }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $descripcion }}</p>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Por qué elegirme --}}
    <section id="nosotros" class="bg-slate-50">
        <div class="mx-auto max-w-6xl px-4 py-20">
            <h2 class="text-center text-3xl font-bold">¿Por qué elegirme?</h2>
            <div class="mt-12 grid gap-8 md:grid-cols-3">
                @foreach ($razones as [$titulo, $descripcion])
                    <div class="text-center">
                        <div class="mx-auto mb-4 flex size-14 items-center justify-center rounded-full bg-teal-600 text-xl font-bold text-white">
                            {{ $loop->iteration }}
                        </div>
                        <h3 class="font-semibold">{{ $titulo }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $descripcion }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-teal-600">
        <div class="mx-auto max-w-4xl px-4 py-16 text-center text-white">
            <h2 class="text-3xl font-bold">¿Lista tu sonrisa para su revisión?</h2>
            <p class="mt-3 text-teal-50">Escríbenos y agenda tu cita en minutos.</p>
            <a href="https://wa.me/{{ $whatsapp }}?text={{ $mensaje }}" target="_blank" rel="noopener"
               class="mt-8 inline-block rounded-full bg-white px-8 py-3 font-semibold text-teal-700 hover:bg-teal-50">
                Escribir por WhatsApp
            </a>
        </div>
    </section>

    {{-- Contacto --}}
    <section id="contacto" class="mx-auto max-w-6xl px-4 py-20">
        <h2 class="text-center text-3xl font-bold">Contacto y ubicación</h2>
        <div class="mt-12 grid gap-8 md:grid-cols-2">
            <ul class="space-y-4 text-slate-700">
                <li><strong>📍 Dirección:</strong> Anexo "A", Esquina Teneria, Calle Francisco Sarabia, La Trinidad, 56130 Texcoco de Mora, Méx.</li>
                <li><strong>📞 Teléfono:</strong> 55 1234 5678</li>
                <li><strong>✉️ Correo:</strong> contacto@consultorio.com</li>
                <li><strong>🕘 Horario:</strong> Lunes a viernes 9:00–19:00 · Sábados 9:00–14:00</li>
            </ul>
            <div class="overflow-hidden rounded-2xl border border-slate-100">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3762.2624074774294!2d-99.11604960321044!3d19.444249999999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d1f950eaaaaaab%3A0x27a5a2362f02faf6!2sIdeal%20Dental%20(Dental%20Tourism)!5e0!3m2!1ses-419!2smx!4v1791581410734!5m2!1ses-419!2smx" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-slate-100 py-8 text-center text-sm text-slate-500">
        <p>© {{ date('Y') }} {{ $consultorio }}. Todos los derechos reservados.</p>
        @guest
            <a href="{{ route('login') }}" class="mt-2 inline-block text-xs text-slate-400 hover:text-teal-700">Acceso del consultorio</a>
        @else
            <a href="{{ route('citas.index') }}" class="mt-2 inline-block text-xs text-slate-400 hover:text-teal-700">Ir a la agenda</a>
        @endguest
    </footer>
</body>
</html>