<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';
    protected $primaryKey = 'dni_cliente'; // se verifica con el dni
    public $incrementing = false;  
    protected $keyType = 'string'; 
    public $timestamps = false;

    protected $fillable = [
        'dni_cliente',
        'nombre_cliente',
        'telefono_cliente',
        'direccion_cliente'
    ];

    // Relación: un cliente puede tener muchos pedidos
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'dni_cliente');
    }
}
