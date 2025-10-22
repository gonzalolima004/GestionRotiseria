<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MetodoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('metodo_pago')->insert([
            ['id_metodo_pago' => 1, 'nombre_metodo_pago' => 'Efectivo'],
            ['id_metodo_pago' => 2, 'nombre_metodo_pago' => 'Transferencia'],
            ['id_metodo_pago' => 3, 'nombre_metodo_pago' => 'Otro'],
        ]);
    }
}
