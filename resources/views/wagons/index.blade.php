@extends('layouts.main')

@section('title', 'Wagons - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Wagons</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Wagons</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Wagon Management</h3>
                <div class="card-tools">
                    <a href="{{ route('wagons.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Wagon
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Wagon Number</th>
                            <th>Train</th>
                            <th>Type</th>
                            <th>TEU Capacity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wagons as $wagon)
                        <tr>
                            <td>{{ $wagon->wagon_number }}</td>
                            <td>{{ $wagon->train->name ?? 'N/A' }}</td>
                            <td>{{ $wagon->wagon_type }}</td>
                            <td>{{ $wagon->teu_capacity }}</td>
                            <td>
                                <span class="badge 
                                    {{ $wagon->status == 'available' ? 'bg-success' : 
                                       ($wagon->status == 'loaded' ? 'bg-info' : 
                                          ($wagon->status == 'maintenance' ? 'bg-warning' : 'bg-danger')) }}">
                                    {{ ucfirst($wagon->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('wagons.show', $wagon) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('wagons.edit', $wagon) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('wagons.destroy', $wagon) }}" method="POST" class="d-inline">
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
            <div class="card-footer clearfix">
                {{ $wagons->links() }}
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection