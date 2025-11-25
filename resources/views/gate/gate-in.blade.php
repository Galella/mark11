@extends('layouts.main')

@section('title', 'Gate In - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Gate In</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('gate.transactions') }}">Gate Operations</a></li>
            <li class="breadcrumb-item active">Gate In</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Container Gate In</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="{{ route('gate.process-in') }}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="terminal_id">Terminal <span class="text-danger">*</span></label>
                                <select class="form-control @error('terminal_id') is-invalid @enderror" 
                                        id="terminal_id" name="terminal_id" required>
                                    <option value="">Select Terminal</option>
                                    @foreach($terminals as $terminal)
                                        <option value="{{ $terminal->id }}" {{ old('terminal_id') == $terminal->id ? 'selected' : '' }}>
                                            {{ $terminal->name }} ({{ $terminal->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('terminal_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="container_number">Container Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('container_number') is-invalid @enderror" 
                                       id="container_number" name="container_number" 
                                       placeholder="Enter 11-digit container number (e.g., ABCU1234567)" 
                                       value="{{ old('container_number') }}" maxlength="11" required>
                                @error('container_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">Format: 4 letters + 7 digits (e.g., ABCU1234567)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="truck_number">Truck Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('truck_number') is-invalid @enderror" 
                                       id="truck_number" name="truck_number" 
                                       placeholder="Enter truck number/plate" 
                                       value="{{ old('truck_number') }}" required>
                                @error('truck_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="driver_name">Driver Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('driver_name') is-invalid @enderror" 
                                       id="driver_name" name="driver_name" 
                                       placeholder="Enter driver name" 
                                       value="{{ old('driver_name') }}" required>
                                @error('driver_name')
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
                                <label for="driver_license">Driver License</label>
                                <input type="text" class="form-control @error('driver_license') is-invalid @enderror" 
                                       id="driver_license" name="driver_license" 
                                       placeholder="Enter driver license number" 
                                       value="{{ old('driver_license') }}">
                                @error('driver_license')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="seal_number">Seal Number</label>
                                <input type="text" class="form-control @error('seal_number') is-invalid @enderror" 
                                       id="seal_number" name="seal_number" 
                                       placeholder="Enter seal number" 
                                       value="{{ old('seal_number') }}">
                                @error('seal_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                  id="remarks" name="remarks" rows="3" 
                                  placeholder="Enter any remarks">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Process Gate In</button>
                    <a href="{{ route('gate.transactions') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection