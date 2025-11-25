@extends('layouts.main')

@section('title', 'Rail Out (UFR) - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Rail Out (UFR)</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('rail.transactions') }}">Rail Operations</a></li>
            <li class="breadcrumb-item active">Rail Out (UFR)</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title">Rail Out - Unload from Rail (UFR)</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="{{ route('rail.process-out') }}">
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
                                <label for="rail_schedule_id">Rail Schedule <span class="text-danger">*</span></label>
                                <select class="form-control @error('rail_schedule_id') is-invalid @enderror" 
                                        id="rail_schedule_id" name="rail_schedule_id" required>
                                    <option value="">Select Rail Schedule</option>
                                    @foreach($schedules as $schedule)
                                        <option value="{{ $schedule->id }}" {{ old('rail_schedule_id') == $schedule->id ? 'selected' : '' }}>
                                            {{ $schedule->schedule_code }} - {{ $schedule->train->name }} ({{ $schedule->origin_terminal->name }} to {{ $schedule->destination_terminal->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('rail_schedule_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wagon_position">Wagon Position <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('wagon_position') is-invalid @enderror" 
                                       id="wagon_position" name="wagon_position" 
                                       placeholder="Enter wagon position (e.g., #01, #02)" 
                                       value="{{ old('wagon_position') }}" required>
                                @error('wagon_position')
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
                                <label for="operation_type">Operation Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('operation_type') is-invalid @enderror" 
                                        id="operation_type" name="operation_type" required>
                                    <option value="UFR" {{ old('operation_type') == 'UFR' ? 'selected' : '' }}>UFR (Unload from Rail)</option>
                                </select>
                                @error('operation_type')
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
                    <button type="submit" class="btn btn-danger">Process Rail Out (UFR)</button>
                    <a href="{{ route('rail.transactions') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection