<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TerminalController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\ActiveInventoryController;
use App\Http\Controllers\GateTransactionController;
use App\Http\Controllers\RailTransactionController;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\WagonController;
use App\Http\Controllers\RailScheduleController;
use App\Http\Controllers\Auth\LoginController;

// Public route for the welcome page
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes - need authentication
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Terminal Management
    Route::resource('terminals', TerminalController::class);

    // Container Management
    Route::resource('containers', ContainerController::class);
    Route::post('/containers/search', [ContainerController::class, 'search'])->name('containers.search');
    Route::get('/containers/{number}/get', [ContainerController::class, 'getByNumber'])->name('containers.getByNumber');

    // Inventory Management
    Route::get('/inventory', [ActiveInventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/{inventory}', [ActiveInventoryController::class, 'show'])->name('inventory.show');
    Route::get('/inventory/high-dwell-time', [ActiveInventoryController::class, 'highDwellTime'])->name('inventory.high-dwell-time');
    Route::get('/inventory/api/count-by-terminal', [ActiveInventoryController::class, 'getInventoryCountByTerminal'])->name('inventory.api.count-by-terminal');
    Route::get('/inventory/api/statistics', [ActiveInventoryController::class, 'getStatistics'])->name('inventory.api.statistics');

    // Gate Operations
    Route::get('/gate/in', [GateTransactionController::class, 'showGateInForm'])->name('gate.in.form');
    Route::post('/gate/in', [GateTransactionController::class, 'processGateIn'])->name('gate.process-in');
    Route::get('/gate/out', [GateTransactionController::class, 'showGateOutForm'])->name('gate.out.form');
    Route::post('/gate/out', [GateTransactionController::class, 'processGateOut'])->name('gate.process-out');
    Route::get('/gate/transactions', [GateTransactionController::class, 'getGateTransactions'])->name('gate.transactions');

    // Rail Operations
    Route::get('/rail/in', [RailTransactionController::class, 'showRailInForm'])->name('rail.in.form');
    Route::post('/rail/in', [RailTransactionController::class, 'processRailIn'])->name('rail.process-in');
    Route::get('/rail/out', [RailTransactionController::class, 'showRailOutForm'])->name('rail.out.form');
    Route::post('/rail/out', [RailTransactionController::class, 'processRailOut'])->name('rail.process-out');
    Route::get('/rail/transactions', [RailTransactionController::class, 'getRailTransactions'])->name('rail.transactions');

    // Train Management
    Route::resource('trains', TrainController::class);

    // Wagon Management
    Route::resource('wagons', WagonController::class);

    // Rail Schedule Management
    Route::resource('rail-schedules', RailScheduleController::class);

    // Reports
    Route::get('/reports/inventory', [DashboardController::class, 'inventoryReport'])->name('reports.inventory');
    Route::get('/reports/transactions', [DashboardController::class, 'transactionReport'])->name('reports.transactions');
});
