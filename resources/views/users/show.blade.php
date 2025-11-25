@extends('layouts.main')

@section('title', $user->name . ' - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">{{ $user->name }}</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
            <li class="breadcrumb-item active">{{ $user->name }}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Details</h3>
                <div class="card-tools">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Name</span>
                                <span class="info-box-number">{{ $user->name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-envelope"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Email</span>
                                <span class="info-box-number">{{ $user->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-calendar-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Created At</span>
                                <span class="info-box-number">{{ $user->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-history"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Last Updated</span>
                                <span class="info-box-number">{{ $user->updated_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Terminal Access Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h4>Terminal Access</h4>
                        
                        @if($user->userTerminalAccesses->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Terminal</th>
                                            <th>Role</th>
                                            <th>Assigned At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->userTerminalAccesses as $access)
                                        <tr>
                                            <td>{{ $access->terminal->name ?? 'N/A' }} ({{ $access->terminal->code ?? 'N/A' }})</td>
                                            <td>{{ $access->role->name ?? 'N/A' }} ({{ $access->role->display_name ?? 'N/A' }})</td>
                                            <td>{{ $access->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No terminal access assigned.</p>
                        @endif
                    </div>
                </div>
                
                <!-- Recent Activity Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h4>Recent Activity</h4>
                        
                        @if($recentActivity && $recentActivity->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Description</th>
                                            <th>Terminal</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentActivity as $activity)
                                        <tr>
                                            <td>
                                                <span class="badge
                                                    @if(str_contains($activity->description, 'gate')) bg-primary
                                                    @elseif(str_contains($activity->description, 'rail')) bg-success
                                                    @else bg-info @endif">
                                                    {{ ucfirst(explode(' ', $activity->description)[0] ?? '') }}
                                                </span>
                                            </td>
                                            <td>{{ $activity->description }}</td>
                                            <td>{{ $activity->terminal->name ?? 'N/A' }}</td>
                                            <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No recent activity found.</p>
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