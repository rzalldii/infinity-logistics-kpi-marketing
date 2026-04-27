@extends('layouts.app')
@section('title')
User Management | Key Performance Indicator Marketing
@endsection
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
                                <i class="fas fa-plus"></i>
                                <span class="d-none d-lg-inline"> New Data</span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title" id="modalTitle">
                                            <span class="fw-mediumbold">New</span>
                                            <span class="fw-light">Users</span>
                                        </h5>
                                    </div>
                                    <div class="modal-body">
                                        <form id="userForm">
                                            @csrf
                                            <input type="hidden" name="user_id" id="user_id">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="name" id="name" autocomplete="off" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" name="email" id="email" autocomplete="off" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="password">Password <span class="text-danger" id="passwordRequired">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="password" class="form-control" name="password" id="password" required/>
                                                            <button type="button" class="btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2" id="togglePassword" style="background: transparent; border: none; padding: 0; z-index: 10;">
                                                                <i class="fas fa-eye" id="toggleIcon"></i>
                                                            </button>
                                                        </div>
                                                        <small class="text-muted" id="passwordHelp" style="display:none;">Leave blank to keep current password</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="role">Role <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="role" id="role" required>
                                                            <option value="" disabled selected>Select Role</option>
                                                            <option value="SUPER ADMIN">SUPER ADMIN</option>
                                                            <option value="ADMIN">ADMIN</option>
                                                            <option value="MARKETING">MARKETING</option>
                                                            <option value="GUEST">GUEST</option>
                                                        </select>
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
                            <table class="display table table-striped table-hover table-bordered">
                                <thead class="text-center">
                                    <tr>
                                        <th>NAME</th>
                                        <th>EMAIL</th>
                                        <th>ROLE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center text-nowrap">
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if($user->id !== Auth::id())
                                                    @if(!$user->is_primary)
                                                        <button type="button" class="btn btn-sm btn-warning text-white editBtn" data-id="{{ $user->id }}" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    @endif
                                                    @if(!$user->is_primary)
                                                        <button type="button" class="btn btn-sm btn-danger deleteBtn" data-id="{{ $user->id }}" data-bs-toggle="tooltip" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-success" style="cursor: not-allowed;" data-bs-toggle="tooltip" title="Protected">
                                                            <i class="fas fa-shield-alt"></i>
                                                        </button>
                                                    @endif
                                                @else
                                                    <button type="button" class="btn btn-sm btn-warning text-white editBtn" data-id="{{ $user->id }}" data-bs-toggle="tooltip" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
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
@endsection
@section('script')
<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('[data-bs-toggle="tooltip"]').tooltip();
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
        $('#user_id').val('');
        $('#userForm').trigger('reset');
        $('#modalTitle').html('<span class="fw-mediumbold">New</span> <span class="fw-light">User</span>');
        $('#password').prop('required', true).attr('type', 'password');
        $('#passwordRequired').show();
        $('#passwordHelp').hide();
        $('#togglePassword').show();
        $('#toggleIcon').removeClass('fa-eye-slash').addClass('fa-eye');
        $('#userModal').modal('show');
    });
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        var formData = new FormData($('#userForm')[0]);
        var user_id = $('#user_id').val();
        var url = user_id ? "{{ route('users.index') }}" + '/' + user_id : "{{ route('users.store') }}";
        if (user_id) {
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
                $('#userForm').trigger('reset');
                $('#userModal').modal('hide');
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
                var isEditMode = $('#user_id').val() !== '';
                $('#userForm').trigger('reset');
                if (isEditMode) {
                    $('#password').prop('required', false).attr('type', 'password');
                    $('#passwordRequired').hide();
                    $('#passwordHelp').show();
                } else {
                    $('#password').prop('required', true).attr('type', 'password');
                    $('#passwordRequired').show();
                    $('#passwordHelp').hide();
                }
                $('#toggleIcon').removeClass('fa-eye-slash').addClass('fa-eye');
                Swal.fire({
                    icon: 'success',
                    title: 'Form Cleared Successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });
    $('body').on('click', '.editBtn', function () {
        var user_id = $(this).data('id');
        Swal.fire({
            title: 'Loading Data...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        $.get("{{ route('users.index') }}" + '/' + user_id + '/edit', function (data) {
            Swal.close();
            $('#modalTitle').html('<span class="fw-mediumbold">Edit</span> <span class="fw-light">User</span>');
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
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Failed to Load Data',
                confirmButtonColor: '#d33'
            });
        });
    });
    $('body').on('click', '.deleteBtn', function () {
        var user_id = $(this).data('id');
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
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('users.index') }}" + '/' + user_id,
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Data Deleted Successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            location.reload();
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
});
</script>
@endsection