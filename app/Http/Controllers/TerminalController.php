<?php

namespace App\Http\Controllers;

use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TerminalController extends Controller
{
    /**
     * Display a listing of the terminals.
     */
    public function index()
    {
        $terminals = Terminal::all();
        return view('terminals.index', compact('terminals'));
    }

    /**
     * Show the form for creating a new terminal.
     */
    public function create()
    {
        return view('terminals.create');
    }

    /**
     * Store a newly created terminal in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:3|unique:terminals,code',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        Terminal::create($validator->validated());

        return redirect()->route('terminals.index')
                         ->with('success', 'Terminal created successfully.');
    }

    /**
     * Display the specified terminal.
     */
    public function show(Terminal $terminal)
    {
        return view('terminals.show', compact('terminal'));
    }

    /**
     * Show the form for editing the specified terminal.
     */
    public function edit(Terminal $terminal)
    {
        return view('terminals.edit', compact('terminal'));
    }

    /**
     * Update the specified terminal in storage.
     */
    public function update(Request $request, Terminal $terminal)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:3|unique:terminals,code,' . $terminal->id,
            'location' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $terminal->update($validator->validated());

        return redirect()->route('terminals.index')
                         ->with('success', 'Terminal updated successfully.');
    }

    /**
     * Remove the specified terminal from storage.
     */
    public function destroy(Terminal $terminal)
    {
        $terminal->delete();

        return redirect()->route('terminals.index')
                         ->with('success', 'Terminal deleted successfully.');
    }
}