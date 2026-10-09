<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->string('paciente');
            $table->string('telefono', 20);
            $table->string('email')->nullable();
            $table->dateTime('fecha_hora');
            $table->string('servicio');
            $table->string('estado')->default('pendiente');
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index(['fecha_hora', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
