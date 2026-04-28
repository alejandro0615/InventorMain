<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salidas', function (Blueprint $table) {
            $table->string('factus_id')->nullable();
            $table->string('estado_dian')->nullable();
            $table->text('cufe')->nullable();
            $table->text('pdf_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('salidas', function (Blueprint $table) {
            $table->dropColumn([
                'factus_id',
                'estado_dian',
                'cufe',
                'pdf_url'
            ]);
        });
    }
};