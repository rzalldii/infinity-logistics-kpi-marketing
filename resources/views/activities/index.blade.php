@extends('layouts.app')
@section('title')
Report Activities | Admin Infinity Logistics Indonesia
@endsection('title')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h1 class="fw-bold mb-3">Report Activities</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="card-title">Daily Report</div>
                            <button class="btn btn-primary btn-round ms-auto" id="createNewActivity" data-bs-toggle="tooltip" title="Add">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="modal fade" id="activityModal" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title" id="modalTitle">
                                            <span class="fw-mediumbold">New</span>
                                            <span class="fw-light">Activity</span>
                                        </h5>
                                    </div>
                                    <div class="modal-body">
                                        <form id="activityForm">
                                            @csrf
                                            <input type="hidden" name="activity_id" id="activity_id">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="report_date">Report Date <span class="text-danger">*</span></label>
                                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                                        <input type="date" class="form-control" name="report_date" id="report_date" value="{{ date('Y-m-d') }}" required>
                                                        @else
                                                        <input type="date" class="form-control" name="report_date" id="report_date" value="{{ date('Y-m-d') }}" readonly required>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="concept_type">Concept Type <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="concept_type" id="concept_type" required>
                                                            <option value="" disabled selected>Select Concept Type</option>
                                                            <option value="new_shipper">NEW SHIPPER</option>
                                                            <option value="follow_up">FOLLOW UP</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="shipper_id">Shipper Name <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="shipper_id" id="shipper_id" required>
                                                            <option value="" disabled selected>Select Shipper Name</option>
                                                            @foreach($shippers as $shipper)
                                                            <option value="{{ $shipper->id }}" data-type="{{ $shipper->shipper_type }}">
                                                            {{ Str::upper($shipper->shipper_name) }} - {{ Str::upper($shipper->shipper_city) }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="edit_shipper_type_group" style="display: none;">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="edit_shipper_type_display">Shipper Type</label>
                                                        <input type="text" class="form-control" id="edit_shipper_type_display" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="activity_type">Activity Type <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="activity_type" id="activity_type" required>
                                                            <option value="" disabled selected>Select Activity Type</option>
                                                            <option value="visit">VISIT</option>
                                                            <option value="call">CALL</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default" id="visit_date_group" style="display: none;">
                                                        <label class="form-label" for="visit_date">Visit Date</label>
                                                        <input type="date" class="form-control" name="visit_date" id="visit_date">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="status">Status Type <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="status" id="status">
                                                            <option value="" disabled selected>Select Status Type</option>
                                                            <option value="CLOSING">CLOSING</option>
                                                            <option value="PENDING">PENDING</option>
                                                            <option value="FAILED">FAILED</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default" id="status_detail_group" style="display: none;">
                                                        <label class="form-label" for="status_detail">Status Detail</label>
                                                        <input type="text" class="form-control" name="status_detail" id="status_detail">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="prospect">Prospect</span></label>
                                                        <textarea class="form-control-plaintext"name="prospect" id="prospect" rows="2"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" id="saveBtn" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Save
                                        </button>    
                                        <button type="button" id="clearBtn" class="btn btn-warning text-white">
                                            <i class="fas fa-eraser"></i> Clear
                                        </button>
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
                                                    <label class="form-label" for="view_report_date">Report Date</label>
                                                    <input type="text" class="form-control-plaintext" id="view_report_date" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-group-default">
                                                    <label class="form-label" for="view_concept_type">Concept Type</label>
                                                    <input type="text" class="form-control-plaintext" id="view_concept_type" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-group-default">
                                                    <label class="form-label" for="view_shipper_id">Shipper Name</label>
                                                    <input type="text" class="form-control-plaintext" id="view_shipper_id" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="shipper_type_group" style="display: none;">
                                                <div class="form-group form-group-default">
                                                    <label class="form-label" for="shipper_type">Shipper Type</label>
                                                    <input type="text" class="form-control" id="shipper_type_display" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-group-default">
                                                    <label class="form-label" for="view_activity_type">Activity Type</label>
                                                    <input type="text" class="form-control-plaintext" id="view_activity_type" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-group-default" id="visit_date_group" style="display: none;">
                                                    <label class="form-label" for="visit_date">Visit Date</label>
                                                    <input type="date" class="form-control" name="visit_date" id="visit_date">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-group-default">
                                                    <label class="form-label" for="view_status">Status Type</label>
                                                    <input type="text" class="form-control-plaintext" id="view_status" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-group-default" id="status_detail_group" style="display: none;">
                                                    <label class="form-label" for="status_detail">Status Detail</label>
                                                    <input type="text" class="form-control" name="status_detail" id="status_detail">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group form-group-default">
                                                    <label class="form-label" for="view_prospect">Prospect</span></label>
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
                        <div class="table-responsive">
                            <table id="multi-filter-select" class="display table table-striped table-hover">
                                <thead class="text-center">
                                    <tr>
                                        <th>DATE</th>
                                        <th>CONCEPT</th>
                                        <th>SHIPPER</th>
                                        <th>DETAIL</th>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                        <th>CREATED</th>
                                        @endif
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tfoot class="text-center">
                                    <tr>
                                        <th>DATE</th>
                                        <th>CONCEPT</th>
                                        <th>SHIPPER</th>
                                        <th>DETAIL</th>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                        <th>CREATED</th>
                                        @endif
                                        <th>ACTION</th>
                                    </tr>
                                </tfoot>
                                <tbody class="text-center">
                                    @forelse($activities as $activity)
                                    <tr>
                                        <td>{{ Str::upper(\Carbon\Carbon::parse($activity->report_date)->format("d M")) }}</td>
                                        <td>{{ Str::upper(str_replace('_', ' ', $activity->concept_type)) }}</td>
                                        <td>{{ Str::upper($activity->shipper->shipper_name) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info viewActivity" data-id="{{ $activity->id }}" data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                        <td>{{ Str::upper($activity->user->name) }}</td>
                                        @endif
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
                                        <td>
                                            @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || (Auth::user()->isMarketing() && $activity->user_id == Auth::id()))
                                            <button type="button" class="btn btn-sm btn-warning text-white editActivity" data-id="{{ $activity->id }}" data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger deleteActivity" data-id="{{ $activity->id }}" data-bs-toggle="tooltip" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @else
                                            <span class="btn btn-sm btn-secondary" style="cursor: default;" data-bs-toggle="tooltip" title="None">
                                                <i class="fas fa-minus"></i>
                                            </span>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="@if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()) 7 @elseif(Auth::user()->isMarketing()) 6 @else 5 @endif" class="text-center">
                                            No Data Available
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="card-title">WEEKLY SUMMARY</div>
                        <p class="text-white mb-0"><small>{{ now()->startOfWeek()->format('d M') }} - {{ now()->endOfWeek()->format('d M Y') }}</small></p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <p><strong>New Shipper:</strong> {{ $weeklyReport->new_shipper_count ?? 0 }}</p>
                                <p><strong>Follow Up:</strong> {{ $weeklyReport->follow_up_count ?? 0 }}</p>
                            </div>
                            <div class="col-6">
                                <p><strong>Visit:</strong> {{ $weeklyReport->visit_count ?? 0 }}</p>
                                <p><strong>Call:</strong> {{ $weeklyReport->call_count ?? 0 }}</p>
                            </div>
                        </div>
                        <hr>
                        <p class="mb-0">
                            <span class="badge badge-success">CLOSING: {{ $weeklyReport->closing_count ?? 0 }}</span>
                            <span class="badge badge-warning">PENDING: {{ $weeklyReport->pending_count ?? 0 }}</span>
                            <span class="badge badge-danger">FAILED: {{ $weeklyReport->failed_count ?? 0 }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="card-title">MONTHLY SUMMARY</div>
                        <p class="text-white mb-0"><small>{{ now()->format('F Y') }}</small></p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <p><strong>New Shipper:</strong> {{ $monthlyReport->new_shipper_count ?? 0 }}</p>
                                <p><strong>Follow Up:</strong> {{ $monthlyReport->follow_up_count ?? 0 }}</p>
                            </div>
                            <div class="col-6">
                                <p><strong>Visit:</strong> {{ $monthlyReport->visit_count ?? 0 }}</p>
                                <p><strong>Call:</strong> {{ $monthlyReport->call_count ?? 0 }}</p>
                            </div>
                        </div>
                        <hr>
                        <p class="mb-0">
                            <span class="badge badge-success">CLOSING: {{ $monthlyReport->closing_count ?? 0 }}</span>
                            <span class="badge badge-warning">PENDING: {{ $monthlyReport->pending_count ?? 0 }}</span>
                            <span class="badge badge-danger">FAILED: {{ $monthlyReport->failed_count ?? 0 }}</span>
                        </p>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var userRole = "{{ Auth::user()->role }}";
    var isAdmin = (userRole === 'super_admin' || userRole === 'admin');

    try {
        var notOrderableColumns;
        if (isAdmin) {
            notOrderableColumns = [3, 4, 5];
        } else {
            notOrderableColumns = [3, 4];
        }

        var skipColumns;
        if (isAdmin) {
            skipColumns = [3, 4, 5];
        } else {
            skipColumns = [3, 4];
        }
    
        var table = $("#multi-filter-select").DataTable({
            pageLength: 10,
            order: [[0, 'desc']],
            columnDefs: [
                { orderable: false, targets: notOrderableColumns }
            ],
            language: {
                emptyTable: "No data available in table",
                loadingRecords: "Loading Data...",
                processing: "Processing your request...",
                search: "Search:",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    var columnIndex = column.index();

                    if (skipColumns.includes(columnIndex)) {
                        $(column.footer()).empty();
                        return;
                    }

                    var select = $('<select class="form-select"><option value=""></option></select>')
                    .appendTo($(column.footer()).empty())
                    .on("change", function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column
                        .search(val ? "^" + val + "$" : "", true, false)
                        .draw();
                    });

                    column.data().unique().sort().each(function (d, j) {
                        select.append(
                            '<option value="' + d + '">' + d + "</option>"
                        );
                    });
                });
            },
        });

        table.on('draw', function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

    } catch (error) {
        console.error('DataTables initialization error:', error);
    }

    // ✅ VIEW ACTIVITY BUTTON - FIXED
    $('body').on('click', '.viewActivity', function () {
        var activity_id = $(this).data('id');

        Swal.fire({
            title: 'Loading Data...',
            text: 'Please wait a moment',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.get("{{ route('activities.index') }}" + '/' + activity_id + '/edit', function (data) {
            Swal.close();

            // Format report date - SAMA FORMAT DENGAN TABLE
            if (data.report_date) {
                var reportDate = new Date(data.report_date);
                var day = reportDate.getDate().toString().padStart(2, '0');
                var month = reportDate.toLocaleString('en', { month: 'short' }).toUpperCase();
                $('#view_report_date').val(day + ' ' + month);
            } else {
                $('#view_report_date').val('-');
            }

            $('#view_concept_type').val(data.concept_type ? data.concept_type.replace('_', ' ').toUpperCase() : '-');
            
            // ✅ FIXED: Show shipper name AND shipper type
            if (data.shipper) {
                $('#view_shipper_name').val(data.shipper.shipper_name.toUpperCase());
                
                if (data.shipper.shipper_type) {
                    $('#view_shipper_type_group').show();
                    $('#view_shipper_type').val(data.shipper.shipper_type.toUpperCase());
                } else {
                    $('#view_shipper_type_group').hide();
                }
            } else {
                $('#view_shipper_name').val('-');
                $('#view_shipper_type_group').hide();
            }
            
            $('#view_activity_type').val(data.activity_type ? data.activity_type.toUpperCase() : '-');
            
            // Format visit date jika ada
            if (data.visit_date) {
                var visitDate = new Date(data.visit_date);
                var vDay = visitDate.getDate().toString().padStart(2, '0');
                var vMonth = visitDate.toLocaleString('en', { month: 'short' }).toUpperCase();
                $('#view_visit_date').val(vDay + ' ' + vMonth);
                $('#view_visit_date_group').show();
            } else {
                $('#view_visit_date_group').hide();
            }

            $('#view_status').val(data.status || '-');
            
            if (data.status_detail) {
                $('#view_status_detail').val(data.status_detail);
                $('#view_status_detail_group').show();
            } else {
                $('#view_status_detail_group').hide();
            }
            
            $('#view_prospect').val(data.prospect || '-');

            $('#Viewactivity').modal('show');

        }).fail(function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Failed to Load Data',
                text: xhr.responseJSON?.error || xhr.responseJSON?.message || 'Unable to retrieve activity information.',
                confirmButtonColor: '#d33'
            });
        });
    });

    // ✅ SHOW/HIDE CONDITIONAL FIELDS - FIXED FOR EDIT MODAL
    $('#shipper_id').on('change', function() {
        var selectedOption = $(this).find(':selected');
        var shipperType = selectedOption.data('type');
        
        if ($(this).val()) {
            $('#edit_shipper_type_group').show();
            $('#edit_shipper_type_display').val(shipperType || '-');
        } else {
            $('#edit_shipper_type_group').hide();
            $('#edit_shipper_type_display').val('');
        }
    });

    $('#activity_type').change(function() {
        if ($(this).val() === 'visit') {
            $('#visit_date_group').show();
            $('#visit_date').prop('required', true);
        } else {
            $('#visit_date_group').hide();
            $('#visit_date').prop('required', false);
            $('#visit_date').val('');
        }
    });

    $('#status').change(function() {
        if ($(this).val()) {
            $('#status_detail_group').show();
        } else {
            $('#status_detail_group').hide();
        }
    });

    if (hasActionColumn) {
        // CREATE NEW ACTIVITY BUTTON
        $('#createNewActivity').click(function () {
            $('#saveBtn').val("create-activity");
            $('#activity_id').val('');
            $('#activityForm').trigger("reset");
            
            $('#report_date').val('{{ date("Y-m-d") }}');
            
            $('#edit_shipper_type_group').hide();
            $('#visit_date_group').hide();
            $('#status_detail_group').hide();
            $('#visit_date').prop('required', false);
            
            $('#modalTitle').html('<span class="fw-mediumbold">New</span> <span class="fw-light">Activity</span>');
            $('#activityModal').modal('show');
        });

        // SAVE BUTTON
        $('#saveBtn').click(function (e) {
            e.preventDefault();

            var formData = new FormData($('#activityForm')[0]);
            var activity_id = $('#activity_id').val();
            var url = activity_id ? "{{ route('activities.index') }}" + '/' + activity_id : "{{ route('activities.store') }}";
            var actionText = activity_id ? 'updated' : 'added';

            if (activity_id) {
                formData.append('_method', 'PUT');
            }

            $('#saveBtn').html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#saveBtn').html('<i class="fas fa-save"></i> Save').prop('disabled', false);
                    $('#activityForm').trigger("reset");
                    $('#activityModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Data has been ' + actionText + ' successfully',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        location.reload();
                    });
                },
                error: function(response) {
                    $('#saveBtn').html('<i class="fas fa-save"></i> Save').prop('disabled', false);

                    if (response.status === 422) {
                        var errors = response.responseJSON.errors;
                        var errorList = '<ul style="text-align: left; margin: 0; padding-left: 20px;">';

                        $.each(errors, function(key, value) {
                            errorList += '<li>' + value[0] + '</li>';
                        });
                        errorList += '</ul>';

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: errorList,
                            confirmButtonColor: '#d33'
                        });

                    } else if (response.status === 403) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Access Denied',
                            text: response.responseJSON?.error || 'You do not have permission to perform this action!',
                            confirmButtonColor: '#d33'
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Operation Failed',
                            text: response.responseJSON?.error || response.responseJSON?.message || 'An error occurred on the server!',
                            confirmButtonColor: '#d33'
                        });
                    }
                }
            });
        });

        // CLEAR BUTTON
        $('#clearBtn').click(function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Clear Form?',
                text: "All unsaved data will be lost!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Clear',
                cancelButtonText: 'Cancel',
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#activityForm').trigger("reset");
                    
                    $('#report_date').val('{{ date("Y-m-d") }}');
                    
                    $('#edit_shipper_type_group').hide();
                    $('#visit_date_group').hide();
                    $('#status_detail_group').hide();

                    Swal.fire({
                        icon: 'success',
                        title: 'Cleared!',
                        text: 'Form has been cleared.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            });
        });

        // ✅ EDIT ACTIVITY BUTTON - FIXED
        $('body').on('click', '.editActivity', function () {
            var activity_id = $(this).data('id');

            Swal.fire({
                title: 'Loading Data...',
                text: 'Please wait a moment',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.get("{{ route('activities.index') }}" + '/' + activity_id + '/edit', function (data) {
                Swal.close();

                $('#modalTitle').html('<span class="fw-mediumbold">Edit</span> <span class="fw-light">Activity</span>');
                $('#saveBtn').val("edit-activity");

                $('#activityModal').modal('show');

                $('#activity_id').val(data.id);
                $('#report_date').val(data.report_date);
                $('#concept_type').val(data.concept_type);
                $('#shipper_id').val(data.shipper_id).trigger('change'); // ✅ Trigger change untuk show shipper_type
                $('#activity_type').val(data.activity_type).trigger('change'); // ✅ Trigger untuk visit_date
                $('#visit_date').val(data.visit_date);
                $('#prospect').val(data.prospect);
                $('#status').val(data.status).trigger('change'); // ✅ Trigger untuk status_detail
                $('#status_detail').val(data.status_detail);

            }).fail(function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Load Data',
                    text: xhr.responseJSON?.error || xhr.responseJSON?.message || 'Unable to retrieve activity information. Please try again.',
                    confirmButtonColor: '#d33'
                });
            });
        });

        // DELETE ACTIVITY BUTTON
        $('body').on('click', '.deleteActivity', function () {
            var activity_id = $(this).data("id");
            var row = $(this).closest('tr');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Deleting Data...',
                        text: 'Please wait a moment',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('activities.index') }}" + '/' + activity_id,
                        success: function (response) {
                            row.fadeOut(300, function() {
                                table.row($(this)).remove().draw(false);
                            });

                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Data has been deleted successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        },
                        error: function (xhr) {
                            console.error('Error:', xhr);
                            if (xhr.status === 403) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Access Denied',
                                    text: xhr.responseJSON?.error || 'You do not have permission to delete this data!',
                                    confirmButtonColor: '#d33'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Deletion Failed',
                                    text: xhr.responseJSON?.error || xhr.responseJSON?.message || 'An error occurred while deleting data!',
                                    confirmButtonColor: '#d33'
                                });
                            }
                        }
                    });
                }
            });
        });
    }
});
</script>
@endsection('script')