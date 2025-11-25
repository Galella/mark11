@extends('layouts.main')

@section('title', 'Trains - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Trains</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Trains</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Train Management</h3>
                <div class="card-tools">
                    <a href="{{ route('trains.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Train
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Train Number</th>
                            <th>Name</th>
                            <th>Operator</th>
                            <th>Wagons</th>
                            <th>Capacity (TEUs)</th>
                            <th>Route</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trains as $train)
                        <tr>
                            <td>{{ $train->train_number }}</td>
                            <td>{{ $train->name }}</td>
                            <td>{{ $train->operator }}</td>
                            <td>{{ $train->total_wagons }}</td>
                            <td>{{ $train->max_teus_capacity }}</td>
                            <td>{{ $train->route_from }} → {{ $train->route_to }}</td>
                            <td>
                                <span class="badge 
                                    {{ $train->status == 'active' ? 'bg-success' : 
                                       ($train->status == 'maintenance' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($train->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('trains.show', $train) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('trains.edit', $train) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('trains.destroy', $train) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
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
@endsection