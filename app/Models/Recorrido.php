<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recorrido extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'entrada_id',
        'nombre',
        'descripcion',
    ];

    // Relaciones
    public function entrada()
    {
        return $this->belongsTo(Entrada::class);
    }

    public function puntos()
    {
        return $this->hasMany(RecorridoPunto::class)->orderBy('orden');
    }

    // Accessor para obtener el total de puntos
    public function getTotalPuntosAttribute()
    {
        return $this->puntos()->count();
    }

    // Calcular distancia total aproximada (en km)
    public function getDistanciaAproximadaAttribute()
    {
        $puntos = $this->puntos;
        if ($puntos->count() < 2) {
            return 0;
        }

        $distanciaTotal = 0;
        for ($i = 0; $i < $puntos->count() - 1; $i++) {
            $p1 = $puntos[$i];
            $p2 = $puntos[$i + 1];
            $distanciaTotal += $this->calcularDistancia(
                $p1->latitud, 
                $p1->longitud, 
                $p2->latitud, 
                $p2->longitud
            );
        }

        return round($distanciaTotal, 2);
    }

    // Fórmula de Haversine para calcular distancia entre dos puntos
    private function calcularDistancia($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radio de la Tierra en km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}