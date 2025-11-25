@extends('layouts.main')

@section('title', 'Add Wagon - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Add Wagon</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('wagons.index') }}">Wagons</a></li>
            <li class="breadcrumb-item active">Add Wagon</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Wagon Information</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="{{ route('wagons.store') }}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="train_id">Train <span class="text-danger">*</span></label>
                                <select class="form-control @error('train_id') is-invalid @enderror" 
                                        id="train_id" name="train_id" required>
                                    <option value="">Select Train</option>
                                    @foreach($trains as $train)
                                        <option value="{{ $train->id }}" {{ old('train_id') == $train->id ? 'selected' : '' }}>
                                            {{ $train->train_number }} - {{ $train->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('train_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wagon_number">Wagon Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('wagon_number') is-invalid @enderror" 
                                       id="wagon_number" name="wagon_number" 
                                       placeholder="Enter wagon number (e.g., KAI-0001)" 
                                       value="{{ old('wagon_number') }}" required>
                                @error('wagon_number')
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
                                <label for="wagon_type">Wagon Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('wagon_type') is-invalid @enderror" 
                                        id="wagon_type" name="wagon_type" required>
                                    <option value="">Select Wagon Type</option>
                                    <option value="flatbed" {{ old('wagon_type') == 'flatbed' ? 'selected' : '' }}>Flatbed</option>
                                    <option value="tank" {{ old('wagon_type') == 'tank' ? 'selected' : '' }}>Tank</option>
                                    <option value="box" {{ old('wagon_type') == 'box' ? 'selected' : '' }}>Box</option>
                                    <option value="refrigerated" {{ old('wagon_type') == 'refrigerated' ? 'selected' : '' }}>Refrigerated</option>
                                    <option value="open_top" {{ old('wagon_type') == 'open_top' ? 'selected' : '' }}>Open Top</option>
                                </select>
                                @error('wagon_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="teu_capacity">TEU Capacity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('teu_capacity') is-invalid @enderror" 
                                       id="teu_capacity" name="teu_capacity" 
                                       placeholder="Enter TEU capacity" 
                                       value="{{ old('teu_capacity', 2) }}" min="1" max="4" required>
                                @error('teu_capacity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">Standard: 2 TEUs per wagon</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="loaded" {{ old('status') == 'loaded' ? 'selected' : '' }}>Loaded</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="out-of-service" {{ old('status') == 'out-of-service' ? 'selected' : '' }}>Out of Service</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save Wagon</button>
                    <a href="{{ route('wagons.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection