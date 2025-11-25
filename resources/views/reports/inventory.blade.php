@extends('layouts.main')

@section('title', 'Inventory Report - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Inventory Report</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Reports</li>
            <li class="breadcrumb-item active">Inventory Report</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Inventory Report Filters</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.inventory') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="terminal_id">Terminal</label>
                                <select name="terminal_id" id="terminal_id" class="form-control select2">
                                    <option value="">All Terminals</option>
                                    @foreach($terminals as $terminal)
                                        <option value="{{ $terminal->id }}" {{ request('terminal_id') == $terminal->id ? 'selected' : '' }}>
                                            {{ $terminal->name }} ({{ $terminal->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_from">From Date</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" 
                                       value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_to">To Date</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" 
                                       value="{{ request('date_to') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-info">Generate Report</button>
                            <button type="button" class="btn btn-default" onclick="window.print()">Print</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(isset($inventory) && $inventory->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Inventory Report Results</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Container</th>
                            <th>Terminal</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Size Type</th>
                            <th>Handling Status</th>
                            <th>In Time</th>
                            <th>Dwell Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventory as $item)
                        <tr>
                            <td>{{ $item->container->number }}</td>
                            <td>{{ $item->terminal->name }}</td>
                            <td>
                                <span class="badge 
                                    {{ $item->container->category == 'import' ? 'bg-info' : 
                                       ($item->container->category == 'export' ? 'bg-success' : 'bg-warning') }}">
                                    {{ ucfirst($item->container->category) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge 
                                    {{ $item->container->status == 'full' ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ ucfirst($item->container->status) }}
                                </span>
                            </td>
                            <td>{{ $item->container->size_type }}</td>
                            <td>{{ $item->handling_status }}</td>
                            <td>{{ $item->in_time->format('Y-m-d H:i') }}</td>
                            <td>{{ $item->dwellTimeFormatted }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
@else
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center">
                <h4>No records found matching your criteria.</h4>
                <p>Try adjusting your filters and try again.</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection