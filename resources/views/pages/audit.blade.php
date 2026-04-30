@extends('layouts.app')
@section('title')
Audit Logs | KPI - Marketing
@endsection
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h1 class="card-title">Audit Logs</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="row g-2 mb-2">
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <select class="form-select" id="filterUser">
                                                <option value="">All Users</option>
                                                @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <select class="form-select" id="filterType">
                                                <option value="">All Types</option>
                                                <option value="Checking Rates">Checking Rates</option>
                                                <option value="Touch Shippers">Touch Shippers</option>
                                                <option value="Report Activities">Report Activities</option>
                                                <option value="User Management">User Management</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <select class="form-select" id="filterAction">
                                                <option value="">All Actions</option>
                                                <option value="Created">Created</option>
                                                <option value="Updated">Updated</option>
                                                <option value="Deleted">Deleted</option>
                                                <option value="Exported">Exported</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2" id="clearFilterRow" style="display: none;">
                                    <div class="col-12 text-end">
                                        <button type="button" class="btn btn-link text-danger btn-sm text-decoration-none" id="clearFilters">
                                            <i class="fas fa-times-circle"></i> Clear All Filters
                                        </button>
                                    </div>
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
                                        <th>ACTION</th>
                                        <th>DETAIL</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center text-nowrap">
                                    @forelse($logs as $log)
                                    <tr data-user-id="{{ optional($log['user'])->id }}" data-type="{{ $log['type'] }}" data-action="{{ $log['action'] }}">
                                        <td data-order="{{ $log['created_at']->format('Y-m-d') }}">
                                            {{ $log['created_at'] ? $log['created_at']->format('d M Y H:i') : '—' }}
                                        </td>
                                        <td>{{ optional($log['user'])->name ?? 'System' }}</td>
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
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if($log['auditable_id'] != 0)
                                                    <button type="button" class="btn btn-sm btn-info viewAudit"
                                                        data-type="{{ class_basename($log['auditable_type']) }}"
                                                        data-action="{{ $log['action'] }}"
                                                        data-old="{{ json_encode($log['old_values']) }}"
                                                        data-new="{{ json_encode($log['new_values']) }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No Audit Logs Available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="modal fade" id="Viewaudit" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold">Detail</span>
                                            <span class="fw-light">Audit</span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="auditChangesTable">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30%">Field</th>
                                                        <th style="width: 35%">Old Value</th>
                                                        <th style="width: 35%">New Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
$(document).ready(function () {
    var table = $('#audit-table').DataTable({
        pageLength: 10,
        order: [[0, 'desc']],
        columnDefs: [
            { 
                orderable: false,
                targets: [1, 2, 3, 4, 5],
            },
        ]
    });
    function checkFilters() {
        var hasFilter = 
            $('#filterUser').val() !== '' ||
            $('#filterType').val() !== '' ||
            $('#filterAction').val() !== '';
        if (hasFilter) {
            $('#clearFilterRow').fadeIn();
        } else {
            $('#clearFilterRow').fadeOut();
        }
    }
    var activeUserFilter = null;
    $('#filterUser').on('change', function() {
        var selectedUserId = $(this).val();
        if (activeUserFilter) {
            var idx = $.fn.dataTable.ext.search.indexOf(activeUserFilter);
            if (idx > -1) $.fn.dataTable.ext.search.splice(idx, 1);
            activeUserFilter = null;
        }
        if (selectedUserId) {
            activeUserFilter = function(settings, data, dataIndex) {
                var row = table.row(dataIndex).node();
                return $(row).data('user-id') == selectedUserId;
            };
            $.fn.dataTable.ext.search.push(activeUserFilter);
        }
        table.draw();
        checkFilters();
    });
    $('#filterType, #filterAction').on('change', function () {
        var mapIdToColumn = {
            'filterType': 2,
            'filterAction': 4
        };
        var targetIndex = mapIdToColumn[this.id];
        if (typeof targetIndex !== 'undefined') {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            table.column(targetIndex).search(val ? '^' + val + '$' : '', true, false).draw();
            checkFilters();
        }
    });
    $('#clearFilters').on('click', function() {
        if (activeUserFilter) {
            var idx = $.fn.dataTable.ext.search.indexOf(activeUserFilter);
            if (idx > -1) $.fn.dataTable.ext.search.splice(idx, 1);
            activeUserFilter = null;
        }
        $('#filterUser, #filterType, #filterAction').val('');
        table.order([0, 'desc']);
        table.search('').columns().search('').draw();
        checkFilters();
    });
    $('body').on('click', '.viewAudit', function() {
        var type = $(this).data('type');
        var action = $(this).data('action');
        var oldVal = $(this).data('old') || {};
        var newVal = $(this).data('new') || {};
        var tbody = $('#auditChangesTable tbody');
        tbody.empty();
        var fieldOrder = {
            'Rate': [
                'pol', 'pod', 'container_type', 'container_20', 'container_40', 
                'liner', 'free_time', 'valid_date', 'notes'
            ],
            'Shipper': [
                'shipper_name', 'shipper_concept', 'shipper_type', 'shipper_city', 
                'shipper_address', 'contact_person', 'phone_number', 'email_address', 
                'export', 'import', 'domestic', 'commodity', 'notes'
            ],
            'Activity': [
                'created_at', 'shipper_name', 'activity_type', 'visit_date', 'status_type', 
                'volume_20', 'volume_40', 'other_volume', 'profit', 'remarks'
            ],
            'User': [
                'name', 'email', 'password', 'role', 'target_activity',
                'target_volume', 'target_profit'
            ]
        };
        function formatLabel(key) {
            var label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            return $('<div>').text(label).html();
        }
        function formatVal(val) {
            if (val === null || val === undefined || val === '') return '—';
            if (typeof val === 'object') return JSON.stringify(val);
            return $('<div>').text(String(val)).html();
        }
        var columnsToShow = fieldOrder[type] ? fieldOrder[type] : Object.keys({...oldVal, ...newVal});
        $.each(columnsToShow, function(i, key) {
            var rawOld = oldVal[key]; 
            var rawNew = newVal[key];
            var oldContent = formatVal(rawOld);
            var newContent = formatVal(rawNew);
            var isChanged = String(rawOld ?? '') !== String(rawNew ?? '');
            if (action === 'Updated') {
                if (isChanged) {
                    newContent = `<span class="text-success">${newContent}</span>`;
                    oldContent = `<span class="text-danger">${oldContent}</span>`;
                } else {
                    oldContent = `<span class="text-muted">${oldContent}</span>`;
                    newContent = `<span class="text-muted">${newContent}</span>`;
                }
            } else if (action === 'Created') {
                oldContent = '<span class="text-muted">Created</span>';
                newContent = `<span class="text-success">${newContent}</span>`;
            } else if (action === 'Deleted') {
                oldContent = `<span class="text-danger">${oldContent}</span>`;
                newContent = '<span class="text-muted">Deleted</span>';
            }
            tbody.append(`
                <tr>
                    <td class="fw-bold">${formatLabel(key)}</td>
                    <td>${oldContent}</td>
                    <td>${newContent}</td>
                </tr>
            `);
        });
        var modalEl = document.getElementById('Viewaudit');
        var myModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        myModal.show();
    });
});
</script>
@endsection