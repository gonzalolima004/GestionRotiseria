<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido', function (Blueprint $table) {
            $table->id('id_pedido');

            $table->dateTime('fecha_hora')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->decimal('monto_total', 10, 2);

            $table->string('dni_cliente', 20);
            $table->unsignedBigInteger('id_metodo_pago');
            $table->unsignedBigInteger('id_estado_pedido');
            $table->unsignedBigInteger('id_modalidad_entrega');

            $table->foreign('dni_cliente')->references('dni_cliente')->on('cliente');
            $table->foreign('id_metodo_pago')->references('id_metodo_pago')->on('metodo_pago');
            $table->foreign('id_estado_pedido')->references('id_estado_pedido')->on('estado_pedido');
            $table->foreign('id_modalidad_entrega')->references('id_modalidad_entrega')->on('modalidad_entrega');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido');
    }
};
