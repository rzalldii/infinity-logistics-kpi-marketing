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
                            @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
                            <button class="btn btn-primary btn-round ms-auto" id="createNewRate" data-bs-toggle="tooltip" title="Add">
                                <i class="fas fa-plus"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="modal fade" id="rateModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                            <div class="modal-dialog modal-lg">
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
                                                        <input type="text" class="form-control" name="pol" id="pol" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="pod">Port Of Destination <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="pod" id="pod" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="container">Container Type <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="container" id="container" required>
                                                            <option value="" disabled selected>Select Container Type</option>
                                                            <option value="GP">GP</option>
                                                            <option value="RF">RF</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="container_20">Container 20FT</label>
                                                        <input type="number" class="form-control" name="container_20" id="container_20"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="container_40">Container 40FT</label>
                                                        <input type="number" class="form-control" name="container_40" id="container_40"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="liner">Liner / Forwarding <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="liner" id="liner" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="free">Free Time</label>
                                                        <input type="text" class="form-control" id="free"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="valid">Validity Date <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" name="valid" id="valid" required/>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="notes" id="notes">
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="remarks">Notes</label>
                                                        <textarea class="form-control" id="remarks" rows="6"></textarea>
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
                        <div class="modal fade" id="Viewrate" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                            <div class="modal-dialog modal-lg">
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
                                                    <label class="form-label" for="view_container">Container Type</label>
                                                    <input type="text" class="form-control-plaintext" id="view_container" readonly/>
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
                                                    <label class="form-label" for="view_free">Free Time</label>
                                                    <input type="text" class="form-control-plaintext" id="view_free" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-group-default">
                                                    <label class="form-label" for="view_valid_date">Validity Date</label>
                                                    <input type="text" class="form-control-plaintext" id="view_valid_date" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group form-group-default">
                                                    <label class="form-label" for="view_remarks">Notes</label>
                                                    <textarea class="form-control-plaintext" id="view_remarks" rows="5" readonly></textarea>
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
                                        <th>POD</th>
                                        <th>20'</th>
                                        <th>40'</th>
                                        <th>LINER</th>
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
                                        <th>POD</th>
                                        <th>20'</th>
                                        <th>40'</th>
                                        <th>LINER</th>
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
                                    @forelse($rates as $rate)
                                    <tr id="row-{{ $rate->id }}">
                                        <td>{{ Str::upper($rate->pod) }}</td>
                                        <td>
                                            @if($rate->container_20)
                                            {{ number_format($rate->container_20) }}
                                            @else
                                            <span class="btn btn-sm btn-secondary" style="cursor: default;" data-bs-toggle="tooltip" title="None">
                                                <i class="fas fa-minus"></i>
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($rate->container_40)
                                            {{ number_format($rate->container_40) }}
                                            @else
                                            <span class="btn btn-sm btn-secondary" style="cursor: default;" data-bs-toggle="tooltip" title="None">
                                                <i class="fas fa-minus"></i>
                                            </span>
                                            @endif
                                        </td>
                                        <td>{{ Str::upper($rate->liner) }}</td>
                                        <td>{{ Str::upper(\Carbon\Carbon::parse($rate->valid)->format("M'y")) }}</td>
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
                                            <span class="btn btn-sm btn-secondary" style="cursor: default;" data-bs-toggle="tooltip" title="None">
                                                <i class="fas fa-minus"></i>
                                            </span>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="@if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()) 8 @elseif(Auth::user()->isMarketing()) 7 @else 6 @endif" class="text-center">
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

    function combineNotesData() {
        var freeVal = $('#free').val() || '';
        var remarksVal = $('#remarks').val() || '';
        
        var notesData = 'Free Time : ' + freeVal + '\n' +
                       'Remarks : ' + remarksVal;

        $('#notes').val(notesData);
    }

    function parseNotesData(notesText) {
        var lines = notesText.split('\n');
        var data = {
            free: '',
            remarks: ''
        };

        lines.forEach(function(line) {
            if (line.startsWith('Free Time :')) {
                data.free = line.replace('Free Time :', '').trim();
            } else if (line.startsWith('Remarks :')) {
                data.remarks = line.replace('Remarks :', '').trim();
            }
        });

        return data;
    }

    function number_format(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    try {
        var notOrderableColumns;
        if (isAdmin) {
            notOrderableColumns = [5, 7];
        } else if (hasActionColumn) {
            notOrderableColumns = [5, 6];
        } else {
            notOrderableColumns = [5];
        }

        var skipColumns;
        if (isAdmin) {
            skipColumns = [1, 2, 5, 7];
        } else if (hasActionColumn) {
            skipColumns = [1, 2, 5, 6];
        } else {
            skipColumns = [1, 2, 5];
        }
    
        var table = $("#multi-filter-select").DataTable({
            pageLength: 10,
            order: [[4, 'desc']],
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

    $('body').on('click', '.viewRate', function () {
        var rate_id = $(this).data('id');

        Swal.fire({
            title: 'Loading Data...',
            text: 'Please wait a moment',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.get("{{ route('rates.index') }}" + '/' + rate_id + '/edit', function (data) {
            Swal.close();

            $('#view_pol').val(data.pol || '-');
            $('#view_pod').val(data.pod || '-');
            $('#view_container').val(data.container || '-');
            $('#view_container_20').val(data.container_20 ? number_format(data.container_20) : '-');
            $('#view_container_40').val(data.container_40 ? number_format(data.container_40) : '-');
            $('#view_liner').val(data.liner || '-');

            if (data.valid) {
                var validDate = new Date(data.valid);
                var formattedDate = validDate.getDate().toString().padStart(2, '0') + '/' + 
                                  (validDate.getMonth() + 1).toString().padStart(2, '0') + '/' + 
                                  validDate.getFullYear();
                $('#view_valid_date').val(formattedDate);
            } else {
                $('#view_valid_date').val('-');
            }

            if (data.notes) {
                var parsedData = parseNotesData(data.notes);
                $('#view_free').val(parsedData.free || '-');
                $('#view_remarks').val(parsedData.remarks || '-');
            } else {
                $('#view_free').val('-');
                $('#view_remarks').val('-');
            }

            $('#Viewrate').modal('show');

        }).fail(function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Failed to Load Data',
                text: xhr.responseJSON?.error || xhr.responseJSON?.message || 'Unable to retrieve rate information.',
                confirmButtonColor: '#d33'
            });
        });
    });

    if (hasActionColumn) {
        $('#createNewRate').click(function () {
            $('#saveBtn').val("create-rate");
            $('#rate_id').val('');
            $('#rateForm').trigger("reset");
            $('#free').val('');
            $('#remarks').val('');
            $('#modalTitle').html('<span class="fw-mediumbold">New</span> <span class="fw-light">Rate</span>');
            $('#rateModal').modal('show');
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();

            combineNotesData();

            var formData = new FormData($('#rateForm')[0]);
            var rate_id = $('#rate_id').val();
            var url = rate_id ? "{{ route('rates.index') }}" + '/' + rate_id : "{{ route('rates.store') }}";
            var actionText = rate_id ? 'updated' : 'added';

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
                    $('#rateForm').trigger("reset");
                    $('#container').val('');
                    $('#free').val('');
                    $('#remarks').val('');

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

        $('body').on('click', '.editRate', function () {
            var rate_id = $(this).data('id');

            Swal.fire({
                title: 'Loading Data...',
                text: 'Please wait a moment',
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
                $('#container').val(data.container);
                $('#container_20').val(data.container_20);
                $('#container_40').val(data.container_40);
                $('#liner').val(data.liner);
                if (data.valid) {
                    var validDate = new Date(data.valid);
                    var formattedDate = validDate.getFullYear() + '-' + 
                                        String(validDate.getMonth() + 1).padStart(2, '0') + '-' + 
                                        String(validDate.getDate()).padStart(2, '0');
                    $('#valid').val(formattedDate);
                }
                if (data.notes) {
                    var parsedData = parseNotesData(data.notes);
                    $('#free').val(parsedData.free);
                    $('#remarks').val(parsedData.remarks);
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

        $('body').on('click', '.deleteRate', function () {
            var rate_id = $(this).data("id");
            var row = $('#row-' + rate_id);

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
                        url: "{{ route('rates.index') }}" + '/' + rate_id,
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