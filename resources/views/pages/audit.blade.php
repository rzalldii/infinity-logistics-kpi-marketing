@extends('layouts.app')
@section('title')
Audit Logs | Admin Infinity Logistics Indonesia
@endsection('title')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Audit Logs</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-6 ms-sm-3">
                                <div class="numbers">
                                    <p class="card-category">Total Rates</p>
                                    <h4 class="card-title" id="totalRates">{{ $totalRates }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-list-ul"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-6 ms-sm-3">
                                <div class="numbers">
                                    <p class="card-category">Total Shippers</p>
                                    <h4 class="card-title" id="totalShippers">{{ $totalShippers }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                    <i class="fas fa-book-open"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-6 ms-sm-3">
                                <div class="numbers">
                                    <p class="card-category">Total Activities</p>
                                    <h4 class="card-title" id="totalActivities">{{ $totalActivities }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-history"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-6 ms-sm-3">
                                <div class="numbers">
                                    <p class="card-category">Total Logs</p>
                                    <h4 class="card-title" id="totalLogs">{{ $totalLogs }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="filterUser">
                                    <option value="">All Users</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filterType">
                                    <option value="">All Types</option>
                                    <option value="Checking Rates">Checking Rates</option>
                                    <option value="Touch Shippers">Touch Shippers</option>
                                    <option value="Report Activities">Report Activities</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filterDetail">
                                    <option value="">All Details</option>
                                    <option value="Created">Created</option>
                                    <option value="Updated">Updated</option>
                                    <option value="Deleted">Deleted</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                            <table id="audit-table" class="display table table-striped table-hover" style="width:100%">
                                <thead class="text-center">
                                    <tr>
                                        <th>DATE & TIME</th>
                                        <th>USER</th>
                                        <th>TYPE</th>
                                        <th>DESCRIPTION</th>
                                        <th>DETAIL</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @forelse($logs as $log)
                                    <tr data-user-id="{{ $log['user']->id }}" data-type="{{ $log['type'] }}" data-detail="{{ $log['detail'] }}">
                                        <td>{{ $log['created_at'] ? $log['created_at']->format('d M Y H:i') : '-' }}</td>
                                        <td>{{ $log['user']->name }}</td>
                                        <td>{{ $log['type'] }}</td>
                                        <td>{{ $log['description'] }}</td>
                                        <td>
                                            @if($log['detail'] == 'Created')
                                                <span class="badge bg-success">Created</span>
                                            @elseif($log['detail'] == 'Updated')
                                                <span class="badge bg-warning">Updated</span>
                                            @else
                                                <span class="badge bg-danger">Deleted</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No Audit Logs Available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection('content')
@section('script')
<script>
$(document).ready(function () {
    var table = $('#audit-table').DataTable({
        pageLength: 10,
        order: [[0, 'desc']],
        columnDefs: [
            { 
                orderable: false,
                targets: [3, 4],
            },
        ]
    });
    $('#filterUser').on('change', function() {
        var userId = this.value;
        if (userId) {
            var userName = $('#filterUser option:selected').text();
            table.column(1).search('^' + userName + '$', true, false).draw();
        } else {
            table.column(1).search('').draw();
        }
        updateStatistics();
    });
    $('#filterType').on('change', function() {
        var type = this.value;
        if (type) {
            table.column(2).search('^' + type + '$', true, false).draw();
        } else {
            table.column(2).search('').draw();
        }
        updateStatistics();
    });
    $('#filterDetail').on('change', function() {
        var detail = this.value;
        if (detail) {
            table.column(4).search('^' + detail + '$', true, false).draw();
        } else {
            table.column(4).search('').draw();
        }
        updateStatistics();
    });
    function updateStatistics() {
        var selectedUserId = $('#filterUser').val();
        var selectedType = $('#filterType').val();
        var selectedDetail = $('#filterDetail').val();
        
        var totalRates = 0;
        var totalShippers = 0;
        var totalActivities = 0;
        var totalLogs = 0;
        $('#audit-table tbody tr').each(function() {
            var rowUserId = $(this).data('user-id');
            var rowType = $(this).data('type');
            var rowDetail = $(this).data('detail');
            
            if (!rowUserId || !rowType) return;
            
            if (selectedUserId && rowUserId != selectedUserId) {
                return;
            }
            
            if (selectedType && rowType !== selectedType) {
                return;
            }
            
            if (rowType === 'Checking Rates') {
                totalRates++;
            } else if (rowType === 'Touch Shippers') {
                totalShippers++;
            } else if (rowType === 'Report Activities') {
                totalActivities++;
            }
            totalLogs++;
        });
        animateValue('totalRates', parseInt($('#totalRates').text()), totalRates, 300);
        animateValue('totalShippers', parseInt($('#totalShippers').text()), totalShippers, 300);
        animateValue('totalActivities', parseInt($('#totalActivities').text()), totalActivities, 300);
        animateValue('totalLogs', parseInt($('#totalLogs').text()), totalLogs, 300);
    }
    function animateValue(id, start, end, duration) {
        if (start === end) {
            document.getElementById(id).innerHTML = end;
            return;
        }
        
        var range = end - start;
        var current = start;
        var increment = end > start ? 1 : -1;
        var stepTime = Math.abs(Math.floor(duration / range));
        
        if (stepTime < 1) stepTime = 1;
        
        var obj = document.getElementById(id);
        var timer = setInterval(function() {
            current += increment;
            obj.innerHTML = current;
            if (current == end) {
                clearInterval(timer);
            }
        }, stepTime);
    }
});
</script>
@endsection('script')