<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedido';
    protected $primaryKey = 'id_detalle';
    public $timestamps = false;

    protected $fillable = [
        'id_pedido',
        'id_producto',
        'cantidad',
        'subtotal'
    ];

    // Agregar atributo para manejar productos eliminados
    protected $appends = ['nombre_producto_display'];

    /**
     * Relación con Producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    /**
     * Relación con Pedido
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    /**
     * Accessor para mostrar el nombre del producto o "Producto eliminado"
     */
    public function getNombreProductoDisplayAttribute()
    {
        if ($this->id_producto === null || $this->producto === null) {
            return 'Producto eliminado';
        }
        return $this->producto->nombre_producto;
    }

    /**
     * Accessor para obtener el precio del producto o un valor por defecto
     */
    public function getPrecioProductoDisplayAttribute()
    {
        if ($this->id_producto === null || $this->producto === null) {
            return 0;
        }
        return $this->producto->precio_producto;
    }
}