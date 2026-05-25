@extends('layouts.app')
@section('title')
    Report Activities | KPI - Marketing
@endsection
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h1 class="card-title">Report Activities</h1>
                                <button class="btn btn-info btn-round ms-auto" id="ToggleColumns">
                                    <i class="fas fa-toggle-on"></i>
                                    <span class="d-none d-lg-inline"> Toggle Columns</span>
                                </button>
                                <button class="btn btn-success btn-round ms-2" id="ExportExcel">
                                    <i class="fas fa-file-excel"></i>
                                    <span class="d-none d-lg-inline"> Export Excel</span>
                                </button>
                                <button class="btn btn-primary btn-round ms-2" id="createNewActivity">
                                    <i class="fas fa-plus"></i>
                                    <span class="d-none d-lg-inline"> New Data</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-hidden="true"
                                data-bs-backdrop="static" data-bs-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
                                    role="document">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">
                                                <span class="fw-mediumbold">Export</span>
                                                <span class="fw-light">Excel</span>
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <form id="exportForm">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label fw-bold">Quick Select Range</label>
                                                            <div class="d-flex flex-wrap gap-2">
                                                                <button type="button"
                                                                    class="btn btn-outline-secondary btn-sm btn-shortcut"
                                                                    data-range="today">
                                                                    Today
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-outline-secondary btn-sm btn-shortcut"
                                                                    data-range="yesterday">
                                                                    Yesterday
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-outline-secondary btn-sm btn-shortcut"
                                                                    data-range="this_week">
                                                                    This Week
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-outline-secondary btn-sm btn-shortcut"
                                                                    data-range="last_week">
                                                                    Last Week
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-outline-primary btn-sm btn-shortcut active"
                                                                    data-range="this_month">
                                                                    This Month
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-outline-primary btn-sm btn-shortcut"
                                                                    data-range="last_month">
                                                                    Last Month
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label fw-bold" for="export_date_from">From
                                                                Date <span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" id="export_date_from"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label fw-bold" for="export_date_to">To Date
                                                                <span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" id="export_date_to"
                                                                required>
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
                            <div class="modal fade" id="activityModal" role="dialog" aria-hidden="true"
                                data-bs-backdrop="static" data-bs-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
                                    role="document">
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
                                                <input type="hidden" name="parent_id" id="parent_id">
                                                <div class="row">
                                                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                                        <div class="col-md-12">
                                                            <div class="form-group form-group-default">
                                                                <label class="form-label" for="created_date">Date</label>
                                                                <input type="date" class="form-control" name="created_date"
                                                                    id="created_date">
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-default">
                                                            <label class="form-label" for="shipper_id">Shipper Name <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="form-select select2" name="shipper_id"
                                                                id="shipper_id" required>
                                                                <option value="" disabled selected>Select Shipper Name
                                                                </option>
                                                                @foreach($shippers as $shipper)
                                                                    <option value="{{ $shipper->id }}"
                                                                        data-concept="{{ $shipper->shipper_concept }}"
                                                                        data-type="{{ $shipper->shipper_type }}"
                                                                        data-commodity="{{ $shipper->commodity }}">
                                                                        {{ $shipper->shipper_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-default"
                                                            id="shipper_concept_group" style="display: none;">
                                                            <label class="form-label" for="shipper_concept_display">Shipper
                                                                Concept</label>
                                                            <input type="text" class="form-control-plaintext"
                                                                id="shipper_concept_display" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-default" id="shipper_type_group"
                                                            style="display: none;">
                                                            <label class="form-label" for="shipper_type_display">Shipper
                                                                Type</label>
                                                            <input type="text" class="form-control-plaintext"
                                                                id="shipper_type_display" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-default" id="commodity_group"
                                                            style="display: none;">
                                                            <label class="form-label"
                                                                for="commodity_display">Commodity</label>
                                                            <input type="text" class="form-control-plaintext"
                                                                id="commodity_display" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-default">
                                                            <label class="form-label" for="activity_type">Activity Type
                                                                <span class="text-danger">*</span></label>
                                                            <select class="form-select" name="activity_type"
                                                                id="activity_type" required>
                                                                <option value="" disabled selected>Select Activity Type
                                                                </option>
                                                                <option value="QUOTE">QUOTE</option>
                                                                <option value="CALL">CALL</option>
                                                                <option value="VISIT">VISIT</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-default" id="visit_date_group"
                                                            style="display: none;">
                                                            <label class="form-label" for="visit_date">Visit Date</label>
                                                            <input type="date" class="form-control" name="visit_date"
                                                                id="visit_date">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-default">
                                                            <label class="form-label" for="status_type">Status Type<span
                                                                    class="text-danger">*</span></label>
                                                            <select class="form-select" name="status_type" id="status_type"
                                                                required>
                                                                <option value="" disabled selected>Select Status Type
                                                                </option>
                                                                <option value="CLOSING">CLOSING</option>
                                                                <option value="PENDING">PENDING</option>
                                                                <option value="FAILED">FAILED</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group form-group-default" id="volume_20_group"
                                                            style="display: none;">
                                                            <label class="form-label" for="volume_20">Volume 20</label>
                                                            <input type="text" class="form-control" name="volume_20"
                                                                id="volume_20" placeholder="e.g. 1" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group form-group-default" id="volume_40_group"
                                                            style="display: none;">
                                                            <label class="form-label" for="volume_40">Volume 40</label>
                                                            <input type="text" class="form-control" name="volume_40"
                                                                id="volume_40" placeholder="e.g. 2" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-default" id="other_volume_group"
                                                            style="display: none;">
                                                            <label class="form-label" for="other_volume">Other
                                                                Volume</label>
                                                            <select class="form-select" name="other_volume"
                                                                id="other_volume">
                                                                <option value="">Select Other Volume</option>
                                                                <option value="AIR FREIGHT">AIR FREIGHT</option>
                                                                <option value="RAIL FREIGHT">RAIL FREIGHT</option>
                                                                <option value="ROAD FREIGHT">ROAD FREIGHT</option>
                                                                <option value="EMKL">EMKL</option>
                                                                <option value="LCL">LCL</option>
                                                                <option value="OTHER BUSINESS">OTHER BUSINESS</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-default" id="profit_group"
                                                            style="display: none;">
                                                            <label class="form-label" for="profit">Profit</label>
                                                            <input type="text" class="form-control" name="profit_display"
                                                                id="profit" placeholder="e.g. 100.000" autocomplete="off">
                                                            <input type="hidden" name="profit" id="profit_real">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group form-group-default">
                                                            <label class="form-label" for="remarks">Remarks</label>
                                                            <textarea class="form-control" name="remarks" id="remarks"
                                                                rows="1"
                                                                placeholder="e.g. Potential for 20 TEUs/month next quarter..."></textarea>
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
                            <div class="modal fade" id="Viewactivity" tabindex="-1" aria-hidden="true" role="dialog"
                                data-bs-backdrop="static" data-bs-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
                                    role="document">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">
                                                <span class="fw-mediumbold">Detail</span>
                                                <span class="fw-light">Activity</span>
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="view_shipper_name">Shipper
                                                            Name</label>
                                                        <input type="text" class="form-control-plaintext"
                                                            id="view_shipper_name" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default"
                                                        id="view_shipper_concept_group">
                                                        <label class="form-label" for="view_shipper_concept">Shipper
                                                            Concept</label>
                                                        <input type="text" class="form-control-plaintext"
                                                            id="view_shipper_concept" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default" id="view_shipper_type_group">
                                                        <label class="form-label" for="view_shipper_type">Shipper
                                                            Type</label>
                                                        <input type="text" class="form-control-plaintext"
                                                            id="view_shipper_type" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default" id="view_commodity_group">
                                                        <label class="form-label" for="view_commodity">Commodity</label>
                                                        <input type="text" class="form-control-plaintext"
                                                            id="view_commodity" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="view_activity_type">Activity
                                                            Type</label>
                                                        <input type="text" class="form-control-plaintext"
                                                            id="view_activity_type" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default" id="view_visit_date_group">
                                                        <label class="form-label" for="view_visit_date">Visit Date</label>
                                                        <input type="text" class="form-control-plaintext"
                                                            id="view_visit_date" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="view_status_type">Status Type</label>
                                                        <input type="text" class="form-control-plaintext"
                                                            id="view_status_type" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group form-group-default" id="view_volume_20_group">
                                                        <label class="form-label" for="view_volume_20">Volume 20</label>
                                                        <input type="text" class="form-control-plaintext"
                                                            id="view_volume_20" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group form-group-default" id="view_volume_40_group">
                                                        <label class="form-label" for="view_volume_40">Volume 40</label>
                                                        <input type="text" class="form-control-plaintext"
                                                            id="view_volume_40" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default" id="view_other_volume_group">
                                                        <label class="form-label" for="view_other_volume">Other
                                                            Volume</label>
                                                        <input type="text" class="form-control-plaintext"
                                                            id="view_other_volume" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default" id="view_profit_group">
                                                        <label class="form-label" for="view_profit">Profit</label>
                                                        <input type="text" class="form-control-plaintext" id="view_profit"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="view_remarks">Remarks</label>
                                                        <textarea class="form-control-plaintext" id="view_remarks" rows="1"
                                                            readonly></textarea>
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
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-body p-3">
                                    <div class="row g-2 mb-2">
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                            <div class="col-md-6">
                                                <div class="form-group mb-0">
                                                    <select class="form-select" id="filterUser">
                                                        <option value="">All Data</option>
                                                        @if(Auth::user()->isAdmin())
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
                                        @endif
                                        @if(Auth::user()->isMarketing())
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <select class="form-select" id="filterSort">
                                                        <option value="">Sort Default</option>
                                                        <option value="latest">Latest Input</option>
                                                        <option value="oldest">Oldest Input</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <select class="form-select form-select-sm" id="filterDATE">
                                                <option value="">Filter DATE</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-select form-select-sm" id="filterACTIVITY">
                                                <option value="">Filter ACTIVITY</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-select form-select-sm" id="filterSTATUS">
                                                <option value="">Filter STATUS</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2" id="clearFilterRow" style="display: none;">
                                        <div class="col-12 text-end">
                                            <button type="button"
                                                class="btn btn-link text-danger btn-sm text-decoration-none"
                                                id="clearFilters">
                                                <i class="fas fa-times-circle"></i> Clear All Filters
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="multi-filter-select" class="display table table-striped table-hover"
                                    style="width:100%">
                                    <thead class="text-center">
                                        <tr>
                                            <th>DATE</th>
                                            <th>REF</th>
                                            <th>SHIPPER</th>
                                            <th>CONCEPT</th>
                                            <th>TYPE</th>
                                            <th>COMMODITY</th>
                                            <th>ACTIVITY</th>
                                            <th>VISIT</th>
                                            <th>STATUS</th>
                                            <th>20'</th>
                                            <th>40'</th>
                                            <th>OTHER</th>
                                            <th>PROFIT</th>
                                            <th>DETAIL</th>
                                            @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                                <th>CREATED</th>
                                            @endif
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center text-nowrap">
                                        @foreach($activities as $activity)
                                            <tr data-user-id="{{ $activity->user_id }}">
                                                <td data-order="{{ $activity->created_at->format('Y-m-d') }}">
                                                    {{ Str::upper($activity->created_at->format("d M")) }}
                                                </td>
                                                <td>
                                                    @php
                                                        $rootId = $activity->parent_id ?? $activity->id;
                                                        $suffix = $activity->sequence ?? 1;
                                                        $statusCheck = $activity->status_type;
                                                        if ($statusCheck === 'CLOSING') {
                                                            $suffix = 'CLS';
                                                        } elseif ($statusCheck === 'FAILED') {
                                                            $suffix = 'FLD';
                                                        }
                                                    @endphp
                                                    ACT#{{ $rootId }}-{{ $suffix }}
                                                </td>
                                                <td>{{ $activity->shipper->shipper_name }}</td>
                                                <td>{{ $activity->shipper->shipper_concept }}</td>
                                                <td>{{ $activity->shipper->shipper_type }}</td>
                                                <td>{{ $activity->shipper->commodity }}</td>
                                                <td>{{ $activity->activity_type }}</td>
                                                <td>
                                                    @if($activity->visit_date)
                                                        {{ Str::upper(($activity->visit_date)->format("d M")) }}
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>{{ $activity->status_type }}</td>
                                                <td>
                                                    @if($activity->volume_20)
                                                        {{ $activity->volume_20 }}
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($activity->volume_40)
                                                        {{ $activity->volume_40 }}
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($activity->other_volume)
                                                        {{ $activity->other_volume }}
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($activity->profit)
                                                        {{ number_format((float) $activity->profit, 0, ',', '.') }}
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-sm btn-info viewActivity"
                                                            data-id="{{ $activity->id }}" data-bs-toggle="tooltip" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                                    <td>{{ Str::upper($activity->user->name) }}</td>
                                                @endif
                                                <td>
                                                    @php
                                                        $currentRootId = $activity->parent_id ?? $activity->id;
                                                        $closedIds = $closedActivitiesIds ?? [];
                                                        $latestIds = $latestActivityIds ?? [];
                                                        $isActivitiesFinished = in_array($currentRootId, $closedIds);
                                                        $isLatestSequence = in_array($activity->id, $latestIds);
                                                        $createdAt = \Carbon\Carbon::parse($activity->created_at);
                                                        $now = \Carbon\Carbon::now();
                                                        $currentMonth = $now->format('Y-m');
                                                        $previousMonth = $now->copy()->subMonth()->format('Y-m');
                                                        $activityMonth = $createdAt->format('Y-m');
                                                        $isToday = $createdAt->isToday();
                                                        $isSameOrPreviousMonth = ($activityMonth === $currentMonth || $activityMonth === $previousMonth);
                                                        $isClosing = ($activity->status_type === 'CLOSING');
                                                        $canDelete = $isToday;
                                                        if ($isClosing) {
                                                            $canEdit = $isSameOrPreviousMonth;
                                                        } else {
                                                            $canEdit = $isToday;
                                                        }
                                                        $isSuperAdmin = Auth::user()->isSuperAdmin();
                                                        $isAdmin = Auth::user()->isAdmin();
                                                        $isValidSequence = ($activity->sequence != 0);
                                                    @endphp
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        @if($isSuperAdmin || ($isValidSequence && !$isActivitiesFinished && $isLatestSequence))
                                                            <button type="button" class="btn btn-sm btn-primary followUp"
                                                                data-id="{{ $activity->id }}" data-bs-toggle="tooltip"
                                                                title="Follow Up">
                                                                <i class="fas fa-reply"></i>
                                                            </button>
                                                        @endif
                                                        @if($isSuperAdmin || $isAdmin)
                                                            <button type="button" class="btn btn-sm btn-warning text-white editBtn"
                                                                data-id="{{ $activity->id }}"
                                                                data-report-date="{{ $activity->created_at }}"
                                                                data-bs-toggle="tooltip" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger deleteBtn"
                                                                data-id="{{ $activity->id }}"
                                                                data-report-date="{{ $activity->created_at }}"
                                                                data-bs-toggle="tooltip" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @else
                                                            @if($canEdit)
                                                                @if($isValidSequence)
                                                                    <button type="button" class="btn btn-sm btn-warning text-white editBtn"
                                                                        data-id="{{ $activity->id }}"
                                                                        data-report-date="{{ $activity->created_at }}"
                                                                        data-bs-toggle="tooltip" title="Edit">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                @endif
                                                            @else
                                                                <button type="button" class="btn btn-sm btn-success"
                                                                    style="cursor: not-allowed;" data-bs-toggle="tooltip"
                                                                    title="Locked">
                                                                    <i class="fas fa-lock"></i>
                                                                </button>
                                                            @endif
                                                            @if($canDelete)
                                                                <button type="button" class="btn btn-sm btn-danger deleteBtn"
                                                                    data-id="{{ $activity->id }}"
                                                                    data-report-date="{{ $activity->created_at }}"
                                                                    data-bs-toggle="tooltip" title="Delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            @else
                                                                @if(!$canEdit)
                                                                @else
                                                                    <button type="button" class="btn btn-sm btn-success"
                                                                        style="cursor: not-allowed;" data-bs-toggle="tooltip"
                                                                        title="Locked">
                                                                        <i class="fas fa-lock"></i>
                                                                    </button>
                                                                @endif
                                                            @endif
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
@push('script')
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var userRole = '{{ Auth::user()->role }}';
            var isAdmin = (userRole === 'SUPER ADMIN' || userRole === 'ADMIN');
            var currentUserId = {{ Auth::id() }};
            var activitiesData = @json($activities);
            var table;
            var isTableReady = false;
            function formatDate(date) {
                var d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();
                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;
                return [year, month, day].join('-');
            }
            $('.btn-shortcut').on('click', function () {
                $('.btn-shortcut').removeClass('active').removeClass('btn-outline-primary').addClass('btn-outline-secondary');
                $(this).addClass('active').removeClass('btn-outline-secondary').addClass('btn-outline-primary');
                var range = $(this).data('range');
                var today = new Date();
                var fromDate, toDate;
                today.setHours(0, 0, 0, 0);
                switch (range) {
                    case 'today':
                        fromDate = new Date(today);
                        toDate = new Date(today);
                        break;
                    case 'yesterday':
                        var yest = new Date(today);
                        yest.setDate(yest.getDate() - 1);
                        fromDate = yest;
                        toDate = yest;
                        break;
                    case 'this_week':
                        var currentDay = today.getDay() || 7;
                        var monday = new Date(today);
                        monday.setDate(today.getDate() - (currentDay - 1));
                        fromDate = monday;
                        toDate = new Date();
                        break;
                    case 'last_week':
                        var lastWeekMonday = new Date(today);
                        var day = lastWeekMonday.getDay() || 7;
                        lastWeekMonday.setDate(lastWeekMonday.getDate() - day - 6);
                        var lastWeekSunday = new Date(lastWeekMonday);
                        lastWeekSunday.setDate(lastWeekMonday.getDate() + 6);
                        fromDate = lastWeekMonday;
                        toDate = lastWeekSunday;
                        break;
                    case 'this_month':
                        fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
                        toDate = new Date();
                        break;
                    case 'last_month':
                        fromDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                        toDate = new Date(today.getFullYear(), today.getMonth(), 0);
                        break;
                    default:
                        fromDate = new Date();
                        toDate = new Date();
                }
                $('#export_date_from').val(formatDate(fromDate));
                $('#export_date_to').val(formatDate(toDate));
            });
            $('#ExportExcel').on('click', function () {
                $('.btn-shortcut[data-range="this_month"]').trigger('click');
                $('#exportModal').modal('show');
            });
            $('#confirmExport').on('click', function () {
                var dateFrom = $('#export_date_from').val();
                var dateTo = $('#export_date_to').val();
                if (!dateFrom || !dateTo) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Date Range'
                    });
                    return;
                }
                if (new Date(dateFrom) > new Date(dateTo)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Start Date cannot be greater than End Date'
                    });
                    return;
                }
                var filterUser = $('#filterUser').val();
                var filterACTIVITY = $('#filterACTIVITY').val();
                var filterSTATUS = $('#filterSTATUS').val();
                var activeFilters = [];
                activeFilters.push('DATE: <b>' + dateFrom + '</b> To <b>' + dateTo + '</b>');
                if (filterUser && filterUser !== '') {
                    var userText = $('#filterUser option:selected').text();
                    activeFilters.push('SCOPE: <b>' + userText + '</b>');
                }
                if (filterACTIVITY) activeFilters.push('ACTIVITY: <b>' + filterACTIVITY + '</b>');
                if (filterSTATUS) activeFilters.push('STATUS: <b>' + filterSTATUS + '</b>');
                var messageHTML = 'Filters: <br>' + activeFilters.join('<br>');
                $('#exportModal').modal('hide');
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
                            date_from: dateFrom,
                            date_to: dateTo,
                            data: filterUser,
                            activity_type: filterACTIVITY,
                            status_type: filterSTATUS
                        });
                        var url = "{{ route('activities.export') }}?" + params.toString();
                        Swal.fire({
                            title: 'Preparing Excel...',
                            html: 'Exporting data file...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        setTimeout(function () {
                            window.location.href = url;
                            Swal.close();
                            setTimeout(() => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Export Complete!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }, 500);
                        }, 1000);
                    } else {
                        $('#exportModal').modal('show');
                    }
                });
            });
            try {
                var notOrderableColumns;
                if (isAdmin) {
                    notOrderableColumns = [6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
                } else {
                    notOrderableColumns = [6, 7, 8, 9, 10, 11, 12, 13, 14];
                }
                var hiddenColumns = [3, 4, 5, 7, 9, 10, 11, 12];
                table = $('#multi-filter-select').DataTable({
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
                        isTableReady = true;
                        var api = this.api();
                        var Dates = [];
                        api.column(0).nodes().each(function (cell, i) {
                            var displayValue = $(cell).text().trim();
                            var orderValue = $(cell).attr('data-order');
                            if (displayValue && orderValue) {
                                Dates.push({
                                    display: displayValue,
                                    order: orderValue
                                });
                            }
                        });
                        var uniqueDates = {};
                        Dates.forEach(function (item) {
                            if (!uniqueDates[item.display]) {
                                uniqueDates[item.display] = item.order;
                            }
                        });
                        var sortedDates = Object.keys(uniqueDates).sort(function (a, b) {
                            return uniqueDates[b].localeCompare(uniqueDates[a]);
                        });
                        sortedDates.forEach(function (display) {
                            $('#filterDATE').append('<option value="' + display + '">' + display + '</option>');
                        });
                        api.column(6).data().unique().sort().each(function (d, j) {
                            if (d) {
                                $('#filterACTIVITY').append('<option value="' + d + '">' + d + '</option>');
                            }
                        });
                        api.column(8).data().unique().sort().each(function (d, j) {
                            if (d) {
                                $('#filterSTATUS').append('<option value="' + d + '">' + d + '</option>');
                            }
                        });
                    },
                });
                table.on('draw', function () {
                    $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                    $('[data-bs-toggle="tooltip"]').tooltip();
                });
                function checkFilters() {
                    var userFilterVal = $('#filterUser').val() || '';
                    var hasFilter =
                        userFilterVal !== '' ||
                        $('#filterSort').val() !== '' ||
                        $('#filterDATE').val() !== '' ||
                        $('#filterACTIVITY').val() !== '' ||
                        $('#filterSTATUS').val() !== '';
                    if (hasFilter) {
                        $('#clearFilterRow').fadeIn();
                    } else {
                        $('#clearFilterRow').fadeOut();
                    }
                }
                if (isAdmin && $('#filterUser').length) {
                    $('#filterUser').on('change', function () {
                        if (!isTableReady || !table) {
                            console.warn('Table not ready yet');
                            return;
                        }
                        var filterValue = $(this).val();
                        $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(function (fn) {
                            return fn.name !== 'dataFilter';
                        });
                        if (filterValue) {
                            var targetUserId = filterValue === 'mine' ? currentUserId : parseInt(filterValue);
                            var dataFilter = function (settings, data, dataIndex) {
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
                }
                $('#filterSort').on('change', function () {
                    var val = $(this).val();
                    var createdAtIndex = 0;
                    if (val === 'latest') {
                        table.order([createdAtIndex, 'desc']).draw();
                    } else if (val === 'oldest') {
                        table.order([createdAtIndex, 'asc']).draw();
                    } else {
                        table.order([0, 'desc']).draw();
                    }
                    checkFilters();
                });
                $('#filterDATE, #filterACTIVITY, #filterSTATUS').on('change', function () {
                    var mapIdToColumn = {
                        'filterDATE': 0,
                        'filterACTIVITY': 6,
                        'filterSTATUS': 8
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
                $('#clearFilters').on('click', function () {
                    $('#filterUser, #filterSort, #filterDATE, #filterACTIVITY, #filterSTATUS').val('');
                    $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(function (fn) {
                        return fn.name !== 'userFilter';
                    });
                    table.order([0, 'desc']);
                    table.search('').columns().search('').draw();
                    checkFilters();
                });
            } catch (error) {
                console.error('DataTables initialization error:', error);
            }
            $('#ToggleColumns').on('click', function (e) {
                e.preventDefault();
                if (!isTableReady || !table) {
                    console.warn('Table not ready yet');
                    return;
                }
                var $btn = $(this);
                var isHidden = !table.column(3).visible();
                table.columns([3, 4, 5, 7, 9, 10, 11, 12]).visible(isHidden);
                if (isHidden) {
                    $btn.html('<i class="fas fa-toggle-off"></i><span class="d-none d-lg-inline"> Toggle Columns</span>');
                } else {
                    $btn.html('<i class="fas fa-toggle-on"></i><span class="d-none d-lg-inline"> Toggle Columns</span>');
                }
                table.columns.adjust().draw();
            });
            function toggleViewOnlyMode(isViewOnly, shipperText = '') {
                if (isViewOnly) {
                    $('#shipper_id').next('.select2-container').hide();
                    if ($('#shipper_dummy').length === 0) {
                        $('<input>').attr({
                            type: 'text',
                            id: 'shipper_dummy',
                            class: 'form-control fw-bold',
                            readonly: true
                        }).insertAfter($('#shipper_id').next('.select2-container'));
                    }
                    $('#shipper_dummy').val(shipperText).show();
                } else {
                    $('#shipper_id').next('.select2-container').show();
                    $('#shipper_dummy').hide();
                }
            }
            function formatRupiah(angka) {
                if (!angka) return '';
                var number_string = angka.toString().replace(/[^,\d]/g, ''),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah;
            }
            $('#profit').on('keyup', function (e) {
                var value = $(this).val();
                var cleanValue = value.replace(/\./g, '');
                $('#profit_real').val(cleanValue);
                $(this).val(formatRupiah(value));
            });
            var initialValue = $('#profit_real').val();
            if (initialValue) {
                $('#profit').val(formatRupiah(initialValue));
            }
            $('#profit').on('input', function () {
                this.value = this.value.replace(/[^0-9.]/g, '');
            });
            $('#volume_20, #volume_40').on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
            $('#volume_20, #volume_40').on('paste', function (e) {
                var pastedData = (e.originalEvent || e).clipboardData.getData('text/plain');
                if (!/^\d+$/.test(pastedData)) {
                    e.preventDefault();
                }
            });
            function checkVolumeExclusivity() {
                var val20 = $('#volume_20').val() ? $('#volume_20').val().trim() : '';
                var val40 = $('#volume_40').val() ? $('#volume_40').val().trim() : '';
                var valOther = $('#other_volume').val() ? $('#other_volume').val() : '';

                if (val20 !== '' || val40 !== '') {
                    $('#other_volume').val('').prop('disabled', true);
                    $('#volume_20, #volume_40').prop('disabled', false);
                } else if (valOther !== '') {
                    $('#volume_20, #volume_40').val('').prop('disabled', true);
                    $('#other_volume').prop('disabled', false);
                } else {
                    $('#volume_20, #volume_40, #other_volume').prop('disabled', false);
                }
            }
            $('#volume_20, #volume_40').on('input keyup', checkVolumeExclusivity);
            $('#other_volume').on('change', checkVolumeExclusivity);
            function formatShipper(state) {
                if (!state.id) { return state.text; }
                var concept = $(state.element).data('concept') || '—';
                var type = $(state.element).data('type') || '—';
                var commodity = $(state.element).data('commodity') || '—';
                var $state = $(
                    '<div class="py-1">' +
                    '<div class="d-flex justify-content-between align-items-center mb-1">' +
                    '<span class="fw-bold text-dark" style="font-size: 1.05em;">' + state.text + '</span>' +
                    '<span class="badge bg-light text-dark rounded-pill" style="font-size: 0.7em;">' + concept + '</span>' +
                    '</div>' +
                    '<div class="d-flex text-muted small align-items-center gap-3">' +
                    '<span title="Shipper Type">' +
                    type +
                    '</span>' +
                    '<span class="text-black-50">&bull;</span>' +
                    '<span class="text-truncate" style="max-width: 150px;" title="Commodity">' +
                    commodity +
                    '</span>' +
                    '</div>' +
                    '</div>'
                );
                return $state;
            }
            if (!$('#shipper_id').hasClass('select2-hidden-accessible')) {
                $('#shipper_id').select2({
                    width: '100%',
                    dropdownParent: $('#activityModal'),
                    templateResult: formatShipper,
                    language: {
                        noResults: function () {
                            return 'No data found';
                        }
                    },
                });
            }
            $('#shipper_id').on('change', function () {
                var selectedOption = $(this).find(':selected');
                var shipperType = selectedOption.data('type');
                var shipperConcept = selectedOption.data('concept');
                var commodity = selectedOption.data('commodity');
                if ($(this).val()) {
                    $('#shipper_concept_group').show();
                    $('#shipper_concept_display').val(shipperConcept || '—');
                    $('#shipper_type_group').show();
                    $('#shipper_type_display').val(shipperType || '—');
                    $('#commodity_group').show();
                    $('#commodity_display').val(commodity || '—');
                } else {
                    $('#shipper_concept_group').hide();
                    $('#shipper_concept_display').val('');
                    $('#shipper_type_group').hide();
                    $('#shipper_type_display').val('');
                    $('#commodity_group').hide();
                    $('#commodity_display').val('');
                }
            });
            $('#activity_type').change(function () {
                if ($(this).val() === 'VISIT') {
                    $('#visit_date_group').show();
                    $('#visit_date').prop('required', true);
                } else {
                    $('#visit_date_group').hide();
                    $('#visit_date').prop('required', false);
                    $('#visit_date').val('');
                }
            });
            $('#status_type').change(function () {
                if ($(this).val() === 'CLOSING') {
                    $('#volume_20_group').show();
                    $('#volume_40_group').show();
                    $('#other_volume_group').show();
                    $('#profit_group').show();
                    $('#profit_real').prop('required', true);
                    $('#profit').prop('required', true);
                    checkVolumeExclusivity();
                } else {
                    $('#volume_20_group').hide();
                    $('#volume_20').prop('required', false);
                    $('#volume_20').val('');
                    $('#volume_40_group').hide();
                    $('#volume_40').prop('required', false);
                    $('#volume_40').val('');
                    $('#other_volume_group').hide();
                    $('#other_volume').prop('required', false);
                    $('#other_volume').val('');
                    $('#profit_group').hide();
                    $('#profit_real').prop('required', false);
                    $('#profit').prop('required', false);
                    $('#profit_real').val('');
                    $('#profit').val('');
                    $('#volume_20, #volume_40, #other_volume').prop('disabled', false);
                }
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
                    if (data.shipper) {
                        $('#view_shipper_name').val(data.shipper.shipper_name);
                        if (data.shipper.shipper_concept) {
                            $('#view_shipper_concept_group').show();
                            $('#view_shipper_concept').val(data.shipper.shipper_concept);
                        } else {
                            $('#view_shipper_concept_group').hide();
                        }
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
                        $('#view_shipper_concept_group').hide();
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
                    $('#view_status_type').val(data.status_type || '—');
                    if (data.volume_20) {
                        $('#view_volume_20').val(data.volume_20);
                        $('#view_volume_20_group').show();
                    } else {
                        $('#view_volume_20').val('—');
                        $('#view_volume_20_group').hide();
                    }
                    if (data.volume_40) {
                        $('#view_volume_40').val(data.volume_40);
                        $('#view_volume_40_group').show();
                    } else {
                        $('#view_volume_40').val('—');
                        $('#view_volume_40_group').hide();
                    }
                    if (data.other_volume) {
                        $('#view_other_volume').val(data.other_volume);
                        $('#view_other_volume_group').show();
                    } else {
                        $('#view_other_volume').val('—');
                        $('#view_other_volume_group').hide();
                    }
                    if (data.profit) {
                        $('#view_profit').val(formatRupiah(data.profit));
                        $('#view_profit_group').show();
                    } else {
                        $('#view_profit').val('—');
                        $('#view_profit_group').hide();
                    }
                    $('#view_remarks').val(data.remarks || '—');
                    $('#Viewactivity').modal('show');
                }).fail(function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to Load Data',
                        confirmButtonColor: '#d33'
                    });
                });
            });
            $('#createNewActivity').click(function () {
                $('#saveBtn').val('create-activity');
                $('#activity_id').val('');
                $('#parent_id').val('');
                toggleViewOnlyMode(false);
                $('#shipper_id').val(null).trigger('change');
                $('#activityForm').trigger('reset');
                $('#created_date').val(new Date().toISOString().split('T')[0]);
                $('#shipper_id').val(null).trigger('change');
                $('#shipper_concept_group').hide();
                $('#shipper_type_group').hide();
                $('#commodity_group').hide();
                $('#visit_date_group').hide();
                $('#volume_20_group').hide();
                $('#volume_40_group').hide();
                $('#other_volume_group').hide();
                $('#profit_group').hide();
                $('#modalTitle').html('<span class="fw-mediumbold">New</span> <span class="fw-light">Activity</span>');
                checkVolumeExclusivity();
                $('#activityModal').modal('show');
            });
            function showErrorAlert(htmlContent) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: htmlContent,
                    confirmButtonColor: '#d33'
                });
            }
            $('#saveBtn').click(function (e) {
                e.preventDefault();
                var errorMessages = [];
                var activityType = $('#activity_type').val();
                var visitDate = $('#visit_date').val();
                if (activityType === 'VISIT' && !visitDate) {
                    errorMessages.push('The visit date field is required.');
                }
                var statusType = $('#status_type').val();
                if (statusType === 'CLOSING') {
                    var vol20 = $('#volume_20').val();
                    var vol40 = $('#volume_40').val();
                    var volOther = $('#other_volume').val();
                    var profitVal = $('#profit_real').val();
                    if (!profitVal) profitVal = $('#profit').val().replace(/\./g, '').trim();
                    if ((!vol20 || vol20 == 0) && (!vol40 || vol40 == 0) && !volOther) {
                        errorMessages.push('The volume 20 / 40 / other field is required.');
                    }
                    if (!profitVal || profitVal == 0) {
                        errorMessages.push('The profit field is required.');
                    }
                }
                if (errorMessages.length > 0) {
                    var errorList = '<ul style="text-align: left; margin: 0; padding-left: 20px;">';
                    $.each(errorMessages, function (index, value) {
                        errorList += '<li>' + value + '</li>';
                    });
                    errorList += '</ul>';
                    showErrorAlert(errorList);
                    return;
                }
                $('#volume_20, #volume_40, #other_volume').prop('disabled', false);
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
                    success: function (response) {
                        $('#saveBtn').html('<i class="fas fa-save"></i> Save').prop('disabled', false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Data Saved Successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function () {
                            location.reload();
                        });
                    },
                    error: function (response) {
                        $('#saveBtn').html('<i class="fas fa-save"></i> Save').prop('disabled', false);
                        checkVolumeExclusivity();
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            var errorList = '<ul style="text-align: left; margin: 0; padding-left: 20px;">';
                            $.each(errors, function (key, value) {
                                errorList += '<li>' + value[0] + '</li>';
                            });
                            errorList += '</ul>';
                            showErrorAlert(errorList);
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
            $('#clearBtn').click(function (e) {
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
                        var currentParentId = $('#parent_id').val();
                        var currentActivityId = $('#activity_id').val();
                        var currentShipperId = $('#shipper_id').val();
                        var currentShipperNameText = $('#shipper_dummy').val();
                        var isFollowUpMode = (currentParentId !== '' && currentParentId !== null);
                        var isEditMode = (currentActivityId !== '' && currentActivityId !== null);
                        var shouldKeepShipper = (isFollowUpMode || isEditMode);
                        $('#activityForm').trigger('reset');
                        $('#parent_id').val(currentParentId);
                        $('#activity_id').val(currentActivityId);
                        if (shouldKeepShipper) {
                            $('#shipper_id').val(currentShipperId).trigger('change');

                            if ($('#shipper_dummy').length) {
                                $('#shipper_dummy').val(currentShipperNameText);
                            }
                            $('#shipper_concept_group').show();
                            $('#shipper_type_group').show();
                            $('#commodity_group').show();
                        } else {
                            $('#shipper_id').val(null).trigger('change');
                            $('#shipper_concept_group').hide();
                            $('#shipper_type_group').hide();
                            $('#commodity_group').hide();
                        }
                        $('#visit_date_group').hide();
                        $('#volume_20_group').hide();
                        $('#volume_40_group').hide();
                        $('#other_volume_group').hide();
                        $('#profit_group').hide();
                        checkVolumeExclusivity();
                        Swal.fire({
                            icon: 'success',
                            title: 'Form Cleared Successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            });
            $('body').on('click', '.followUp', function () {
                var id = $(this).data('id');
                if (!id) {
                    id = $(this).closest('td').find('.editBtn').data('id');
                }
                Swal.fire({
                    title: 'Loading Data...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.get("{{ route('activities.index') }}" + '/' + id + '/edit', function (data) {
                    Swal.close();
                    $('#modalTitle').html('<span class="fw-mediumbold">Follow Up</span> <span class="fw-light">Activity</span>');
                    $('#saveBtn').val("create-activity");
                    $('#activityModal').modal('show');
                    $('#activityForm').trigger("reset");
                    $('#created_date').val(new Date().toISOString().split('T')[0]);
                    $('#activity_id').val('');
                    $('#parent_id').val(data.id);
                    if (data.shipper) {
                        if ($('#shipper_id').find("option[value='" + data.shipper.id + "']").length) {
                            $('#shipper_id').val(data.shipper.id).trigger('change');
                        } else {
                            var newOption = new Option(data.shipper.shipper_name, data.shipper.id, true, true);
                            $('#shipper_id').append(newOption).trigger('change');
                        }
                    }
                    var shipperName = data.shipper ? data.shipper.shipper_name : '—';
                    toggleViewOnlyMode(true, shipperName);
                    $('#activity_type').val('').trigger('change');
                    $('#status_type').val('').trigger('change');
                    $('#remarks').val('');
                    $('#visit_date_group').hide();
                    $('#volume_20_group').hide();
                    $('#volume_40_group').hide();
                    $('#other_volume_group').hide();
                    $('#profit_group').hide();
                    $('#visit_date').val('');
                    $('#volume_20').val('');
                    $('#volume_40').val('');
                    $('#other_volume').val('');
                    $('#profit_real').val('');
                    $('#profit').val('');
                    checkVolumeExclusivity();
                });
            });
            $('body').on('click', '.editBtn', function () {
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
                    $('#modalTitle').html('<span class="fw-mediumbold">Edit</span> <span class="fw-light">Activity</span>');
                    $('#saveBtn').val('edit-activity');
                    $('#activityModal').modal('show');
                    $('#activity_id').val(data.id);
                    var isChild = (data.parent_id !== null);
                    var isClosing = (data.status_type === 'CLOSING');
                    var createdRaw = new Date(data.created_at);
                    var createdString = createdRaw.getFullYear() + '-' +
                        String(createdRaw.getMonth() + 1).padStart(2, '0') + '-' +
                        String(createdRaw.getDate()).padStart(2, '0');
                    var nowRaw = new Date();
                    var nowString = nowRaw.getFullYear() + '-' +
                        String(nowRaw.getMonth() + 1).padStart(2, '0') + '-' +
                        String(nowRaw.getDate()).padStart(2, '0');
                    var isDifferentDay = (createdString !== nowString);
                    var isLateClosing = (isClosing && isDifferentDay);
                    if (data.shipper) {
                        if ($('#shipper_id').find("option[value='" + data.shipper.id + "']").length) {
                            $('#shipper_id').val(data.shipper.id).trigger('change');
                        } else {
                            var newOption = new Option(data.shipper.shipper_name, data.shipper.id, true, true);
                            $('#shipper_id').append(newOption).trigger('change');
                        }
                    }
                    if (isChild || isLateClosing) {
                        var shipperName = data.shipper ? data.shipper.shipper_name : '—';
                        toggleViewOnlyMode(true, shipperName);
                    } else {
                        toggleViewOnlyMode(false);
                    }
                    if (data.created_at) {
                        var createdDate = new Date(data.created_at);
                        var formattedCreatedDate = createdDate.getFullYear() + '-' +
                            String(createdDate.getMonth() + 1).padStart(2, '0') + '-' +
                            String(createdDate.getDate()).padStart(2, '0');
                        $('#created_date').val(formattedCreatedDate);
                    }
                    $('#activity_type').val(data.activity_type).trigger('change');
                    if (data.visit_date) {
                        var visitDate = new Date(data.visit_date);
                        var formattedDate = visitDate.getFullYear() + '-' +
                            String(visitDate.getMonth() + 1).padStart(2, '0') + '-' +
                            String(visitDate.getDate()).padStart(2, '0');
                        $('#visit_date').val(formattedDate);
                    }
                    $('#status_type').val(data.status_type).trigger('change');
                    $('#volume_20').val(data.volume_20);
                    $('#volume_40').val(data.volume_40);
                    $('#other_volume').val(data.other_volume);
                    $('#profit_real').val(data.profit);
                    $('#profit').val(formatRupiah(data.profit));
                    $('#remarks').val(data.remarks);
                    checkVolumeExclusivity();
                }).fail(function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to Load Data',
                        confirmButtonColor: '#d33'
                    });
                });
            });
            $('body').on('click', '.deleteBtn', function () {
                var activity_id = $(this).data('id');
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
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            type: 'DELETE',
                            url: "{{ route('activities.index') }}" + '/' + activity_id,
                            success: function (response) {
                                $row.fadeOut(300, function () {
                                    dtRow.remove().draw(false);
                                });
                                activitiesData = activitiesData.filter(function (activity) {
                                    return activity.id !== activity_id;
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
        });
    </script>
@endpush