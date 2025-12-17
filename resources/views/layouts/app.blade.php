<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema de Danzas') }} - @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --navbar-height: 56px;
            --primary-color: #667eea;
            --primary-dark: #764ba2;
        }

        body {
            min-height: 100vh;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1030;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand i {
            font-size: 1.5rem;
        }

        /* Layout con Sidebar */
        .app-wrapper {
            display: flex;
            min-height: calc(100vh - var(--navbar-height));
            padding-top: var(--navbar-height);
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.08);
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            height: calc(100vh - var(--navbar-height));
            overflow-y: auto;
            overflow-x: hidden;
            transition: all 0.3s ease;
            z-index: 1020;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        .sidebar-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border-bottom: 3px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-header h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-header p {
            margin: 0.5rem 0 0 0;
            font-size: 0.85rem;
            opacity: 0.95;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-section-title {
            padding: 0.75rem 1.5rem 0.5rem 1.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 0.5rem;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 0.85rem 1.5rem;
            color: #4b5563;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            position: relative;
        }

        .sidebar-item:hover {
            background: #f3f4f6;
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .sidebar-item.active {
            background: rgba(102, 126, 234, 0.1);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
            font-weight: 600;
        }

        .sidebar-item.active::before {
            content: '';
            position: absolute;
            right: 1rem;
            width: 6px;
            height: 6px;
            background: var(--primary-color);
            border-radius: 50%;
        }

        .sidebar-item i {
            width: 24px;
            font-size: 1.2rem;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .sidebar-item span {
            flex-grow: 1;
        }

        .sidebar-item .badge {
            margin-left: auto;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 2rem;
            min-height: calc(100vh - var(--navbar-height));
        }

        /* Footer */
        footer {
            background: white;
            border-top: 1px solid #e5e7eb;
            margin-left: var(--sidebar-width);
        }

        /* Mobile Toggle Button */
        .sidebar-toggle {
            display: none;
            position: fixed;
            bottom: 20px;
            left: 20px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            z-index: 1025;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
        }

        /* Badge purple personalizado */
        .bg-purple {
            background-color: #9333ea !important;
            color: white !important;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }

            .sidebar.show {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            footer {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar-overlay {
                position: fixed;
                top: var(--navbar-height);
                left: 0;
                width: 100%;
                height: calc(100vh - var(--navbar-height));
                background: rgba(0, 0, 0, 0.5);
                z-index: 1010;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        /* Navbar en móvil */
        @media (max-width: 992px) {
            .navbar .container-fluid {
                max-width: 100%;
            }

            .navbar-collapse {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border-radius: 10px;
                margin-top: 1rem;
                padding: 1rem;
            }
        }

        /* Alertas */
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        /* Cards */
        .card {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: none;
            border-radius: 12px;
        }

        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .required::after {
            content: " *";
            color: #ef4444;
            font-weight: bold;
        }

        /* Scrollbar personalizado para el contenido principal */
        .main-content::-webkit-scrollbar {
            width: 8px;
        }

        .main-content::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .main-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .main-content::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-music-note-beamed"></i>
                <span>Sistema de Danzas</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Registrarse
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->nombre }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}">
                                        <i class="bi bi-person"></i> Mi Perfil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    @auth
    <!-- Sidebar Overlay para móviles -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h5>
                <i class="bi bi-speedometer2"></i>
                Panel de Control
            </h5>
            <p>{{ Auth::user()->nombre }}</p>
        </div>

        <nav class="sidebar-menu">
            <div class="sidebar-section-title">Menú Principal</div>
            
            <a href="{{ route('home') }}" class="sidebar-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i>
                <span>Inicio</span>
            </a>

            <div class="sidebar-section-title">Módulos</div>
            
            <a href="{{ route('danzas.index') }}" class="sidebar-item {{ request()->routeIs('danzas.*') ? 'active' : '' }}">
                <i class="bi bi-music-note-beamed"></i>
                <span>Danzas</span>
                <span class="badge bg-primary">{{ \App\Models\Danza::count() }}</span>
            </a>

            <a href="{{ route('entradas.index') }}" class="sidebar-item {{ request()->routeIs('entradas.*') && !request()->routeIs('entradas.trashed') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i>
                <span>Entradas</span>
                <span class="badge bg-success">{{ \App\Models\Entrada::count() }}</span>
            </a>

            <a href="{{ route('fraternidades.index') }}" class="sidebar-item {{ request()->routeIs('fraternidades.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Fraternidades</span>
                <span class="badge bg-warning text-dark">{{ \App\Models\Fraternidad::count() }}</span>
            </a>

            <a href="{{ route('recorridos.index') }}" class="sidebar-item {{ request()->routeIs('recorridos.*') ? 'active' : '' }}">
                <i class="bi bi-map"></i>
                <span>Recorridos</span>
                <span class="badge bg-info text-dark">{{ \App\Models\Recorrido::count() }}</span>
            </a>

            <a href="{{ route('bloques.index') }}" class="sidebar-item {{ request()->routeIs('bloques.*') && !request()->routeIs('bloques.trashed') ? 'active' : '' }}">
                <i class="bi bi-grid-3x3"></i>
                <span>Bloques</span>
                <span class="badge bg-purple">{{ \App\Models\Bloque::count() }}</span>
            </a>

            <div class="sidebar-section-title">Administración</div>
            
            <a href="{{ route('users.index') }}" class="sidebar-item {{ request()->routeIs('users.index') || request()->routeIs('users.show') || request()->routeIs('users.edit') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Usuarios</span>
                <span class="badge bg-secondary">{{ \App\Models\User::count() }}</span>
            </a>

            <a href="{{ route('roles.index') }}" class="sidebar-item {{ request()->routeIs('roles.*') && !request()->routeIs('roles.trashed') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i>
                <span>Roles</span>
                <span class="badge bg-primary">{{ \App\Models\Role::count() }}</span>
            </a>

            <a href="{{ route('users.trashed') }}" class="sidebar-item {{ request()->routeIs('*.trashed') ? 'active' : '' }}">
                <i class="bi bi-trash"></i>
                <span>Papelera</span>
                @php
                    $totalTrashed = \App\Models\User::onlyTrashed()->count() +
                                   \App\Models\Role::onlyTrashed()->count() +
                                   \App\Models\Danza::onlyTrashed()->count() +
                                   \App\Models\Entrada::onlyTrashed()->count() +
                                   \App\Models\Fraternidad::onlyTrashed()->count() +
                                   \App\Models\Recorrido::onlyTrashed()->count() +
                                   \App\Models\Bloque::onlyTrashed()->count();
                @endphp
                @if($totalTrashed > 0)
                    <span class="badge bg-danger">{{ $totalTrashed }}</span>
                @endif
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

            <a href="{{ route('fraternidades.create') }}" class="sidebar-item">
                <i class="bi bi-person-plus-fill"></i>
                <span>Nueva Fraternidad</span>
            </a>

            <a href="{{ route('recorridos.create') }}" class="sidebar-item">
                <i class="bi bi-map-fill"></i>
                <span>Nuevo Recorrido</span>
            </a>
        </nav>
    </aside>

    <!-- Botón toggle para móviles -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>
    @endauth

    <!-- Wrapper del contenido -->
    <div class="app-wrapper">
        <!-- Main Content -->
        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> <strong>¡Éxito!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <strong>¡Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @auth
    <!-- Footer -->
    <footer class="text-center text-muted py-4">
        <div class="container">
            <p class="mb-0">
                <i class="bi bi-music-note"></i> 
                &copy; {{ date('Y') }} Sistema de Danzas Folclóricas - Bolivia
            </p>
            <p class="mb-0 small text-muted">
                Preservando el patrimonio cultural inmaterial
            </p>
        </div>
    </footer>
    @endauth

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle Sidebar en móviles
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }

            // Cerrar sidebar al hacer clic en un enlace (móviles)
            const sidebarLinks = document.querySelectorAll('.sidebar-item');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 992) {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                    }
                });
            });
        });

        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>

    @yield('scripts')
</body>
</html>