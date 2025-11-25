<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Terminal;
use App\Services\RBACService;
use Symfony\Component\HttpFoundation\Response;

class TerminalAccess
{
    protected $rbacService;

    public function __construct(RBACService $rbacService)
    {
        $this->rbacService = $rbacService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission = null): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Get terminal from route parameter, session, or other source
        $terminalId = $request->route('terminal') ?? 
                     $request->route('terminal_id') ?? 
                     session('selected_terminal_id');

        if (!$terminalId) {
            // If no terminal is specified, redirect to terminal selection
            return redirect()->route('terminal.select');
        }

        $terminal = Terminal::find($terminalId);
        
        if (!$terminal) {
            abort(404, 'Terminal not found.');
        }

        // Check if user has access to this terminal
        if (!$user->hasTerminalAccess($terminal->id)) {
            abort(403, 'You do not have access to this terminal.');
        }

        // If a permission is specified, check if user has that permission
        if ($permission && !$this->rbacService->userHasPermission($user, $permission, $terminal)) {
            abort(403, 'You do not have the required permission for this terminal.');
        }

        // Set the terminal in the session
        session(['selected_terminal_id' => $terminal->id]);
        
        return $next($request);
    }
}