<?php

namespace App\Services;

use App\Models\User;
use App\Models\Terminal;
use App\Models\Role;
use App\Models\Permission;

class RBACService
{
    /**
     * Check if user has a specific permission for a terminal
     */
    public function userHasPermission(User $user, string $permissionName, ?Terminal $terminal = null): bool
    {
        // If no terminal is specified, check permissions across all terminals
        if (!$terminal) {
            foreach ($user->terminals as $userTerminal) {
                if ($this->checkPermissionForTerminal($user, $permissionName, $userTerminal)) {
                    return true;
                }
            }
            return false;
        }

        return $this->checkPermissionForTerminal($user, $permissionName, $terminal);
    }

    /**
     * Check permission for a specific terminal
     */
    private function checkPermissionForTerminal(User $user, string $permissionName, Terminal $terminal): bool
    {
        $userTerminalAccess = $user->getRoleForTerminal($terminal->id);
        
        if (!$userTerminalAccess) {
            return false;
        }

        $role = $userTerminalAccess->role;
        
        if (!$role || !$role->is_active) {
            return false;
        }

        // Check if the role has the required permission
        return $role->permissions()
                   ->where('name', $permissionName)
                   ->exists();
    }

    /**
     * Assign a role to a user for a specific terminal
     */
    public function assignRoleToUser(User $user, Role $role, Terminal $terminal): bool
    {
        // Check if the user already has access to this terminal
        $existing = $user->getRoleForTerminal($terminal->id);
        
        if ($existing) {
            // Update the existing role
            $existing->update(['role_id' => $role->id]);
        } else {
            // Create new terminal access
            $user->userTerminalAccesses()->create([
                'terminal_id' => $terminal->id,
                'role_id' => $role->id,
            ]);
        }

        return true;
    }

    /**
     * Get all permissions for a user at a specific terminal
     */
    public function getUserPermissions(User $user, Terminal $terminal): array
    {
        $userTerminalAccess = $user->getRoleForTerminal($terminal->id);
        
        if (!$userTerminalAccess || !$userTerminalAccess->role || !$userTerminalAccess->role->is_active) {
            return [];
        }

        return $userTerminalAccess->role->permissions->pluck('name')->toArray();
    }
}