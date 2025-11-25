@extends('layouts.main')

@section('title', 'Transaction Report - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Transaction Report</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Reports</li>
            <li class="breadcrumb-item active">Transaction Report</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Transaction Report Filters</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.transactions') }}">
                    <div class="row">
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="transaction_type">Transaction Type</label>
                                <select name="transaction_type" id="transaction_type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="gate" {{ request('transaction_type') == 'gate' ? 'selected' : '' }}>Gate Transactions</option>
                                    <option value="rail" {{ request('transaction_type') == 'rail' ? 'selected' : '' }}>Rail Transactions</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_from">From Date</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" 
                                       value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
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

@if(isset($gateTransactions) && $gateTransactions->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Gate Transactions</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Container</th>
                            <th>Transaction Type</th>
                            <th>Truck</th>
                            <th>Driver</th>
                            <th>User</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gateTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction->container->number }}</td>
                            <td>
                                <span class="badge
                                    {{ $transaction->transaction_type == 'GATE_IN' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $transaction->transaction_type }}
                                </span>
                            </td>
                            <td>{{ $transaction->truck_number }}</td>
                            <td>{{ $transaction->driver_name }}</td>
                            <td>{{ $transaction->user_name }}</td>
                            <td>{{ $transaction->transaction_time->format('Y-m-d H:i') }}</td>
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
@endif

@if(isset($railTransactions) && $railTransactions->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Rail Transactions</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Container</th>
                            <th>Operation Type</th>
                            <th>Schedule Code</th>
                            <th>Wagon Position</th>
                            <th>Handover</th>
                            <th>User</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($railTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction->container->number }}</td>
                            <td>
                                <span class="badge 
                                    {{ $transaction->operation_type == 'LOR' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $transaction->operation_type }}
                                </span>
                            </td>
                            <td>{{ $transaction->schedule_code }}</td>
                            <td>{{ $transaction->wagon_position }}</td>
                            <td>
                                @if($transaction->is_handover)
                                    <span class="badge bg-info">Yes</span>
                                    <small>→ {{ $transaction->handover_terminal_id ? \App\Models\Terminal::find($transaction->handover_terminal_id)->name : 'N/A' }}</small>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td>{{ $transaction->user_name }}</td>
                            <td>{{ $transaction->transaction_time->format('Y-m-d H:i') }}</td>
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
@endif

@if((!isset($gateTransactions) || $gateTransactions->count() == 0) && (!isset($railTransactions) || $railTransactions->count() == 0))
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