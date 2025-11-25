@extends('layouts.main')

@section('title', 'Edit User - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Edit User</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
            <li class="breadcrumb-item active">Edit User</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Edit User Information</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" placeholder="Enter full name" 
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" placeholder="Enter email address" 
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Leave blank to keep current password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">Leave blank to keep current password. Minimum 8 characters if changing.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" 
                                       placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Terminal Access & Roles <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Terminals</label>
                                <select name="terminal_ids[]" class="form-control" multiple size="6" required>
                                    @foreach($terminals as $terminal)
                                        <option value="{{ $terminal->id }}"
                                            @if(collect(old('terminal_ids', $user->userTerminalAccesses->pluck('terminal_id')))->contains($terminal->id)) selected @endif>
                                            {{ $terminal->name }} ({{ $terminal->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple</small>
                            </div>
                            <div class="col-md-6">
                                <label>Roles</label>
                                <select name="role_ids[]" class="form-control" multiple size="6" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}"
                                            @if(collect(old('role_ids', $user->userTerminalAccesses->pluck('role_id')))->contains($role->id)) selected @endif>
                                            {{ $role->name }} ({{ $role->display_name }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple</small>
                            </div>
                        </div>
                        <small class="form-text text-muted mt-2">
                            Select terminals and roles for the user. Note: If you select different numbers of terminals and roles,
                            the first selected role will be applied to all terminals.
                        </small>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">Update User</button>
                    <a href="{{ route('users.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

