@extends('layouts.app')
@section('title')
Summary Activities | Key Perfomance Indicator Marketing
@endsection('title')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h1 class="card-title">Summary Activities</h1>
                            <button class="btn btn-success btn-round ms-auto" id="ExportExcel">
                                <i class="fas fa-file-excel"></i>
                                <span class="d-none d-lg-inline"> Export Excel</span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-select" id="filterPeriod">
                                        @foreach ($filterOptions as $val => $label)
                                            <option value="{{ $val }}" {{ $period == $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="table-summary" class="display table table-striped table-hover table-bordered" style="width:100%">
                                <thead class="text-center">
                                    <tr class="text-center">
                                        <th rowspan="2" class="align-middle">NAME</th>
                                        <th colspan="3">ACTIVITY</th>
                                        <th colspan="3">VOLUME</th>
                                        <th colspan="3">PROFIT</th>
                                        <th rowspan="2" class="align-middle">ACTION</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th>ACT</th>
                                        <th>REM</th>
                                        <th>%</th>
                                        <th>ACT</th>
                                        <th>REM</th>
                                        <th>%</th>
                                        <th>ACT</th>
                                        <th>REM</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center text-nowrap">
                                    @forelse($performanceData as $data)
                                    <tr>
                                        <td class="fw-bold">
                                            {{ $data['name'] }}
                                        </td>
                                        <td class="text-center">
                                            {{ number_format((float)$data['activities']['performance']['actual'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            {{ number_format((float)$data['activities']['performance']['remaining'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center fw-bold">
                                            {{ min($data['activities']['performance']['percentage'], 100) }}%
                                        </td>
                                        <td class="text-center">
                                            {{ number_format((float)$data['volume']['performance']['actual'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            {{ number_format((float)$data['volume']['performance']['remaining'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center fw-bold">
                                            {{ min($data['volume']['performance']['percentage'], 100) }}%
                                        </td>
                                        <td class="text-end">
                                            {{ number_format((float)$data['profit']['performance']['actual'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format((float)$data['profit']['performance']['remaining'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center fw-bold">
                                            {{ min($data['profit']['performance']['percentage'], 100) }}%
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if($isCurrentMonth)
                                                    <button type="button" class="btn btn-sm btn-warning text-white editBtn" data-id="{{ $data['user_id'] }}" data-bs-toggle="tooltip" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-success" style="cursor: not-allowed;" data-bs-toggle="tooltip" title="Locked">
                                                        <i class="fas fa-lock"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted">No data available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="modal fade" id="summaryModal" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold">Edit</span>
                                            <span class="fw-light">Target : <span id="modalTargetName"></span></span>
                                        </h5>
                                    </div>
                                    <div class="modal-body">
                                        <form id="summaryForm">
                                            @csrf
                                            <input type="hidden" name="user_id" id="user_id">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="target_activity">Target Activity</label>
                                                        <input type="number" class="form-control" name="target_activity" id="target_activity" autocomplete="off"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="target_volume">Target Volume</label>
                                                        <input type="number" class="form-control" name="target_volume" id="target_volume" autocomplete="off"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label" for="target_profit">Target Profit</label>
                                                        <input type="text" class="form-control" name="target_profit_display" id="target_profit" autocomplete="off"/>
                                                        <input type="hidden" name="target_profit" id="target_profit_real">
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
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title">Chart Summary</div>
                        <small class="text-muted">{{ $chartYear }}</small>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="multipleLineChart"></canvas>
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
    $('#ExportExcel').on('click', function() {
        var period = $('#filterPeriod').val();
        var monthName = $('#filterPeriod option:selected').text();
        Swal.fire({
            title: 'Export Data?',
            html: 'Export data for <b>' + monthName + '</b>?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#31ce36',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Export',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                var url = "{{ route('activities.summaries.export') }}?period=" + period;
                Swal.fire({
                    title: 'Preparing Excel...',
                    html: 'Exporting data <b>' + monthName + '</b>',
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
    $('#filterPeriod').on('change', function() {
        var period = $(this).val();
        var currentUrl = "{{ route('activities.summaries') }}";
        window.location.href = currentUrl + "?period=" + period;
    });
    function formatRupiah(angka) {
        if (angka === null || angka === undefined || angka === '') return '';
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split   = number_string.split(','),
            sisa    = split[0].length % 3,
            rupiah  = split[0].substr(0, sisa),
            ribuan  = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }
    $('#target_profit').on('keyup', function(e) {
        var value = $(this).val();
        var cleanValue = value.replace(/\./g, ''); 
        $('#target_profit_real').val(cleanValue);
        $(this).val(formatRupiah(value));
    });
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        var formData = new FormData($('#summaryForm')[0]);
        var user_id = $('#user_id').val();
        var url = "{{ route('activities.summaries.update', ':id') }}";
        url = url.replace(':id', user_id);
        formData.append('_method', 'PUT');
        $('#saveBtn').html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#saveBtn').html('<i class="fas fa-save"></i> Save').prop('disabled', false);
                $('#summaryForm').trigger('reset');
                $('#summaryModal').modal('hide');
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
                $('#summaryForm').trigger('reset');
                $('#target_profit_real').val('');
                $('#target_profit').val('');
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
        var userName = $(this).closest('tr').find('td:first').text().trim();
        $('#modalTargetName').text(userName);
        var url = "{{ route('activities.summaries.edit', ':id') }}";
        url = url.replace(':id', user_id);
        Swal.fire({
            title: 'Loading Data...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        $.get(url, function (data) {
            Swal.close();
            $('#summaryModal').modal('show');
            $('#user_id').val(data.user_id);
            $('#target_activity').val(data.target_activity);
            $('#target_volume').val(data.target_volume);
            $('#target_profit_real').val(data.target_profit);
            $('#target_profit').val(formatRupiah(data.target_profit));
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Failed to Load Data',
                confirmButtonColor: '#d33'
            });
        });
    });
    var chartCanvas = document.getElementById("multipleLineChart");
    if (chartCanvas) {
        var lineChartDatasets = @json($line['datasets']);
        var lineChartLabels   = @json($line['labels']);
        var isMultiLine       = lineChartDatasets.length > 1;
        var multipleLineChart = chartCanvas.getContext("2d");
        var myMultipleLineChart = new Chart(multipleLineChart, {
            type: "line",
            data: {
                labels: lineChartLabels,
                datasets: lineChartDatasets,
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: isMultiLine,
                    position: "top",
                    onClick: isMultiLine
                        ? Chart.defaults.global.legend.onClick
                        : function(e) { return false; }
                },
                tooltips: {
                    bodySpacing: 4,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10,
                    displayColors: true,
                    callbacks: {
                        title: function() {
                            return '';
                        },
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label;
                            var value = Math.round(tooltipItem.yLabel);
                            return " " + label + " : " + formatRupiah(value);
                        }
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            callback: function(value) {
                                return formatRupiah(Math.round(value));
                            }
                        }
                    }]
                },
                layout: {
                    padding: { left: 15, right: 15, top: 15, bottom: 15 },
                },
            },
        });
    }
});
</script>
@endsection('script')