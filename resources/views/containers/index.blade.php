@extends('layouts.main')

@section('title', 'Containers - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Containers</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Containers</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Container Management</h3>
                <div class="card-tools">
                    <a href="{{ route('containers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Container
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Terminal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($containers as $container)
                        <tr>
                            <td>{{ $container->number }}</td>
                            <td>{{ $container->type }}</td>
                            <td>{{ $container->size_type }}</td>
                            <td>
                                <span class="badge 
                                    {{ $container->category == 'import' ? 'bg-info' : 
                                       ($container->category == 'export' ? 'bg-success' : 'bg-warning') }}">
                                    {{ ucfirst($container->category) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge 
                                    {{ $container->status == 'full' ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ ucfirst($container->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $container->activeInventory ? $container->activeInventory->terminal->name : 'N/A' }}
                            </td>
                            <td>
                                <a href="{{ route('containers.show', $container) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('containers.edit', $container) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('containers.destroy', $container) }}" method="POST" class="d-inline">
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
                {{ $containers->links() }}
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection