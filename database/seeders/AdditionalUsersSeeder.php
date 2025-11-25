<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Terminal;
use App\Models\Role;
use App\Models\UserTerminalAccess;

class AdditionalUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all terminals and roles
        $terminals = Terminal::all();
        $roles = Role::all();

        // Create users for each role at Jakarta terminal (first terminal)
        $jakartaTerminal = $terminals->first();

        // Create an operator user
        $operatorUser = User::create([
            'name' => 'Terminal Operator',
            'email' => 'operator@tms.local',
            'password' => Hash::make('password'),
        ]);

        // Assign operator role to Jakarta terminal
        $operatorRole = $roles->where('name', 'operator')->first();
        if ($operatorRole) {
            UserTerminalAccess::create([
                'user_id' => $operatorUser->id,
                'terminal_id' => $jakartaTerminal->id,
                'role_id' => $operatorRole->id,
            ]);
        }

        // Create a supervisor user
        $supervisorUser = User::create([
            'name' => 'Terminal Supervisor',
            'email' => 'supervisor@tms.local',
            'password' => Hash::make('password'),
        ]);

        // Assign supervisor role to Jakarta terminal
        $supervisorRole = $roles->where('name', 'supervisor')->first();
        if ($supervisorRole) {
            UserTerminalAccess::create([
                'user_id' => $supervisorUser->id,
                'terminal_id' => $jakartaTerminal->id,
                'role_id' => $supervisorRole->id,
            ]);
        }

        // Create a manager user
        $managerUser = User::create([
            'name' => 'Terminal Manager',
            'email' => 'manager@tms.local',
            'password' => Hash::make('password'),
        ]);

        // Assign manager role to Jakarta terminal (assuming 'manager' role exists or using supervisor role as alternative)
        $managerRole = $roles->where('name', 'manager')->first() ?? $supervisorRole;
        if ($managerRole) {
            UserTerminalAccess::create([
                'user_id' => $managerUser->id,
                'terminal_id' => $jakartaTerminal->id,
                'role_id' => $managerRole->id,
            ]);
        }

        // Create a user with access to multiple terminals with different roles
        $multiTerminalUser = User::create([
            'name' => 'Multi Terminal User',
            'email' => 'multi@tms.local',
            'password' => Hash::make('password'),
        ]);

        // Assign different roles to different terminals
        foreach ($terminals as $index => $terminal) {
            $role = $roles->get($index % $roles->count()); // Cycle through roles
            if ($role) {
                UserTerminalAccess::create([
                    'user_id' => $multiTerminalUser->id,
                    'terminal_id' => $terminal->id,
                    'role_id' => $role->id,
                ]);
            }
        }

        echo "Additional users created successfully!\n";
        echo "- Operator: operator@tms.local / password\n";
        echo "- Supervisor: supervisor@tms.local / password\n";
        echo "- Manager: manager@tms.local / password\n";
        echo "- Multi-terminal: multi@tms.local / password\n";
    }
}