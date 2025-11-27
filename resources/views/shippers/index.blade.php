@extends('layouts.app')
@section('title')
Touch Shippers | Admin Infinity Logistics Indonesia
@endsection('title')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h1 class="card-title">Touch Shippers</h1>
                            @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
                            <button class="btn btn-primary btn-round ms-auto" id="createNewShipper" data-bs-toggle="tooltip" title="Add">
                                <i class="fas fa-plus"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="modal fade" id="shipperModal" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title" id="modalTitle">
                                            <span class="fw-mediumbold">New</span>
                                            <span class="fw-light">Shipper</span>
                                        </h5>
                                    </div>
                                    <div class="modal-body">
                                        <form id="shipperForm">
                                            @csrf
                                            <input type="hidden" name="shipper_id" id="shipper_id">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="shipper_name">Shipper Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="shipper_name" id="shipper_name" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="shipper_type">Shipper Type <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="shipper_type" id="shipper_type" required>
                                                            <option value="" disabled selected>Select Shipper Type</option>
                                                            <option value="DIRECT SHIPPER">DIRECT SHIPPER</option>
                                                            <option value="FORWARDING">FORWARDING</option>
                                                            <option value="TRADING">TRADING</option>
                                                            <option value="EMKL / TRANSPORTER">EMKL / TRANSPORTER</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="shipper_city">Shipper City <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="shipper_city" id="shipper_city" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="shipper_address">Shipper Address</label>
                                                        <input type="text" class="form-control" name="shipper_address" id="shipper_address"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="contact_person">Contact Person</label>
                                                        <input type="text" class="form-control" name="contact_person" id="contact_person"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="phone_number">Phone Number</label>
                                                        <input type="text" class="form-control" name="phone_number" id="phone_number"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="email_address">Email Address</label>
                                                        <input type="email" class="form-control" name="email_address" id="email_address"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="export">Export</label>
                                                        <input type="text" class="form-control" name="export" id="export"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="import">Import</label>
                                                        <input type="text" class="form-control" name="import" id="import"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="domestic">Domestic</label>
                                                        <input type="text" class="form-control" name="domestic" id="domestic"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="commodity">Commodity</label>
                                                        <input type="text" class="form-control" name="commodity" id="commodity"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="input_date">Input Date <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" name="input_date" id="input_date" value="{{ date('Y-m-d') }}" required/>
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
                                                    <label class="form-label" for="view_input_date">Input Date</label>
                                                    <input type="text" class="form-control-plaintext" id="view_input_date" readonly/>
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
                                        <th>SHIPPER</th>
                                        <th>CITY</th>
                                        <th>CP</th>
                                        <th>INPUT</th>
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
                                        <th>SHIPPER</th>
                                        <th>CITY</th>
                                        <th>CP</th>
                                        <th>INPUT</th>
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
                                    @forelse($shippers as $shipper)
                                    <tr id="row-{{ $shipper->id }}">
                                        <td>{{ Str::upper($shipper->shipper_name) }}</td>
                                        <td>{{ Str::upper($shipper->shipper_city) }}</td>
                                        <td>
                                            @if($shipper->contact_person)
                                            {{ Str::upper($shipper->contact_person) }}
                                            @else
                                            <span class="btn btn-sm btn-secondary" style="cursor: default;" data-bs-toggle="tooltip" title="None">
                                                <i class="fas fa-minus"></i>
                                            </span>
                                            @endif
                                        </td>
                                        <td>{{ Str::upper(\Carbon\Carbon::parse($shipper->input_date)->format("d M")) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info viewShipper" data-id="{{ $shipper->id }}" data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                        <td>{{ Str::upper($shipper->user->name) }}</td>
                                        @endif
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
                                        <td>
                                            @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || (Auth::user()->isMarketing() && $shipper->user_id == Auth::id()))
                                            <button type="button" class="btn btn-sm btn-warning text-white editShipper" data-id="{{ $shipper->id }}" data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger deleteShipper" data-id="{{ $shipper->id }}" data-bs-toggle="tooltip" title="Delete">
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

    try {
        var notOrderableColumns;
        if (isAdmin) {
            notOrderableColumns = [2, 4, 6];
        } else if (hasActionColumn) {
            notOrderableColumns = [2, 4, 5];
        } else {
            notOrderableColumns = [2, 4];
        }

        var skipColumns;
        if (isAdmin) {
            skipColumns = [0, 2, 3, 4, 6];
        } else if (hasActionColumn) {
            skipColumns = [0, 2, 3, 4, 5];
        } else {
            skipColumns = [0, 2, 3, 4];
        }

        var table = $("#multi-filter-select").DataTable({
            pageLength: 10,
            order: [[3, 'desc']],
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

    $('body').on('click', '.viewShipper', function () {
        var shipper_id = $(this).data('id');
        
        Swal.fire({
            title: 'Loading Data...',
            text: 'Please wait a moment',
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

            if (data.input_date) {
                var inputDate = new Date(data.input_date);
                var formattedDate = inputDate.getDate().toString().padStart(2, '0') + '/' + 
                                  (inputDate.getMonth() + 1).toString().padStart(2, '0') + '/' + 
                                  inputDate.getFullYear();
                $('#view_input_date').val(formattedDate);
            } else {
                $('#view_input_date').val('-');
            }

            $('#Viewshipper').modal('show');

        }).fail(function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Failed to Load Data',
                text: xhr.responseJSON?.error || xhr.responseJSON?.message || 'Unable to retrieve shipper information.',
                confirmButtonColor: '#d33'
            });
        });
    });

    if (hasActionColumn) {
        $('#createNewShipper').click(function () {
            $('#saveBtn').val("create-shipper");
            $('#shipper_id').val('');
            $('#shipperForm').trigger("reset");
            $('#modalTitle').html('<span class="fw-mediumbold">New</span> <span class="fw-light">Shipper</span>');
            $('#shipperModal').modal('show');
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();

            var formData = new FormData($('#shipperForm')[0]);
            var shipper_id = $('#shipper_id').val();
            var url = shipper_id ? "{{ route('shippers.index') }}" + '/' + shipper_id : "{{ route('shippers.store') }}";
            var actionText = shipper_id ? 'updated' : 'added';

            if (shipper_id) {
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
                    $('#shipperForm').trigger("reset");
                    $('#shipperModal').modal('hide');

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
                    $('#shipperForm').trigger("reset");

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

        $('body').on('click', '.editShipper', function () {
            var shipper_id = $(this).data('id');

            Swal.fire({
                title: 'Loading Data...',
                text: 'Please wait a moment',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.get("{{ route('shippers.index') }}" + '/' + shipper_id + '/edit', function (data) {
                Swal.close();

                $('#modalTitle').html('<span class="fw-mediumbold">Edit</span> <span class="fw-light">Shipper</span>');
                $('#saveBtn').val("edit-shipper");

                $('#shipperModal').modal('show');

                $('#shipper_id').val(data.id);
                $('#shipper_name').val(data.shipper_name);
                $('#shipper_type').val(data.shipper_type);
                $('#shipper_city').val(data.shipper_city);
                $('#shipper_address').val(data.shipper_address);
                $('#contact_person').val(data.contact_person);
                $('#phone_number').val(data.phone_number);
                $('#email_address').val(data.email_address);
                $('#export').val(data.export);
                $('#import').val(data.import);
                $('#domestic').val(data.domestic);
                $('#commodity').val(data.commodity);
                if (data.input_date) {
                    var inputDate = new Date(data.input_date);
                    var formattedDate = inputDate.getFullYear() + '-' + 
                                        String(inputDate.getMonth() + 1).padStart(2, '0') + '-' + 
                                        String(inputDate.getDate()).padStart(2, '0');
                    $('#input_date').val(formattedDate);
                }

            }).fail(function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Load Data',
                    text: xhr.responseJSON?.error || xhr.responseJSON?.message || 'Unable to retrieve user information. Please try again.',
                    confirmButtonColor: '#d33'
                });
            });
        });

        $('body').on('click', '.deleteShipper', function () {
            var shipper_id = $(this).data("id");
            var row = $('#row-' + shipper_id);

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
                        url: "{{ route('shippers.index') }}" + '/' + shipper_id,
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