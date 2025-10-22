<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropForeign(['id_pedido']);

            $table->foreign('id_pedido')
                  ->references('id_pedido')
                  ->on('pedido')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropForeign(['id_pedido']);

            $table->foreign('id_pedido')
                  ->references('id_pedido')
                  ->on('pedido');
        });
    }
};
