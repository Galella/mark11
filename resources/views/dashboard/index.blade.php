@extends('layouts.main')

@section('title', 'Dashboard - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <!-- Total Containers Card -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalContainers }}</h3>
                <p>Total Active Containers</p>
            </div>
            <div class="icon">
                <i class="fas fa-boxes"></i>
            </div>
            <a href="{{ route('containers.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <!-- Import Containers Card -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalContainersImport }}</h3>
                <p>Import Containers</p>
            </div>
            <div class="icon">
                <i class="fas fa-arrow-down"></i>
            </div>
            <a href="{{ route('containers.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <!-- Export Containers Card -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalContainersExport }}</h3>
                <p>Export Containers</p>
            </div>
            <div class="icon">
                <i class="fas fa-arrow-up"></i>
            </div>
            <a href="{{ route('containers.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <!-- Transactions Card -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $totalTruckTransactions + $totalRailTransactions }}</h3>
                <p>Total Transactions</p>
            </div>
            <div class="icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Data</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="terminal_id">Terminal</label>
                                <select name="terminal_id" id="terminal_id" class="form-control select2">
                                    <option value="">All Terminals</option>
                                    @foreach($terminals as $t)
                                        <option value="{{ $t->id }}" {{ request('terminal_id') == $t->id ? 'selected' : '' }}>
                                            {{ $t->name }} ({{ $t->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Gate Transactions -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Gate Transactions</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Container</th>
                            <th>Type</th>
                            <th>Time</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentGateTransactions as $transaction)
                            <tr>
                                <td>{{ $transaction->container->number }}</td>
                                <td>{{ $transaction->transaction_type }}</td>
                                <td>{{ $transaction->transaction_time->format('Y-m-d H:i') }}</td>
                                <td>{{ $transaction->user->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No recent transactions</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Recent Rail Transactions -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Rail Transactions</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Container</th>
                            <th>Type</th>
                            <th>Schedule</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRailTransactions as $transaction)
                            <tr>
                                <td>{{ $transaction->container->number }}</td>
                                <td>{{ $transaction->operation_type }}</td>
                                <td>{{ $transaction->railSchedule->schedule_code ?? 'N/A' }}</td>
                                <td>{{ $transaction->transaction_time->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No recent transactions</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Inventory by Category Chart -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Inventory by Category</h3>
            </div>
            <div class="card-body">
                <canvas id="inventoryCategoryChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    // Inventory by Category Chart
    const categoryCtx = document.getElementById('inventoryCategoryChart').getContext('2d');
    
    const inventoryData = @json($inventoryByCategory);
    const categories = Object.keys(inventoryData);
    const counts = Object.values(inventoryData);
    
    new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: categories,
            datasets: [{
                label: 'Number of Containers',
                data: counts,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgb(54, 162, 235)',
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection