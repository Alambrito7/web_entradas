<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// ==========================================
// MODELO PRINCIPAL: DANZA
// ==========================================
class Danza extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'categoria',
        'departamento_principal',
        'region_origen',
        'tipo_fecha_origen',
        'siglo_origen',
        'anio_aprox',
        'anio_inicio',
        'anio_fin',
        'fecha_origen',
        'estado_ficha',
        'descripcion_corta',
        'historia_origen',
        'significado_cultural',
        'instrumentos',
        'ritmo_compas',
        'pasos_basicos',
        'formacion',
        'declaratorias',
    ];

    protected $casts = [
        'fecha_origen' => 'date',
    ];

    // Relaciones
    public function personajes()
    {
        return $this->hasMany(DanzaPersonaje::class);
    }

    public function multimedia()
    {
        return $this->hasMany(DanzaMultimedia::class);
    }

    public function documentos()
    {
        return $this->hasMany(DanzaDocumento::class);
    }

    // AGREGAR AQUÍ la relación con fraternidades
    public function fraternidades()
    {
        return $this->hasMany(Fraternidad::class);
    }

    // Accessor para mostrar el origen formateado
    public function getOrigenFormateadoAttribute()
    {
        switch ($this->tipo_fecha_origen) {
            case 'siglo':
                return 'Siglo ' . $this->siglo_origen;
            case 'anio_aprox':
                return 'Aprox. ' . $this->anio_aprox;
            case 'rango':
                return $this->anio_inicio . ' - ' . $this->anio_fin;
            case 'exacta':
                return $this->fecha_origen ? $this->fecha_origen->format('d/m/Y') : 'No especificado';
            default:
                return 'No especificado';
        }
    }

    // Constantes para selects
    public static function getCategorias()
    {
        return [
            'Pesada',
            'Liviana',
            'Autóctona',
            'Mestiza',
            'Ritual',
            'Urbana',
            'Oriental/Amazónica'
        ];
    }

    public static function getDepartamentos()
    {
        return [
            'La Paz',
            'Oruro',
            'Cochabamba',
            'Potosí',
            'Chuquisaca',
            'Tarija',
            'Santa Cruz',
            'Beni',
            'Pando'
        ];
    }

    public static function getEstadosFicha()
    {
        return ['Borrador', 'En revisión', 'Publicada'];
    }
}

// ==========================================
// MODELO: PERSONAJE/ROL
// ==========================================
class DanzaPersonaje extends Model
{
    use HasFactory;

    protected $fillable = [
        'danza_id',
        'nombre',
        'descripcion',
        'foto',
    ];

    public function danza()
    {
        return $this->belongsTo(Danza::class);
    }

    public function vestimentas()
    {
        return $this->hasMany(DanzaVestimenta::class, 'personaje_id');
    }
}

// ==========================================
// MODELO: VESTIMENTA
// ==========================================
class DanzaVestimenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'personaje_id',
        'elemento',
        'descripcion',
        'material',
        'peso',
        'costo',
    ];

    protected $casts = [
        'peso' => 'decimal:2',
        'costo' => 'decimal:2',
    ];

    public function personaje()
    {
        return $this->belongsTo(DanzaPersonaje::class, 'personaje_id');
    }
}

// ==========================================
// MODELO: MULTIMEDIA
// ==========================================
class DanzaMultimedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'danza_id',
        'tipo',
        'archivo',
        'url',
        'titulo',
        'creditos',
    ];

    public function danza()
    {
        return $this->belongsTo(Danza::class);
    }

    public static function getTipos()
    {
        return ['Foto', 'Video'];
    }
}

// ==========================================
// MODELO: DOCUMENTO
// ==========================================
class DanzaDocumento extends Model
{
    use HasFactory;

    protected $fillable = [
        'danza_id',
        'tipo',
        'titulo',
        'descripcion',
        'archivo',
        'fuente',
    ];

    public function danza()
    {
        return $this->belongsTo(Danza::class);
    }

    public static function getTipos()
    {
        return ['PDF', 'Word', 'Libro', 'Resolución', 'Otro'];
    }
}