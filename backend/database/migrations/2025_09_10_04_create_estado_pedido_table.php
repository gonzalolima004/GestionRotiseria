<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estado_pedido', function (Blueprint $table) {
            $table->id('id_estado_pedido');
            $table->string('nombre_estado_pedido', 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estado_pedido');
    }
};
