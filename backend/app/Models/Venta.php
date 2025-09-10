<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';
    protected $primaryKey = 'id_venta';
    public $timestamps = false; // tu tabla no tiene created_at ni updated_at

    protected $fillable = [
        'fecha',
        'monto_venta',
        'id_pedido'
    ];

    // Relación: una venta pertenece a un pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}
