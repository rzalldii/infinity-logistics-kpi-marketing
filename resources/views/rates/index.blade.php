@extends('layouts.app')
@section('title')
Checking Rates | Admin Infinity Logistics Indonesia
@endsection('title')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h1 class="card-title">Checking Rates</h1>
                            <button class="btn btn-info btn-round ms-auto" id="ToggleColumns">
                                <i class="fas fa-eye"></i> Toggle Columns
                            </button>
                            @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
                                <button class="btn btn-primary btn-round ms-2" id="createNewRate">
                                    <i class="fas fa-plus"></i> Add Data
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="modal fade" id="rateModal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title" id="modalTitle">
                                            <span class="fw-mediumbold">New</span>
                                            <span class="fw-light">Rate</span>
                                        </h5>
                                    </div>
                                    <div class="modal-body">
                                        <form id="rateForm">
                                            @csrf
                                            <input type="hidden" name="rate_id" id="rate_id">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="pol">Port Of Loading <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="pol" id="pol" placeholder="e.g. SURABAYA" autocomplete="off" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="pod">Port Of Destination <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="pod" id="pod" placeholder="e.g. PORT KLANG" autocomplete="off" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="container_type">Container Type <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="container_type" id="container_type" required>
                                                            <option value="" disabled selected>Select Container Type</option>
                                                            <option value="GP">GP</option>
                                                            <option value="RF">RF</option>
                                                            <option value="OT">OT</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="container_20">Container 20FT</label>
                                                        <input type="text" class="form-control" name="container_20" id="container_20" placeholder="e.g. 150" autocomplete="off"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="container_40">Container 40FT</label>
                                                        <input type="text" class="form-control" name="container_40" id="container_40" placeholder="e.g. 250" autocomplete="off"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="liner">Liner / Forwarding <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="liner" id="liner" placeholder="e.g. INFINITY" autocomplete="off" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="free_time">Free Time</label>
                                                        <input type="text" class="form-control" name="free_time" id="free_time" placeholder="e.g. 14 Days + DET/DEM" autocomplete="off"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="valid_date">Valid Date <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" name="valid_date" id="valid_date" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="notes">Notes</label>
                                                        <textarea class="form-control" name="notes" id="notes" rows="6" placeholder="Add any specific conditions, incoterms, or internal remarks here..." autocomplete="off"></textarea>
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
                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-select" id="filterData">
                                        <option value="">All Data</option>
                                        <option value="mine">My Data</option>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}">Data {{ $user->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="table-responsive">
                            <table id="multi-filter-select" class="display table table-striped table-hover" style="width:100%">
                                <thead class="text-center">
                                    <tr>
                                        <th>POL</th>
                                        <th>POD</th>
                                        <th>CT</th>
                                        <th>20'</th>
                                        <th>40'</th>
                                        <th>LINER</th>
                                        <th>FT</th>
                                        <th>VALID</th>
                                        <th>DETAIL</th>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                            <th>CREATED</th>
                                        @endif
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
                                            <th>ACTION</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tfoot class="text-center">
                                    <tr>
                                        <th>POL</th>
                                        <th>POD</th>
                                        <th>CT</th>
                                        <th>20'</th>
                                        <th>40'</th>
                                        <th>LINER</th>
                                        <th>FT</th>
                                        <th>VALID</th>
                                        <th>DETAIL</th>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                            <th>CREATED</th>
                                        @endif
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
                                            <th>ACTION</th>
                                        @endif
                                    </tr>
                                </tfoot>
                                <tbody class="text-center">
                                    @foreach($rates as $rate)
                                    <tr id="row-{{ $rate->id }}" data-user-id="{{ $rate->user_id }}">
                                        <td>{{ $rate->pol }}</td>
                                        <td>{{ $rate->pod }}</td>
                                        <td>{{ $rate->container_type }}</td>
                                        <td>
                                            @if($rate->container_20)
                                                {{ number_format((float)$rate->container_20) }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($rate->container_40)
                                                {{ number_format((float)$rate->container_40) }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ $rate->liner }}</td>
                                        <td>
                                            @if($rate->free_time)
                                                {{ $rate->free_time }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ Str::upper(\Carbon\Carbon::parse($rate->valid_date)->format("M y")) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info viewRate" data-id="{{ $rate->id }}" data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                            <td>{{ Str::upper($rate->user->name) }}</td>
                                        @endif
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
                                            <td>
                                                @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || (Auth::user()->isMarketing() && $rate->user_id == Auth::id()))
                                                    <button type="button" class="btn btn-sm btn-warning text-white editRate" data-id="{{ $rate->id }}" data-bs-toggle="tooltip" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger deleteRate" data-id="{{ $rate->id }}" data-bs-toggle="tooltip" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-success" style="cursor: not-allowed;" data-bs-toggle="tooltip" title="Locked">
                                                        <i class="fas fa-lock"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var userRole = "{{ Auth::user()->role }}";
    var isAdmin = (userRole === 'super_admin' || userRole === 'admin');
    var hasActionColumn = (userRole === 'super_admin' || userRole === 'admin' || userRole === 'marketing');
    var currentUserId = {{ Auth::id() }};
    try {
        var notOrderableColumns;
        if (isAdmin) {
            notOrderableColumns = [0, 1, 2, 5, 6, 8, 9, 10];
        } else if (hasActionColumn) {
            notOrderableColumns = [0, 1, 2, 5, 6, 8, 9];
        } else {
            notOrderableColumns = [0, 1, 2, 5, 6, 8];
        }
        var skipColumns;
        if (isAdmin) {
            skipColumns = [3, 4, 6, 8, 9, 10];
        } else if (hasActionColumn) {
            skipColumns = [3, 4, 6, 8, 10];
        } else {
            skipColumns = [3, 4, 6, 8];
        }
        var hiddenColumns;
        if (isAdmin) {
            hiddenColumns = [0, 2, 6];
        } else if (hasActionColumn) {
            hiddenColumns = [0, 2, 6];
        } else {
            hiddenColumns = [0, 2, 6];
        }
        var table = $("#multi-filter-select").DataTable({
            pageLength: 10,
            autoWidth: false,
            order: [[7, 'desc']],
            columnDefs: [
            { 
                orderable: false, 
                targets: notOrderableColumns 
            },
            {
                visible: false,
                searchable: true,
                targets: hiddenColumns
            },
            ],
            language: {
                emptyTable: "No data available in table",
                zeroRecords: "No matching records found",
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
                    var uniqueValues = [];
                    column.data().unique().sort().each(function (d, j) {
                        if (d && !uniqueValues.includes(d)) {
                            uniqueValues.push(d);
                            select.append('<option value="' + d + '">' + d + "</option>");
                        }
                    });
                });
            },
        });
        table.on('draw', function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
        $('#filterData').on('change', function() {
            var filterValue = $(this).val();
            $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(function(fn) {
                return fn.name !== 'dataFilter';
            });
            if (filterValue) {
                var targetUserId = filterValue === 'mine' ? currentUserId : parseInt(filterValue);
                var dataFilter = function(settings, data, dataIndex) {
                    var row = table.row(dataIndex).node();
                    var userId = $(row).data('user-id');
                    return userId == targetUserId;
                };
                dataFilter.name = 'dataFilter';
                $.fn.dataTable.ext.search.push(dataFilter);
            }
            table.draw();
        });
    } catch (error) {
        console.error('DataTables initialization error:', error);
    }
    $('#ToggleColumns').on('click', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var isHidden = !table.column(0).visible();
        table.columns([0, 2, 6]).visible(isHidden);
        if (isHidden) {
            $btn.html('<i class="fas fa-eye-slash"></i> Toggle Columns');
        } else {
            $btn.html('<i class="fas fa-eye"></i> Toggle Columns');
        }
        table.columns.adjust().draw();
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
            $('#view_pol').val(data.pol || '—');
            $('#view_pod').val(data.pod || '—');
            $('#view_container_type').val(data.container_type || '—');
            $('#view_container_20').val(data.container_20 || '—');
            $('#view_container_40').val(data.container_40 || '—');
            $('#view_liner').val(data.liner || '—');
            $('#view_free_time').val(data.free_time || '—');
            if (data.valid_date) {
                var validDate = new Date(data.valid_date);
                var formattedDate = validDate.getDate().toString().padStart(2, '0') + '/' + 
                                  (validDate.getMonth() + 1).toString().padStart(2, '0') + '/' + 
                                  validDate.getFullYear();
                $('#view_valid_date').val(formattedDate);
            } else {
                $('#view_valid_date').val('—');
            }
            if ($('#view_notes').length) {
                $('#view_notes').val(data.notes || '—');
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
    if (hasActionColumn) {
        $('#createNewRate').click(function () {
            $('#saveBtn').val("create-rate");
            $('#rate_id').val('');
            $('#rateForm').trigger("reset");
            $('#modalTitle').html('<span class="fw-mediumbold">New</span> <span class="fw-light">Rate</span>');
            $('#rateModal').modal('show');
        });
        $('#saveBtn').click(function (e) {
            e.preventDefault();
            var formData = new FormData($('#rateForm')[0]);
            var rate_id = $('#rate_id').val();
            var url = rate_id ? "{{ route('rates.index') }}" + '/' + rate_id : "{{ route('rates.store') }}";
            if (rate_id) {
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
                    $('#rateForm').trigger("reset");
                    $('#rateModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Data Saved Successfully!',
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
                            confirmButtonColor: '#d33'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Operation Failed',
                            confirmButtonColor: '#d33'
                        });
                    }
                }
            });
        });
        $('#clearBtn').click(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Clear This Form?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Clear',
                cancelButtonText: 'Cancel',
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#rateForm').trigger("reset");
                    Swal.fire({
                        icon: 'success',
                        title: 'Form Cleared Successfully!',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            });
        });
        $('body').on('click', '.editRate', function () {
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
                $('#modalTitle').html('<span class="fw-mediumbold">Edit</span> <span class="fw-light">Rate</span>');
                $('#saveBtn').val("edit-rate");
                $('#rateModal').modal('show');
                $('#rate_id').val(data.id);
                $('#pol').val(data.pol);
                $('#pod').val(data.pod);
                $('#container_type').val(data.container_type);
                $('#container_20').val(data.container_20);
                $('#container_40').val(data.container_40);
                $('#liner').val(data.liner);
                $('#free_time').val(data.free_time);
                if (data.valid_date) {
                    var validDate = new Date(data.valid_date);
                    var formattedDate = validDate.getFullYear() + '-' + 
                                        String(validDate.getMonth() + 1).padStart(2, '0') + '-' + 
                                        String(validDate.getDate()).padStart(2, '0');
                    $('#valid_date').val(formattedDate);
                }
                $('#notes').val(data.notes);
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Load Data',
                    confirmButtonColor: '#d33'
                });
            });
        });
        $('body').on('click', '.deleteRate', function () {
            var rate_id = $(this).data("id");
            var row = $('#row-' + rate_id);
            Swal.fire({
                title: 'Delete This Data?',
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
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('rates.index') }}" + '/' + rate_id,
                        success: function (response) {
                            row.fadeOut(300, function() {
                                table.row($(this)).remove().draw(false);
                            });
                            Swal.fire({
                                icon: 'success',
                                title: 'Data Deleted Successfully!',
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
                                    confirmButtonColor: '#d33'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Deletion Failed',
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