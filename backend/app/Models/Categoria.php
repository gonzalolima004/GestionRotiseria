<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'Categoria'; // nombre exacto de tu tabla
    protected $primaryKey = 'id_categoria'; // clave primaria personalizada
    public $timestamps = false; // tu tabla no tiene created_at ni updated_at

    protected $fillable = ['nombre_categoria'];

    // Relación con productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_categoria', 'id_categoria');
    }
}
