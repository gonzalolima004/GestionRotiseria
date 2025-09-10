<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venta', function (Blueprint $table) {
            $table->id('id_venta');
            $table->date('fecha');
            $table->decimal('monto_venta', 10, 2);
            $table->unsignedBigInteger('id_pedido');

            $table->foreign('id_pedido')->references('id_pedido')->on('pedido');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venta');
    }
};
