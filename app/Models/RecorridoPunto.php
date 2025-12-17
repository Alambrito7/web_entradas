<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecorridoPunto extends Model
{
    use HasFactory;

    protected $fillable = [
        'recorrido_id',
        'nombre',
        'descripcion',
        'latitud',
        'longitud',
        'orden',
    ];

    protected $casts = [
        'latitud' => 'decimal:7',
        'longitud' => 'decimal:7',
    ];

    public function recorrido()
    {
        return $this->belongsTo(Recorrido::class);
    }
}