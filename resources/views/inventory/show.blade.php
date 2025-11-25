@extends('layouts.main')

@section('title', 'Inventory Details - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Inventory Details</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Active Inventory</a></li>
            <li class="breadcrumb-item active">Details</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Container: {{ $inventory->container->number }}</h3>
                <div class="card-tools">
                    <a href="{{ route('inventory.index') }}" class="btn btn-sm btn-default">
                        <i class="fas fa-arrow-left"></i> Back to Inventory
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
                                <span class="info-box-number">{{ $inventory->container->number }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-warehouse"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Terminal</span>
                                <span class="info-box-number">{{ $inventory->terminal->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-th"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Size Type</span>
                                <span class="info-box-number">{{ $inventory->container->size_type }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-tags"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Category</span>
                                <span class="info-box-number">{{ ucfirst($inventory->container->category) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-box-open"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Status</span>
                                <span class="info-box-number">{{ ucfirst($inventory->container->status) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Dwell Time</span>
                                <span class="info-box-number">{{ $inventory->dwellTimeFormatted }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Entry Information</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Entry Time:</strong></td>
                                        <td>{{ $inventory->in_time->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Handling Status:</strong></td>
                                        <td>{{ $inventory->handling_status }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Current Location:</strong></td>
                                        <td>{{ $inventory->current_location ?: 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Last Transaction</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Type:</strong></td>
                                        <td>{{ $inventory->last_transaction_type ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Time:</strong></td>
                                        <td>{{ $inventory->last_transaction_id ? \Carbon\Carbon::parse($inventory->last_transaction->transaction_time)->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection