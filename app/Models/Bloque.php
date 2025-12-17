<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bloque extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'fraternidad_id',
        'nombre',
        'fecha_fundacion',
        'lema',
        'integrantes_aproximados',
        'historia',
        'facebook',
        'youtube',
        'foto_principal',
        'estado',
    ];

    protected $casts = [
        'fecha_fundacion' => 'date',
        'integrantes_aproximados' => 'integer',
    ];

    /**
     * Relación con Fraternidad
     */
    public function fraternidad()
    {
        return $this->belongsTo(Fraternidad::class);
    }

    /**
     * Relación con Responsables
     */
    public function responsables()
    {
        return $this->hasMany(BloqueResponsable::class)->orderBy('orden');
    }

    /**
     * Incrementar contador de integrantes
     */
    public function incrementarIntegrantes($cantidad = 1)
    {
        $this->integrantes_aproximados += $cantidad;
        $this->save();
    }

    /**
     * Decrementar contador de integrantes
     */
    public function decrementarIntegrantes($cantidad = 1)
    {
        $this->integrantes_aproximados = max(0, $this->integrantes_aproximados - $cantidad);
        $this->save();
    }

    /**
     * Verificar si está activo
     */
    public function isActivo()
    {
        return $this->estado === 'activo';
    }

    /**
     * Obtener badge de estado
     */
    public function getEstadoBadgeAttribute()
    {
        return $this->estado === 'activo' 
            ? '<span class="badge bg-success">Activo</span>'
            : '<span class="badge bg-secondary">Inactivo</span>';
    }

    /**
     * Obtener estados disponibles
     */
    public static function getEstados()
    {
        return [
            'activo' => 'Activo',
            'inactivo' => 'Inactivo',
        ];
    }

    /**
     * Scope para bloques activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para bloques inactivos
     */
    public function scopeInactivos($query)
    {
        return $query->where('estado', 'inactivo');
    }
}