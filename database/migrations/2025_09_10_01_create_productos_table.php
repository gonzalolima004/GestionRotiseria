<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->id('id_producto');
            $table->string('nombre_producto', 100);
            $table->string('descripcion_producto', 255)->nullable();
            $table->decimal('precio_producto', 10, 2);
            $table->boolean('disponible')->default(true);

            $table->unsignedBigInteger('id_categoria');

            $table->foreign('id_categoria')->references('id_categoria')->on('categoria');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};
