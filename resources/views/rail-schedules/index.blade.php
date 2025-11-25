@extends('layouts.main')

@section('title', 'Rail Schedules - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Rail Schedules</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Rail Schedules</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Rail Schedule Management</h3>
                <div class="card-tools">
                    <a href="{{ route('rail-schedules.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Schedule
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Schedule Code</th>
                            <th>Train</th>
                            <th>Route</th>
                            <th>Departure</th>
                            <th>Arrival</th>
                            <th>Expected TEUs</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->schedule_code }}</td>
                            <td>{{ $schedule->train->name ?? 'N/A' }}</td>
                            <td>{{ $schedule->originTerminal->name ?? 'N/A' }} → {{ $schedule->destinationTerminal->name ?? 'N/A' }}</td>
                            <td>{{ $schedule->departure_time->format('Y-m-d H:i') }}</td>
                            <td>{{ $schedule->arrival_time->format('Y-m-d H:i') }}</td>
                            <td>{{ $schedule->expected_teus }}</td>
                            <td>
                                <span class="badge 
                                    {{ $schedule->status == 'scheduled' ? 'bg-info' : 
                                       ($schedule->status == 'departed' ? 'bg-warning' : 
                                          ($schedule->status == 'in-transit' ? 'bg-primary' : 
                                             ($schedule->status == 'arrived' ? 'bg-success' : 'bg-danger'))) }}">
                                    {{ ucfirst(str_replace('-', ' ', $schedule->status)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('rail-schedules.show', $schedule) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('rail-schedules.edit', $schedule) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('rail-schedules.destroy', $schedule) }}" method="POST" class="d-inline">
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
                {{ $schedules->links() }}
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection