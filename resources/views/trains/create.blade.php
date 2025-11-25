@extends('layouts.main')

@section('title', 'Add Train - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Add Train</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('trains.index') }}">Trains</a></li>
            <li class="breadcrumb-item active">Add Train</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Train Information</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="{{ route('trains.store') }}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="train_number">Train Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('train_number') is-invalid @enderror" 
                                       id="train_number" name="train_number" 
                                       placeholder="Enter train number (e.g., ITT-001)" 
                                       value="{{ old('train_number') }}" required>
                                @error('train_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Train Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" 
                                       placeholder="Enter train name" 
                                       value="{{ old('name') }}">
                                @error('name')
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
                                <label for="operator">Operator <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('operator') is-invalid @enderror" 
                                       id="operator" name="operator" 
                                       placeholder="Enter operator name" 
                                       value="{{ old('operator') }}" required>
                                @error('operator')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_wagons">Total Wagons <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('total_wagons') is-invalid @enderror" 
                                       id="total_wagons" name="total_wagons" 
                                       placeholder="Enter number of wagons" 
                                       value="{{ old('total_wagons', 2) }}" min="1" required>
                                @error('total_wagons')
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
                                <label for="route_from">Route From <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('route_from') is-invalid @enderror" 
                                       id="route_from" name="route_from" 
                                       placeholder="Enter origin terminal" 
                                       value="{{ old('route_from') }}" required>
                                @error('route_from')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="route_to">Route To <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('route_to') is-invalid @enderror" 
                                       id="route_to" name="route_to" 
                                       placeholder="Enter destination terminal" 
                                       value="{{ old('route_to') }}" required>
                                @error('route_to')
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
                                <label for="max_teus_capacity">Max TEUs Capacity</label>
                                <input type="number" class="form-control @error('max_teus_capacity') is-invalid @enderror" 
                                       id="max_teus_capacity" name="max_teus_capacity" 
                                       placeholder="Enter max TEUs capacity" 
                                       value="{{ old('max_teus_capacity') }}" min="2">
                                @error('max_teus_capacity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">Calculated as Wagons × 2 TEUs per wagon</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="decommissioned" {{ old('status') == 'decommissioned' ? 'selected' : '' }}>Decommissioned</option>
                                </select>
                                @error('status')
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
                                <label for="commissioning_date">Commissioning Date</label>
                                <input type="date" class="form-control @error('commissioning_date') is-invalid @enderror" 
                                       id="commissioning_date" name="commissioning_date" 
                                       value="{{ old('commissioning_date') }}">
                                @error('commissioning_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="next_maintenance_date">Next Maintenance Date</label>
                                <input type="date" class="form-control @error('next_maintenance_date') is-invalid @enderror" 
                                       id="next_maintenance_date" name="next_maintenance_date" 
                                       value="{{ old('next_maintenance_date') }}">
                                @error('next_maintenance_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save Train</button>
                    <a href="{{ route('trains.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection