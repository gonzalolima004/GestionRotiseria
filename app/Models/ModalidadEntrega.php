<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadEntrega extends Model
{
    protected $table = 'modalidad_entrega';
    protected $primaryKey = 'id_modalidad_entrega';
    public $timestamps = false;

    protected $fillable = ['nombre_modalidad_entrega'];
}
