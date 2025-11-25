@extends('layouts.main')

@section('title', $container->number . ' - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Container: {{ $container->number }}</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('containers.index') }}">Containers</a></li>
            <li class="breadcrumb-item active">{{ $container->number }}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Container Details</h3>
                <div class="card-tools">
                    <a href="{{ route('containers.edit', $container) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('containers.index') }}" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-box"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Container Number</span>
                                <span class="info-box-number">{{ $container->number }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-tags"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Type</span>
                                <span class="info-box-number">{{ $container->type }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-ruler-combined"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Size Type</span>
                                <span class="info-box-number">{{ $container->size_type }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-layer-group"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Category</span>
                                <span class="info-box-number">
                                    <span class="badge 
                                        {{ $container->category == 'import' ? 'bg-info' : 
                                           ($container->category == 'export' ? 'bg-success' : 'bg-warning') }}">
                                        {{ ucfirst($container->category) }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-box-open"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Status</span>
                                <span class="info-box-number">
                                    <span class="badge 
                                        {{ $container->status == 'full' ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ ucfirst($container->status) }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-id-card"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">ISO Code</span>
                                <span class="info-box-number">{{ $container->iso_code }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-indigo elevation-1"><i class="fas fa-toggle-on"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Is Active</span>
                                <span class="info-box-number">
                                    <span class="badge {{ $container->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $container->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Active Inventory Section -->
                @if($container->activeInventory)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h4>Active Inventory Status</h4>
                        <div class="card card-outline card-info">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Terminal:</strong>
                                        <p>{{ $container->activeInventory->terminal->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Handling Status:</strong>
                                        <p>{{ $container->activeInventory->handling_status ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Current Location:</strong>
                                        <p>{{ $container->activeInventory->current_location ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Entry Time:</strong>
                                        <p>{{ $container->activeInventory->in_time->format('Y-m-d H:i') ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Dwell Time:</strong>
                                        <p>{{ $container->activeInventory->dwellTimeFormatted }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Transactions Section -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h4>Transactions</h4>
                        
                        @if($gateTransactions->count() > 0 || $railTransactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Operation</th>
                                            <th>Terminal</th>
                                            <th>Date</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($gateTransactions as $transaction)
                                        <tr>
                                            <td><span class="badge bg-primary">Gate</span></td>
                                            <td>{{ $transaction->transaction_type }}</td>
                                            <td>{{ $transaction->terminal->name }}</td>
                                            <td>{{ $transaction->transaction_time->format('Y-m-d H:i') }}</td>
                                            <td>
                                                Truck: {{ $transaction->truck_number }}<br>
                                                Driver: {{ $transaction->driver_name }}
                                            </td>
                                        </tr>
                                        @endforeach
                                        
                                        @foreach($railTransactions as $transaction)
                                        <tr>
                                            <td><span class="badge bg-danger">Rail</span></td>
                                            <td>{{ $transaction->operation_type }}</td>
                                            <td>{{ $transaction->terminal->name }}</td>
                                            <td>{{ $transaction->transaction_time->format('Y-m-d H:i') }}</td>
                                            <td>
                                                Schedule: {{ $transaction->railSchedule->schedule_code ?? 'N/A' }}<br>
                                                @if($transaction->is_handover) Handover → {{ $transaction->handoverTerminal->name ?? 'N/A' }} @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>No transactions recorded for this container.</p>
                        @endif
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection