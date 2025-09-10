<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administrador', function (Blueprint $table) {
            $table->id('id_administrador');
            $table->string('email_administrador', 50)->unique();
            $table->string('contrasena_administrador', 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administrador');
    }
};
