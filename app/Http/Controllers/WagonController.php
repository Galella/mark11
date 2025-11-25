<?php

namespace App\Http\Controllers;

use App\Models\Wagon;
use App\Models\Train;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WagonController extends Controller
{
    /**
     * Display a listing of the wagons.
     */
    public function index()
    {
        $wagons = Wagon::with('train')->paginate(15);
        return view('wagons.index', compact('wagons'));
    }

    /**
     * Show the form for creating a new wagon.
     */
    public function create()
    {
        $trains = Train::where('is_active', true)->get();
        return view('wagons.create', compact('trains'));
    }

    /**
     * Store a newly created wagon in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'train_id' => 'required|exists:trains,id',
            'wagon_number' => 'required|string|unique:wagons,wagon_number',
            'wagon_type' => 'required|string|in:flatbed,tank,box,refrigerated,open_top',
            'teu_capacity' => 'required|integer|min:1|max:4', // Usually 2 TEUs, but can vary
            'status' => 'required|in:available,loaded,maintenance,out-of-service',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        Wagon::create($validator->validated());

        return redirect()->route('wagons.index')
                         ->with('success', 'Wagon created successfully.');
    }

    /**
     * Display the specified wagon.
     */
    public function show(Wagon $wagon)
    {
        $wagon->load('train');
        return view('wagons.show', compact('wagon'));
    }

    /**
     * Show the form for editing the specified wagon.
     */
    public function edit(Wagon $wagon)
    {
        $trains = Train::where('is_active', true)->get();
        return view('wagons.edit', compact('wagon', 'trains'));
    }

    /**
     * Update the specified wagon in storage.
     */
    public function update(Request $request, Wagon $wagon)
    {
        $validator = Validator::make($request->all(), [
            'train_id' => 'required|exists:trains,id',
            'wagon_number' => 'required|string|unique:wagons,wagon_number,' . $wagon->id,
            'wagon_type' => 'required|string|in:flatbed,tank,box,refrigerated,open_top',
            'teu_capacity' => 'required|integer|min:1|max:4',
            'status' => 'required|in:available,loaded,maintenance,out-of-service',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $wagon->update($validator->validated());

        return redirect()->route('wagons.index')
                         ->with('success', 'Wagon updated successfully.');
    }

    /**
     * Remove the specified wagon from storage.
     */
    public function destroy(Wagon $wagon)
    {
        // Check if wagon is referenced in any rail transactions
        if ($wagon->railTransactions()->count() > 0) {
            return redirect()->back()
                             ->with('error', 'Cannot delete wagon that has been used in rail transactions.');
        }

        $wagon->delete();

        return redirect()->route('wagons.index')
                         ->with('success', 'Wagon deleted successfully.');
    }
}