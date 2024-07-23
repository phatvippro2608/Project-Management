@extends('auth.main')

@section('contents')
    <div class="card">
        <div class="card-header py-4 d-flex justify-content-between">
            <div class="dropdown">
                <button class="btn btn-primary bg-secondary-2 mx-2 rounded-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-filter pe-2"></i> Filter
                </button>
                <ul class="dropdown-menu dropdown-menu-arrow-left p-0">
                    <div class="dropdown-filter position-relative" style="width:576px;">
                        <div class="card mb-0">
                            <div class="card-header">filter</div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="btn btn-primary">start date</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="btn btn-primary">start date</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="btn btn-primary">start date</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="btn btn-primary">start date</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </ul>
            </div>

            <div>
                <button class="btn btn-primary bg-primary-2 mx-2 rounded-4">
                    <i class="bi bi-file-earmark-plus pe-2"></i>
                    Add
                </button>
                <button class="btn btn-primary bg-secondary-2 mx-2 rounded-4">
                    <i class="bi bi-file-earmark-arrow-up pe-2"></i>
                    Import
                </button>
                <button class="btn btn-primary bg-secondary-2 mx-2 rounded-4">
                    <i class="bi bi-file-earmark-arrow-down pe-2"></i>
                    Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-responsive">
                <tr>
                    <th>Project name
                        <i class="bi bi-sort-alpha-down text-secondary"></i>
                        <i class="bi bi-sort-alpha-down-alt visually-hidden"></i>
                    </th>
                    <th>Sale
                        <i class="bi bi-sort-alpha-down text-secondary"></i>
                        <i class="bi bi-sort-alpha-down-alt visually-hidden"></i>
                    </th>
                    <th>Start date
                        <i class="bi bi-sort-alpha-down text-secondary"></i>
                        <i class="bi bi-sort-alpha-down-alt visually-hidden"></i>
                    </th>
                    <th>End date
                        <i class="bi bi-sort-alpha-down text-secondary"></i>
                        <i class="bi bi-sort-alpha-down-alt visually-hidden"></i>
                    </th>
                    <th>Status
                        <i class="bi bi-sort-alpha-down text-secondary"></i>
                        <i class="bi bi-sort-alpha-down-alt visually-hidden"></i>
                    </th>
                    <th>Budget
                        <i class="bi bi-sort-alpha-down text-secondary"></i>
                        <i class="bi bi-sort-alpha-down-alt visually-hidden"></i>
                    </th>
                </tr>
            </table>
        </div>
    </div>
@endsection
