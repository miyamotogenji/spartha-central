<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — ASOIINFO Platform</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: Figtree, ui-sans-serif, system-ui, sans-serif; }
        .nav-item { display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; color:#cbd5e1; transition:all .15s; text-decoration:none; }
        .nav-item:hover { background:rgba(37,99,235,.2); }
        .nav-item.active { background:#2563eb; color:#fff; }
        .form-input, .form-select { width:100%; border-radius:8px; border:1px solid #cbd5e1; padding:8px 12px; font-size:.875rem; color:#1e293b; outline:none; }
        .form-input:focus, .form-select:focus { border-color:#3b82f6; box-shadow:0 0 0 1px #3b82f6; }
        .btn-primary { display:inline-flex; align-items:center; gap:8px; border-radius:8px; background:#2563eb; padding:8px 16px; font-size:.875rem; font-weight:600; color:#fff; text-decoration:none; border:none; cursor:pointer; }
        .btn-primary:hover { background:#1d4ed8; }
        .btn-secondary { display:inline-flex; align-items:center; gap:8px; border-radius:12px; border:1px solid #e2e8f0; background:#fff; padding:10px 16px; font-size:.875rem; font-weight:600; color:#334155; box-shadow:0 1px 2px rgba(0,0,0,.05); text-decoration:none; cursor:pointer; }
        .btn-secondary:hover { background:#f8fafc; }
        .btn-danger { display:inline-flex; align-items:center; gap:8px; border-radius:8px; background:#e11d48; padding:8px 16px; font-size:.875rem; font-weight:600; color:#fff; border:none; cursor:pointer; text-decoration:none; }
        .btn-sm { padding:6px 12px; font-size:.75rem; }
        .btn-xs { padding:4px 10px; font-size:.72rem; }
        .page-card { overflow:hidden; border-radius:16px; border:1px solid #e2e8f0; background:#fff; box-shadow:0 1px 2px rgba(0,0,0,.05); }
        .page-card-header { display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #f1f5f9; padding:20px 24px; }
        .data-table { min-width:100%; font-size:.875rem; border-collapse:collapse; }
        .data-table thead { background:#f8fafc; text-align:left; font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; color:#94a3b8; }
        .data-table th { padding:12px 24px; }
        .data-table td { padding:14px 24px; color:#475569; border-bottom:1px solid #f1f5f9; }
        .badge { display:inline-flex; align-items:center; border-radius:9999px; padding:4px 10px; font-size:.75rem; font-weight:600; }
        .badge-green { background:#ecfdf5; color:#047857; }
        .badge-red { background:#fff1f2; color:#be123c; }
        .badge-yellow { background:#fffbeb; color:#b45309; }
        .badge-blue { background:#eff6ff; color:#1d4ed8; }
        .badge-gray { background:#f1f5f9; color:#475569; }
        .badge-orange { background:#fff7ed; color:#c2410c; }
        .stat-card { border-radius:16px; border:1px solid #e2e8f0; background:#fff; padding:20px; box-shadow:0 1px 2px rgba(0,0,0,.05); }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-slate-100" x-data="{ mobileMenuOpen: false, userMenuOpen: false }">

<div class="min-h-screen flex">
    {{-- Sidebar desktop --}}
    <aside class="hidden lg:flex fixed left-0 top-0 h-screen w-72 flex-col bg-slate-950 text-white shadow-2xl z-40">
        <div class="px-6 py-6 border-b border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <div>
                    <div class="text-lg font-bold leading-tight">ASOIINFO</div>
                    <div class="text-xs text-slate-400">Platform Multiempresa</div>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-6 text-sm overflow-y-auto">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">🏠 <span>Dashboard</span></a>
            </div>

            <div>
                <p class="px-4 mb-2 text-xs uppercase tracking-wider text-slate-500">CRM</p>
                <a href="{{ route('admin.grupos.index') }}" class="nav-item {{ request()->routeIs('admin.grupos.*') ? 'active' : '' }}">🏢 <span>Grupos Empresariales</span></a>
                <a href="{{ route('admin.empresas.index') }}" class="nav-item {{ request()->routeIs('admin.empresas.*') ? 'active' : '' }}">🏛 <span>Empresas Legales</span></a>
                <a href="{{ route('admin.sucursales.index') }}" class="nav-item {{ request()->routeIs('admin.sucursales.*') ? 'active' : '' }}">🏪 <span>Sucursales</span></a>
                <a href="{{ route('admin.contactos.index') }}" class="nav-item {{ request()->routeIs('admin.contactos.*') ? 'active' : '' }}">👥 <span>Contactos</span></a>
            </div>

            <div>
                <p class="px-4 mb-2 text-xs uppercase tracking-wider text-slate-500">Planes & Servicios</p>
                <a href="{{ route('admin.planes.index') }}" class="nav-item {{ request()->routeIs('admin.planes.*') ? 'active' : '' }}">📋 <span>Planes</span></a>
                <a href="{{ route('admin.servicios.index') }}" class="nav-item {{ request()->routeIs('admin.servicios.*') ? 'active' : '' }}">⚡ <span>Servicios Contratados</span></a>
            </div>

            <div>
                <p class="px-4 mb-2 text-xs uppercase tracking-wider text-slate-500">Facturación</p>
                <a href="{{ route('admin.invoices.to-emit') }}" class="nav-item {{ request()->routeIs('admin.invoices.to-emit') ? 'active' : '' }}">✅ <span>Facturas a Emitir</span></a>
                <a href="{{ route('admin.invoices.index') }}" class="nav-item {{ request()->routeIs('admin.invoices.index','admin.invoices.show') ? 'active' : '' }}">🧾 <span>Facturas Emitidas</span></a>
                <a href="{{ route('admin.cxc.index') }}" class="nav-item {{ request()->routeIs('admin.cxc.*') ? 'active' : '' }}">💰 <span>Cuentas x Cobrar</span></a>
                <a href="{{ route('admin.payments.index') }}" class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">💳 <span>Pagos Recibidos</span></a>
            </div>

            <div>
                <p class="px-4 mb-2 text-xs uppercase tracking-wider text-slate-500">Operaciones</p>
                <a href="{{ route('admin.reports.financial') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">📊 <span>Reportes</span></a>
                <a href="{{ route('admin.audit.index') }}" class="nav-item {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">📝 <span>Auditoría</span></a>
            </div>

            <div>
                <p class="px-4 mb-2 text-xs uppercase tracking-wider text-slate-500">Administración</p>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">👤 <span>Usuarios</span></a>
                <a href="{{ route('admin.two-factor.show') }}" class="nav-item {{ request()->routeIs('admin.two-factor.*') ? 'active' : '' }}">🔐 <span>Seguridad 2FA</span></a>
            </div>
        </nav>

        <div class="px-6 py-5 border-t border-slate-800 text-sm">
            <div class="font-semibold">{{ auth()->user()->name }}</div>
            <div class="text-xs text-slate-400">{{ auth()->user()->email }}</div>
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button class="w-full rounded-xl bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Salir</button>
            </form>
        </div>
    </aside>

    {{-- Mobile sidebar --}}
    <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
        <div class="absolute inset-0 bg-slate-950/60" @click="mobileMenuOpen = false"></div>
        <aside class="relative flex h-full w-80 max-w-[86vw] flex-col bg-slate-950 text-white shadow-2xl overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-800 px-5 py-5">
                <div class="text-base font-bold">ASOIINFO Platform</div>
                <button type="button" @click="mobileMenuOpen = false" class="rounded-xl border border-slate-700 px-3 py-2 text-sm">Cerrar</button>
            </div>
            <nav class="px-4 py-5 space-y-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" @click="mobileMenuOpen=false" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.grupos.index') }}" @click="mobileMenuOpen=false" class="nav-item">Grupos</a>
                <a href="{{ route('admin.cxc.index') }}" @click="mobileMenuOpen=false" class="nav-item">CxC</a>
                <a href="{{ route('admin.invoices.to-emit') }}" @click="mobileMenuOpen=false" class="nav-item">Facturas</a>
            </nav>
        </aside>
    </div>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0 lg:ml-72 h-screen overflow-hidden">
        <header class="min-h-20 bg-white border-b border-slate-200 flex items-center justify-between gap-3 px-4 py-3 shadow-sm sm:px-6">
            <div class="flex min-w-0 items-center gap-3">
                <button type="button" @click="mobileMenuOpen = true" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm lg:hidden">Menú</button>
                <div class="min-w-0">
                    <h2 class="text-lg font-bold text-slate-800 sm:text-xl">@yield('title', 'Dashboard')</h2>
                    <p class="text-xs text-slate-500 sm:text-sm">@yield('breadcrumb', 'Panel administrativo ASOIINFO')</p>
                </div>
            </div>
            <div class="flex shrink-0 items-center gap-3">
                <div class="hidden md:block text-right">
                    <div class="text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-500">{{ auth()->user()->email }}</div>
                </div>
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </header>

        @if(session('success'))
            <div class="mx-4 mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 sm:mx-6">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mx-4 mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 sm:mx-6">{{ session('error') }}</div>
        @endif

        <main class="flex-1 bg-slate-100 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
