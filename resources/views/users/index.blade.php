@extends('layouts.app')
@section('title')
User Management | Admin Infinity Logistics Indonesia
@endsection('title')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h1 class="card-title">User Management</h1>
                            <button class="btn btn-primary btn-round ms-auto" id="createNewUser">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title" id="modalTitle">
                                            <span class="fw-mediumbold">New</span>
                                            <span class="fw-light">Users</span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="userForm">
                                            @csrf
                                            <input type="hidden" name="user_id" id="user_id">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="name" id="name" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Email <span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" name="email" id="email" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Password <span class="text-danger" id="passwordRequired">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="password" class="form-control" name="password" id="password" required/>
                                                            <button type="button" class="btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2" id="togglePassword" style="display: none; background: transparent; border: none; padding: 0; z-index: 10;">
                                                                <i class="fas fa-eye" id="toggleIcon"></i>
                                                            </button>
                                                        </div>
                                                        <small class="text-muted" id="passwordHelp" style="display:none;">Leave blank to keep current password</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Role <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="role" id="role" required>
                                                            <option value="" disabled selected>Select Role</option>
                                                            <option value="super_admin">SUPER ADMIN</option>
                                                            <option value="admin">ADMIN</option>
                                                            <option value="marketing">MARKETING</option>
                                                            <option value="guest">GUEST</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" id="saveBtn" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead class="text-center">
                                <tr>
                                    <th>NAME</th>
                                    <th>EMAIL</th>
                                    <th>ROLE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="text-center">
                                <tr>
                                    <th>NAME</th>
                                    <th>EMAIL</th>
                                    <th>ROLE</th>
                                    <th>ACTION</th>
                                </tr>
                            </tfoot>
                            <tbody class="text-center">
                                @forelse($users as $user)
                                <tr id="row-{{ $user->id }}">
                                    <td>{{ Str::upper($user->name) }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ Str::upper(str_replace('_', ' ',$user->role)) }}</td>
                                    <td>
                                    @if($user->id !== Auth::id())
                                    @if(!$user->is_primary || $user->id === Auth::id())
                                        <button type="button" class="btn btn-sm btn-primary editUser" data-id="{{ $user->id }}" data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                    @if(!$user->is_primary)
                                        <button type="button" class="btn btn-sm btn-danger deleteUser" data-id="{{ $user->id }}" data-bs-toggle="tooltip" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-shield-alt"></i> Protected
                                        </span>
                                    @endif
                                    @else
                                        <button type="button" class="btn btn-sm btn-primary editUser" data-id="{{ $user->id }}" data-primary="{{ $user->is_primary ? 'true' : 'false' }}" data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">
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
@endsection('content')
@section('script')
<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    try {
        var notOrderableColumns = [1, 3];
        var skipColumns = [0, 1, 3];
        var table = $("#multi-filter-select").DataTable({
            pageLength: 10,
            order: [[0, 'asc']],
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
                    var select = $(
                        '<select class="form-select"><option value=""></option></select>'
                    )
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
    $('#togglePassword').click(function() {
        const passwordField = $('#password');
        const toggleIcon = $('#toggleIcon');
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            toggleIcon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            toggleIcon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    $('#createNewUser').click(function () {
        $('#saveBtn').val("create-user");
        $('#user_id').val('');
        $('#userForm').trigger("reset");
        $('#modalTitle').html('<span class="fw-mediumbold">New</span> <span class="fw-light">User</span>');
        $('#password').prop('required', true).attr('type', 'password');
        $('#passwordRequired').show();
        $('#passwordHelp').hide();
        $('#togglePassword').show();
        $('#toggleIcon').removeClass('fa-eye-slash').addClass('fa-eye');
        $('#userModal').modal('show');
    });
    $('body').on('click', '.editUser', function () {
        var user_id = $(this).data('id');
        Swal.fire({
            title: 'Loading Data...',
            text: 'Please wait a moment',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        $.get("{{ route('users.index') }}" + '/' + user_id + '/edit', function (data) {
            Swal.close();
            $('#modalTitle').html('<span class="fw-mediumbold">Edit</span> <span class="fw-light">User</span>');
            $('#saveBtn').val("edit-user");
            $('#userModal').modal('show');
            $('#user_id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#password').val('').attr('type', 'password');
            $('#password').prop('required', false);
            $('#passwordRequired').hide();
            $('#passwordHelp').show();
            $('#togglePassword').show();
            $('#toggleIcon').removeClass('fa-eye-slash').addClass('fa-eye');
            $('#role').val(data.role);
        }).fail(function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Failed to Load Data',
                text: xhr.responseJSON?.error || xhr.responseJSON?.message || 'Unable to retrieve user information. Please try again.',
                confirmButtonColor: '#d33'
            });
        });
    });
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        var formData = new FormData($('#userForm')[0]);
        var user_id = $('#user_id').val();
        var url = user_id ? "{{ route('users.index') }}" + '/' + user_id : "{{ route('users.store') }}";
        var actionText = user_id ? 'updated' : 'added';
        if (user_id) {
            formData.append('_method', 'PUT');
        }
        $('#saveBtn').html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#saveBtn').html('<i class="fa fa-save"></i> Save').prop('disabled', false);
                $('#userForm').trigger("reset");
                $('#userModal').modal('hide');
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
                $('#saveBtn').html('<i class="fa fa-save"></i> Save').prop('disabled', false);
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
                        text: response.responseJSON?.error || 'You do not have permission to perform this action.',
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
    $('body').on('click', '.deleteUser', function () {
        var user_id = $(this).data("id");
        var row = $('#row-' + user_id);        
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
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
                    url: "{{ route('users.index') }}" + '/' + user_id,
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
});
</script>
@endsection('script')