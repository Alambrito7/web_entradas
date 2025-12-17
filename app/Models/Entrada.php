<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrada extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_fundacion',
        'santo',
        'historia',
        'departamento',
        'fecha_evento',
        'status',
        'latitud',
        'longitud',
        'imagen',
    ];

    protected $casts = [
        'fecha_fundacion' => 'date',
        'fecha_evento' => 'date',
        'latitud' => 'decimal:7',
        'longitud' => 'decimal:7',
    ];

    // Constantes
    public static function getDepartamentos()
    {
        return [
            'La Paz',
            'Cochabamba',
            'Santa Cruz',
            'Oruro',
            'Potosí',
            'Chuquisaca',
            'Tarija',
            'Beni',
            'Pando'
        ];
    }

    public static function getEstados()
    {
        return ['planificada', 'en_curso', 'finalizada'];
    }

    // Accessor para el badge de estado
    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'planificada' => '<span class="badge bg-info">Planificada</span>',
            'en_curso' => '<span class="badge bg-success">En Curso</span>',
            'finalizada' => '<span class="badge bg-secondary">Finalizada</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-dark">Desconocido</span>';
    }

    // Accessor para verificar si tiene ubicación
    public function getTieneUbicacionAttribute()
    {
        return !is_null($this->latitud) && !is_null($this->longitud);
    }

    // Coordenadas por defecto para cada departamento (capitales)
    public static function getCoordenadasPorDefecto($departamento)
    {
        $coordenadas = [
            'La Paz' => ['lat' => -16.5000, 'lng' => -68.1500],
            'Cochabamba' => ['lat' => -17.3935, 'lng' => -66.1570],
            'Santa Cruz' => ['lat' => -17.7833, 'lng' => -63.1821],
            'Oruro' => ['lat' => -17.9833, 'lng' => -67.1250],
            'Potosí' => ['lat' => -19.5836, 'lng' => -65.7531],
            'Chuquisaca' => ['lat' => -19.0333, 'lng' => -65.2627],
            'Tarija' => ['lat' => -21.5355, 'lng' => -64.7296],
            'Beni' => ['lat' => -14.8333, 'lng' => -64.9000],
            'Pando' => ['lat' => -11.0267, 'lng' => -68.7692],
        ];

        return $coordenadas[$departamento] ?? ['lat' => -16.5000, 'lng' => -68.1500];
    }

    // Agregar esta relación en el modelo Entrada
public function fraternidades()
{
    return $this->belongsToMany(Fraternidad::class, 'entrada_fraternidad')
        ->withTimestamps();
}

// Agregar esta relación en el modelo Entrada
public function recorridos()
{
    return $this->hasMany(Recorrido::class);
}
}