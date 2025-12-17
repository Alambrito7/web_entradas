<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Danza;
use App\Models\Entrada;
use App\Models\Fraternidad;
use App\Models\Recorrido;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_danzas' => Danza::count(),
            'total_entradas' => Entrada::count(),
            'total_fraternidades' => Fraternidad::count(),
            'total_recorridos' => Recorrido::count(),
            'total_usuarios' => User::count(),
        ];

        // Danzas por categoría
        $danzasPorCategoria = Danza::select('categoria', DB::raw('count(*) as total'))
            ->groupBy('categoria')
            ->orderBy('total', 'desc')
            ->get();

        // Danzas por departamento
        $danzasPorDepartamento = Danza::select('departamento_principal', DB::raw('count(*) as total'))
            ->groupBy('departamento_principal')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Entradas por estado
        $entradasPorEstado = Entrada::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Entradas por departamento
        $entradasPorDepartamento = Entrada::select('departamento', DB::raw('count(*) as total'))
            ->groupBy('departamento')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Últimas danzas agregadas
        $ultimasDanzas = Danza::latest()->limit(5)->get();

        // Últimas entradas agregadas
        $ultimasEntradas = Entrada::latest()->limit(5)->get();

        // Actividad reciente (últimos registros)
        $actividadReciente = collect([
            ...Danza::latest()->limit(3)->get()->map(function($danza) {
                return [
                    'tipo' => 'danza',
                    'titulo' => $danza->nombre,
                    'icono' => 'music-note-beamed',
                    'color' => 'primary',
                    'fecha' => $danza->created_at,
                    'url' => route('danzas.show', $danza)
                ];
            }),
            ...Entrada::latest()->limit(3)->get()->map(function($entrada) {
                return [
                    'tipo' => 'entrada',
                    'titulo' => $entrada->nombre,
                    'icono' => 'calendar-event',
                    'color' => 'success',
                    'fecha' => $entrada->created_at,
                    'url' => route('entradas.show', $entrada)
                ];
            }),
            ...Fraternidad::latest()->limit(3)->get()->map(function($fraternidad) {
                return [
                    'tipo' => 'fraternidad',
                    'titulo' => $fraternidad->nombre,
                    'icono' => 'people-fill',
                    'color' => 'warning',
                    'fecha' => $fraternidad->created_at,
                    'url' => route('fraternidades.show', $fraternidad)
                ];
            }),
        ])->sortByDesc('fecha')->take(10);

        return view('home', compact(
            'stats',
            'danzasPorCategoria',
            'danzasPorDepartamento',
            'entradasPorEstado',
            'entradasPorDepartamento',
            'ultimasDanzas',
            'ultimasEntradas',
            'actividadReciente'
        ));
    }
}