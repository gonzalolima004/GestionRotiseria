<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModalidadEntregaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('modalidad_entrega')->insert([
            ['id_modalidad_entrega' => 1, 'nombre_modalidad_entrega' => 'Retiro'],
            ['id_modalidad_entrega' => 2, 'nombre_modalidad_entrega' => 'Envío'],
        ]);
    }
}
