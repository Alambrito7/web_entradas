<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fraternidad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fraternidades';

    protected $fillable = [
        'nombre',
        'fecha_fundacion',
        'descripcion',
        'danza_id',
        'lema',
        'telefono',
        'correo_electronico',
    ];

    protected $casts = [
        'fecha_fundacion' => 'date',
    ];

    // Relaciones
    public function danza()
    {
        return $this->belongsTo(Danza::class);
    }

    public function pasantes()
    {
        return $this->hasMany(FraternidadPasante::class)->orderBy('orden');
    }

    public function entradas()
    {
        return $this->belongsToMany(Entrada::class, 'entrada_fraternidad')
            ->withTimestamps();
    }

    // Accessor para años de fundación
    public function getAniosFundacionAttribute()
    {
        if (!$this->fecha_fundacion) {
            return null;
        }
        return $this->fecha_fundacion->age;
    }
}