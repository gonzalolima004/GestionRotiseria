<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedido'; 
    protected $primaryKey = 'id_pedido';
    public $timestamps = false;

    protected $fillable = [
        'fecha_hora',
        'monto_total',
        'dni_cliente',
        'id_metodo_pago',
        'id_estado_pedido',
        'id_modalidad_entrega',
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class, 'dni_cliente');
    }

    public function metodoPago() {
        return $this->belongsTo(MetodoPago::class, 'id_metodo_pago');
    }

    public function estado() {
        return $this->belongsTo(EstadoPedido::class, 'id_estado_pedido');
    }

    public function modalidad() {
        return $this->belongsTo(ModalidadEntrega::class, 'id_modalidad_entrega');
    }

    public function detalles() {
        return $this->hasMany(DetallePedido::class, 'id_pedido', 'id_pedido');
    }
}
