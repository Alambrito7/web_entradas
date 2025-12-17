<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloqueResponsable extends Model
{
    use HasFactory;

    protected $table = 'bloque_responsables';

    protected $fillable = [
        'bloque_id',
        'nombre',
        'telefono',
        'email',
        'orden',
    ];

    /**
     * Relación con Bloque
     */
    public function bloque()
    {
        return $this->belongsTo(Bloque::class);
    }

    /**
     * Obtener nombre con contacto
     */
    public function getNombreCompletoAttribute()
    {
        $info = $this->nombre;
        
        if ($this->telefono) {
            $info .= " - Tel: {$this->telefono}";
        }
        
        if ($this->email) {
            $info .= " - Email: {$this->email}";
        }
        
        return $info;
    }
}