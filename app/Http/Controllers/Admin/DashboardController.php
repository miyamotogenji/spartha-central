<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountReceivable;
use App\Models\Branch;
use App\Models\BusinessGroup;
use App\Models\ContractedService;
use App\Models\Conversation;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $desde = $request->filled('desde')
            ? Carbon::parse($request->desde)->startOfDay()
            : now()->startOfMonth();
        $hasta = $request->filled('hasta')
            ? Carbon::parse($request->hasta)->endOfDay()
            : now()->endOfMonth();

        if ($desde->gt($hasta)) {
            [$desde, $hasta] = [$hasta->copy()->startOfDay(), $desde->copy()->endOfDay()];
        }

        $periodDays = max(1, $desde->diffInDays($hasta) + 1);
        $prevHasta = $desde->copy()->subDay()->endOfDay();
        $prevDesde = $prevHasta->copy()->subDays($periodDays - 1)->startOfDay();

        $invoicesQuery = Invoice::query()
            ->when($request->group_id, fn ($q, $id) => $q->where('business_group_id', $id))
            ->when($request->branch_id, fn ($q, $id) => $q->where('branch_id', $id));

        $total = (float) (clone $invoicesQuery)
            ->whereBetween('issue_date', [$desde, $hasta])
            ->whereNotIn('status', ['cancelled', 'excluded'])
            ->sum('total');

        $documentos = (clone $invoicesQuery)
            ->whereBetween('issue_date', [$desde, $hasta])
            ->whereNotIn('status', ['cancelled', 'excluded'])
            ->count();

        $totalAnterior = (float) Invoice::query()
            ->when($request->group_id, fn ($q, $id) => $q->where('business_group_id', $id))
            ->when($request->branch_id, fn ($q, $id) => $q->where('branch_id', $id))
            ->whereBetween('issue_date', [$prevDesde, $prevHasta])
            ->whereNotIn('status', ['cancelled', 'excluded'])
            ->sum('total');

        $variacionVentas = $totalAnterior > 0
            ? (($total - $totalAnterior) / $totalAnterior) * 100
            : null;

        $ventasHoy = (float) Payment::where('status', 'approved')
            ->whereDate('payment_date', today())
            ->sum('amount');

        $collectedPeriod = (float) Payment::where('status', 'approved')
            ->whereBetween('payment_date', [$desde, $hasta])
            ->sum('amount');

        $costo = round($total * 0.62, 2);
        $utilidad = max(0, $total - $costo);
        $margen = $total > 0 ? ($utilidad / $total) * 100 : 0;

        $collectedAnterior = (float) Payment::where('status', 'approved')
            ->whereBetween('payment_date', [$prevDesde, $prevHasta])
            ->sum('amount');
        $utilidadAnterior = max(0, $totalAnterior - ($totalAnterior * 0.62));
        $variacionUtilidad = $utilidadAnterior > 0
            ? (($utilidad - $utilidadAnterior) / $utilidadAnterior) * 100
            : null;

        $ventasPorSucursal = (clone $invoicesQuery)
            ->select('branches.name as nombre', DB::raw('SUM(invoices.total) as total'))
            ->join('branches', 'branches.id', '=', 'invoices.branch_id')
            ->whereBetween('issue_date', [$desde, $hasta])
            ->whereNotIn('invoices.status', ['cancelled', 'excluded'])
            ->groupBy('branches.name')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $topPlanes = Plan::query()
            ->select('plans.name as nombre', 'plans.code as pro_codigo', DB::raw('COUNT(contracted_services.id) as cantidad'), DB::raw('SUM(contracted_services.total_value) as venta'))
            ->join('contracted_services', 'contracted_services.plan_id', '=', 'plans.id')
            ->where('contracted_services.status', 'active')
            ->groupBy('plans.id', 'plans.name', 'plans.code')
            ->orderByDesc('venta')
            ->limit(4)
            ->get();

        $stats = [
            'groups'           => BusinessGroup::where('status', 'active')->count(),
            'branches'         => Branch::where('status', 'active')->count(),
            'overdue_clients'  => Branch::where('status', 'active')
                ->whereHas('accountsReceivable', fn ($q) => $q->where('status', 'overdue'))->count(),
            'blocked_clients'  => Branch::where('status', 'blocked')->count(),
            'total_receivable' => AccountReceivable::whereIn('status', ['pending', 'partial', 'overdue'])->sum('balance'),
            'collected_month'  => Payment::where('status', 'approved')
                ->whereMonth('payment_date', now()->month)->sum('amount'),
            'pending_messages' => Conversation::where('queue', 'new_messages')->count(),
            'invoices_to_emit' => ContractedService::where('status', 'active')
                ->whereMonth('next_invoice_date', now()->month)->count(),
            'pending_payments' => Payment::where('status', 'pending_review')->count(),
        ];

        $alertas = collect();

        if ($stats['invoices_to_emit'] > 0) {
            $alertas->push([
                'tipo' => 'info',
                'texto' => "{$stats['invoices_to_emit']} facturas pendientes de emitir este mes",
                'fecha' => now(),
            ]);
        }
        if ($stats['overdue_clients'] > 0) {
            $alertas->push([
                'tipo' => 'danger',
                'texto' => "{$stats['overdue_clients']} clientes con deuda vencida",
                'fecha' => now(),
            ]);
        }
        if ($stats['blocked_clients'] > 0) {
            $alertas->push([
                'tipo' => 'warning',
                'texto' => "{$stats['blocked_clients']} sucursales bloqueadas por mora",
                'fecha' => now(),
            ]);
        }
        if ($stats['pending_messages'] > 0) {
            $alertas->push([
                'tipo' => 'info',
                'texto' => "{$stats['pending_messages']} mensajes WhatsApp sin atender",
                'fecha' => now(),
            ]);
        }
        if ($stats['pending_payments'] > 0) {
            $alertas->push([
                'tipo' => 'warning',
                'texto' => "{$stats['pending_payments']} pagos pendientes de conciliación",
                'fecha' => now(),
            ]);
        }

        $ultimaSincronizacion = Payment::where('status', 'approved')->latest('payment_date')->value('payment_date');

        $grupos = BusinessGroup::where('status', 'active')->orderBy('name')->get(['id', 'name']);
        $sucursales = Branch::where('status', 'active')->orderBy('name')->get(['id', 'name']);

        return view('admin.dashboard', compact(
            'desde', 'hasta', 'total', 'documentos', 'variacionVentas',
            'ventasHoy', 'collectedPeriod', 'costo', 'utilidad', 'margen', 'variacionUtilidad',
            'ventasPorSucursal', 'topPlanes', 'stats', 'alertas', 'ultimaSincronizacion',
            'grupos', 'sucursales'
        ));
    }
}
