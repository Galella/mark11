<?php

namespace App\Http\Controllers;

use App\Models\ActiveInventory;
use App\Models\Container;
use App\Models\Terminal;
use Illuminate\Http\Request;

class ActiveInventoryController extends Controller
{
    /**
     * Display a listing of the active inventory.
     */
    public function index(Request $request)
    {
        $query = ActiveInventory::with(['container', 'terminal'])
                               ->orderBy('in_time', 'desc');

        // Filter by terminal if specified
        if ($request->has('terminal_id') && $request->terminal_id) {
            $query->where('terminal_id', $request->terminal_id);
        }

        $inventories = $query->paginate(20);

        // Get all terminals for the filter dropdown
        $terminals = Terminal::all();

        // Get summary statistics
        $totalActiveContainers = $request->terminal_id
            ? ActiveInventory::where('terminal_id', $request->terminal_id)->count()
            : ActiveInventory::count();

        $importContainers = $request->terminal_id
            ? ActiveInventory::where('terminal_id', $request->terminal_id)
                             ->whereHas('container', function($q) {
                                 $q->where('category', 'import');
                             })->count()
            : ActiveInventory::whereHas('container', function($q) {
                $q->where('category', 'import');
            })->count();

        $exportContainers = $request->terminal_id
            ? ActiveInventory::where('terminal_id', $request->terminal_id)
                             ->whereHas('container', function($q) {
                                 $q->where('category', 'export');
                             })->count()
            : ActiveInventory::whereHas('container', function($q) {
                $q->where('category', 'export');
            })->count();

        // Count containers with high dwell time (more than 24 hours)
        $highDwellContainers = $request->terminal_id
            ? ActiveInventory::where('terminal_id', $request->terminal_id)
                             ->whereRaw('strftime("%s", "now") - strftime("%s", in_time) > 86400') // More than 24 hours (86400 seconds)
                             ->count()
            : ActiveInventory::whereRaw('strftime("%s", "now") - strftime("%s", in_time) > 86400') // More than 24 hours (86400 seconds)
                             ->count();

        return view('inventory.index', compact(
            'inventories',
            'terminals',
            'totalActiveContainers',
            'importContainers',
            'exportContainers',
            'highDwellContainers'
        ));
    }

    /**
     * Show the specified active inventory item.
     */
    public function show(ActiveInventory $inventory)
    {
        $inventory->load(['container', 'terminal']);
        return view('inventory.show', compact('inventory'));
    }

    /**
     * Show containers with high dwell time.
     */
    public function highDwellTime()
    {
        $inventories = ActiveInventory::with(['container', 'terminal'])
                                    ->whereNotNull('dwell_time_start')
                                    ->whereRaw('TIMESTAMPDIFF(HOUR, dwell_time_start, NOW()) > 24') // More than 24 hours
                                    ->orderByRaw('TIMESTAMPDIFF(HOUR, dwell_time_start, NOW()) DESC')
                                    ->paginate(20);

        return view('inventory.high-dwell-time', compact('inventories'));
    }

    /**
     * Get active inventory count by terminal.
     */
    public function getInventoryCountByTerminal()
    {
        $inventoryCounts = ActiveInventory::select('terminal_id', \DB::raw('count(*) as count'))
                                        ->groupBy('terminal_id')
                                        ->with('terminal:id,name,code')
                                        ->get();

        return response()->json($inventoryCounts);
    }

    /**
     * Get inventory statistics.
     */
    public function getStatistics()
    {
        $stats = [
            'total_active_containers' => ActiveInventory::count(),
            'total_import_containers' => ActiveInventory::whereHas('container', function($q) {
                $q->where('category', 'import');
            })->count(),
            'total_export_containers' => ActiveInventory::whereHas('container', function($q) {
                $q->where('category', 'export');
            })->count(),
            'total_empty_containers' => ActiveInventory::whereHas('container', function($q) {
                $q->where('status', 'empty');
            })->count(),
        ];

        return response()->json($stats);
    }
}