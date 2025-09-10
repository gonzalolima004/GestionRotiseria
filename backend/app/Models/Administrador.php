<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Administrador extends Model
{
    protected $table = 'administrador';
    protected $primaryKey = 'id_administrador';
    public $timestamps = false;
   
    protected $fillable = [
        'email_administrador',
        'contrasena_administrador'
    ];
    
    protected $hidden = [
        'contrasena_administrador'
    ];

    protected $casts = [
        'contrasena_administrador' => 'hashed' 
    ];
}
