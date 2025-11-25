<?php

namespace App\Http\Controllers;

use App\Models\RailSchedule;
use App\Models\Train;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RailScheduleController extends Controller
{
    /**
     * Display a listing of the rail schedules.
     */
    public function index()
    {
        $schedules = RailSchedule::with(['train', 'originTerminal', 'destinationTerminal'])->paginate(15);
        return view('rail-schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new rail schedule.
     */
    public function create()
    {
        $trains = Train::where('is_active', true)->get();
        $terminals = Terminal::all();
        return view('rail-schedules.create', compact('trains', 'terminals'));
    }

    /**
     * Store a newly created rail schedule in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'train_id' => 'required|exists:trains,id',
            'schedule_code' => 'required|string|unique:rail_schedules,schedule_code',
            'origin_terminal_id' => 'required|exists:terminals,id',
            'destination_terminal_id' => 'required|exists:terminals,id|different:origin_terminal_id',
            'departure_time' => 'required|date|after:now',
            'arrival_time' => 'required|date|after:departure_time',
            'expected_teus' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,departed,in-transit,arrived,delayed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        RailSchedule::create($validator->validated());

        return redirect()->route('rail-schedules.index')
                         ->with('success', 'Rail schedule created successfully.');
    }

    /**
     * Display the specified rail schedule.
     */
    public function show(RailSchedule $railSchedule)
    {
        $railSchedule->load(['train', 'originTerminal', 'destinationTerminal', 'railTransactions']);
        return view('rail-schedules.show', compact('railSchedule'));
    }

    /**
     * Show the form for editing the specified rail schedule.
     */
    public function edit(RailSchedule $railSchedule)
    {
        $trains = Train::where('is_active', true)->get();
        $terminals = Terminal::all();
        return view('rail-schedules.edit', compact('railSchedule', 'trains', 'terminals'));
    }

    /**
     * Update the specified rail schedule in storage.
     */
    public function update(Request $request, RailSchedule $railSchedule)
    {
        $validator = Validator::make($request->all(), [
            'train_id' => 'required|exists:trains,id',
            'schedule_code' => 'required|string|unique:rail_schedules,schedule_code,' . $railSchedule->id,
            'origin_terminal_id' => 'required|exists:terminals,id',
            'destination_terminal_id' => 'required|exists:terminals,id|different:origin_terminal_id',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'expected_teus' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,departed,in-transit,arrived,delayed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $railSchedule->update($validator->validated());

        return redirect()->route('rail-schedules.index')
                         ->with('success', 'Rail schedule updated successfully.');
    }

    /**
     * Remove the specified rail schedule from storage.
     */
    public function destroy(RailSchedule $railSchedule)
    {
        if ($railSchedule->railTransactions()->count() > 0) {
            return redirect()->back()
                             ->with('error', 'Cannot delete schedule that has rail transactions.');
        }

        $railSchedule->delete();

        return redirect()->route('rail-schedules.index')
                         ->with('success', 'Rail schedule deleted successfully.');
    }
}