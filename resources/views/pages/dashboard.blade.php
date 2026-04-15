@extends('layouts.app')
@section('title')
Dashboard | Key Perfomance Indicator Marketing
@endsection('title')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
                <h6 class="op-7 mb-2">Welcome, {{ Auth::user()->name }}!</h6>
            </div>
            @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
            <div class="ms-md-auto py-2 py-md-0">
                <form method="GET" action="{{ route('dashboard.index') }}" class="d-inline-flex align-items-center me-2">
                    <label for="user_id" class="form-label me-2 mb-0">Filter :</label>
                    <select name="user_id" id="user_id" class="form-select form-select-sm" style="width: 200px;" onchange="this.form.submit()">
                        <option value="">All Users</option>
                        @if(Auth::user()->isAdmin())
                        <option value="mine" {{ $selectedUserId === 'mine' ? 'selected' : '' }}>
                            My Data
                        </option>
                        @endif
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                </form>
            </div>
            @endif
        </div>
        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
        <div class="row">
            <div class="col-sm-6 col-lg-4">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('rates.index') }}">
                            <span class="stamp stamp-md bg-info me-3">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </a>
                        <div>
                            <h5 class="mb-1">
                                <b>{{ $totalRates }} <small>Submission Checking Rates</small></b>
                            </h5>
                            <small class="text-muted">{{ $ratesThisMonth }} Submitted this Month</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('shippers.index') }}">
                            <span class="stamp stamp-md bg-primary me-3">
                                <i class="fas fa-ship"></i>
                            </span>
                        </a>
                        <div>
                            <h5 class="mb-1">
                                <b>{{ $totalShippers }} <small>Submission Touch Shippers</small></b>
                            </h5>
                            <small class="text-muted">{{ $shippersThisMonth }} Submitted this Month</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('activities.index') }}">
                            <span class="stamp stamp-md bg-secondary me-3">
                                <i class="fas fa-book-open"></i>
                            </span>
                        </a>
                        <div>
                            <h5 class="mb-1">
                                <b>{{ $totalActivities }} <small>Submission Report Activities</small></b>
                            </h5>
                            <small class="text-muted">{{ $activitiesThisMonth }} Submitted this Month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-card-no-pd">
            <div class="col-12 col-sm-6 col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-header text-center">
                        <div class="card-title">Activity Performance</div>
                    </div>
                    <div class="card-body">
                        <div>
                            <p class="text-muted">Target : {{ number_format((float)$performance['activities']['performance']['target'], 0, ',', '.') }}</p>
                            <p class="text-muted">Actual : {{ number_format((float)$performance['activities']['performance']['actual'], 0, ',', '.') }}</p>
                            <p class="text-muted">Remaining : {{ number_format((float)$performance['activities']['performance']['remaining'], 0, ',', '.') }}</p>
                        </div>
                        @php
                            $actPercent = $performance['activities']['performance']['percentage'];
                            $actPercentDisplay = $actPercent > 100 ? 100 : $actPercent;
                        @endphp
                        <div class="progress progress-sm">
                            <div class="progress-bar {{ $actPercent <= 33 ? 'bg-danger' : ($actPercent <= 66 ? 'bg-warning' : 'bg-success') }}"
                                role="progressbar"
                                style="width: {{ $actPercentDisplay }}%"
                                aria-valuenow="{{ $actPercentDisplay }}"
                                aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <p class="text-muted mb-0">Achievement</p>
                            <p class="text-muted mb-0">{{ $actPercentDisplay }}%</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-header text-center">
                        <div class="card-title">Volume Performance</div>
                    </div>
                    <div class="card-body">
                        <div>
                            <p class="text-muted">Target : {{ number_format((float)$performance['volume']['performance']['target'], 0, ',', '.') }}</p>
                            <p class="text-muted">Actual : {{ number_format((float)$performance['volume']['performance']['actual'], 0, ',', '.') }}</p>
                            <p class="text-muted">Remaining : {{ number_format((float)$performance['volume']['performance']['remaining'], 0, ',', '.') }}</p>
                        </div>
                        @php
                            $volPercent = $performance['volume']['performance']['percentage'];
                            $volPercentDisplay = $volPercent > 100 ? 100 : $volPercent;
                        @endphp
                        <div class="progress progress-sm">
                            <div class="progress-bar {{ $volPercent <= 33 ? 'bg-danger' : ($volPercent <= 66 ? 'bg-warning' : 'bg-success') }}"
                                role="progressbar"
                                style="width: {{ $volPercentDisplay }}%"
                                aria-valuenow="{{ $volPercentDisplay }}"
                                aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <p class="text-muted mb-0">Achievement</p>
                            <p class="text-muted mb-0">{{ $volPercentDisplay }}%</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-header text-center">
                        <div class="card-title">Profit Performance</div>
                    </div>
                    <div class="card-body">
                        <div>
                            <p class="text-muted">Target : Rp {{ number_format((float)$performance['profit']['performance']['target'], 0, ',', '.') }}</p>
                            <p class="text-muted">Actual : Rp {{ number_format((float)$performance['profit']['performance']['actual'], 0, ',', '.') }}</p>
                            <p class="text-muted">Remaining : Rp {{ number_format((float)$performance['profit']['performance']['remaining'], 0, ',', '.') }}</p>
                        </div>
                        @php
                            $profPercent = $performance['profit']['performance']['percentage'];
                            $profPercentDisplay = $profPercent > 100 ? 100 : $profPercent;
                        @endphp
                        <div class="progress progress-sm">
                            <div class="progress-bar {{ $profPercent <= 33 ? 'bg-danger' : ($profPercent <= 66 ? 'bg-warning' : 'bg-success') }}"
                                role="progressbar"
                                style="width: {{ $profPercentDisplay }}%"
                                aria-valuenow="{{ $profPercentDisplay }}"
                                aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <p class="text-muted mb-0">Achievement</p>
                            <p class="text-muted mb-0">{{ $profPercentDisplay }}%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title">Annual Profit Overview</div>
                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                        <form method="GET" action="{{ route('dashboard.index') }}" class="d-inline-flex align-items-center">
                            @if($selectedUserId)
                            <input type="hidden" name="user_id" value="{{ $selectedUserId }}">
                            @endif
                            <select name="year" class="form-select form-select-sm" style="width: 110px;" onchange="this.form.submit()">
                                @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                                @endforeach
                            </select>
                        </form>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="multipleLineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <div class="card-title">Today's Summary</div>
                        <p class="mb-0">
                            <small>{{ now()->format('d M Y') }}</small>
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-2">
                                <small>New Shipper</small>
                                <h5 class="font-weight-bold mb-0">{{ $dailyReport->new_shipper_count ?? 0 }}</h5>
                            </div>
                            <div class="col-6 mb-2">
                                <small>Existing Shipper</small>
                                <h5 class="font-weight-bold mb-0">{{ $dailyReport->existing_shipper_count ?? 0 }}</h5>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <div class="col-6 mb-2">
                                <small>Direct Shipper</small>
                                <h5 class="font-weight-bold mb-0">{{ $dailyReport->direct_shipper_count ?? 0 }}</h5>
                            </div>
                            <div class="col-6 mb-2">
                                <small>Forwarding</small>
                                <h5 class="font-weight-bold mb-0">{{ $dailyReport->forwarding_count ?? 0 }}</h5>
                            </div>
                            <div class="col-6 mb-2">
                                <small>Vendoring</small>
                                <h5 class="font-weight-bold mb-0">{{ $dailyReport->vendoring_count ?? 0 }}</h5>
                            </div>
                            <div class="col-6 mb-2">
                                <small>Trading</small>
                                <h5 class="font-weight-bold mb-0">{{ $dailyReport->trading_count ?? 0 }}</h5>
                            </div>
                        </div>
                        <hr>
                        <p class="mb-2 text-center">
                            <span class="badge badge-success">CLOSING : {{ $dailyReport->closing_count ?? 0 }}</span>
                            <span class="badge badge-warning">PENDING : {{ $dailyReport->pending_count ?? 0 }}</span>
                            <span class="badge badge-danger">FAILED : {{ $dailyReport->failed_count ?? 0 }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <div class="card-title">This Week's Summary</div>
                        <p class="mb-0">
                            <small>{{ now()->startOfWeek()->format('d M') }} - {{ now()->endOfWeek()->format('d M Y') }}</small>
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-2">
                                <small>New Shipper</small>
                                <h5 class="font-weight-bold mb-0">{{ $weeklyReport->new_shipper_count ?? 0 }}</h5>
                            </div>
                            <div class="col-6 mb-2">
                                <small>Existing Shipper</small>
                                <h5 class="font-weight-bold mb-0">{{ $weeklyReport->existing_shipper_count ?? 0 }}</h5>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <div class="col-6 mb-2">
                                <small>Direct Shipper</small>
                                <h5 class="font-weight-bold mb-0">{{ $weeklyReport->direct_shipper_count ?? 0 }}</h5>
                            </div>
                            <div class="col-6 mb-2">
                                <small>Forwarding</small>
                                <h5 class="font-weight-bold mb-0">{{ $weeklyReport->forwarding_count ?? 0 }}</h5>
                            </div>
                            <div class="col-6 mb-2">
                                <small>Vendoring</small>
                                <h5 class="font-weight-bold mb-0">{{ $weeklyReport->vendoring_count ?? 0 }}</h5>
                            </div>
                            <div class="col-6 mb-2">
                                <small>Trading</small>
                                <h5 class="font-weight-bold mb-0">{{ $weeklyReport->trading_count ?? 0 }}</h5>
                            </div>
                        </div>
                        <hr>
                        <p class="mb-2 text-center">
                            <span class="badge badge-success">CLOSING : {{ $weeklyReport->closing_count ?? 0 }}</span>
                            <span class="badge badge-warning">PENDING : {{ $weeklyReport->pending_count ?? 0 }}</span>
                            <span class="badge badge-danger">FAILED : {{ $weeklyReport->failed_count ?? 0 }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <div class="card-title">This Month's Summary</div>
                        <p class="mb-0">
                            <small>{{ now()->format('F Y') }}</small>
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-2">
                                <small>New Shipper</small>
                                <h5 class="font-weight-bold mb-0">{{ $monthlyReport->new_shipper_count ?? 0 }}</h5>
                            </div>
                            <div class="col-6 mb-2">
                                <small>Existing Shipper</small>
                                <h5 class="font-weight-bold mb-0">{{ $monthlyReport->existing_shipper_count ?? 0 }}</h5>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <div class="col-6 mb-2">
                                <small>Direct Shipper</small>
                                <h5 class="font-weight-bold mb-0">{{ $monthlyReport->direct_shipper_count ?? 0 }}</h5>
                            </div>
                            <div class="col-6 mb-2">
                                <small>Forwarding</small>
                                <h5 class="font-weight-bold mb-0">{{ $monthlyReport->forwarding_count ?? 0 }}</h5>
                            </div>
                            <div class="col-6 mb-2">
                                <small>Vendoring</small>
                                <h5 class="font-weight-bold mb-0">{{ $monthlyReport->vendoring_count ?? 0 }}</h5>
                            </div>
                            <div class="col-6 mb-2">
                                <small>Trading</small>
                                <h5 class="font-weight-bold mb-0">{{ $monthlyReport->trading_count ?? 0 }}</h5>
                            </div>
                        </div>
                        <hr>
                        <p class="mb-2 text-center">
                            <span class="badge badge-success">CLOSING : {{ $monthlyReport->closing_count ?? 0 }}</span>
                            <span class="badge badge-warning">PENDING : {{ $monthlyReport->pending_count ?? 0 }}</span>
                            <span class="badge badge-danger">FAILED : {{ $monthlyReport->failed_count ?? 0 }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-title">Recent Activity Logs</div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>DATE & TIME</th>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                        <th>USER</th>
                                        @endif
                                        <th>TYPE</th>
                                        <th>DESCRIPTION</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center text-nowrap">
                                    @forelse($logs as $log)
                                    <tr>
                                        <td data-order="{{ $log['created_at']->format('Y-m-d') }}">
                                            {{ $log['created_at'] ? $log['created_at']->format('d M Y H:i') : '—' }}
                                        </td>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                        <td>{{ optional($log['user'])->name ?? 'System' }}</td>
                                        @endif
                                        <td>
                                            @if($log['type'] == 'Checking Rates')
                                                <span class="badge bg-info">Checking Rates</span>
                                            @elseif($log['type'] == 'Touch Shippers')
                                                <span class="badge bg-primary">Touch Shippers</span>
                                            @elseif($log['type'] == 'Report Activities')
                                                <span class="badge bg-secondary">Report Activities</span>
                                            @else
                                                <span class="badge bg-dark">User Management</span>
                                            @endif
                                        </td>
                                        <td>{{ $log['description'] }}</td>
                                        <td>
                                            @if($log['action'] == 'Created')
                                                <span class="badge bg-success">Created</span>
                                            @elseif($log['action'] == 'Updated')
                                                <span class="badge bg-warning">Updated</span>
                                            @elseif($log['action'] == 'Deleted')
                                                <span class="badge bg-danger">Deleted</span>
                                            @else
                                                <span class="badge bg-light text-dark">Exported</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                        <td colspan="5" class="text-center text-muted">
                                            No Activity Logs Available.
                                        </td>
                                        @else
                                        <td colspan="4" class="text-center text-muted">
                                            No Activity Logs Available.
                                        </td>
                                        @endif
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(Auth::user()->isGuest())
        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 50vh;">
                        <h3 class="mb-3">Welcome to Key Perfomance Indicator Marketing</h3>
                        <p class="text-muted">You have view-only access to rates and shippers data.</p>
                        <div class="mt-4">
                            <a href="{{ route('rates.index') }}" class="btn btn-primary me-2">
                                <i class="fas fa-dollar-sign"></i> View Checking Rates
                            </a>
                            <a href="{{ route('shippers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-ship"></i> View Touch Shippers
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
@section('script')
<script>
var chartCanvas = document.getElementById("multipleLineChart");
if (chartCanvas) {
    var lineChartDatasets = @json($line['datasets']);
    var lineChartLabels   = @json($line['labels']);
    var isMultiLine       = lineChartDatasets.length > 1;
    var multipleLineChart = chartCanvas.getContext("2d");
    var myMultipleLineChart = new Chart(multipleLineChart, {
        type: "line",
        data: {
            labels: lineChartLabels,
            datasets: lineChartDatasets,
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: isMultiLine,
                position: "top",
                onClick: isMultiLine
                    ? Chart.defaults.global.legend.onClick
                    : function(e) { return false; }
            },
            tooltips: {
                bodySpacing: 4,
                mode: "nearest",
                intersect: 0,
                position: "nearest",
                xPadding: 10,
                yPadding: 10,
                caretPadding: 10,
                callbacks: {
                    title: function(tooltipItems) {
                        return tooltipItems[0].xLabel + " {{ $selectedYear }}";
                    },
                    label: function(tooltipItem, data) {
                        var label = data.datasets[tooltipItem.datasetIndex].label;
                        var value = tooltipItem.yLabel;
                        return " " + label + ": Rp " + value.toLocaleString("id-ID");
                    }
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        callback: function(value) {
                            return "Rp " + value.toLocaleString("id-ID");
                        }
                    }
                }]
            },
            layout: {
                padding: { left: 15, right: 15, top: 15, bottom: 15 },
            },
        },
    });
}
</script>
@endsection('script')