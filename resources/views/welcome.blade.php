@extends('layouts.main')

@section('title', 'AdminLTE Laravel - Welcome')

@section('content-header')
<div class="row">
    <div class="col-sm-6">
        <h1 class="m-0">Welcome to Laravel</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Laravel Welcome Page</h5>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="bi bi-dash"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center">
                            <h2>Laravel {{ Illuminate\Foundation\Application::VERSION }}</h2>
                            <p class="lead">Welcome to your Laravel application!</p>
                        </div>
                        
                        <div class="mt-4">
                            @if (Route::has('login'))
                                <div class="d-flex justify-content-center gap-3">
                                    @auth
                                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                                            Dashboard
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                            Log in
                                        </a>
                                        
                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="btn btn-outline-success">
                                                Register
                                            </a>
                                        @endif
                                    @endauth
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Documentation</span>
                                <span class="info-box-number">
                                    <a href="https://laravel.com/docs" target="_blank" class="btn btn-info btn-sm">Read</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-book"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tutorials</span>
                                <span class="info-box-number">
                                    <a href="https://laracasts.com" target="_blank" class="btn btn-success btn-sm">Watch</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-laptop-code"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Examples</span>
                                <span class="info-box-number">
                                    <a href="https://github.com/laravel/laravel" target="_blank" class="btn btn-warning btn-sm">Explore</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="mb-0">Thank you for using Laravel! We hope you enjoy your experience.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Add any additional scripts here if needed
document.addEventListener('DOMContentLoaded', function() {
    console.log('Welcome page loaded with AdminLTE layout');
});
</script>
@endsection