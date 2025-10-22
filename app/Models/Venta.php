<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';
    protected $primaryKey = 'id_venta';
    public $timestamps = false;

    protected $fillable = [
        'fecha',
        'monto_venta',
        'id_pedido'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}
