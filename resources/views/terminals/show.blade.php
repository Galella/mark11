@extends('layouts.main')

@section('title', $terminal->name . ' - Terminal Management System')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">{{ $terminal->name }}</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('terminals.index') }}">Terminals</a></li>
            <li class="breadcrumb-item active">{{ $terminal->name }}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Terminal Details</h3>
                <div class="card-tools">
                    <a href="{{ route('terminals.edit', $terminal) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('terminals.index') }}" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-building"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Name</span>
                                <span class="info-box-number">{{ $terminal->name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-tag"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Code</span>
                                <span class="info-box-number">{{ $terminal->code }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-map-marker-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Location</span>
                                <span class="info-box-number">{{ $terminal->location }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-phone"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Contact Phone</span>
                                <span class="info-box-number">{{ $terminal->contact_phone ?: 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-address-card"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Address</span>
                                <span class="info-box-number">{{ $terminal->address ?: 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Contact Person</span>
                                <span class="info-box-number">{{ $terminal->contact_person ?: 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-indigo elevation-1"><i class="fas fa-toggle-on"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Status</span>
                                <span class="info-box-number">
                                    <span class="badge {{ $terminal->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $terminal->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <h5>Terminal Statistics</h5>
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-boxes"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Active Containers</span>
                                        <span class="info-box-number">{{ $terminal->activeInventories()->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-truck"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Gate In/Out</span>
                                        <span class="info-box-number">{{ $terminal->gateTransactions()->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-train"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Rail In/Out</span>
                                        <span class="info-box-number">{{ $terminal->railTransactions()->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-clock"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Avg Dwell Time</span>
                                        <span class="info-box-number">N/A</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection