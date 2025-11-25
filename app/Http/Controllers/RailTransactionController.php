<?php

namespace App\Http\Controllers;

use App\Models\RailTransaction;
use App\Models\Container;
use App\Models\Terminal;
use App\Models\ActiveInventory;
use App\Models\Train;
use App\Models\Wagon;
use App\Models\RailSchedule;
use App\Rules\ISO6346ContainerNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RailTransactionController extends Controller
{
    /**
     * Show the rail in form (LOR - Load on Rail).
     */
    public function showRailInForm()
    {
        $terminals = Terminal::all();
        $trains = Train::where('is_active', true)->get();
        $schedules = RailSchedule::where('is_active', true)->get();
        $wagons = Wagon::where('is_active', true)->get();
        
        return view('rail.rail-in', compact('terminals', 'trains', 'schedules', 'wagons'));
    }

    /**
     * Process rail in transaction (LOR).
     */
    public function processRailIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'terminal_id' => 'required|exists:terminals,id',
            'container_number' => ['required', 'string', 'size:11', new ISO6346ContainerNumber],
            'rail_schedule_id' => 'required|exists:rail_schedules,id',
            'wagon_id' => 'required|exists:wagons,id',
            'wagon_position' => 'required|string|max:10',
            'operation_type' => 'required|in:LOR',
            'is_handover' => 'boolean',
            'handover_terminal_id' => 'nullable|exists:terminals,id',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $validated = $validator->validated();

        // Find the container
        $container = Container::where('number', $validated['container_number'])->first();
        
        if (!$container) {
            return redirect()->back()
                             ->with('error', 'Container not found.')
                             ->withInput();
        }

        // Check if container is in active inventory at this terminal
        $activeInventory = ActiveInventory::where('container_id', $container->id)
                                         ->where('terminal_id', $validated['terminal_id'])
                                         ->first();

        if (!$activeInventory) {
            return redirect()->back()
                             ->with('error', 'Container is not in active inventory at this terminal.')
                             ->withInput();
        }

        // Create the rail transaction
        $railTransaction = RailTransaction::create([
            'terminal_id' => $validated['terminal_id'],
            'container_id' => $container->id,
            'rail_schedule_id' => $validated['rail_schedule_id'],
            'wagon_id' => $validated['wagon_id'],
            'transaction_type' => 'RAIL_IN',
            'operation_type' => $validated['operation_type'],
            'wagon_position' => $validated['wagon_position'],
            'is_handover' => $validated['is_handover'] ?? false,
            'handover_terminal_id' => $validated['handover_terminal_id'] ?? null,
            'status' => 'completed',
            'transaction_time' => now(),
            'created_by' => Auth::id(),
            'remarks' => $validated['remarks'] ?? null,
        ]);

        // Update active inventory
        $activeInventory->update([
            'handling_status' => 'in-rail',
            'last_transaction_id' => $railTransaction->id,
            'last_transaction_type' => 'RAIL_IN',
        ]);

        // If it's a handover, remove from current terminal and add to destination terminal
        if ($validated['is_handover'] && !empty($validated['handover_terminal_id'])) {
            // Remove from current terminal's active inventory
            $activeInventory->delete();
            
            // Add to destination terminal's active inventory
            ActiveInventory::create([
                'container_id' => $container->id,
                'terminal_id' => $validated['handover_terminal_id'],
                'handling_status' => 'in-transit',
                'last_transaction_id' => $railTransaction->id,
                'last_transaction_type' => 'RAIL_IN',
                'in_time' => now(),
                'dwell_time_start' => now(),
            ]);
        }

        return redirect()->back()
                         ->with('success', 'Container successfully loaded on rail.');
    }

    /**
     * Show the rail out form (UFR - Unload from Rail).
     */
    public function showRailOutForm()
    {
        $terminals = Terminal::all();
        $trains = Train::where('is_active', true)->get();
        $schedules = RailSchedule::where('is_active', true)->get();
        
        return view('rail.rail-out', compact('terminals', 'trains', 'schedules'));
    }

    /**
     * Process rail out transaction (UFR).
     */
    public function processRailOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'terminal_id' => 'required|exists:terminals,id',
            'container_number' => ['required', 'string', 'size:11', new ISO6346ContainerNumber],
            'rail_schedule_id' => 'required|exists:rail_schedules,id',
            'wagon_position' => 'required|string|max:10',
            'operation_type' => 'required|in:UFR',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $validated = $validator->validated();

        // Find the container
        $container = Container::where('number', $validated['container_number'])->first();
        
        if (!$container) {
            return redirect()->back()
                             ->with('error', 'Container not found.')
                             ->withInput();
        }

        // For a handover scenario, the container might not be in the current terminal's active inventory
        // It may have been moved to the destination terminal during RAIL_IN (LOR)
        
        // Check if container is in active inventory at this terminal or find any existing inventory
        $activeInventory = ActiveInventory::where('container_id', $container->id)
                                         ->where('terminal_id', $validated['terminal_id'])
                                         ->first();

        // If not found in current terminal, it might be a handover situation
        if (!$activeInventory) {
            // For handover, the container would be in the destination terminal's inventory
            // In this case, we'll create new inventory at this terminal
            $activeInventory = ActiveInventory::create([
                'container_id' => $container->id,
                'terminal_id' => $validated['terminal_id'],
                'handling_status' => 'in-yard',
                'last_transaction_id' => null, // Will be set below
                'last_transaction_type' => null, // Will be set below
                'in_time' => now(),
                'dwell_time_start' => now(),
            ]);
        } else {
            // Update existing inventory
            $activeInventory->update([
                'handling_status' => 'in-yard',
            ]);
        }

        // Create the rail transaction
        $railTransaction = RailTransaction::create([
            'terminal_id' => $validated['terminal_id'],
            'container_id' => $container->id,
            'rail_schedule_id' => $validated['rail_schedule_id'],
            'wagon_id' => null, // Unloading, so no specific wagon
            'transaction_type' => 'RAIL_OUT',
            'operation_type' => $validated['operation_type'],
            'wagon_position' => $validated['wagon_position'],
            'is_handover' => false, // UFR is unload at destination
            'handover_terminal_id' => null,
            'status' => 'completed',
            'transaction_time' => now(),
            'created_by' => Auth::id(),
            'remarks' => $validated['remarks'] ?? null,
        ]);

        // Update the inventory with the new transaction
        $activeInventory->update([
            'last_transaction_id' => $railTransaction->id,
            'last_transaction_type' => 'RAIL_OUT',
        ]);

        return redirect()->back()
                         ->with('success', 'Container successfully unloaded from rail.');
    }

    /**
     * Get rail transactions by terminal and date range.
     */
    public function getRailTransactions(Request $request)
    {
        $query = RailTransaction::with(['container', 'terminal', 'railSchedule', 'wagon', 'user'])
                               ->where('terminal_id', $request->terminal_id)
                               ->orderBy('transaction_time', 'desc');

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('transaction_time', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('transaction_time', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20);

        return view('rail.transactions', compact('transactions'));
    }
}