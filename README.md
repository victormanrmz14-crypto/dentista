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


## Pruebas

```bash
php artisan test
```
