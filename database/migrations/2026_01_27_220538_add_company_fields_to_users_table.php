<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Empresa Colombia
            $table->string('razon_social', 255)->after('name');
            $table->string('nit', 12)->unique()->after('razon_social');
            $table->string('rut')->nullable()->after('nit');

            // Contacto
            $table->string('telefono', 15)->after('email');
        });

        // Agregar local_id si no existe, luego clave foránea
        if (!Schema::hasColumn('users', 'local_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('local_id')->nullable()->after('telefono');
            });
        }

        // Clave foránea para local_id
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('local_id')->references('id')->on('locales')->onDelete('cascade');
        });

        // Agregar estado SOLO si no existe
        if (!Schema::hasColumn('users', 'estado')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('estado', ['activo', 'inactivo'])->default('activo')->after('local_id');
            });
        }
    }

    public function down(): void
    {
        // Eliminar clave foránea primero
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['local_id']);
        });
        
        // Eliminar columnas
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'razon_social',
                'nit', 
                'rut',
                'telefono',
                'local_id',
                'estado'
            ]);
        });
    }
};
