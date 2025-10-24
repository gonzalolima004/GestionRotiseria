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

    public function getImagenUrlAttribute()
{
    return $this->imagen
        ? asset('storage/' . $this->imagen)
        : null;
}


    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }
}
