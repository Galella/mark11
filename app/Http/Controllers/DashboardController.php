<?php

namespace App\Http\Controllers;

use App\Models\Terminal;
use App\Models\Container;
use App\Models\ActiveInventory;
use App\Models\GateTransaction;
use App\Models\RailTransaction;
use App\Models\Train;
use App\Models\Wagon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard.
     */
    public function index(Request $request)
    {
        // Get selected terminal from request or session
        $terminalId = $request->get('terminal_id') ?? session('selected_terminal_id');
        $terminal = $terminalId ? Terminal::find($terminalId) : null;

        // Get KPIs based on selected terminal or all terminals
        $totalContainers = $terminal 
            ? $terminal->activeInventories()->count()
            : ActiveInventory::count();

        $totalContainersImport = $terminal
            ? ActiveInventory::where('terminal_id', $terminal->id)
                            ->whereHas('container', function($q) {
                                $q->where('category', 'import');
                            })->count()
            : ActiveInventory::whereHas('container', function($q) {
                $q->where('category', 'import');
            })->count();

        $totalContainersExport = $terminal
            ? ActiveInventory::where('terminal_id', $terminal->id)
                            ->whereHas('container', function($q) {
                                $q->where('category', 'export');
                            })->count()
            : ActiveInventory::whereHas('container', function($q) {
                $q->where('category', 'export');
            })->count();

        $totalTruckTransactions = $terminal
            ? $terminal->gateTransactions()->count()
            : GateTransaction::count();

        $totalRailTransactions = $terminal
            ? $terminal->railTransactions()->count()
            : RailTransaction::count();

        // Get terminals for the filter dropdown
        $terminals = Terminal::all();

        // Get recent transactions
        $recentGateTransactions = $terminal
            ? $terminal->gateTransactions()->with(['container', 'user'])->latest()->limit(5)->get()
            : GateTransaction::with(['container', 'user'])->latest()->limit(5)->get();

        $recentRailTransactions = $terminal
            ? $terminal->railTransactions()->with(['container', 'railSchedule', 'user'])->latest()->limit(5)->get()
            : RailTransaction::with(['container', 'railSchedule', 'user'])->latest()->limit(5)->get();

        // Get inventory by category
        $inventoryByCategory = $terminal
            ? ActiveInventory::select('containers.category', DB::raw('count(*) as count'))
                            ->join('containers', 'active_inventory.container_id', '=', 'containers.id')
                            ->where('terminal_id', $terminal->id)
                            ->groupBy('containers.category')
                            ->pluck('count', 'containers.category')
            : ActiveInventory::select('containers.category', DB::raw('count(*) as count'))
                            ->join('containers', 'active_inventory.container_id', '=', 'containers.id')
                            ->groupBy('containers.category')
                            ->pluck('count', 'containers.category');

        return view('dashboard.index', compact(
            'terminal',
            'terminals',
            'totalContainers',
            'totalContainersImport',
            'totalContainersExport',
            'totalTruckTransactions',
            'totalRailTransactions',
            'recentGateTransactions',
            'recentRailTransactions',
            'inventoryByCategory'
        ));
    }

    /**
     * Get dashboard statistics via AJAX.
     */
    public function getStatistics(Request $request)
    {
        $terminalId = $request->get('terminal_id');
        
        // Get basic statistics
        $stats = [
            'total_active_containers' => $terminalId 
                ? ActiveInventory::where('terminal_id', $terminalId)->count()
                : ActiveInventory::count(),
            'total_import_containers' => $terminalId
                ? ActiveInventory::where('terminal_id', $terminalId)
                                ->whereHas('container', function($q) {
                                    $q->where('category', 'import');
                                })->count()
                : ActiveInventory::whereHas('container', function($q) {
                    $q->where('category', 'import');
                })->count(),
            'total_export_containers' => $terminalId
                ? ActiveInventory::where('terminal_id', $terminalId)
                                ->whereHas('container', function($q) {
                                    $q->where('category', 'export');
                                })->count()
                : ActiveInventory::whereHas('container', function($q) {
                    $q->where('category', 'export');
                })->count(),
            'total_truck_transactions_today' => $terminalId
                ? GateTransaction::where('terminal_id', $terminalId)
                                ->whereDate('transaction_time', today())
                                ->count()
                : GateTransaction::whereDate('transaction_time', today())->count(),
            'total_rail_transactions_today' => $terminalId
                ? RailTransaction::where('terminal_id', $terminalId)
                                ->whereDate('transaction_time', today())
                                ->count()
                : RailTransaction::whereDate('transaction_time', today())->count(),
        ];

        // Get transaction trends
        $trends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            
            $gateCount = $terminalId
                ? GateTransaction::where('terminal_id', $terminalId)
                                ->whereDate('transaction_time', $date)
                                ->count()
                : GateTransaction::whereDate('transaction_time', $date)->count();
            
            $railCount = $terminalId
                ? RailTransaction::where('terminal_id', $terminalId)
                                ->whereDate('transaction_time', $date)
                                ->count()
                : RailTransaction::whereDate('transaction_time', $date)->count();
            
            $trends[] = [
                'date' => $date,
                'gate_transactions' => $gateCount,
                'rail_transactions' => $railCount,
            ];
        }

        // Get inventory by size type
        $inventoryBySize = $terminalId
            ? ActiveInventory::select('containers.size_type', DB::raw('count(*) as count'))
                            ->join('containers', 'active_inventory.container_id', '=', 'containers.id')
                            ->where('terminal_id', $terminalId)
                            ->groupBy('containers.size_type')
                            ->pluck('count', 'containers.size_type')
            : ActiveInventory::select('containers.size_type', DB::raw('count(*) as count'))
                            ->join('containers', 'active_inventory.container_id', '=', 'containers.id')
                            ->groupBy('containers.size_type')
                            ->pluck('count', 'containers.size_type');

        return response()->json([
            'stats' => $stats,
            'trends' => $trends,
            'inventoryBySize' => $inventoryBySize,
        ]);
    }

    /**
     * Get inventory report.
     */
    public function inventoryReport(Request $request)
    {
        $terminalId = $request->get('terminal_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = ActiveInventory::with(['container', 'terminal'])
                              ->select('active_inventory.*', 
                                      'containers.number as container_number',
                                      'containers.size_type',
                                      'containers.category',
                                      'containers.status',
                                      'terminals.name as terminal_name');

        $query->join('containers', 'active_inventory.container_id', '=', 'containers.id')
              ->join('terminals', 'active_inventory.terminal_id', '=', 'terminals.id');

        if ($terminalId) {
            $query->where('active_inventory.terminal_id', $terminalId);
        }

        if ($dateFrom) {
            $query->where('active_inventory.in_time', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('active_inventory.in_time', '<=', $dateTo);
        }

        $inventory = $query->get();

        $terminals = \App\Models\Terminal::all();
        return view('reports.inventory', compact('inventory', 'terminals'));
    }

    /**
     * Get transaction report.
     */
    public function transactionReport(Request $request)
    {
        $terminalId = $request->get('terminal_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $transactionType = $request->get('transaction_type'); // 'gate' or 'rail'

        $gateTransactions = collect();
        $railTransactions = collect();

        if (!$transactionType || $transactionType === 'gate') {
            $gateQuery = GateTransaction::with(['container', 'terminal', 'user'])
                                      ->select('gate_transactions.*',
                                              'containers.number as container_number',
                                              'terminals.name as terminal_name',
                                              'users.name as user_name');

            $gateQuery->join('containers', 'gate_transactions.container_id', '=', 'containers.id')
                      ->join('terminals', 'gate_transactions.terminal_id', '=', 'terminals.id')
                      ->join('users', 'gate_transactions.created_by', '=', 'users.id');

            if ($terminalId) {
                $gateQuery->where('gate_transactions.terminal_id', $terminalId);
            }

            if ($dateFrom) {
                $gateQuery->whereDate('gate_transactions.transaction_time', '>=', $dateFrom);
            }

            if ($dateTo) {
                $gateQuery->whereDate('gate_transactions.transaction_time', '<=', $dateTo);
            }

            $gateTransactions = $gateQuery->get();
        }

        if (!$transactionType || $transactionType === 'rail') {
            $railQuery = RailTransaction::with(['container', 'terminal', 'railSchedule', 'user'])
                                      ->select('rail_transactions.*',
                                              'containers.number as container_number',
                                              'terminals.name as terminal_name',
                                              'rail_schedules.schedule_code',
                                              'users.name as user_name');

            $railQuery->join('containers', 'rail_transactions.container_id', '=', 'containers.id')
                      ->join('terminals', 'rail_transactions.terminal_id', '=', 'terminals.id')
                      ->join('rail_schedules', 'rail_transactions.rail_schedule_id', '=', 'rail_schedules.id')
                      ->join('users', 'rail_transactions.created_by', '=', 'users.id');

            if ($terminalId) {
                $railQuery->where('rail_transactions.terminal_id', $terminalId);
            }

            if ($dateFrom) {
                $railQuery->whereDate('rail_transactions.transaction_time', '>=', $dateFrom);
            }

            if ($dateTo) {
                $railQuery->whereDate('rail_transactions.transaction_time', '<=', $dateTo);
            }

            $railTransactions = $railQuery->get();
        }

        $terminals = \App\Models\Terminal::all();
        return view('reports.transactions', compact('gateTransactions', 'railTransactions', 'terminals'));
    }
}