@extends('layouts.app')
@section('title')
Touch Shippers | Key Perfomance Indicator Marketing
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
                            <button class="btn btn-info btn-round ms-auto" id="ToggleColumns">
                                <i class="fas fa-toggle-on"></i>
                                <span class="d-none d-lg-inline"> Toggle Columns</span>
                            </button>
                            @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
                            <button class="btn btn-success btn-round ms-2" id="ExportExcel">
                                <i class="fas fa-file-excel"></i>
                                <span class="d-none d-lg-inline"> Export Excel</span>
                            </button>
                            <button class="btn btn-primary btn-round ms-2" id="createNewShipper">
                                <i class="fas fa-plus"></i>
                                <span class="d-none d-lg-inline"> New Data</span>
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="modal fade" id="shipperModal" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
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
                                                        <div class="input-group">
                                                            <select class="form-select" id="shipper_prefix" style="max-width: 60px;">
                                                                <option value="PT">PT</option>
                                                                <option value="CV">CV</option>
                                                                <option value="UD">UD</option>
                                                                <option value="PS">PS</option>
                                                                <option value=""></option>
                                                            </select>
                                                            <input type="text" class="form-control" id="shipper_name_input" placeholder="e.g. AJINOMOTO INDONESIA (AJI)" autocomplete="off" required/>
                                                            <input type="hidden" name="shipper_name" id="shipper_name_final">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="shipper_address">Shipper Address</label>
                                                        <input type="text" class="form-control" name="shipper_address" id="shipper_address" placeholder="e.g. Jl. Raya Mlirip No.110, Mlirip, Kec. Jetis, Kabupaten Mojokerto" autocomplete="off"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="shipper_concept">Shipper Concept <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="shipper_concept" id="shipper_concept" required>
                                                            <option value="" disabled selected>Select Shipper Concept</option>
                                                            <option value="NEW SHIPPER">NEW SHIPPER</option>
                                                            <option value="EXISTING SHIPPER">EXISTING SHIPPER</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="shipper_type">Shipper Type <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="shipper_type" id="shipper_type" required>
                                                            <option value="" disabled selected>Select Shipper Type</option>
                                                            <option value="DIRECT SHIPPER">DIRECT SHIPPER</option>
                                                            <option value="FORWARDING">FORWARDING</option>
                                                            <option value="VENDORING">VENDORING</option>
                                                            <option value="TRADING">TRADING</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="shipper_city">Shipper City <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="shipper_city" id="shipper_city" placeholder="e.g. MOJOKERTO" autocomplete="off" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="contact_person">Contact Person</label>
                                                        <input type="text" class="form-control" name="contact_person" id="contact_person" placeholder="e.g. PAK YUDHA" autocomplete="off"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="phone_number">Phone Number</label>
                                                        <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="e.g. +62 812-3456-7891" autocomplete="off"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="email_address">Email Address</label>
                                                        <input type="email" class="form-control" name="email_address" id="email_address" placeholder="e.g. yudha@ajinomoto.co.id" autocomplete="off"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="export">Export</label>
                                                        <input type="text" class="form-control" name="export" id="export" placeholder="e.g. THAILAND" autocomplete="off"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="import">Import</label>
                                                        <input type="text" class="form-control" name="import" id="import" placeholder="e.g. SINGAPORE" autocomplete="off"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="domestic">Domestic</label>
                                                        <input type="text" class="form-control" name="domestic" id="domestic" placeholder="e.g. KARAWANG" autocomplete="off"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="commodity">Commodity <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="commodity" id="commodity" placeholder="e.g. RAW MATERIALS" autocomplete="off" required/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="notes">Notes</label>
                                                        <textarea class="form-control" name="notes" id="notes" rows="1" placeholder="e.g. Add any specific handling or crane here..."></textarea>
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
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">
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
                                                    <label class="form-label" for="view_shipper_address">Shipper Address</label>
                                                    <input type="text" class="form-control-plaintext" id="view_shipper_address" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-group-default">
                                                    <label class="form-label" for="view_shipper_concept">Shipper Concept</label>
                                                    <input type="text" class="form-control-plaintext" id="view_shipper_concept" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-group-default">
                                                    <label class="form-label" for="view_shipper_type">Shipper Type</label>
                                                    <input type="text" class="form-control-plaintext" id="view_shipper_type" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-group-default">
                                                    <label class="form-label" for="view_shipper_city">Shipper City</label>
                                                    <input type="text" class="form-control-plaintext" id="view_shipper_city" readonly/>
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
                                                    <label class="form-label" for="view_notes">Notes</label>
                                                    <textarea class="form-control-plaintext" id="view_notes" rows="1" readonly></textarea>
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
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="row g-2 mb-2">
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <select class="form-select" id="filterData">
                                                <option value="">All Data</option>
                                                @if(Auth::user()->isAdmin() || Auth::user()->isMarketing())
                                                <option value="mine">My Data</option>
                                                @endif
                                                @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                                    @foreach($users as $user)
                                                    <option value="{{ $user->id }}">Data {{ $user->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <select class="form-select" id="filterSort">
                                                <option value="">Sort Default</option>
                                                <option value="latest">Latest Input</option>
                                                <option value="oldest">Oldest Input</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <select class="form-select form-select-sm" id="filterCONCEPT">
                                            <option value="">Filter CONCEPT</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select form-select-sm" id="filterTYPE">
                                            <option value="">Filter TYPE</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select form-select-sm" id="filterCITY">
                                            <option value="">Filter CITY</option>
                                        </select>
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
                        @endif
                        <div class="table-responsive">
                            <table id="multi-filter-select" class="display table table-striped table-hover">
                                <thead class="text-center">
                                    <tr>
                                        <th>SHIPPER</th>
                                        <th>CONCEPT</th>
                                        <th>TYPE</th>
                                        <th>CITY</th>
                                        <th>ADDRESS</th>
                                        <th>CP</th>
                                        <th>PHONE</th>
                                        <th>EMAIL</th>
                                        <th>EXPORT</th>
                                        <th>IMPORT</th>
                                        <th>DOMESTIC</th>
                                        <th>COMMODITY</th>
                                        <th>CREATED AT</th>
                                        <th>DETAIL</th>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                        <th>CREATED</th>
                                        @endif
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
                                        <th>ACTION</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="text-center text-nowrap">
                                    @foreach($shippers as $shipper)
                                    <tr id="row-{{ $shipper->id }}" data-user-id="{{ $shipper->user_id }}">
                                        <td>{{ $shipper->shipper_name }}</td>
                                        <td>{{ $shipper->shipper_concept }}</td>
                                        <td>{{ $shipper->shipper_type }}</td>
                                        <td>{{ $shipper->shipper_city }}</td>
                                        <td>{{ $shipper->shipper_address }}</td>
                                        <td>
                                            @if($shipper->contact_person)
                                            {{ $shipper->contact_person }}
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($shipper->phone_number)
                                            {{ $shipper->phone_number }}
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($shipper->email_address)
                                            {{ $shipper->email_address }}
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($shipper->export)
                                            {{ $shipper->export }}
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($shipper->import)
                                            {{ $shipper->import }}
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($shipper->domestic)
                                            {{ $shipper->domestic }}
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ $shipper->commodity }}</td>
                                        <td data-order="{{ $shipper->created_at }}">
                                            {{ $shipper->created_at->format('d M Y') }}
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-sm btn-info viewShipper" data-id="{{ $shipper->id }}" data-bs-toggle="tooltip" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                        <td>{{ Str::upper($shipper->user->name) }}</td>
                                        @endif
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || (Auth::user()->isMarketing() && $shipper->user_id == Auth::id()))
                                                    <button type="button" class="btn btn-sm btn-warning text-white editBtn" data-id="{{ $shipper->id }}" data-bs-toggle="tooltip" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger deleteBtn" data-id="{{ $shipper->id }}" data-bs-toggle="tooltip" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    @else
                                                    <button type="button" class="btn btn-sm btn-success" style="cursor: not-allowed;" data-bs-toggle="tooltip" title="Locked">
                                                        <i class="fas fa-lock"></i>
                                                    </button>
                                                @endif
                                            </div>
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
    var userRole = '{{ Auth::user()->role }}';
    var isAdmin = (userRole === 'SUPER ADMIN' || userRole === 'ADMIN');
    var hasActionColumn = (userRole === 'SUPER ADMIN' || userRole === 'ADMIN' || userRole === 'MARKETING');
    var currentUserId = {{ Auth::id() }};
    $('#ExportExcel').on('click', function() {
        var filterData = $('#filterData').val();
        var filterCONCEPT = $('#filterCONCEPT').val();
        var filterTYPE = $('#filterTYPE').val();
        var filterCITY = $('#filterCITY').val();
        var activeFilters = [];
        if (filterData && filterData !== '') {
            var dataText = $('#filterData option:selected').text();
            activeFilters.push('SCOPE : <b>' + dataText + '</b>');
        }
        if (filterCONCEPT) activeFilters.push('CONCEPT : <b>' + filterCONCEPT + '</b>');
        if (filterTYPE) activeFilters.push('TYPE : <b>' + filterTYPE + '</b>');
        if (filterCITY) activeFilters.push('CITY : <b>' + filterCITY + '</b>');
        var messageHTML = '';
        if (activeFilters.length > 0) {
            messageHTML = 'Filters : <br>' + activeFilters.join('<br>');
        } else {
            messageHTML = 'All Data';
        }
        Swal.fire({
            title: 'Export Data?',
            html: messageHTML,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#31ce36',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Export',
            cancelButtonText: 'Cancel',
            reverseButtons: false
        }).then((result) => {
            if (result.isConfirmed) {
                var params = new URLSearchParams({
                    data: filterData,
                    shipper_concept: filterCONCEPT,
                    shipper_type: filterTYPE,
                    shipper_city: filterCITY
                });
                var url = "{{ route('shippers.export') }}?" + params.toString();
                Swal.fire({
                    title: 'Preparing Excel...',
                    html: 'Exporting data file...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                setTimeout(function() {
                    window.location.href = url;
                    Swal.close();
                    setTimeout(function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Export Complete!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }, 500);
                }, 1000);
            }
        });
    });
    try {
        var notOrderableColumns;
        if (isAdmin) {
            notOrderableColumns = [1, 2, 3, 4, 6, 7, 11, 12, 13, 14, 15];
        } else if (hasActionColumn) {
            notOrderableColumns = [1, 2, 3, 4, 6, 7, 11, 12, 13, 14];
        } else {
            notOrderableColumns = [1, 2, 3, 4, 6, 7, 11, 12, 13];
        }
        var hiddenColumns = [4, 5, 6, 7, 8, 9, 10, 12];
        var table = $('#multi-filter-select').DataTable({
            pageLength: 10,
            autoWidth: false,
            order: [[0, 'asc']],
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
                emptyTable: 'No data available in table',
                zeroRecords: 'No matching records found',
                loadingRecords: 'Loading Data...',
                processing: 'Processing your request...',
                search: 'Search:',
                paginate: {
                    first: 'First',
                    last: 'Last',
                    next: 'Next',
                    previous: 'Previous'
                }
            },
            initComplete: function () {
                var api = this.api();
                api.column(1).data().unique().sort().each(function (d, j) {
                    if (d) {
                        $('#filterCONCEPT').append('<option value="' + d + '">' + d + '</option>');
                    }
                });
                api.column(2).data().unique().sort().each(function (d, j) {
                    if (d) {
                        $('#filterTYPE').append('<option value="' + d + '">' + d + '</option>');
                    }
                });
                api.column(3).data().unique().sort().each(function (d, j) {
                    if (d) {
                        $('#filterCITY').append('<option value="' + d + '">' + d + '</option>');
                    }
                });
            },
        });
        table.on('draw', function () {
            $('[data-bs-toggle="tooltip"]').tooltip('dispose');
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
        function checkFilters() {
            var hasFilter = 
                $('#filterData').val() !== '' ||
                $('#filterSort').val() !== '' ||
                $('#filterCONCEPT').val() !== '' ||
                $('#filterTYPE').val() !== '' ||
                $('#filterCITY').val() !== '';
            if (hasFilter) {
                $('#clearFilterRow').fadeIn();
            } else {
                $('#clearFilterRow').fadeOut();
            }
        }
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
            checkFilters();
        });
        $('#filterSort').on('change', function() {
            var val = $(this).val();
            var createdAtIndex = 12;
            if (val === 'latest') {
                table.order([createdAtIndex, 'desc']).draw();
            } else if (val === 'oldest') {
                table.order([createdAtIndex, 'asc']).draw();
            } else {
                table.order([0, 'asc']).draw();
            }
            checkFilters();
        });
        $('#filterCONCEPT, #filterTYPE, #filterCITY').on('change', function () {
            var mapIdToColumn = {
                'filterCONCEPT': 1,
                'filterTYPE': 2,
                'filterCITY': 3
            };
            var colIndex = mapIdToColumn[this.id];
            if (typeof colIndex !== 'undefined') {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                table.column(colIndex).search(val ? '^' + val + '$' : '', true, false).draw();
                checkFilters();
            } else {
                console.error('Column index not found for filter ID:', this.id);
            }
        });
        $('#clearFilters').on('click', function() {
            $('#filterData, #filterSort, #filterCONCEPT, #filterTYPE, #filterCITY').val('');
            $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(function(fn) {
                return fn.name !== 'dataFilter';
            });
            table.order([0, 'asc']);
            table.search('').columns().search('').draw();
            checkFilters();
        });
    } catch (error) {
        console.error('DataTables initialization error:', error);
    }
    $('#ToggleColumns').on('click', function (e) {
        e.preventDefault();
        if (!table) return;
        var $btn = $(this);
        var isHidden = !table.column(5).visible();
        table.columns([5, 8, 9, 10]).visible(isHidden);
        if (isHidden) {
            $btn.html('<i class="fas fa-toggle-off"></i><span class="d-none d-lg-inline"> Toggle Columns</span>');
        } else {
            $btn.html('<i class="fas fa-toggle-on"></i><span class="d-none d-lg-inline"> Toggle Columns</span>');
        }
        table.columns.adjust().draw();
    });
    function updateShipperName() {
        var prefix = $('#shipper_prefix').val();
        var nameInput = $('#shipper_name_input').val();
        var fullName = '';
        if (prefix === '') {
            fullName = nameInput;
        } else {
            fullName = prefix + '. ' + nameInput;
        }
        $('#shipper_name_final').val(fullName);
    }
    $('#shipper_prefix, #shipper_name_input').on('change keyup', updateShipperName);
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
            $('#view_shipper_name').val(data.shipper_name || '—');
            $('#view_shipper_concept').val(data.shipper_concept || '—');
            $('#view_shipper_type').val(data.shipper_type || '—');
            $('#view_shipper_city').val(data.shipper_city || '—');
            $('#view_shipper_address').val(data.shipper_address || '—');
            $('#view_contact_person').val(data.contact_person || '—');
            $('#view_phone_number').val(data.phone_number || '—');
            $('#view_email_address').val(data.email_address || '—');
            $('#view_export').val(data.export || '—');
            $('#view_import').val(data.import || '—');
            $('#view_domestic').val(data.domestic || '—');
            $('#view_commodity').val(data.commodity || '—');
            if ($('#view_notes').length) {
                $('#view_notes').val(data.notes || '—');
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
    if (hasActionColumn) {
        $('#createNewShipper').click(function () {
            $('#saveBtn').val('create-shipper');
            $('#shipper_id').val('');
            $('#shipperForm').trigger('reset');
            $('#shipper_prefix').val('PT');
            $('#shipper_name_input').val('');
            $('#shipper_name_final').val('');
            $('#modalTitle').html('<span class="fw-mediumbold">New</span> <span class="fw-light">Shipper</span>');
            $('#shipperModal').modal('show');
        });
        $('#saveBtn').click(function (e) {
            updateShipperName();
            e.preventDefault();
            var formData = new FormData($('#shipperForm')[0]);
            var shipper_id = $('#shipper_id').val();
            var url = shipper_id ? "{{ route('shippers.index') }}" + '/' + shipper_id : "{{ route('shippers.store') }}";
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
                    $('#shipperForm').trigger('reset');
                    $('#shipperModal').modal('hide');
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
                    $('#shipperForm').trigger('reset');
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
                $('#modalTitle').html('<span class="fw-mediumbold">Edit</span> <span class="fw-light">Shipper</span>');
                $('#saveBtn').val('edit-shipper');
                $('#shipperModal').modal('show');
                $('#shipper_id').val(data.id);
                $('#shipper_name_final').val(data.shipper_name);
                var existingName = data.shipper_name || '';
                var parts = existingName.split('. ');
                var prefixFound = false;
                if (parts.length > 1) {
                    var potentialPrefix = parts[0];
                    $('#shipper_prefix option').each(function() {
                        if ($(this).val() === potentialPrefix) {
                            $('#shipper_prefix').val(potentialPrefix);
                            parts.shift();
                            $('#shipper_name_input').val(parts.join('. '));
                            prefixFound = true;
                            return false;
                        }
                    });
                }
                if (!prefixFound) {
                    $('#shipper_prefix').val('');
                    $('#shipper_name_input').val(existingName);
                }
                $('#shipper_concept').val(data.shipper_concept);
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
                $('#notes').val(data.notes);
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Load Data',
                    confirmButtonColor: '#d33'
                });
            });
        });
        $('body').on('click', '.deleteBtn', function () {
            var shipper_id = $(this).data('id');
            var row = $('#row-' + shipper_id);
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
                        type: 'DELETE',
                        url: "{{ route('shippers.index') }}" + '/' + shipper_id,
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