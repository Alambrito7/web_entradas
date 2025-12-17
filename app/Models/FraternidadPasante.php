<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraternidadPasante extends Model
{
    use HasFactory;

    protected $fillable = [
        'fraternidad_id',
        'nombre',
        'orden',
    ];

    public function fraternidad()
    {
        return $this->belongsTo(Fraternidad::class);
    }
}