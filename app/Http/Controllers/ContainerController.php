<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\Terminal;
use App\Models\ActiveInventory;
use App\Rules\ISO6346ContainerNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContainerController extends Controller
{
    /**
     * Display a listing of the containers.
     */
    public function index()
    {
        $containers = Container::with('activeInventory.terminal')->paginate(15);
        return view('containers.index', compact('containers'));
    }

    /**
     * Show the form for creating a new container.
     */
    public function create()
    {
        return view('containers.create');
    }

    /**
     * Store a newly created container in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number' => ['required', 'string', 'size:11', 'unique:containers,number', new ISO6346ContainerNumber],
            'type' => 'required|string|max:4',
            'size_type' => 'required|string|in:20GP,40GP,20HC,40HC,20OT,40OT,20RF,40RF',
            'category' => 'required|in:import,export,transhipment',
            'status' => 'required|in:full,empty',
            'iso_code' => 'required|string|size:4',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        Container::create($validator->validated());

        return redirect()->route('containers.index')
                         ->with('success', 'Container created successfully.');
    }

    /**
     * Display the specified container.
     */
    public function show(Container $container)
    {
        $container->load(['activeInventory.terminal', 'gateTransactions', 'railTransactions']);
        return view('containers.show', compact('container'));
    }

    /**
     * Show the form for editing the specified container.
     */
    public function edit(Container $container)
    {
        return view('containers.edit', compact('container'));
    }

    /**
     * Update the specified container in storage.
     */
    public function update(Request $request, Container $container)
    {
        $validator = Validator::make($request->all(), [
            'number' => ['required', 'string', 'size:11', 'unique:containers,number,' . $container->id, new ISO6346ContainerNumber],
            'type' => 'required|string|max:4',
            'size_type' => 'required|in:20GP,40GP,20HC,40HC,20OT,40OT,20RF,40RF',
            'category' => 'required|in:import,export,transhipment',
            'status' => 'required|in:full,empty',
            'iso_code' => 'required|string|size:4',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $container->update($validator->validated());

        return redirect()->route('containers.index')
                         ->with('success', 'Container updated successfully.');
    }

    /**
     * Remove the specified container from storage.
     */
    public function destroy(Container $container)
    {
        // Check if container is in active inventory
        if ($container->activeInventory) {
            return redirect()->back()
                             ->with('error', 'Cannot delete container that is in active inventory.');
        }

        $container->delete();

        return redirect()->route('containers.index')
                         ->with('success', 'Container deleted successfully.');
    }

    /**
     * Search containers by number.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $containers = Container::where('number', 'LIKE', "%{$query}%")
                              ->orWhere('type', 'LIKE', "%{$query}%")
                              ->orWhere('size_type', 'LIKE', "%{$query}%")
                              ->paginate(15);

        return view('containers.index', compact('containers'));
    }

    /**
     * Get container by number (API endpoint).
     */
    public function getByNumber($number)
    {
        $container = Container::where('number', $number)->first();
        
        if (!$container) {
            return response()->json(['error' => 'Container not found'], 404);
        }

        return response()->json($container);
    }
}