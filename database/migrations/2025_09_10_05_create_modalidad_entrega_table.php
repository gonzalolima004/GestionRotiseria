<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modalidad_entrega', function (Blueprint $table) {
            $table->id('id_modalidad_entrega');
            $table->string('nombre_modalidad_entrega', 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modalidad_entrega');
    }
};
