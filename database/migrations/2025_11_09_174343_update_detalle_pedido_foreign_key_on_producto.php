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
        Schema::table('detalle_pedido', function (Blueprint $table) {
            // Primero eliminamos la foreign key existente de id_producto
            $table->dropForeign(['id_producto']);
            
            // Modificamos la columna para que permita NULL
            $table->unsignedBigInteger('id_producto')->nullable()->change();
            
            // Creamos la nueva foreign key con onDelete('set null')
            $table->foreign('id_producto')
                  ->references('id_producto')
                  ->on('producto')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_pedido', function (Blueprint $table) {
            // Eliminamos la foreign key modificada
            $table->dropForeign(['id_producto']);
            
            // Volvemos la columna a NOT NULL
            $table->unsignedBigInteger('id_producto')->nullable(false)->change();
            
            // Recreamos la foreign key original sin onDelete
            $table->foreign('id_producto')
                  ->references('id_producto')
                  ->on('producto');
        });
    }
};