<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoPedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estado_pedido')->insert([
            ['id_estado_pedido' => 1, 'nombre_estado_pedido' => 'Pendiente'],
            ['id_estado_pedido' => 2, 'nombre_estado_pedido' => 'Confirmado'],
            ['id_estado_pedido' => 3, 'nombre_estado_pedido' => 'Rechazado'],
            ['id_estado_pedido' => 4, 'nombre_estado_pedido' => 'Entregado']
        ]);
    }
}
