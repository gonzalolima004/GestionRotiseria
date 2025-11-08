<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    protected $table = 'producto'; 
    protected $primaryKey = 'id_producto';
    public $timestamps = false;
    
    protected $fillable = [
        'nombre_producto',
        'descripcion_producto',
        'precio_producto',
        'disponible',
        'id_categoria',
        'imagen'
    ];

    protected $appends = ['imagen_url'];

    /**
     * Accessor para obtener la URL completa de la imagen
     */
    public function getImagenUrlAttribute()
    {
        return $this->imagen
            ? asset('storage/' . $this->imagen)
            : null;
    }

    /**
     * Relación con la tabla Categoria
     * El segundo parámetro es la clave foránea en la tabla productos
     * El tercer parámetro es la clave primaria en la tabla categorias
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    /**
     * Cast de atributos
     * Asegura que disponible sea siempre un entero
     */
    protected $casts = [
        'precio_producto' => 'decimal:2',
        'disponible' => 'integer',
        'id_categoria' => 'integer'
    ];
}