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
                                    <td>{{ $log['created_at'] ? $log['created_at']->format('d M Y H:i') : '-' }}</td>
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
                                        @if($log['auditable_type'] == 'Rate')
                                            <button type="button" class="btn btn-sm btn-info viewRate" data-id="{{ $log['auditable_id'] }}" data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @elseif($log['auditable_type'] == 'Shipper')
                                            <button type="button" class="btn btn-sm btn-info viewShipper" data-id="{{ $log['auditable_id'] }}" data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @elseif($log['auditable_type'] == 'Activity')
                                            <button type="button" class="btn btn-sm btn-info viewActivity" data-id="{{ $log['auditable_id'] }}" data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @endif
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
                    <div class="modal fade" id="Viewrate" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title" id="modalTitle">
                                        <span class="fw-mediumbold">Detail</span>
                                        <span class="fw-light">Rate</span>
                                    </h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_pol">Port Of Loading</label>
                                                <input type="text" class="form-control-plaintext" id="view_pol" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_pod">Port Of Destination</label>
                                                <input type="text" class="form-control-plaintext" id="view_pod" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_container_type">Container Type</label>
                                                <input type="text" class="form-control-plaintext" id="view_container_type" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_container_20">Container 20FT</label>
                                                <input type="text" class="form-control-plaintext" id="view_container_20" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_container_40">Container 40FT</label>
                                                <input type="text" class="form-control-plaintext" id="view_container_40" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_liner">Liner / Forwarding</label>
                                                <input type="text" class="form-control-plaintext" id="view_liner" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_free_time">Free Time</label>
                                                <input type="text" class="form-control-plaintext" id="view_free_time" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_valid_date">Valid Date</label>
                                                <input type="text" class="form-control-plaintext" id="view_valid_date" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_notes">Notes</label>
                                                <textarea class="form-control-plaintext" id="view_notes" rows="5" readonly></textarea>
                                            </div>
                                        </div>
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
                    <div class="modal fade" id="Viewshipper" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title" id="modalTitle">
                                        <span class="fw-mediumbold">Detail</span>
                                        <span class="fw-light">Shipper</span>
                                    </h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_shipper_name">Shipper Name</label>
                                                <input type="text" class="form-control-plaintext" id="view_shipper_name" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_shipper_type">Shipper Type</label>
                                                <input type="text" class="form-control-plaintext" id="view_shipper_type" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_shipper_city">Shipper City</label>
                                                <input type="text" class="form-control-plaintext" id="view_shipper_city" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_shipper_address">Shipper Address</label>
                                                <input type="text" class="form-control-plaintext" id="view_shipper_address" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_contact_person">Contact Person</label>
                                                <input type="text" class="form-control-plaintext" id="view_contact_person" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_phone_number">Phone Number</label>
                                                <input type="text" class="form-control-plaintext" id="view_phone_number" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_email_address">Email Address</label>
                                                <input type="text" class="form-control-plaintext" id="view_email_address" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_export">Export</label>
                                                <input type="text" class="form-control-plaintext" id="view_export" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_import">Import</label>
                                                <input type="text" class="form-control-plaintext" id="view_import" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_domestic">Domestic</label>
                                                <input type="text" class="form-control-plaintext" id="view_domestic" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_commodity">Commodity</label>
                                                <input type="text" class="form-control-plaintext" id="view_commodity" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_shipper_notes">Notes</label>
                                                <textarea class="form-control-plaintext" id="view_shipper_notes" rows="1" readonly></textarea>
                                            </div>
                                        </div>
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
                    <div class="modal fade" id="Viewactivity" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title" id="modalTitle">
                                        <span class="fw-mediumbold">Detail</span>
                                        <span class="fw-light">Activity</span>
                                    </h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_concept_type">Concept Type</label>
                                                <input type="text" class="form-control-plaintext" id="view_concept_type" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_activity_shipper_name">Shipper Name</label>
                                                <input type="text" class="form-control-plaintext" id="view_activity_shipper_name" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default" id="view_activity_shipper_type_group">
                                                <label class="form-label" for="view_activity_shipper_type">Shipper Type</label>
                                                <input type="text" class="form-control-plaintext" id="view_activity_shipper_type" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default" id="view_activity_commodity_group">
                                                <label class="form-label" for="view_activity_commodity">Commodity</label>
                                                <input type="text" class="form-control-plaintext" id="view_activity_commodity" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_activity_type">Activity Type</label>
                                                <input type="text" class="form-control-plaintext" id="view_activity_type" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default" id="view_visit_date_group">
                                                <label class="form-label" for="view_visit_date">Visit Date</label>
                                                <input type="text" class="form-control-plaintext" id="view_visit_date" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_status">Status</label>
                                                <input type="text" class="form-control-plaintext" id="view_status" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default" id="view_status_detail_group">
                                                <label class="form-label" for="view_status_detail">Status Detail</label>
                                                <input type="text" class="form-control-plaintext" id="view_status_detail" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group form-group-default">
                                                <label class="form-label" for="view_prospect">Prospect</label>
                                                <textarea class="form-control-plaintext" id="view_prospect" rows="1" readonly></textarea>
                                            </div>
                                        </div>
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
    $('body').on('click', '.viewRate', function () {
        var rate_id = $(this).data('id');
        Swal.fire({
            title: 'Loading Data...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        $.get("{{ route('rates.index') }}" + '/' + rate_id + '/edit', function (data) {
            Swal.close();
            $('#view_pol').val(data.pol || '-');
            $('#view_pod').val(data.pod || '-');
            $('#view_container_type').val(data.container_type || '-');
            $('#view_container_20').val(data.container_20 || '-');
            $('#view_container_40').val(data.container_40 || '-');
            $('#view_liner').val(data.liner || '-');
            $('#view_free_time').val(data.free_time || '-');
            if (data.valid_date) {
                var validDate = new Date(data.valid_date);
                var formattedDate = validDate.getDate().toString().padStart(2, '0') + '/' + 
                                  (validDate.getMonth() + 1).toString().padStart(2, '0') + '/' + 
                                  validDate.getFullYear();
                $('#view_valid_date').val(formattedDate);
            } else {
                $('#view_valid_date').val('-');
            }
            if ($('#view_notes').length) {
                $('#view_notes').val(data.notes || '-');
            }
            $('#Viewrate').modal('show');
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Failed to Load Data',
                confirmButtonColor: '#d33'
            });
        });
    });
    $('body').on('click', '.viewShipper', function () {
        var shipper_id = $(this).data('id');
        Swal.fire({
            title: 'Loading Data...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        $.get("{{ route('shippers.index') }}" + '/' + shipper_id + '/edit', function (data) {
            Swal.close();
            $('#view_shipper_name').val(data.shipper_name || '-');
            $('#view_shipper_type').val(data.shipper_type || '-');
            $('#view_shipper_city').val(data.shipper_city || '-');
            $('#view_shipper_address').val(data.shipper_address || '-');
            $('#view_contact_person').val(data.contact_person || '-');
            $('#view_phone_number').val(data.phone_number || '-');
            $('#view_email_address').val(data.email_address || '-');
            $('#view_export').val(data.export || '-');
            $('#view_import').val(data.import || '-');
            $('#view_domestic').val(data.domestic || '-');
            $('#view_commodity').val(data.commodity || '-');
            if ($('#view_shipper_notes').length) {
                $('#view_shipper_notes').val(data.notes || '-');
            }
            $('#Viewshipper').modal('show');
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Failed to Load Data',
                confirmButtonColor: '#d33'
            });
        });
    });
    $('body').on('click', '.viewActivity', function () {
        var activity_id = $(this).data('id');
        Swal.fire({
            title: 'Loading Data...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        $.get("{{ route('activities.index') }}" + '/' + activity_id + '/edit', function (data) {
            Swal.close();
            $('#view_concept_type').val(data.concept_type || '-');
            if (data.shipper) {
                $('#view_activity_shipper_name').val(data.shipper.shipper_name);
                if (data.shipper.shipper_type) {
                    $('#view_activity_shipper_type_group').show();
                    $('#view_activity_shipper_type').val(data.shipper.shipper_type);
                } else {
                    $('#view_activity_shipper_type_group').hide();
                }
                if (data.shipper.commodity) {
                    $('#view_activity_commodity_group').show();
                    $('#view_activity_commodity').val(data.shipper.commodity);
                } else {
                    $('#view_activity_commodity_group').hide();
                }
            } else {
                $('#view_activity_shipper_name').val('-');
                $('#view_activity_shipper_type_group').hide();
                $('#view_activity_commodity_group').hide();
            }
            $('#view_activity_type').val(data.activity_type || '-');
            if (data.visit_date) {
                var visitDate = new Date(data.visit_date);
                var formattedVisitDate = visitDate.getDate().toString().padStart(2, '0') + '/' + 
                                    (visitDate.getMonth() + 1).toString().padStart(2, '0') + '/' + 
                                    visitDate.getFullYear();
                $('#view_visit_date').val(formattedVisitDate);
                $('#view_visit_date_group').show();
            } else {
                $('#view_visit_date').val('-');
                $('#view_visit_date_group').hide();
            }
            $('#view_status').val(data.status || '-');
            if (data.status_detail) {
                $('#view_status_detail').val(data.status_detail);
                $('#view_status_detail_group').show();
            } else {
                $('#view_status_detail').val('-');
                $('#view_status_detail_group').hide();
            }
            $('#view_prospect').val(data.prospect || '-');
            $('#Viewactivity').modal('show');
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Failed to Load Data',
                confirmButtonColor: '#d33'
            });
        });
    });
});
</script>
@endsection('script')