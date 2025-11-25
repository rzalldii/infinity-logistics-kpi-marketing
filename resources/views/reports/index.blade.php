@extends('layouts.app')
@section('title')
Report Marketing | Admin Infinity Logistics Indonesia
@endsection('title')
@section('content')
<!-- <div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 67vh;">
                        <div class="text-center">
                            <div class="display-4 fw-bold mb-2">UNDER MAINTENANCE</div>
                            <div class="lead text-muted">This page is still developing</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h1 class="fw-bold mb-3">Report Marketing</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="card-title">Daily Activities</div>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal" data-bs-toggle="tooltip" title="Add">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="modal fade" id="addRowModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">   
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold">New</span>
                                            <span class="fw-light">Row</span>
                                        </h5>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label">Shipper Name <span class="text-danger">*</span></label>
                                                        <select class="form-select" required>
                                                            <option disabled selected>Select Shipper Name</option>
                                                            <option>PT. ABC</option>
                                                            <option>PT. DEF</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label">Visit</label>
                                                        <input type="date" class="form-control" required/>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                    <label class="form-label">Call</label>
                                                        <div class="d-flex">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="1" required/>
                                                                <label class="form-check-label" for="flexRadioDefault1">
                                                                    Male
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="0" checked/>
                                                                <label class="form-check-label" for="flexRadioDefault2">
                                                                    Female
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> -->
                                                <div class="col-md-12">
                                                    <div class="form-group form-group-default">
                                                        <label class="form-label">Prospect</label>
                                                        <textarea class="form-control" rows="6"></textarea>
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
                            <table class="display table table-striped table-hover mt-3">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First</th>
                                        <th scope="col">Last</th>
                                        <th scope="col">Handle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Mark</td>
                                        <td>Otto</td>
                                        <td>@mdo</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Jacob</td>
                                        <td>Thornton</td>
                                        <td>@fat</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Larry</td>
                                        <td>Bird</td>
                                        <td>@twitter</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Weekly Report</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-striped table-hover mt-3">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First</th>
                                        <th scope="col">Last</th>
                                        <th scope="col">Handle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Mark</td>
                                        <td>Otto</td>
                                        <td>@mdo</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Jacob</td>
                                        <td>Thornton</td>
                                        <td>@fat</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Larry</td>
                                        <td>Bird</td>
                                        <td>@twitter</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Monthly Report</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-striped table-hover mt-3">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First</th>
                                        <th scope="col">Last</th>
                                        <th scope="col">Handle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Mark</td>
                                        <td>Otto</td>
                                        <td>@mdo</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Jacob</td>
                                        <td>Thornton</td>
                                        <td>@fat</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Larry</td>
                                        <td>Bird</td>
                                        <td>@twitter</td>
                                    </tr>
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
        $("#addRowButton").click(function () {
            $("#add-row")
            .dataTable()
            .fnAddData([
                $("#addName").val(),
                $("#addPosition").val(),
                $("#addOffice").val(),
                action,
            ]);
        $("#addRowModal").modal("hide");
        });
    });
</script>
@endsection('script')