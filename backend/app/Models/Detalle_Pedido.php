<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedido';
    protected $primaryKey = 'id_detalle_pedido';
    public $timestamps = false;

    protected $fillable = [
        'id_pedido',
        'id_producto',
        'cantidad',
        'subtotal'
    ];

    // Relación con pedido
    public function pedido()
    {
        return $this->belongsTo(pedido::class, 'id_pedido');
    }
    public function producto()
    {
        return $this->belongsTo(producto::class, 'id_producto');
    }

    

}
