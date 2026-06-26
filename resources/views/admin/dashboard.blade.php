@extends('layouts.admin')
@section('title', 'Dashboard del Dueño')
@section('breadcrumb', 'Vista ejecutiva — resumen general de su negocio')

@php
    $dinero = fn ($valor) => '$ '.number_format((float) $valor, 2);
    $colores = ['#2563eb', '#22c55e', '#f59e0b', '#8b5cf6', '#06b6d4', '#f43f5e'];
    $segmentos = [];
    $acumulado = 0;

    foreach ($ventasPorSucursal as $indice => $sucursal) {
        $porcentaje = $total > 0 ? ((float) $sucursal->total / $total) * 100 : 0;
        $segmentos[] = $colores[$indice % count($colores)].' '.$acumulado.'% '.($acumulado + $porcentaje).'%';
        $acumulado += $porcentaje;
    }

    $fondoDona = $segmentos ? 'conic-gradient('.implode(', ', $segmentos).')' : '#e2e8f0';
    $etiquetaPeriodo = $desde->isSameMonth($hasta) && $desde->isStartOfMonth()
        ? ucfirst($desde->locale('es')->isoFormat('MMMM YYYY'))
        : $desde->format('d/m/Y').' - '.$hasta->format('d/m/Y');
@endphp

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6" x-data="{ filtros: false }">

    {{-- Header --}}
    <section class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600">Vista ejecutiva</p>
            <h1 class="mt-1 text-2xl sm:text-3xl font-bold text-slate-900">Dashboard del Dueño</h1>
            <p class="mt-1 text-sm text-slate-500">Resumen general de su negocio</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-600 shadow-sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/></svg>
                {{ $desde->format('d/m/Y') }} - {{ $hasta->format('d/m/Y') }}
            </div>
            <button type="button" @click="filtros = !filtros" class="btn-secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 4h18l-7 8v6l-4 2v-8L3 4Z"/></svg>
                Filtros
            </button>
            <a href="{{ route('admin.dashboard', request()->query()) }}" class="btn-secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 11a8 8 0 1 0-2.34 5.66M20 4v7h-7"/></svg>
                Actualizar
            </a>
        </div>
    </section>

    {{-- Filters --}}
    <form x-show="filtros" x-cloak method="GET" action="{{ route('admin.dashboard') }}" class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:grid-cols-2 xl:grid-cols-5">
        <label class="text-sm font-medium text-slate-600">Desde
            <input type="date" name="desde" value="{{ $desde->toDateString() }}" class="mt-1 form-input">
        </label>
        <label class="text-sm font-medium text-slate-600">Hasta
            <input type="date" name="hasta" value="{{ $hasta->toDateString() }}" class="mt-1 form-input">
        </label>
        <label class="text-sm font-medium text-slate-600">Grupo
            <select name="group_id" class="mt-1 form-select">
                <option value="">Todos</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}" @selected(request('group_id') == $grupo->id)>{{ $grupo->name }}</option>
                @endforeach
            </select>
        </label>
        <label class="text-sm font-medium text-slate-600">Sucursal
            <select name="branch_id" class="mt-1 form-select">
                <option value="">Todas</option>
                @foreach($sucursales as $sucursal)
                    <option value="{{ $sucursal->id }}" @selected(request('branch_id') == $sucursal->id)>{{ $sucursal->name }}</option>
                @endforeach
            </select>
        </label>
        <div class="flex items-end">
            <button class="btn-primary w-full justify-center">Aplicar filtros</button>
        </div>
    </form>

    {{-- KPI strip --}}
    <section class="-mx-4 flex gap-4 overflow-x-auto px-4 pb-2 sm:mx-0 sm:grid sm:grid-cols-2 sm:px-0 xl:grid-cols-3 2xl:grid-cols-6">
        <article class="min-w-[235px] flex-none rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:min-w-0 sm:p-5">
            <div class="flex items-start justify-between">
                <span class="rounded-xl bg-emerald-50 p-2.5 text-emerald-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.8" d="M7 17 17 7M7 7h10v10"/></svg></span>
                <span class="text-xs text-slate-400">Hoy</span>
            </div>
            <p class="mt-4 text-xs font-medium text-slate-500">Cobrado del Día</p>
            <p class="mt-1 text-xl font-bold text-slate-900">{{ $dinero($ventasHoy) }}</p>
            <p class="mt-2 text-xs text-slate-400">Actualización cada 30 min</p>
        </article>

        <article class="min-w-[235px] flex-none rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:min-w-0 sm:p-5">
            <div class="flex items-start justify-between">
                <span class="rounded-xl bg-blue-50 p-2.5 text-blue-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.8" d="M5 20V9m7 11V4m7 16v-7"/></svg></span>
                <span class="text-xs text-slate-400">{{ $documentos }} docs.</span>
            </div>
            <p class="mt-4 text-xs font-medium text-slate-500">Facturación del Periodo</p>
            <p class="mt-1 text-xl font-bold text-slate-900">{{ $dinero($total) }}</p>
            <p class="mt-2 text-xs {{ $variacionVentas !== null && $variacionVentas >= 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                {{ $variacionVentas === null ? 'Sin periodo anterior' : (($variacionVentas >= 0 ? '↑ ' : '↓ ').number_format(abs($variacionVentas), 1).'% vs. anterior') }}
            </p>
        </article>

        <article class="min-w-[235px] flex-none rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:min-w-0 sm:p-5">
            <div class="flex items-start justify-between">
                <span class="rounded-xl bg-violet-50 p-2.5 text-violet-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.8" d="M4 17 9 12l4 4 7-9"/></svg></span>
                <span class="text-xs text-slate-400">{{ number_format($margen, 1) }}%</span>
            </div>
            <p class="mt-4 text-xs font-medium text-slate-500">Utilidad Bruta</p>
            <p class="mt-1 text-xl font-bold text-slate-900">{{ $dinero($utilidad) }}</p>
            <p class="mt-2 text-xs {{ $variacionUtilidad !== null && $variacionUtilidad >= 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                {{ $variacionUtilidad === null ? 'Sin periodo anterior' : (($variacionUtilidad >= 0 ? '↑ ' : '↓ ').number_format(abs($variacionUtilidad), 1).'% vs. anterior') }}
            </p>
        </article>

        <article class="min-w-[235px] flex-none rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:min-w-0 sm:p-5 cursor-pointer" onclick="window.location='{{ route('admin.cxc.index') }}'">
            <div class="flex items-start justify-between">
                <span class="rounded-xl bg-amber-50 p-2.5 text-amber-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8" stroke-width="1.8"/><path stroke-width="1.8" d="M12 8v4l3 2"/></svg></span>
            </div>
            <p class="mt-4 text-xs font-medium text-slate-500">Cuentas por Cobrar</p>
            <p class="mt-1 text-xl font-bold text-slate-900">{{ $dinero($stats['total_receivable']) }}</p>
            <p class="mt-2 text-xs text-amber-600">{{ $stats['overdue_clients'] }} clientes vencidos</p>
        </article>

        <article class="min-w-[235px] flex-none rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:min-w-0 sm:p-5 cursor-pointer" onclick="window.location='{{ route('admin.payments.index') }}'">
            <div class="flex items-start justify-between">
                <span class="rounded-xl bg-rose-50 p-2.5 text-rose-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.8" d="M6 3h12v18l-3-2-3 2-3-2-3 2V3Z"/></svg></span>
            </div>
            <p class="mt-4 text-xs font-medium text-slate-500">Pagos Recibidos</p>
            <p class="mt-1 text-xl font-bold text-slate-900">{{ $dinero($collectedPeriod) }}</p>
            <p class="mt-2 text-xs text-rose-500">{{ $stats['pending_payments'] }} pendientes de conciliar</p>
        </article>

        <article class="min-w-[235px] flex-none rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:min-w-0 sm:p-5">
            <div class="flex items-start justify-between">
                <span class="rounded-xl bg-cyan-50 p-2.5 text-cyan-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.8" d="M4 20h16V8l-8-4-8 4v12Zm5 0v-6h6v6"/></svg></span>
            </div>
            <p class="mt-4 text-xs font-medium text-slate-500">Empresas Activas</p>
            <p class="mt-1 text-xl font-bold text-slate-900">{{ $stats['groups'] }}</p>
            <p class="mt-2 text-xs text-emerald-600">{{ $stats['branches'] }} sucursales activas</p>
        </article>
    </section>

    {{-- Charts row --}}
    <section class="grid gap-6 xl:grid-cols-2">
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-slate-900">Facturación por Sucursal</h2>
                    <p class="mt-1 text-xs text-slate-400">{{ $etiquetaPeriodo }}</p>
                </div>
                <span class="rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-500">{{ $ventasPorSucursal->count() }} sucursales</span>
            </div>
            <div class="mt-6 grid gap-8 md:grid-cols-[220px_1fr] md:items-center">
                <div class="relative mx-auto h-48 w-48 rounded-full" style="background: {{ $fondoDona }}">
                    <div class="absolute inset-9 flex flex-col items-center justify-center rounded-full bg-white text-center shadow-inner">
                        <span class="text-xl font-bold text-slate-900">{{ $dinero($total) }}</span>
                        <span class="mt-1 text-xs text-slate-400">Total</span>
                    </div>
                </div>
                <div class="space-y-4">
                    @forelse($ventasPorSucursal as $indice => $sucursal)
                        @php $porcentaje = $total > 0 ? ((float) $sucursal->total / $total) * 100 : 0; @endphp
                        <div class="grid grid-cols-[1fr_auto_auto] items-center gap-3 text-sm">
                            <div class="flex min-w-0 items-center gap-2">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background: {{ $colores[$indice % count($colores)] }}"></span>
                                <span class="truncate text-slate-600">{{ $sucursal->nombre }}</span>
                            </div>
                            <span class="font-semibold text-slate-800">{{ $dinero($sucursal->total) }}</span>
                            <span class="w-12 text-right text-xs text-slate-400">{{ number_format($porcentaje, 1) }}%</span>
                        </div>
                    @empty
                        <div class="rounded-xl bg-slate-50 p-5 text-center text-sm text-slate-500">Aún no hay facturas emitidas para este periodo.</div>
                    @endforelse
                </div>
            </div>
        </article>

        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="font-bold text-slate-900">Top Planes Contratados</h2>
                    <p class="mt-1 text-xs text-slate-400">Ordenado por valor</p>
                </div>
                <span class="rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-500">{{ $etiquetaPeriodo }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-3">Plan / Servicio</th>
                            <th class="px-4 py-3 text-right">Contratos</th>
                            <th class="px-6 py-3 text-right">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($topPlanes as $plan)
                            <tr>
                                <td class="px-6 py-3.5">
                                    <div class="font-medium text-slate-800">{{ $plan->nombre }}</div>
                                    <div class="text-xs text-slate-400">{{ $plan->pro_codigo }}</div>
                                </td>
                                <td class="px-4 py-3.5 text-right text-slate-600">{{ number_format((float) $plan->cantidad, 0) }}</td>
                                <td class="px-6 py-3.5 text-right font-semibold text-slate-800">{{ $dinero($plan->venta) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-slate-500">Los planes aparecerán cuando haya servicios contratados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>

    {{-- Bottom row --}}
    <section class="grid gap-6 xl:grid-cols-[1.15fr_.85fr]">
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-slate-900">Resumen Financiero</h2>
                <span class="rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-500">{{ $etiquetaPeriodo }}</span>
            </div>
            <div class="mt-6 grid gap-8 md:grid-cols-[1fr_230px] md:items-center">
                <dl class="divide-y divide-slate-100 text-sm">
                    <div class="flex justify-between py-3"><dt class="font-medium text-slate-600">Total Facturado</dt><dd class="font-bold text-emerald-600">{{ $dinero($total) }}</dd></div>
                    <div class="flex justify-between py-3"><dt class="font-medium text-slate-600">Total Cobrado</dt><dd class="font-bold text-emerald-600">{{ $dinero($collectedPeriod) }}</dd></div>
                    <div class="flex justify-between py-3"><dt class="font-medium text-slate-600">Por Cobrar</dt><dd class="font-bold text-amber-600">{{ $dinero($stats['total_receivable']) }}</dd></div>
                    <div class="flex justify-between py-3"><dt class="font-medium text-slate-600">Utilidad Bruta</dt><dd class="font-bold text-emerald-600">{{ $dinero($utilidad) }}</dd></div>
                    <div class="flex justify-between py-3"><dt class="font-bold text-slate-800">Facturas a Emitir</dt><dd class="font-medium text-blue-600">{{ $stats['invoices_to_emit'] }} este mes</dd></div>
                </dl>
                <div class="relative mx-auto flex h-44 w-44 items-center justify-center rounded-full" style="background: conic-gradient(#22c55e 0 {{ min(100, max(0, $margen)) }}%, #e2e8f0 {{ min(100, max(0, $margen)) }}% 100%)">
                    <div class="flex h-32 w-32 flex-col items-center justify-center rounded-full bg-white shadow-inner">
                        <span class="text-3xl font-bold text-slate-900">{{ number_format($margen, 1) }}%</span>
                        <span class="mt-1 text-xs text-slate-400">Margen bruto</span>
                    </div>
                </div>
            </div>
        </article>

        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="font-bold text-slate-900">Alertas y Notificaciones</h2>
                    <p class="mt-1 text-xs text-slate-400">Operaciones que requieren atención</p>
                </div>
                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-600">{{ $alertas->count() }}</span>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($alertas as $alerta)
                    @php
                        $clase = match($alerta['tipo']) {
                            'danger' => 'bg-rose-50 text-rose-600',
                            'warning' => 'bg-amber-50 text-amber-600',
                            default => 'bg-blue-50 text-blue-600',
                        };
                    @endphp
                    <div class="flex items-center gap-3 px-6 py-4">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $clase }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 8v4m0 4h.01M4.9 19h14.2a2 2 0 0 0 1.73-3L13.73 4a2 2 0 0 0-3.46 0L3.17 16a2 2 0 0 0 1.73 3Z"/></svg>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-slate-700">{{ $alerta['texto'] }}</p>
                            <p class="mt-0.5 text-xs text-slate-400">{{ $alerta['fecha']->locale('es')->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="m5 12 4 4L19 6"/></svg>
                        </div>
                        <p class="mt-3 text-sm font-medium text-slate-700">Todo está al día</p>
                        <p class="mt-1 text-xs text-slate-400">No hay alertas activas.</p>
                    </div>
                @endforelse
            </div>
        </article>
    </section>

    <footer class="flex flex-col gap-1 border-t border-slate-200 pt-4 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between">
        <span>El dashboard se recarga automáticamente cada 30 minutos.</span>
        <span>Última sincronización: {{ $ultimaSincronizacion ? \Carbon\Carbon::parse($ultimaSincronizacion)->format('d/m/Y H:i') : 'sin datos' }}</span>
    </footer>
</div>

<script>window.setTimeout(() => window.location.reload(), 30 * 60 * 1000);</script>
@endsection
