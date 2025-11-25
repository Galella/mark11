<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Terminal;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Container;
use App\Models\Train;
use App\Models\Wagon;
use Illuminate\Support\Facades\Hash;

class TMSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default terminals
        $terminals = [
            ['name' => 'Jakarta Terminal', 'code' => 'JKT', 'location' => 'Jakarta'],
            ['name' => 'Karawang Terminal', 'code' => 'KRW', 'location' => 'Karawang'],
            ['name' => 'Semarang Terminal', 'code' => 'SMG', 'location' => 'Semarang'],
            ['name' => 'Surabaya Terminal', 'code' => 'SBY', 'location' => 'Surabaya'],
        ];
        
        foreach ($terminals as $terminalData) {
            Terminal::create($terminalData);
        }
        
        // Create default roles
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Administrator', 'description' => 'Full system access']);
        $operatorRole = Role::create(['name' => 'operator', 'display_name' => 'Operator', 'description' => 'Can perform gate and rail operations']);
        $supervisorRole = Role::create(['name' => 'supervisor', 'display_name' => 'Supervisor', 'description' => 'Can supervise operations and view reports']);
        
        // Create default permissions
        $permissions = [
            ['name' => 'view_containers', 'display_name' => 'View Containers'],
            ['name' => 'create_gate_in', 'display_name' => 'Create Gate In'],
            ['name' => 'create_gate_out', 'display_name' => 'Create Gate Out'],
            ['name' => 'create_rail_in', 'display_name' => 'Create Rail In'],
            ['name' => 'create_rail_out', 'display_name' => 'Create Rail Out'],
            ['name' => 'view_inventory', 'display_name' => 'View Inventory'],
            ['name' => 'manage_terminals', 'display_name' => 'Manage Terminals'],
            ['name' => 'view_reports', 'display_name' => 'View Reports'],
        ];

        $permissionModels = collect();
        foreach ($permissions as $permissionData) {
            $permissionModel = Permission::create($permissionData);
            $permissionModels->push($permissionModel);
        }

        // Assign permissions to roles
        // Admin gets all permissions
        $adminRole->permissions()->attach($permissionModels->pluck('id')->toArray());

        // Operator gets operational permissions
        $operatorPermissions = $permissionModels->filter(function($perm) {
            return in_array($perm->name, ['view_containers', 'create_gate_in', 'create_gate_out', 'create_rail_in', 'create_rail_out', 'view_inventory']);
        });
        $operatorRole->permissions()->attach($operatorPermissions->pluck('id')->toArray());

        // Supervisor gets view and report permissions
        $supervisorPermissions = $permissionModels->filter(function($perm) {
            return in_array($perm->name, ['view_containers', 'view_inventory', 'view_reports']);
        });
        $supervisorRole->permissions()->attach($supervisorPermissions->pluck('id')->toArray());
        
        // Create default user
        $user = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@tms.local',
            'password' => Hash::make('password'),
        ]);

        // Also create a second admin user for testing
        $testUser = User::create([
            'name' => 'Test Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Assign admin role to users for all terminals
        $terminals = Terminal::all();
        foreach ($terminals as $terminal) {
            $user->userTerminalAccesses()->create([
                'terminal_id' => $terminal->id,
                'role_id' => $adminRole->id,
            ]);

            $testUser->userTerminalAccesses()->create([
                'terminal_id' => $terminal->id,
                'role_id' => $adminRole->id,
            ]);
        }
        
        // Create sample trains
        $train1 = Train::create([
            'train_number' => 'ITT-001',
            'name' => 'Jakarta-Surabaya Express',
            'operator' => 'PT Kereta Api Indonesia',
            'total_wagons' => 20,
            'max_teus_capacity' => 40,
            'route_from' => 'Jakarta',
            'route_to' => 'Surabaya',
            'status' => 'active',
        ]);
        
        $train2 = Train::create([
            'train_number' => 'ITT-002',
            'name' => 'Surabaya-Jakarta Express',
            'operator' => 'PT Kereta Api Indonesia',
            'total_wagons' => 15,
            'max_teus_capacity' => 30,
            'route_from' => 'Surabaya',
            'route_to' => 'Jakarta',
            'status' => 'active',
        ]);
        
        // Create sample wagons
        for ($i = 1; $i <= 10; $i++) {
            Wagon::create([
                'train_id' => $train1->id,
                'wagon_number' => 'KAI-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'wagon_type' => 'flatbed',
                'teu_capacity' => 2,
                'status' => 'available',
            ]);
        }
        
        for ($i = 1; $i <= 8; $i++) {
            Wagon::create([
                'train_id' => $train2->id,
                'wagon_number' => 'KAI-B-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'wagon_type' => 'flatbed',
                'teu_capacity' => 2,
                'status' => 'available',
            ]);
        }
        
        // Create sample containers
        $containerNumbers = [
            'TCLU1234567', 'TCLU7654321', 'APLU9876543', 'APLU3456789',
            'CAIU1111111', 'CAIU2222222', 'CAIU3333333', 'CAIU4444444',
            'CAIU5555555', 'CAIU6666666', 'CAIU7777777', 'CAIU8888888'
        ];
        
        foreach ($containerNumbers as $number) {
            Container::create([
                'number' => $number,
                'type' => substr($number, 0, 4),
                'size_type' => strlen($number) == 11 ? '20GP' : '40GP',
                'category' => in_array(substr($number, 0, 3), ['TCL', 'APL']) ? 'import' : 'export',
                'status' => 'full',
                'iso_code' => substr($number, 0, 4),
            ]);
        }
    }
}