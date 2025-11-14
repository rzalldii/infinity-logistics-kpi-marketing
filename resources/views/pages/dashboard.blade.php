@extends('layouts.app')
@section('title')
Dashboard | Admin Infinity Logistics Indonesi
@endsection('title')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
                <h6 class="op-7 mb-2">Welcome, {{ Auth::user()->name }}!</h6>
            </div>
        </div>
        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-6 ms-sm-3">
                                <div class="numbers">
                                    <p class="card-category">Last Submission - Checking Rates</p>
                                    @if($lastRate)
                                    <h4 class="card-title">{{ Str::upper($lastRate->pol) }} - {{ Str::upper($lastRate->pod) }}</h4>
                                    <small class="text-muted">
                                        <i class="far fa-clock"></i> 
                                        {{ $lastRate->created_at->diffForHumans() }}
                                        @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                                        <br>
                                        <i class="far fa-user"></i> Submitted by {{ $lastRate->user->name }}
                                        @endif
                                    </small>
                                    @else
                                    <h4 class="card-title text-muted">No Data Available</h4>
                                    <small class="text-muted">Begin by adding your first rate entry</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto ms-auto">
                                <a href="{{ route('rates.index') }}" class="btn btn-link btn-lg text-primary">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                    <i class="fas fa-list-ul"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-6 ms-sm-3">
                                <div class="numbers">
                                    <p class="card-category">Last Submission - Touch Shippers</p>
                                    @if($lastShipper)
                                    <h4 class="card-title">{{ Str::upper($lastShipper->shipper_name) }}</h4>
                                    <small class="text-muted">
                                        <i class="far fa-clock"></i> {{ $lastShipper->created_at->diffForHumans() }}
                                        @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                                        <br>
                                        <i class="far fa-user"></i> Submitted by {{ $lastShipper->user->name }}
                                        @endif
                                    </small>
                                    @else
                                    <h4 class="card-title text-muted">No Data Available</h4>
                                    <small class="text-muted">Begin by adding your first shipper entry</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto ms-auto">
                                <a href="{{ route('shippers.index') }}" class="btn btn-link btn-lg text-secondary">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">User Statistics</div>
                            <div class="card-tools">
                                <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                                    <span class="btn-label">
                                        <i class="fa fa-pencil"></i>
                                    </span>
                                    Export
                                </a>
                                <a href="#" class="btn btn-label-info btn-round btn-sm">
                                    <span class="btn-label">
                                        <i class="fa fa-print"></i>
                                    </span>
                                    Print
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="min-height: 375px">
                            <canvas id="statisticsChart"></canvas>
                        </div>
                        <div id="myChartLegend"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(Auth::user()->isGuest())
        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-body text-center py-5">
                        <h3 class="mb-3">Welcome to Admin Infinity Logistics Indonesia</h3>
                        <p class="text-muted">You have view-only access to rates and shippers data.</p>
                        <div class="mt-4">
                            <a href="/rates" class="btn btn-primary me-2">
                                <i class="fas fa-dollar-sign"></i> View Checking Rates
                            </a>
                            <a href="/shippers" class="btn btn-secondary">
                                <i class="fas fa-list-ul"></i> View Touch Shippers
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection('content')