<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->id('id_detalle');

            $table->unsignedBigInteger('id_pedido');
            $table->unsignedBigInteger('id_producto');

            $table->integer('cantidad');
            $table->decimal('subtotal', 10, 2);

            $table->foreign('id_pedido')->references('id_pedido')->on('pedido');
            $table->foreign('id_producto')->references('id_producto')->on('producto');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_pedido');
    }
};
