@extends('layouts.main')

@section('title', 'Active Inventory - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Active Inventory</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Active Inventory</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Active Inventory Management</h3>
                <div class="card-tools">
                    <form method="GET" action="{{ route('inventory.index') }}" class="form-inline">
                        <div class="input-group">
                            <select name="terminal_id" class="form-control form-control-sm">
                                <option value="">All Terminals</option>
                                @foreach($terminals as $terminal)
                                    <option value="{{ $terminal->id }}" {{ request('terminal_id') == $terminal->id ? 'selected' : '' }}>
                                        {{ $terminal->name }} ({{ $terminal->code }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-default" type="submit">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Container Number</th>
                            <th>Terminal</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Size Type</th>
                            <th>Handling Status</th>
                            <th>In Time</th>
                            <th>Dwell Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventories as $inventory)
                        <tr>
                            <td>{{ $inventory->container->number }}</td>
                            <td>{{ $inventory->terminal->name }}</td>
                            <td>
                                <span class="badge 
                                    {{ $inventory->container->category == 'import' ? 'bg-info' : 
                                       ($inventory->container->category == 'export' ? 'bg-success' : 'bg-warning') }}">
                                    {{ ucfirst($inventory->container->category) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge 
                                    {{ $inventory->container->status == 'full' ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ ucfirst($inventory->container->status) }}
                                </span>
                            </td>
                            <td>{{ $inventory->container->size_type }}</td>
                            <td>{{ $inventory->handling_status }}</td>
                            <td>{{ $inventory->in_time->format('Y-m-d H:i') }}</td>
                            <td>{{ $inventory->dwellTimeFormatted }}</td>
                            <td>
                                <a href="{{ route('inventory.show', $inventory) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                {{ $inventories->links() }}
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Inventory Summary</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-boxes"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Containers</span>
                                <span class="info-box-number">{{ $totalActiveContainers }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-arrow-down"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Import</span>
                                <span class="info-box-number">{{ $importContainers }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-arrow-up"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Export</span>
                                <span class="info-box-number">{{ $exportContainers }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">High Dwell</span>
                                <span class="info-box-number">{{ $highDwellContainers }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection