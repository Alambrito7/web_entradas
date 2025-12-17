@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<style>
    body {
        background: #f8f9fa;
    }

    .dashboard-wrapper {
        display: flex;
        min-height: calc(100vh - 56px);
    }

    /* Sidebar */
    .sidebar {
        width: 280px;
        background: white;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        position: fixed;
        height: calc(100vh - 56px);
        overflow-y: auto;
        z-index: 100;
    }

    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: #667eea;
        border-radius: 3px;
    }

    .sidebar-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .sidebar-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .sidebar-header p {
        margin: 0;
        font-size: 0.85rem;
        opacity: 0.9;
    }

    .sidebar-menu {
        padding: 1rem 0;
    }

    .sidebar-section-title {
        padding: 0.75rem 1.5rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .sidebar-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .sidebar-item:hover {
        background: #f3f4f6;
        color: #667eea;
        border-left-color: #667eea;
    }

    .sidebar-item.active {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        border-left-color: #667eea;
        font-weight: 600;
    }

    .sidebar-item i {
        width: 24px;
        font-size: 1.2rem;
        margin-right: 0.75rem;
    }

    .sidebar-item .badge {
        margin-left: auto;
    }

    /* Main Content */
    .main-content {
        margin-left: 280px;
        flex: 1;
        padding: 2rem;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .dashboard-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .dashboard-header p {
        font-size: 1.1rem;
        opacity: 0.95;
        margin-bottom: 0;
    }

    .dashboard-date {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 10px;
        display: inline-block;
        font-weight: 600;
        margin-top: 1rem;
    }

    /* Stat Cards */
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
    }

    .stat-card.card-primary {
        --card-color: #667eea;
        --card-color-light: #764ba2;
    }

    .stat-card.card-success {
        --card-color: #10b981;
        --card-color-light: #059669;
    }

    .stat-card.card-warning {
        --card-color: #f59e0b;
        --card-color-light: #d97706;
    }

    .stat-card.card-info {
        --card-color: #06b6d4;
        --card-color-light: #0891b2;
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, var(--card-color), var(--card-color-light));
        color: white;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, var(--card-color), var(--card-color-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        color: #6b7280;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .stat-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--card-color);
        font-weight: 600;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .stat-link:hover {
        gap: 0.75rem;
    }

    /* Chart Cards */
    .chart-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .chart-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .chart-card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .chart-card-title i {
        font-size: 1.5rem;
    }

    /* Activity Items */
    .activity-item {
        background: #f9fafb;
        border-radius: 15px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        text-decoration: none;
        border: 2px solid transparent;
    }

    .activity-item:hover {
        background: white;
        border-color: #e5e7eb;
        transform: translateX(8px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .activity-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .activity-icon.bg-primary-soft {
        background: rgba(102, 126, 234, 0.15);
        color: #667eea;
    }

    .activity-icon.bg-success-soft {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
    }

    .activity-icon.bg-warning-soft {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
    }

    .activity-content {
        flex-grow: 1;
    }

    .activity-title {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }

    .activity-time {
        font-size: 0.85rem;
        color: #6b7280;
    }

    /* Quick Actions */
    .quick-action {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        padding: 2rem 1rem;
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
        height: 100%;
    }

    .quick-action:hover {
        border-color: #667eea;
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(102, 126, 234, 0.2);
    }

    .quick-action-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        display: block;
    }

    .quick-action-title {
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        font-size: 1rem;
    }

    /* List Items */
    .list-item {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.2s ease;
    }

    .list-item:hover {
        background: #f9fafb;
    }

    .list-item:last-child {
        border-bottom: none;
    }

    .list-item-title {
        font-weight: 600;
        color: #1f2937;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .list-item-title:hover {
        color: #667eea;
    }

    .list-item-subtitle {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }

    .badge-custom {
        padding: 0.4rem 0.85rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.75rem;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .sidebar {
            margin-left: -280px;
            transition: margin-left 0.3s ease;
        }

        .sidebar.show {
            margin-left: 0;
        }

        .main-content {
            margin-left: 0;
        }

        .dashboard-header h1 {
            font-size: 2rem;
        }
    }

    /* Empty States */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        margin: 0;
        font-size: 0.95rem;
    }
</style>
@endsection

@section('content')
<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h5><i class="bi bi-speedometer2"></i> Panel de Control</h5>
            <p>{{ Auth::user()->name }}</p>
        </div>

        <nav class="sidebar-menu">
            <div class="sidebar-section-title">Menú Principal</div>
            
            <a href="{{ route('home') }}" class="sidebar-item active">
                <i class="bi bi-house-fill"></i>
                <span>Inicio</span>
            </a>

            <div class="sidebar-section-title">Módulos</div>
            
            <a href="{{ route('danzas.index') }}" class="sidebar-item">
                <i class="bi bi-music-note-beamed"></i>
                <span>Danzas</span>
                <span class="badge bg-primary">{{ $stats['total_danzas'] }}</span>
            </a>

            <a href="{{ route('entradas.index') }}" class="sidebar-item">
                <i class="bi bi-calendar-event"></i>
                <span>Entradas</span>
                <span class="badge bg-success">{{ $stats['total_entradas'] }}</span>
            </a>

            <a href="{{ route('fraternidades.index') }}" class="sidebar-item">
                <i class="bi bi-people-fill"></i>
                <span>Fraternidades</span>
                <span class="badge bg-warning">{{ $stats['total_fraternidades'] }}</span>
            </a>

            <a href="{{ route('recorridos.index') }}" class="sidebar-item">
                <i class="bi bi-map"></i>
                <span>Recorridos</span>
                <span class="badge bg-info">{{ $stats['total_recorridos'] }}</span>
            </a>

            <div class="sidebar-section-title">Administración</div>
            
            <a href="{{ route('users.index') }}" class="sidebar-item">
                <i class="bi bi-people"></i>
                <span>Usuarios</span>
                <span class="badge bg-secondary">{{ $stats['total_usuarios'] }}</span>
            </a>

            <a href="{{ route('danzas.trashed') }}" class="sidebar-item">
                <i class="bi bi-trash"></i>
                <span>Papelera</span>
            </a>

            <div class="sidebar-section-title">Acciones Rápidas</div>
            
            <a href="{{ route('danzas.create') }}" class="sidebar-item">
                <i class="bi bi-plus-circle"></i>
                <span>Nueva Danza</span>
            </a>

            <a href="{{ route('entradas.create') }}" class="sidebar-item">
                <i class="bi bi-calendar-plus"></i>
                <span>Nueva Entrada</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <div class="dashboard-header">
            <h1><i class="bi bi-speedometer2"></i> Panel de Control</h1>
            <p>Sistema de Gestión de Danzas Folclóricas de Bolivia</p>
            <div class="dashboard-date">
                <i class="bi bi-calendar3"></i> {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
            </div>
        </div>

        <!-- Estadísticas Principales -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card card-primary">
                    <div class="stat-icon">
                        <i class="bi bi-music-note-beamed"></i>
                    </div>
                    <div class="stat-number">{{ $stats['total_danzas'] }}</div>
                    <div class="stat-label">Danzas Registradas</div>
                    <a href="{{ route('danzas.index') }}" class="stat-link">
                        Ver todas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stat-card card-success">
                    <div class="stat-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="stat-number">{{ $stats['total_entradas'] }}</div>
                    <div class="stat-label">Entradas Folclóricas</div>
                    <a href="{{ route('entradas.index') }}" class="stat-link">
                        Ver todas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stat-card card-warning">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-number">{{ $stats['total_fraternidades'] }}</div>
                    <div class="stat-label">Fraternidades</div>
                    <a href="{{ route('fraternidades.index') }}" class="stat-link">
                        Ver todas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stat-card card-info">
                    <div class="stat-icon">
                        <i class="bi bi-map"></i>
                    </div>
                    <div class="stat-number">{{ $stats['total_recorridos'] }}</div>
                    <div class="stat-label">Recorridos</div>
                    <a href="{{ route('recorridos.index') }}" class="stat-link">
                        Ver todos <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Columna Izquierda (Gráficos) -->
            <div class="col-lg-8">
                <!-- Gráfico de Danzas por Categoría -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h5 class="chart-card-title">
                            <i class="bi bi-bar-chart-fill text-primary"></i>
                            Danzas por Categoría
                        </h5>
                    </div>
                    <canvas id="danzasCategoriaChart" height="80"></canvas>
                </div>

                <!-- Gráfico de Entradas por Estado -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h5 class="chart-card-title">
                            <i class="bi bi-pie-chart-fill text-success"></i>
                            Estado de las Entradas
                        </h5>
                    </div>
                    <canvas id="entradasEstadoChart" height="80"></canvas>
                </div>

                <!-- Acciones Rápidas -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h5 class="chart-card-title">
                            <i class="bi bi-lightning-fill text-warning"></i>
                            Acciones Rápidas
                        </h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="{{ route('danzas.create') }}" class="quick-action">
                                <span class="quick-action-icon text-primary">
                                    <i class="bi bi-plus-circle-fill"></i>
                                </span>
                                <p class="quick-action-title">Nueva Danza</p>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('entradas.create') }}" class="quick-action">
                                <span class="quick-action-icon text-success">
                                    <i class="bi bi-calendar-plus-fill"></i>
                                </span>
                                <p class="quick-action-title">Nueva Entrada</p>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('fraternidades.create') }}" class="quick-action">
                                <span class="quick-action-icon text-warning">
                                    <i class="bi bi-people-fill"></i>
                                </span>
                                <p class="quick-action-title">Nueva Fraternidad</p>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('recorridos.create') }}" class="quick-action">
                                <span class="quick-action-icon text-info">
                                    <i class="bi bi-map-fill"></i>
                                </span>
                                <p class="quick-action-title">Nuevo Recorrido</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha (Actividad y Listas) -->
            <div class="col-lg-4">
                <!-- Actividad Reciente -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h5 class="chart-card-title">
                            <i class="bi bi-clock-history"></i>
                            Actividad Reciente
                        </h5>
                    </div>
                    <div class="activity-list">
                        @forelse($actividadReciente as $actividad)
                            <a href="{{ $actividad['url'] }}" class="activity-item">
                                <div class="activity-icon bg-{{ $actividad['color'] }}-soft">
                                    <i class="bi bi-{{ $actividad['icono'] }}"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">{{ $actividad['titulo'] }}</div>
                                    <div class="activity-time">
                                        {{ $actividad['fecha']->locale('es')->diffForHumans() }}
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p>No hay actividad reciente</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Últimas Danzas -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h5 class="chart-card-title">
                            <i class="bi bi-star-fill text-warning"></i>
                            Últimas Danzas
                        </h5>
                    </div>
                    @forelse($ultimasDanzas as $danza)
                        <div class="list-item">
                            <div>
                                <a href="{{ route('danzas.show', $danza) }}" class="list-item-title">
                                    {{ $danza->nombre }}
                                </a>
                                <div class="list-item-subtitle">
                                    <i class="bi bi-geo-alt"></i> {{ $danza->departamento_principal }}
                                </div>
                            </div>
                            <span class="badge-custom bg-primary text-white">
                                {{ $danza->categoria }}
                            </span>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-music-note"></i>
                            <p>No hay danzas registradas</p>
                        </div>
                    @endforelse
                </div>

                <!-- Próximas Entradas -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <h5 class="chart-card-title">
                            <i class="bi bi-calendar-check text-success"></i>
                            Próximas Entradas
                        </h5>
                    </div>
                    @forelse($ultimasEntradas as $entrada)
                        <div class="list-item">
                            <div>
                                <a href="{{ route('entradas.show', $entrada) }}" class="list-item-title">
                                    {{ $entrada->nombre }}
                                </a>
                                <div class="list-item-subtitle">
                                    <i class="bi bi-calendar3"></i> {{ $entrada->fecha_evento->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                                </div>
                            </div>
                            {!! $entrada->estado_badge !!}
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-calendar-x"></i>
                            <p>No hay entradas registradas</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configuración de Chart.js en español
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#6b7280';

    // Gráfico de Danzas por Categoría
    const ctxCategoria = document.getElementById('danzasCategoriaChart').getContext('2d');
    const danzasCategoriaChart = new Chart(ctxCategoria, {
        type: 'bar',
        data: {
            labels: {!! json_encode($danzasPorCategoria->pluck('categoria')) !!},
            datasets: [{
                label: 'Número de Danzas',
                data: {!! json_encode($danzasPorCategoria->pluck('total')) !!},
                backgroundColor: [
                    'rgba(102, 126, 234, 0.9)',
                    'rgba(118, 75, 162, 0.9)',
                    'rgba(16, 185, 129, 0.9)',
                    'rgba(245, 158, 11, 0.9)',
                    'rgba(6, 182, 212, 0.9)',
                    'rgba(239, 68, 68, 0.9)',
                    'rgba(139, 92, 246, 0.9)',
                ],
                borderRadius: 10,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Gráfico de Entradas por Estado
    const ctxEstado = document.getElementById('entradasEstadoChart').getContext('2d');
    
    const estadosTraducidos = {!! json_encode($entradasPorEstado->pluck('status')) !!}.map(status => {
        const traducciones = {
            'planificada': 'Planificada',
            'en_curso': 'En Curso',
            'finalizada': 'Finalizada'
        };
        return traducciones[status] || status;
    });

    const entradasEstadoChart = new Chart(ctxEstado, {
        type: 'doughnut',
        data: {
            labels: estadosTraducidos,
            datasets: [{
                data: {!! json_encode($entradasPorEstado->pluck('total')) !!},
                backgroundColor: [
                    'rgba(6, 182, 212, 0.9)',
                    'rgba(16, 185, 129, 0.9)',
                    'rgba(107, 114, 128, 0.9)',
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 13,
                            weight: '600'
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    cornerRadius: 8
                }
            }
        }
    });
</script>
@endsection