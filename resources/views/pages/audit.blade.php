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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <select class="form-select" id="filterUser">
                                    <option value="">All Users</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="filterType">
                                    <option value="">All Types</option>
                                    <option value="Checking Rates">Checking Rates</option>
                                    <option value="Touch Shippers">Touch Shippers</option>
                                    <option value="Report Activities">Report Activities</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="filterAction">
                                    <option value="">All Actions</option>
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
                                    <th>ACTION</th>
                                    <th>DETAIL</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @forelse($logs as $log)
                                <tr data-user-id="{{ $log['user']->id }}" data-type="{{ $log['type'] }}" data-action="{{ $log['action'] }}">
                                    <td>{{ $log['created_at'] ? $log['created_at']->format('d M Y H:i') : '—' }}</td>
                                    <td>{{ $log['user']->name }}</td>
                                    <td>
                                        @if($log['type'] == 'Checking Rates')
                                            <span class="badge bg-info">Checking Rates</span>
                                        @elseif($log['type'] == 'Touch Shippers')
                                            <span class="badge bg-primary">Touch Shippers</span>
                                        @else
                                            <span class="badge bg-secondary">Report Activities</span>
                                        @endif
                                    </td>
                                    <td>{{ $log['description'] }}</td>
                                    <td>
                                        @if($log['action'] == 'Created')
                                            <span class="badge bg-success">Created</span>
                                        @elseif($log['action'] == 'Updated')
                                            <span class="badge bg-warning">Updated</span>
                                        @else
                                            <span class="badge bg-danger">Deleted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info viewAudit" data-type="{{ $log['auditable_type'] }}" data-action="{{ $log['action'] }}" data-old="{{ json_encode($log['old_values']) }}" data-new="{{ json_encode($log['new_values']) }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
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
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title" id="modalTitle">
                                        <span class="fw-mediumbold">Detail</span>
                                        <span class="fw-light">Audit</span>
                                    </h5>
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
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                        <i class="fas fa-window-close"></i> Close
                                    </button>
                                </div>
                            </div>
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
                targets: [1, 2, 3, 4, 5],
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
    });
    $('#filterType').on('change', function() {
        var type = this.value;
        if (type) {
            table.column(2).search('^' + type + '$', true, false).draw();
        } else {
            table.column(2).search('').draw();
        }
    });
    $('#filterAction').on('change', function() {
        var action = this.value;
        if (action) {
            table.column(4).search('^' + action + '$', true, false).draw();
        } else {
            table.column(4).search('').draw();
        }
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
                'shipper_name', 'shipper_type', 'shipper_city', 'shipper_address', 
                'contact_person', 'phone_number', 'email_address', 
                'export', 'import', 'domestic', 'commodity', 'notes'
            ],
            'Activity': [
                'concept_type', 'shipper_id', 'activity_type', 'visit_date', 
                'status', 'status_detail', 'prospect'
            ]
        };
        function formatLabel(key) {
            return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        }
        function formatVal(val) {
            if (val === null || val === undefined || val === '') return '—';
            if (typeof val === 'object') return JSON.stringify(val);
            return val;
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
        var myModal = new bootstrap.Modal(document.getElementById('Viewaudit'));
        myModal.show();
    });
});
</script>
@endsection('script')