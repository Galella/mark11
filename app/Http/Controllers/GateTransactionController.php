<?php

namespace App\Http\Controllers;

use App\Models\GateTransaction;
use App\Models\Container;
use App\Models\Terminal;
use App\Models\ActiveInventory;
use App\Models\Equipment;
use App\Rules\ISO6346ContainerNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class GateTransactionController extends Controller
{
    /**
     * Show the gate in form.
     */
    public function showGateInForm()
    {
        // Get terminals the user has access to
        $user = Auth::user();
        $userTerminalAccesses = $user->userTerminalAccesses()->with(['terminal', 'role'])->get();

        // Get the terminals that the user has access to
        $accessibleTerminals = collect();
        foreach($userTerminalAccesses as $access) {
            $accessibleTerminals->push($access->terminal);
        }

        // Check if user has access to only one terminal
        $autoSelectTerminal = ($accessibleTerminals->count() == 1) ? $accessibleTerminals->first() : null;

        return view('gate.gate-in', compact('accessibleTerminals', 'autoSelectTerminal'));
    }

    /**
     * Process gate in transaction.
     */
    public function processGateIn(Request $request)
    {
        // Get user's accessible terminals
        $user = Auth::user();
        $accessibleTerminalIds = $user->userTerminalAccesses->pluck('terminal_id')->toArray();

        $validator = Validator::make($request->all(), [
            'terminal_id' => 'required|in:' . implode(',', $accessibleTerminalIds), // Ensure user can only submit to their terminals
            'container_number' => ['required', 'string', 'size:11', new ISO6346ContainerNumber],
            'truck_number' => 'required|string|max:20',
            'driver_name' => 'required|string|max:255',
            'driver_license' => 'nullable|string|max:50',
            'seal_number' => 'nullable|string|max:50',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $validated = $validator->validated();

        // Find or create the container
        $container = Container::firstOrCreate(
            ['number' => $validated['container_number']],
            [
                'type' => substr($validated['container_number'], 0, 4),
                'size_type' => '20GP', // Default, should be determined from container number
                'category' => 'import', // Default, could be determined from context
                'status' => 'full', // Default, could be determined from context
                'iso_code' => substr($validated['container_number'], 0, 4),
            ]
        );

        // Check if container is already in active inventory at this terminal
        $existingInventory = ActiveInventory::where('container_id', $container->id)
                                           ->where('terminal_id', $validated['terminal_id'])
                                           ->first();

        if ($existingInventory) {
            return redirect()->back()
                             ->with('error', 'Container is already in active inventory at this terminal.')
                             ->withInput();
        }

        // Create the gate transaction
        $gateTransaction = GateTransaction::create([
            'terminal_id' => $validated['terminal_id'],
            'container_id' => $container->id,
            'transaction_type' => 'GATE_IN',
            'truck_number' => $validated['truck_number'],
            'driver_name' => $validated['driver_name'],
            'driver_license' => $validated['driver_license'] ?? null,
            'seal_number' => $validated['seal_number'] ?? null,
            'status' => 'completed',
            'transaction_time' => now(),
            'created_by' => Auth::id(),
            'remarks' => $validated['remarks'] ?? null,
        ]);

        // Add to active inventory
        ActiveInventory::create([
            'container_id' => $container->id,
            'terminal_id' => $validated['terminal_id'],
            'handling_status' => 'in-yard',
            'last_transaction_id' => $gateTransaction->id,
            'last_transaction_type' => 'GATE_IN',
            'in_time' => now(),
            'dwell_time_start' => now(),
        ]);

        return redirect()->back()
                         ->with('success', 'Container successfully checked in at gate.');
    }

    /**
     * Show the gate out form.
     */
    public function showGateOutForm()
    {
        // Get terminals the user has access to
        $user = Auth::user();
        $userTerminalAccesses = $user->userTerminalAccesses()->with(['terminal', 'role'])->get();

        // Get the terminals that the user has access to
        $accessibleTerminals = collect();
        foreach($userTerminalAccesses as $access) {
            $accessibleTerminals->push($access->terminal);
        }

        // Check if user has access to only one terminal
        $autoSelectTerminal = ($accessibleTerminals->count() == 1) ? $accessibleTerminals->first() : null;

        return view('gate.gate-out', compact('accessibleTerminals', 'autoSelectTerminal'));
    }

    /**
     * Process gate out transaction.
     */
    public function processGateOut(Request $request)
    {
        // Get user's accessible terminals
        $user = Auth::user();
        $accessibleTerminalIds = $user->userTerminalAccesses->pluck('terminal_id')->toArray();

        $validator = Validator::make($request->all(), [
            'terminal_id' => 'required|in:' . implode(',', $accessibleTerminalIds), // Ensure user can only submit to their terminals
            'container_number' => ['required', 'string', 'size:11', new ISO6346ContainerNumber],
            'truck_number' => 'required|string|max:20',
            'driver_name' => 'required|string|max:255',
            'driver_license' => 'nullable|string|max:50',
            'seal_number' => 'nullable|string|max:50',
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

        // Create the gate transaction
        $gateTransaction = GateTransaction::create([
            'terminal_id' => $validated['terminal_id'],
            'container_id' => $container->id,
            'transaction_type' => 'GATE_OUT',
            'truck_number' => $validated['truck_number'],
            'driver_name' => $validated['driver_name'],
            'driver_license' => $validated['driver_license'] ?? null,
            'seal_number' => $validated['seal_number'] ?? null,
            'status' => 'completed',
            'transaction_time' => now(),
            'created_by' => Auth::id(),
            'remarks' => $validated['remarks'] ?? null,
        ]);

        // Update the active inventory with out time
        $activeInventory->update([
            'out_time' => now(),
            'handling_status' => 'out-yard',
            'last_transaction_id' => $gateTransaction->id,
            'last_transaction_type' => 'GATE_OUT',
        ]);

        // Remove from active inventory since it's now out
        $activeInventory->delete();

        return redirect()->back()
                         ->with('success', 'Container successfully checked out from gate.');
    }

    /**
     * Get gate transactions by terminal and date range.
     */
    public function getGateTransactions(Request $request)
    {
        $query = GateTransaction::with(['container', 'terminal', 'user'])
                               ->where('terminal_id', $request->terminal_id)
                               ->orderBy('transaction_time', 'desc');

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('transaction_time', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('transaction_time', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20);

        return view('gate.transactions', compact('transactions'));
    }
}