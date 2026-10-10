# Consultorio Dental

Aplicación de gestión para un consultorio dental construida con Laravel, Livewire y Flux UI. Permite administrar las citas de los pacientes y cuenta con autenticación para el personal del consultorio.

## Requisitos

- PHP 8.3 o superior
- Composer
- Node.js y npm
- SQLite (conexión por defecto)

## Instalación

```bash
# Instala dependencias de PHP y JavaScript, genera la clave y migra la base de datos
composer run setup
```

Si prefieres hacerlo paso a paso:

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install
npm run build
```

Para desarrollo, levanta todos los servicios (servidor, cola, Vite) con:

```bash
composer run dev
```

## Seeders

Los seeders son clases que insertan datos iniciales o de prueba en la base de datos. Se guardan en `database/seeders/`.

### Crear un seeder

```bash
php artisan make:seeder NombreDelSeeder
```

Esto genera el archivo `database/seeders/NombreDelSeeder.php`. Dentro del método `run()` debes colocar la lógica de inserción. Por ejemplo, usando el modelo y su factory:

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class NombreDelSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(10)->create();
    }
}
```

Para datos que deben existir siempre, usa `updateOrCreate()` y así el seeder puede ejecutarse varias veces sin duplicar registros:

```php
User::updateOrCreate(
    ['email' => 'correo@consultorio.com'],
    ['name' => 'Nombre', 'password' => 'password'],
);
```

### Registrar el seeder

Los seeders individuales no se ejecutan solos: hay que registrarlos en `database/seeders/DatabaseSeeder.php` mediante el método `call()`.

```php
public function run(): void
{
    $this->call([
        DentistaUserSeeder::class,
    ]);
}
```

### Ejecutar los seeders

```bash
# Ejecuta todos los seeders registrados en DatabaseSeeder
php artisan db:seed

# Ejecuta un seeder específico
php artisan db:seed --class=DentistaUserSeeder

# Recrea el esquema de la base de datos y luego ejecuta los seeders
php artisan migrate:fresh --seed
```

## Usuario de la dentista

El seeder `DentistaUserSeeder` crea la cuenta de acceso de la doctora:

| Campo | Valor |
| --- | --- |
| Nombre | Dra. Rocio Perez |
| Correo | `rocio.perez@consultorio.com` |
| Contraseña | `password` |

> Cambia la contraseña después del primer inicio de sesión.

## Pruebas

```bash
php artisan test
```
