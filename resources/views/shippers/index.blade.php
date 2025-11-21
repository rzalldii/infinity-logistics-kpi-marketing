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
                        <div class="modal fade" id="shipperModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title" id="modalTitle">
                                            <span class="fw-mediumbold">New</span>
                                            <span class="fw-light">Shippers</span>
                                        </h5>
                                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                                    </div>
                                    <div class="modal-body">
                                        <form id="shipperForm">
                                            @csrf
                                            <input type="hidden" name="shipper_id" id="shipper_id">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Shipper Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="shipper_name" id="shipper_name" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>City Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="city_name" id="city_name" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label>Contact Person</label>
                                                        <input type="text" class="form-control" name="contact_person" id="contact_person"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label>Phone Number</label>
                                                        <input type="text" class="form-control" name="phone_number" id="phone_number"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label>Email Address</label>
                                                        <input type="email" class="form-control" name="email_address" id="email_address"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Input Date <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" name="input" id="input" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Remarks</label>
                                                        <textarea class="form-control" name="remarks" id="remarks" rows="6"></textarea>
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
                        <div class="table-responsive">
                            <table id="multi-filter-select" class="display table table-striped table-hover">
                                <thead class="text-center">
                                    <tr>
                                        <th>SHIPPER</th>
                                        <th>CITY</th>
                                        <th>CP</th>
                                        <th>PHONE</th>
                                        <th>EMAIL</th>
                                        <th>INPUT</th>
                                        <th>REMARKS</th>
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
                                        <th>PHONE</th>
                                        <th>EMAIL</th>
                                        <th>INPUT</th>
                                        <th>REMARKS</th>
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
                                        <td>{{ Str::upper($shipper->city_name) }}</td>
                                        <td>
                                            @if($shipper->contact_person)
                                            {{ Str::upper($shipper->contact_person) }}
                                            @else
                                            <span class="btn btn-sm btn-secondary" style="cursor: default;" data-bs-toggle="tooltip" title="None">
                                                <i class="fas fa-minus"></i>
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($shipper->phone_number)
                                            {{ $shipper->phone_number }}
                                            @else
                                            <span class="btn btn-sm btn-secondary" style="cursor: default;" data-bs-toggle="tooltip" title="None">
                                                <i class="fas fa-minus"></i>
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($shipper->email_address)
                                            {{ $shipper->email_address }}
                                            @else
                                            <span class="btn btn-sm btn-secondary" style="cursor: default;" data-bs-toggle="tooltip" title="None">
                                                <i class="fas fa-minus"></i>
                                            </span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($shipper->input)->format('d/m/y') }}</td>
                                        <td>
                                            @if($shipper->remarks)
                                            <button type="button" class="btn btn-sm btn-info viewRemarks" data-remarks="{{ $shipper->remarks }}" data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @else
                                            <span class="btn btn-sm btn-secondary" style="cursor: default;" data-bs-toggle="tooltip" title="None">
                                                <i class="fas fa-minus"></i>
                                            </span>
                                            @endif
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
                                        <td colspan="@if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()) 9 @elseif(Auth::user()->isMarketing()) 8 @else 7 @endif" class="text-center">
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
            notOrderableColumns = [3, 4, 6, 8];
        } else if (hasActionColumn) {
            notOrderableColumns = [3, 4, 6, 7];
        } else {
            notOrderableColumns = [3, 4, 6];
        }

        var skipColumns;
        if (isAdmin) {
            skipColumns = [0, 2, 3, 4, 5, 6, 8];
        } else if (hasActionColumn) {
            skipColumns = [0, 2, 3, 4, 5, 6, 7];
        } else {
            skipColumns = [0, 2, 3, 4, 5, 6];
        }

        var table = $("#multi-filter-select").DataTable({
            pageLength: 10,
            order: [[5, 'desc']],
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

    $('body').on('click', '.viewRemarks', function () {
        var remarks = $(this).data('remarks');
        Swal.fire({
            title: 'Remarks',
            html: '<div style="text-align: left; max-height: 400px; overflow-y: auto; padding: 15px; background-color: #f8f9fa; border-radius: 4px; border-left: 4px solid #3085d6; line-height: 1.6;">' + remarks.replace(/\n/g, '<br>') + '</div>',
            icon: 'info',
            width: '600px',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Close',
            allowOutsideClick: false
        });
    });

    if (hasActionColumn) {
        $('#shipperModal').on('shown.bs.modal', function (e) {
            if ($('#saveBtn').val() === 'create-shipper') {
                var remarksTemplate = 'Export : \nImport : \nDomestic : \nCommodity : \nNotes : ';
                $('#remarks').val(remarksTemplate);
            }
        });

        var originalRemarks = 'Export : \nImport : \nDomestic : \nCommodity : \nNotes : ';

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
                    $('#container').val('');
                    if ($('#saveBtn').val() === 'create-shipper') {
                        var remarksTemplate = 'Export : \nImport : \nDomestic : \nCommodity : \nNotes : ';
                        $('#remarks').val(remarksTemplate);
                    } else {
                        $('#remarks').val(originalRemarks);
                    }

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
                $('#city_name').val(data.city_name);
                $('#contact_person').val(data.contact_person);
                $('#phone_number').val(data.phone_number);
                $('#email_address').val(data.email_address);
                if (data.input) {
                    var inputDate = new Date(data.input);
                    var formattedDate = inputDate.getFullYear() + '-' + 
                                        String(inputDate.getMonth() + 1).padStart(2, '0') + '-' + 
                                        String(inputDate.getDate()).padStart(2, '0');
                    $('#input').val(formattedDate);
                }
                $('#remarks').val(data.remarks);

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