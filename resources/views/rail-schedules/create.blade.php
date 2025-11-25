@extends('layouts.main')

@section('title', 'Add Rail Schedule - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Add Rail Schedule</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('rail-schedules.index') }}">Rail Schedules</a></li>
            <li class="breadcrumb-item active">Add Schedule</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Rail Schedule Information</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="{{ route('rail-schedules.store') }}">
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
                                <label for="schedule_code">Schedule Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('schedule_code') is-invalid @enderror" 
                                       id="schedule_code" name="schedule_code" 
                                       placeholder="Enter schedule code (e.g., JKT-SBY-20241201)" 
                                       value="{{ old('schedule_code') }}" required>
                                @error('schedule_code')
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
                                <label for="origin_terminal_id">Origin Terminal <span class="text-danger">*</span></label>
                                <select class="form-control @error('origin_terminal_id') is-invalid @enderror" 
                                        id="origin_terminal_id" name="origin_terminal_id" required>
                                    <option value="">Select Origin Terminal</option>
                                    @foreach($terminals as $terminal)
                                        <option value="{{ $terminal->id }}" {{ old('origin_terminal_id') == $terminal->id ? 'selected' : '' }}>
                                            {{ $terminal->name }} ({{ $terminal->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('origin_terminal_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="destination_terminal_id">Destination Terminal <span class="text-danger">*</span></label>
                                <select class="form-control @error('destination_terminal_id') is-invalid @enderror" 
                                        id="destination_terminal_id" name="destination_terminal_id" required>
                                    <option value="">Select Destination Terminal</option>
                                    @foreach($terminals as $terminal)
                                        <option value="{{ $terminal->id }}" {{ old('destination_terminal_id') == $terminal->id ? 'selected' : '' }}
                                                {{ old('origin_terminal_id') == $terminal->id ? 'disabled' : '' }}>
                                            {{ $terminal->name }} ({{ $terminal->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('destination_terminal_id')
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
                                <label for="departure_time">Departure Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('departure_time') is-invalid @enderror" 
                                       id="departure_time" name="departure_time" 
                                       value="{{ old('departure_time') }}" required>
                                @error('departure_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="arrival_time">Arrival Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('arrival_time') is-invalid @enderror" 
                                       id="arrival_time" name="arrival_time" 
                                       value="{{ old('arrival_time') }}" required>
                                @error('arrival_time')
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
                                <label for="expected_teus">Expected TEUs <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('expected_teus') is-invalid @enderror" 
                                       id="expected_teus" name="expected_teus" 
                                       placeholder="Enter expected TEUs" 
                                       value="{{ old('expected_teus', 20) }}" min="1" required>
                                @error('expected_teus')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="scheduled" {{ old('status', 'scheduled') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="departed" {{ old('status') == 'departed' ? 'selected' : '' }}>Departed</option>
                                    <option value="in-transit" {{ old('status') == 'in-transit' ? 'selected' : '' }}>In Transit</option>
                                    <option value="arrived" {{ old('status') == 'arrived' ? 'selected' : '' }}>Arrived</option>
                                    <option value="delayed" {{ old('status') == 'delayed' ? 'selected' : '' }}>Delayed</option>
                                </select>
                                @error('status')
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
                    <button type="submit" class="btn btn-primary">Save Schedule</button>
                    <a href="{{ route('rail-schedules.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection