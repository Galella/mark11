<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Terminal;
use App\Models\Role;
use App\Models\UserTerminalAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::with('userTerminalAccesses.terminal', 'userTerminalAccesses.role')->paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $terminals = Terminal::all();
        $roles = Role::all();
        return view('users.create', compact('terminals', 'roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terminal_ids' => 'required|array',
            'terminal_ids.*' => 'exists:terminals,id',
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign terminal access for the user
        if ($request->has('terminal_ids') && $request->has('role_ids')) {
            $terminals = $request->terminal_ids;
            $roles = $request->role_ids;

            // If they have the same length, assign each role to corresponding terminal
            if (count($terminals) == count($roles)) {
                for ($i = 0; $i < count($terminals); $i++) {
                    $user->userTerminalAccesses()->create([
                        'terminal_id' => $terminals[$i],
                        'role_id' => $roles[$i],
                    ]);
                }
            } else {
                // If different lengths, assign first role to all terminals
                $roleId = $roles[0] ?? null;
                foreach ($terminals as $terminalId) {
                    $user->userTerminalAccesses()->create([
                        'terminal_id' => $terminalId,
                        'role_id' => $roleId,
                    ]);
                }
            }
        }

        return redirect()->route('users.index')
                         ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('userTerminalAccesses.terminal', 'userTerminalAccesses.role');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $user->load('userTerminalAccesses.terminal', 'userTerminalAccesses.role');
        $terminals = Terminal::all();
        $roles = Role::all();
        return view('users.edit', compact('user', 'terminals', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'terminal_ids' => 'required|array',
            'terminal_ids.*' => 'exists:terminals,id',
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $userUpdateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userUpdateData['password'] = Hash::make($request->password);
        }

        $user->update($userUpdateData);

        // Sync terminal access for the user
        $user->userTerminalAccesses()->delete(); // Remove old associations

        if ($request->has('terminal_ids') && $request->has('role_ids')) {
            $terminals = $request->terminal_ids;
            $roles = $request->role_ids;

            // If they have the same length, assign each role to corresponding terminal
            if (count($terminals) == count($roles)) {
                for ($i = 0; $i < count($terminals); $i++) {
                    $user->userTerminalAccesses()->create([
                        'terminal_id' => $terminals[$i],
                        'role_id' => $roles[$i],
                    ]);
                }
            } else {
                // If different lengths, assign first role to all terminals
                $roleId = $roles[0] ?? null;
                foreach ($terminals as $terminalId) {
                    $user->userTerminalAccesses()->create([
                        'terminal_id' => $terminalId,
                        'role_id' => $roleId,
                    ]);
                }
            }
        }

        return redirect()->route('users.index')
                         ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()
                             ->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'User deleted successfully.');
    }
}