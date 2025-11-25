@extends('layouts.main')

@section('title', 'Add Container - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Add Container</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('containers.index') }}">Containers</a></li>
            <li class="breadcrumb-item active">Add Container</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Container Information</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="{{ route('containers.store') }}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="number">Container Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('number') is-invalid @enderror" 
                                       id="number" name="number" 
                                       placeholder="Enter container number (e.g., ABCU1234567)" 
                                       value="{{ old('number') }}" maxlength="11" required>
                                @error('number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">Format: 4 letters + 7 digits (e.g., ABCU1234567)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('type') is-invalid @enderror" 
                                       id="type" name="type" 
                                       placeholder="Enter type (e.g., 22G1, 45G1)" 
                                       value="{{ old('type') }}" maxlength="4" required>
                                @error('type')
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
                                <label for="size_type">Size Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('size_type') is-invalid @enderror" 
                                        id="size_type" name="size_type" required>
                                    <option value="">Select Size Type</option>
                                    <option value="20GP" {{ old('size_type') == '20GP' ? 'selected' : '' }}>20' General Purpose</option>
                                    <option value="40GP" {{ old('size_type') == '40GP' ? 'selected' : '' }}>40' General Purpose</option>
                                    <option value="20HC" {{ old('size_type') == '20HC' ? 'selected' : '' }}>20' High Cube</option>
                                    <option value="40HC" {{ old('size_type') == '40HC' ? 'selected' : '' }}>40' High Cube</option>
                                    <option value="20OT" {{ old('size_type') == '20OT' ? 'selected' : '' }}>20' Open Top</option>
                                    <option value="40OT" {{ old('size_type') == '40OT' ? 'selected' : '' }}>40' Open Top</option>
                                    <option value="20RF" {{ old('size_type') == '20RF' ? 'selected' : '' }}>20' Refrigerated</option>
                                    <option value="40RF" {{ old('size_type') == '40RF' ? 'selected' : '' }}>40' Refrigerated</option>
                                </select>
                                @error('size_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category">Category <span class="text-danger">*</span></label>
                                <select class="form-control @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="import" {{ old('category') == 'import' ? 'selected' : '' }}>Import</option>
                                    <option value="export" {{ old('category') == 'export' ? 'selected' : '' }}>Export</option>
                                    <option value="transhipment" {{ old('category') == 'transhipment' ? 'selected' : '' }}>Transhipment</option>
                                </select>
                                @error('category')
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
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="full" {{ old('status') == 'full' ? 'selected' : '' }}>Full</option>
                                    <option value="empty" {{ old('status') == 'empty' ? 'selected' : '' }}>Empty</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="iso_code">ISO Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('iso_code') is-invalid @enderror" 
                                       id="iso_code" name="iso_code" 
                                       placeholder="Enter ISO code (4 characters)" 
                                       value="{{ old('iso_code') }}" maxlength="4" required>
                                @error('iso_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror" 
                               id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Is Active</label>
                        @error('is_active')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save Container</button>
                    <a href="{{ route('containers.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection