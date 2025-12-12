@extends('layouts.app')
@section('title')
Report Activities | Admin Infinity Logistics Indonesia
@endsection('title')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h1 class="card-title">Report Activities</h1>
                            @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                <button class="btn btn-success btn-round ms-auto" id="ExportExcel">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </button>
                                <button class="btn btn-info btn-round ms-2" id="ToggleColumns">
                                    <i class="fas fa-eye"></i> Toggle Columns
                                </button>
                                <button class="btn btn-primary btn-round ms-2" id="createNewActivity">
                                    <i class="fas fa-plus"></i> Add Data
                                </button>
                            @else
                                <button class="btn btn-info btn-round ms-auto" id="ToggleColumns">
                                    <i class="fas fa-eye"></i> Toggle Columns
                                </button>
                                <button class="btn btn-primary btn-round ms-2" id="createNewActivity">
                                    <i class="fas fa-plus"></i> Add Data
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title" id="modalTitle">
                                            <span class="fw-mediumbold">Export</span>
                                            <span class="fw-light">Excel</span>
                                        </h5>
                                    </div>
                                    <div class="modal-body">
                                        <form id="exportForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold" for="export_date_from">From Date <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" id="export_date_from" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold" for="export_date_to">To Date <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" id="export_date_to" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-success" id="confirmExport">
                                            <i class="fas fa-download"></i> Download
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            <i class="fas fa-window-close"></i> Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="activityModal" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                                                        <label class="form-label" for="concept_type">Concept Type <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="concept_type" id="concept_type" required>
                                                            <option value="" disabled selected>Select Concept Type</option>
                                                            <option value="NEW SHIPPER">NEW SHIPPER</option>
                                                            <option value="FOLLOW UP">FOLLOW UP</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="shipper_id">Shipper Name <span class="text-danger">*</span></label>
                                                        <select class="form-select select2" name="shipper_id" id="shipper_id" required>
                                                            <option value="" disabled selected>Select Shipper Name</option>
                                                            @foreach($shippers as $shipper)
                                                                <option value="{{ $shipper->id }}" data-type="{{ $shipper->shipper_type }}" data-commodity="{{ $shipper->commodity }}">
                                                                    {{ $shipper->shipper_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default" id="shipper_type_group" style="display: none;">
                                                        <label class="form-label" for="shipper_type_display">Shipper Type</label>
                                                        <input type="text" class="form-control-plaintext" id="shipper_type_display" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default" id="commodity_group" style="display: none;">
                                                        <label class="form-label" for="commodity_display">Commodity</label>
                                                        <input type="text" class="form-control-plaintext" id="commodity_display" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="activity_type">Activity Type <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="activity_type" id="activity_type" required>
                                                            <option value="" disabled selected>Select Activity Type</option>
                                                            <option value="VISIT">VISIT</option>
                                                            <option value="CALL">CALL</option>
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
                                                        <label class="form-label" for="status">Status</label>
                                                        <select class="form-select" name="status" id="status">
                                                            <option value="" disabled selected>Select Status</option>
                                                            <option value="CLOSING">CLOSING</option>
                                                            <option value="PENDING">PENDING</option>
                                                            <option value="FAILED">FAILED</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default" id="status_detail_group" style="display: none;">
                                                        <label class="form-label" for="status_detail">Status Detail</label>
                                                        <input type="text" class="form-control" name="status_detail" id="status_detail" placeholder="e.g. Waiting for approval, Price too high, etc." autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="prospect">Prospect</label>
                                                        <textarea class="form-control" name="prospect" id="prospect" rows="1" placeholder="e.g. Potential for 20 TEUs/month next quarter..." autocomplete="off"></textarea>
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
                                                    <label class="form-label" for="view_concept_type">Concept Type</label>
                                                    <input type="text" class="form-control-plaintext" id="view_concept_type" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-group-default">
                                                    <label class="form-label" for="view_shipper_name">Shipper Name</label>
                                                    <input type="text" class="form-control-plaintext" id="view_shipper_name" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-group-default" id="view_shipper_type_group">
                                                    <label class="form-label" for="view_shipper_type">Shipper Type</label>
                                                    <input type="text" class="form-control-plaintext" id="view_shipper_type" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-group-default" id="view_commodity_group">
                                                    <label class="form-label" for="view_commodity">Commodity</label>
                                                    <input type="text" class="form-control-plaintext" id="view_commodity" readonly>
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
                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-select" id="filterUser">
                                        <option value="">All Data</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->name }}">Data {{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="table-responsive">
                            <table id="multi-filter-select" class="display table table-striped table-hover" style="width:100%">
                                <thead class="text-center">
                                    <tr>
                                        <th>DATE</th>
                                        <th>CONCEPT</th>
                                        <th>SHIPPER</th>
                                        <th>TYPE</th>
                                        <th>COMMODITY</th>
                                        <th>ACTIVITY</th>
                                        <th>VISIT</th>
                                        <th>STATUS</th>
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
                                        <th>TYPE</th>
                                        <th>COMMODITY</th>
                                        <th>ACTIVITY</th>
                                        <th>VISIT</th>
                                        <th>STATUS</th>
                                        <th>DETAIL</th>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                            <th>CREATED</th>
                                        @endif
                                        <th>ACTION</th>
                                    </tr>
                                </tfoot>
                                <tbody class="text-center">
                                    @foreach($activities as $activity)
                                    <tr>
                                        <td>{{ Str::upper($activity->created_at->format("d M")) }}</td>
                                        <td>{{ $activity->concept_type }}</td>
                                        <td>{{ $activity->shipper->shipper_name }}</td>
                                        <td>{{ $activity->shipper->shipper_type }}</td>
                                        <td>{{ $activity->shipper->commodity }}</td>
                                        <td>{{ $activity->activity_type }}</td>
                                        <td>
                                            @if($activity->visit_date)
                                                {{ Str::upper(\Carbon\Carbon::parse($activity->visit_date)->format("d M")) }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($activity->status)
                                                {{ $activity->status }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info viewActivity" data-id="{{ $activity->id }}" data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                            <td>{{ Str::upper($activity->user->name) }}</td>
                                        @endif
                                        <td>
                                            @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                                <button type="button" class="btn btn-sm btn-warning text-white editActivity" data-id="{{ $activity->id }}" data-report-date="{{ $activity->created_at }}" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger deleteActivity" data-id="{{ $activity->id }}" data-report-date="{{ $activity->created_at }}" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @else
                                                @php
                                                    $reportDate = $activity->created_at->format('Y-m-d');
                                                    $today = \Carbon\Carbon::now()->format('Y-m-d');
                                                    $isToday = ($reportDate === $today);
                                                @endphp
                                                @if($isToday)
                                                    <button type="button" class="btn btn-sm btn-warning text-white editActivity" data-id="{{ $activity->id }}" data-report-date="{{ $activity->created_at }}" data-bs-toggle="tooltip" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger deleteActivity" data-id="{{ $activity->id }}" data-report-date="{{ $activity->created_at }}" data-bs-toggle="tooltip" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-success" style="cursor: not-allowed;" data-bs-toggle="tooltip" title="Locked">
                                                        <i class="fas fa-lock"></i>
                                                    </button>
                                                @endif
                                            @endif
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
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header text-center">
                        <div class="card-title">DAILY SUMMARY</div>
                        <p class="text-white mb-0">
                            <small>{{ now()->format('d M Y') }}</small>
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <p><strong>New :</strong> <span id="dailyNewShipper">{{ $dailyReport->new_shipper_count ?? 0 }}</span></p>
                                <p><strong>Follow :</strong> <span id="dailyFollowUp">{{ $dailyReport->follow_up_count ?? 0 }}</span></p>
                            </div>
                            <div class="col-6">
                                <p><strong>Visit :</strong> <span id="dailyVisit">{{ $dailyReport->visit_count ?? 0 }}</span></p>
                                <p><strong>Call :</strong> <span id="dailyCall">{{ $dailyReport->call_count ?? 0 }}</span></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <p>Direct : <span id="dailyDirect">{{ $dailyReport->direct_shipper_count ?? 0 }}</span></p>
                                <p>Forward : <span id="dailyForward">{{ $dailyReport->forwarding_count ?? 0 }}</span></p>
                            </div>
                            <div class="col-6">
                                <p>Trade : <span id="dailyTrade">{{ $dailyReport->trading_count ?? 0 }}</span></p>
                                <p>EMKL : <span id="dailyEmkl">{{ $dailyReport->emkl_count ?? 0 }}</span></p>
                            </div>
                        </div>
                        <hr>
                        <p class="mb-2 text-center">
                            <span class="badge badge-success">CLOSING : <span id="dailyClosing">{{ $dailyReport->closing_count ?? 0 }}</span></span>
                            <span class="badge badge-warning">PENDING : <span id="dailyPending">{{ $dailyReport->pending_count ?? 0 }}</span></span>
                            <span class="badge badge-danger">FAILED : <span id="dailyFailed">{{ $dailyReport->failed_count ?? 0 }}</span></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header text-center">
                        <div class="card-title">WEEKLY SUMMARY</div>
                        <p class="text-white mb-0">
                            <small>{{ now()->startOfWeek()->format('d M') }} - {{ now()->endOfWeek()->format('d M Y') }}</small>
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <p><strong>New :</strong> <span id="weeklyNewShipper">{{ $weeklyReport->new_shipper_count ?? 0 }}</span></p>
                                <p><strong>Follow :</strong> <span id="weeklyFollowUp">{{ $weeklyReport->follow_up_count ?? 0 }}</span></p>
                            </div>
                            <div class="col-6">
                                <p><strong>Visit :</strong> <span id="weeklyVisit">{{ $weeklyReport->visit_count ?? 0 }}</span></p>
                                <p><strong>Call :</strong> <span id="weeklyCall">{{ $weeklyReport->call_count ?? 0 }}</span></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <p>Direct : <span id="weeklyDirect">{{ $weeklyReport->direct_shipper_count ?? 0 }}</span></p>
                                <p>Forward : <span id="weeklyForward">{{ $weeklyReport->forwarding_count ?? 0 }}</span></p>
                            </div>
                            <div class="col-6">
                                <p>Trade : <span id="weeklyTrade">{{ $weeklyReport->trading_count ?? 0 }}</span></p>
                                <p>EMKL : <span id="weeklyEmkl">{{ $weeklyReport->emkl_count ?? 0 }}</span></p>
                            </div>
                        </div>
                        <hr>
                        <p class="mb-2 text-center">
                            <span class="badge badge-success">CLOSING : <span id="weeklyClosing">{{ $weeklyReport->closing_count ?? 0 }}</span></span>
                            <span class="badge badge-warning">PENDING : <span id="weeklyPending">{{ $weeklyReport->pending_count ?? 0 }}</span></span>
                            <span class="badge badge-danger">FAILED : <span id="weeklyFailed">{{ $weeklyReport->failed_count ?? 0 }}</span></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-secondary">
                    <div class="card-header text-center">
                        <div class="card-title">MONTHLY SUMMARY</div>
                        <p class="text-white mb-0">
                            <small>{{ now()->format('F Y') }}</small>
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <p><strong>New :</strong> <span id="monthlyNewShipper">{{ $monthlyReport->new_shipper_count ?? 0 }}</span></p>
                                <p><strong>Follow :</strong> <span id="monthlyFollowUp">{{ $monthlyReport->follow_up_count ?? 0 }}</span></p>
                            </div>
                            <div class="col-6">
                                <p><strong>Visit :</strong> <span id="monthlyVisit">{{ $monthlyReport->visit_count ?? 0 }}</span></p>
                                <p><strong>Call :</strong> <span id="monthlyCall">{{ $monthlyReport->call_count ?? 0 }}</span></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <p>Direct : <span id="monthlyDirect">{{ $monthlyReport->direct_shipper_count ?? 0 }}</span></p>
                                <p>Forward : <span id="monthlyForward">{{ $monthlyReport->forwarding_count ?? 0 }}</span></p>
                            </div>
                            <div class="col-6">
                                <p>Trade : <span id="monthlyTrade">{{ $monthlyReport->trading_count ?? 0 }}</span></p>
                                <p>EMKL : <span id="monthlyEmkl">{{ $monthlyReport->emkl_count ?? 0 }}</span></p>
                            </div>
                        </div>
                        <hr>
                        <p class="mb-2 text-center">
                            <span class="badge badge-success">CLOSING : <span id="monthlyClosing">{{ $monthlyReport->closing_count ?? 0 }}</span></span>
                            <span class="badge badge-warning">PENDING : <span id="monthlyPending">{{ $monthlyReport->pending_count ?? 0 }}</span></span>
                            <span class="badge badge-danger">FAILED : <span id="monthlyFailed">{{ $monthlyReport->failed_count ?? 0 }}</span></span>
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
    var activitiesData = @json($activities);
    var table;
    var isTableReady = false;
    function isSameDate(date1, date2) {
        return date1.getFullYear() === date2.getFullYear() &&
               date1.getMonth() === date2.getMonth() &&
               date1.getDate() === date2.getDate();
    }
    function calculateSummaryFromFiltered(userName) {
        var today = new Date();
        today.setHours(0, 0, 0, 0);
        var weekStart = new Date(today);
        var day = weekStart.getDay();
        var diff = weekStart.getDate() - day + (day === 0 ? -6 : 1);
        weekStart.setDate(diff);
        weekStart.setHours(0, 0, 0, 0);
        var weekEnd = new Date(weekStart);
        weekEnd.setDate(weekStart.getDate() + 6);
        weekEnd.setHours(23, 59, 59, 999);
        var monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
        monthStart.setHours(0, 0, 0, 0);
        var monthEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        monthEnd.setHours(23, 59, 59, 999);
        var daily = {
            new_shipper: 0, follow_up: 0, visit: 0, call: 0,
            direct: 0, forward: 0, trade: 0, emkl: 0,
            closing: 0, pending: 0, failed: 0
        };
        var weekly = {
            new_shipper: 0, follow_up: 0, visit: 0, call: 0,
            direct: 0, forward: 0, trade: 0, emkl: 0,
            closing: 0, pending: 0, failed: 0
        };
        var monthly = {
            new_shipper: 0, follow_up: 0, visit: 0, call: 0,
            direct: 0, forward: 0, trade: 0, emkl: 0,
            closing: 0, pending: 0, failed: 0
        };
        activitiesData.forEach(function(activity) {
            if (userName && activity.user && activity.user.name !== userName) {
                return;
            }
            var reportDate = new Date(activity.created_at);
            reportDate.setHours(0, 0, 0, 0);
            if (reportDate.getTime() === today.getTime()) {
                updateCounters(daily, activity);
            }
            if (reportDate >= weekStart && reportDate <= weekEnd) {
                updateCounters(weekly, activity);
            }
            if (reportDate >= monthStart && reportDate <= monthEnd) {
                updateCounters(monthly, activity);
            }
        });
        updateSummaryUI(daily, weekly, monthly);
    }
    function updateCounters(counter, activity) {
        if (activity.concept_type === 'NEW SHIPPER') counter.new_shipper++;
        if (activity.concept_type === 'FOLLOW UP') counter.follow_up++;
        if (activity.activity_type === 'VISIT') counter.visit++;
        if (activity.activity_type === 'CALL') counter.call++;
        if (activity.shipper) {
            if (activity.shipper.shipper_type === 'DIRECT SHIPPER') counter.direct++;
            if (activity.shipper.shipper_type === 'FORWARDING') counter.forward++;
            if (activity.shipper.shipper_type === 'TRADING') counter.trade++;
            if (activity.shipper.shipper_type === 'EMKL / TRANSPORTER') counter.emkl++;
        }
        if (activity.status === 'CLOSING') counter.closing++;
        if (activity.status === 'PENDING') counter.pending++;
        if (activity.status === 'FAILED') counter.failed++;
    }
    function updateSummaryUI(daily, weekly, monthly) {
        $('#dailyNewShipper').text(daily.new_shipper);
        $('#dailyFollowUp').text(daily.follow_up);
        $('#dailyVisit').text(daily.visit);
        $('#dailyCall').text(daily.call);
        $('#dailyDirect').text(daily.direct);
        $('#dailyForward').text(daily.forward);
        $('#dailyTrade').text(daily.trade);
        $('#dailyEmkl').text(daily.emkl);
        $('#dailyClosing').text(daily.closing);
        $('#dailyPending').text(daily.pending);
        $('#dailyFailed').text(daily.failed);
        $('#weeklyNewShipper').text(weekly.new_shipper);
        $('#weeklyFollowUp').text(weekly.follow_up);
        $('#weeklyVisit').text(weekly.visit);
        $('#weeklyCall').text(weekly.call);
        $('#weeklyDirect').text(weekly.direct);
        $('#weeklyForward').text(weekly.forward);
        $('#weeklyTrade').text(weekly.trade);
        $('#weeklyEmkl').text(weekly.emkl);
        $('#weeklyClosing').text(weekly.closing);
        $('#weeklyPending').text(weekly.pending);
        $('#weeklyFailed').text(weekly.failed);
        $('#monthlyNewShipper').text(monthly.new_shipper);
        $('#monthlyFollowUp').text(monthly.follow_up);
        $('#monthlyVisit').text(monthly.visit);
        $('#monthlyCall').text(monthly.call);
        $('#monthlyDirect').text(monthly.direct);
        $('#monthlyForward').text(monthly.forward);
        $('#monthlyTrade').text(monthly.trade);
        $('#monthlyEmkl').text(monthly.emkl);
        $('#monthlyClosing').text(monthly.closing);
        $('#monthlyPending').text(monthly.pending);
        $('#monthlyFailed').text(monthly.failed);
    }
    $('#ExportExcel').on('click', function() {
        var today = new Date();
        var firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        $('#export_date_from').val(formatDateForInput(firstDayOfMonth));
        $('#export_date_to').val(formatDateForInput(today));
        $('#exportModal').modal('show');
    });
    function formatDateForInput(date) {
        var year = date.getFullYear();
        var month = String(date.getMonth() + 1).padStart(2, '0');
        var day = String(date.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }
    $('#confirmExport').on('click', function() {
        var dateFrom = $('#export_date_from').val();
        var dateTo = $('#export_date_to').val();
        if (!dateFrom || !dateTo) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Date Range',
            });
            return;
        }
        if (new Date(dateFrom) > new Date(dateTo)) {
            Swal.fire({
                icon: 'error',
                title: 'Start Date Cannot Exceed End Date!',
            });
            return;
        }
        var url = "{{ route('activities.export.excel') }}";
        url += '?date_from=' + dateFrom;
        url += '&date_to=' + dateTo;
        $('#confirmExport').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        $('#exportModal').modal('hide');
        Swal.fire({
            title: 'Preparing Excel...',
            html: 'Exporting data from <b>' + dateFrom + '</b> to <b>' + dateTo + '</b>',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        setTimeout(function() {
            window.location.href = url;
            Swal.close();
            $('#confirmExport').prop('disabled', false).html('<i class="fas fa-download"></i> Download Excel');
            setTimeout(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Export Complete!',
                    timer: 1500,
                    showConfirmButton: false
                });
            }, 500);
        }, 1000);
    });
    $('#export_date_from').on('change', function() {
        var dateFrom = new Date($(this).val());
        var dateTo = $('#export_date_to').val();
        if (dateTo) {
            var dateToObj = new Date(dateTo);
            if (dateFrom > dateToObj) {
                $('#export_date_to').val($(this).val());
            }
        }
    });
    try {
        var notOrderableColumns;
        if (isAdmin) {
            notOrderableColumns = [1, 3, 5, 7, 8, 9, 10];
        } else {
            notOrderableColumns = [1, 3, 5, 7, 8, 9];
        }
        var skipColumns;
        if (isAdmin) {
            skipColumns = [0, 2, 4, 6, 8, 9, 10];
        } else {
            skipColumns = [0, 2, 4, 6, 8, 9];
        }
        var hiddenColumns;
        if (isAdmin) {
            hiddenColumns = [3, 4, 6];
        } else {
            hiddenColumns = [3, 4, 6];
        }
        table = $("#multi-filter-select").DataTable({
            pageLength: 10,
            autoWidth: false,
            order: [[0, 'desc']],
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
                isTableReady = true;
                console.log('DataTable initialized successfully');
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
                calculateSummaryFromFiltered(null);
            },
        });
        table.on('draw', function () {
            $('[data-bs-toggle="tooltip"]').tooltip('dispose');
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    } catch (error) {
        console.error('DataTables initialization error:', error);
    }
    $('#filterUser').on('change', function() {
        if (!isTableReady || !table) {
            console.warn('Table not ready yet');
            return;
        }
        var userName = this.value;
        if (isAdmin) {
            var searchValue = userName ? "^" + userName + "$" : "";
            table.column(9).search(searchValue, true, false).draw();
            calculateSummaryFromFiltered(userName);
        }
    });
    $('#ToggleColumns').on('click', function (e) {
        e.preventDefault();
        if (!isTableReady || !table) {
            console.warn('Table not ready yet');
            return;
        }
        var $btn = $(this);
        var isHidden = !table.column(3).visible();
        table.columns([3, 4, 6]).visible(isHidden);
        if (isHidden) {
            $btn.html('<i class="fas fa-eye-slash"></i> Toggle Columns');
        } else {
            $btn.html('<i class="fas fa-eye"></i> Toggle Columns');
        }
        table.columns.adjust().draw();
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
            $('#view_concept_type').val(data.concept_type || '—');
            if (data.shipper) {
                $('#view_shipper_name').val(data.shipper.shipper_name);
                if (data.shipper.shipper_type) {
                    $('#view_shipper_type_group').show();
                    $('#view_shipper_type').val(data.shipper.shipper_type);
                } else {
                    $('#view_shipper_type_group').hide();
                }
                if (data.shipper.commodity) {
                    $('#view_commodity_group').show();
                    $('#view_commodity').val(data.shipper.commodity);
                } else {
                    $('#view_commodity_group').hide();
                }
            } else {
                $('#view_shipper_name').val('—');
                $('#view_shipper_type_group').hide();
                $('#view_commodity_group').hide();
            }
            $('#view_activity_type').val(data.activity_type || '—');
            if (data.visit_date) {
                var visitDate = new Date(data.visit_date);
                var formattedVisitDate = visitDate.getDate().toString().padStart(2, '0') + '/' + 
                                    (visitDate.getMonth() + 1).toString().padStart(2, '0') + '/' + 
                                    visitDate.getFullYear();
                $('#view_visit_date').val(formattedVisitDate);
                $('#view_visit_date_group').show();
            } else {
                $('#view_visit_date').val('—');
                $('#view_visit_date_group').hide();
            }
            $('#view_status').val(data.status || '—');
            if (data.status_detail) {
                $('#view_status_detail').val(data.status_detail);
                $('#view_status_detail_group').show();
            } else {
                $('#view_status_detail').val('—');
                $('#view_status_detail_group').hide();
            }
            $('#view_prospect').val(data.prospect || '—');
            $('#Viewactivity').modal('show');
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Failed to Load Data',
                confirmButtonColor: '#d33'
            });
        });
    });
    function formatShipper(state) {
        if (!state.id) { return state.text; }
        var type = $(state.element).data('type');
        var commodity = $(state.element).data('commodity');
        var $state = $(
            '<div class="d-flex flex-column">' +
                '<span class="fw-bold">' + state.text + '</span>' +
                '<span class="text-muted small" style="font-size: 0.85em;">' + 
                    '<i class="fas fa-info-circle me-1"></i>' + (type || '-') + 
                    (commodity ? ' &bull; ' + commodity : '') + 
                '</span>' +
            '</div>'
        );
        return $state;
    }
    if (!$('#shipper_id').hasClass("select2-hidden-accessible")) {
        $('#shipper_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#activityModal'),
            templateResult: formatShipper,
            language: {
                noResults: function() {
                    return "No data found";
                }
            },
        });
    }
    $('#shipper_id').on('change', function() {
        var selectedOption = $(this).find(':selected');
        var shipperType = selectedOption.data('type');
        var commodity = selectedOption.data('commodity');
        if ($(this).val()) {
            $('#shipper_type_group').show();
            $('#shipper_type_display').val(shipperType || '-');
            if (commodity) {
                $('#commodity_group').show();
                $('#commodity_display').val(commodity);
            } else {
                $('#commodity_group').hide();
                $('#commodity_display').val('');
            }
        } else {
            $('#shipper_type_group').hide();
            $('#shipper_type_display').val('');
            $('#commodity_group').hide();
            $('#commodity_display').val('');
        }
    });
    $('#activity_type').change(function() {
        if ($(this).val() === 'VISIT') {
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
    $('#createNewActivity').click(function () {
        $('#saveBtn').val("create-activity");
        $('#activity_id').val('');
        $('#activityForm').trigger("reset");
        $('#shipper_id').val(null).trigger('change');
        $('#shipper_type_group').hide();
        $('#commodity_group').hide();
        $('#visit_date_group').hide();
        $('#status_detail_group').hide();
        $('#modalTitle').html('<span class="fw-mediumbold">New</span> <span class="fw-light">Activity</span>');
        $('#activityModal').modal('show');
    });
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        var formData = new FormData($('#activityForm')[0]);
        var activity_id = $('#activity_id').val();
        var url = activity_id ? "{{ route('activities.index') }}" + '/' + activity_id : "{{ route('activities.store') }}";
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
                $('#activityForm').trigger("reset");
                $('#shipper_id').val(null).trigger('change');
                $('#shipper_type_group').hide();
                $('#commodity_group').hide();
                $('#visit_date_group').hide();
                $('#status_detail_group').hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Form Cleared Successfully!',
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        });
    });
    $('body').on('click', '.editActivity', function () {
        var activity_id = $(this).data('id');
        var reportDate = $(this).data('created_at');
        if (userRole === 'marketing') {
            var activityDate = new Date(reportDate);
            var today = new Date();
            if (!isSameDate(activityDate, today)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    confirmButtonColor: '#d33'
                });
                return false;
            }
        }
        Swal.fire({
            title: 'Loading Data...',
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
            $('#concept_type').val(data.concept_type);
            $('#shipper_id').val(data.shipper_id).trigger('change');
            $('#activity_type').val(data.activity_type).trigger('change');
            if (data.visit_date) {
                var visitDate = new Date(data.visit_date);
                var formattedDate = visitDate.getFullYear() + '-' + 
                                    String(visitDate.getMonth() + 1).padStart(2, '0') + '-' + 
                                    String(visitDate.getDate()).padStart(2, '0');
                $('#visit_date').val(formattedDate);
            }
            $('#status').val(data.status).trigger('change');
            $('#status_detail').val(data.status_detail);
            $('#prospect').val(data.prospect);
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Failed to Load Data',
                confirmButtonColor: '#d33'
            });
        });
    });
    $('body').on('click', '.deleteActivity', function () {
        var activity_id = $(this).data("id");
        var reportDate = $(this).data('created_at');
        if (userRole === 'marketing') {
            var activityDate = new Date(reportDate);
            var today = new Date();
            if (!isSameDate(activityDate, today)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    confirmButtonColor: '#d33'
                });
                return false;
            }
        }
        var $row = $(this).closest('tr');
        var dtRow = table.row($row);
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
                    url: "{{ route('activities.index') }}" + '/' + activity_id,
                    success: function (response) {
                        if (isTableReady && table) {
                            dtRow.remove();
                            table.draw(false);
                        }
                        $row.fadeOut(300);
                        activitiesData = activitiesData.filter(function(activity) {
                            return activity.id !== activity_id;
                        });
                        var currentFilter = $('#filterUser').val();
                        calculateSummaryFromFiltered(currentFilter);
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
});
</script>
@endsection('script')