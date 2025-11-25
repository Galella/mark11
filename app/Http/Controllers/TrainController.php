<?php

namespace App\Http\Controllers;

use App\Models\Train;
use App\Models\Wagon;
use App\Models\RailSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainController extends Controller
{
    /**
     * Display a listing of the trains.
     */
    public function index()
    {
        $trains = Train::all();
        return view('trains.index', compact('trains'));
    }

    /**
     * Show the form for creating a new train.
     */
    public function create()
    {
        return view('trains.create');
    }

    /**
     * Store a newly created train in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'train_number' => 'required|string|unique:trains,train_number',
            'name' => 'nullable|string|max:255',
            'operator' => 'required|string|max:255',
            'total_wagons' => 'required|integer|min:1',
            'max_teus_capacity' => 'required|integer|min:2', // At least 2 TEUs (1 wagon * 2 TEUs)
            'route_from' => 'required|string|max:255',
            'route_to' => 'required|string|max:255',
            'status' => 'required|in:active,maintenance,decommissioned',
            'commissioning_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        // Calculate max TEUs capacity based on 2 TEUs per wagon if not provided
        $validated = $validator->validated();
        if (!isset($validated['max_teus_capacity'])) {
            $validated['max_teus_capacity'] = $validated['total_wagons'] * 2;
        }

        Train::create($validated);

        return redirect()->route('trains.index')
                         ->with('success', 'Train created successfully.');
    }

    /**
     * Display the specified train.
     */
    public function show(Train $train)
    {
        $train->load('wagons', 'railSchedules');
        return view('trains.show', compact('train'));
    }

    /**
     * Show the form for editing the specified train.
     */
    public function edit(Train $train)
    {
        return view('trains.edit', compact('train'));
    }

    /**
     * Update the specified train in storage.
     */
    public function update(Request $request, Train $train)
    {
        $validator = Validator::make($request->all(), [
            'train_number' => 'required|string|unique:trains,train_number,' . $train->id,
            'name' => 'nullable|string|max:255',
            'operator' => 'required|string|max:255',
            'total_wagons' => 'required|integer|min:1',
            'max_teus_capacity' => 'required|integer|min:2',
            'route_from' => 'required|string|max:255',
            'route_to' => 'required|string|max:255',
            'status' => 'required|in:active,maintenance,decommissioned',
            'commissioning_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        // Calculate max TEUs capacity based on 2 TEUs per wagon if not provided
        $validated = $validator->validated();
        if (!isset($validated['max_teus_capacity'])) {
            $validated['max_teus_capacity'] = $validated['total_wagons'] * 2;
        }

        $train->update($validated);

        return redirect()->route('trains.index')
                         ->with('success', 'Train updated successfully.');
    }

    /**
     * Remove the specified train from storage.
     */
    public function destroy(Train $train)
    {
        if ($train->railSchedules()->count() > 0) {
            return redirect()->back()
                             ->with('error', 'Cannot delete train that has scheduled trips.');
        }

        $train->delete();

        return redirect()->route('trains.index')
                         ->with('success', 'Train deleted successfully.');
    }
}