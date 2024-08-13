@extends('auth.main')
@section('head')
    <style>
        label {
            font-weight: bolder;
            margin-left: 5px;
            margin-top: 20px;
        }

        tr{
            border-bottom: 1px solid #E8E8E8;
        }
        .bg-hover:hover{
            background: #E2E3E5!important;
        }
        .dropdown-toggle::after {
            display: none!important;
        }
    </style>
@endsection
@section('contents')
    <div class="pagetitle">
        <h1>Contract list</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Contract List</li>
            </ol>
        </nav>
    </div>
    <div class="btn btn-primary my-3 btn-add-contract">
        <div class="d-flex align-items-center">
            <i class="bi bi-file-earmark-plus pe-2"></i>
            Add contract
        </div>
    </div>
    <div class="card border rounded-4 p-2">
        <div class="card-body">
            <table id="contractTable" class="table table-borderless table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Contract detail</th>
                        <th class="text-center">Contact date</th>
                        <th class="text-center">Contact end date</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1 @endphp
                    @foreach($contracts as $contract)
                        <tr>
                            <td class="text-center">{{$i}}</td>
                            @php $i++ @endphp
                            <td style="width: 33%">
                                <div class="fw-bold">{{Str::limit($contract->contract_name,60)}}</div>
                                <div>{{Str::limit($contract->contract_details,120)}}</div>
                            </td>
                            <td class="text-center">{{\App\Http\Controllers\AccountController::format($contract->contract_date)}}</td>
                            <td class="text-center">{{\App\Http\Controllers\AccountController::format($contract->contract_end_date)}}</td>

                            <td>{{number_format($contract->amount)}}</td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="d-flex align-items-center">
                                        <a class=" edit" style="cursor: pointer">
                                            <i class="bi bi-pencil-square ic-update ic-btn at2"
                                               data="{{(\App\Http\Controllers\AccountController::toAttrJson($contract))}}"></i>
                                        </a>
                                        <a class=" delete" style="cursor: pointer">
                                            <i class="bi bi-trash ic-delete ic-btn at3" aria-hidden="true"
                                               data="{{$contract->contract_id}}"></i>
                                        </a>
                                    </div>

                                </div>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal modal-md fade modal-add-contract">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-bold">Add contract</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg text-white"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="" class="fw-semibold my-2">
                                        Contract name
                                    </label>
                                    <input type="text" class="form-control contract-name">
                                </div>
                                <div class="col-md-12">
                                    <label for="" class="fw-semibold my-2">
                                        Contract date
                                    </label>
                                    <input type="date" class="form-control contract-date">
                                </div>
                                <div class="col-md-12">
                                    <label for="" class="fw-semibold my-2">
                                        Contract end date
                                    </label>
                                    <input type="date" class="form-control contract-end-date">
                                </div>
                                <div class="col-md-12">
                                    <label for="" class="fw-semibold my-2">
                                        Contract details
                                    </label>
                                    <textarea class="form-control contract-details"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label for="" class="fw-semibold my-2">
                                        Amount
                                    </label>
                                    <input type="number" class="form-control amount">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-upload at1">Create</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var table = $('#contractTable').DataTable({
            language: { search: "" },
            initComplete: function (settings, json) {
                $('.dt-search').addClass('input-group');
                $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
            },
            responsive: true,
        });

        $('.btn-add-contract').click(function (){
            $('.modal-add-contract').modal('show');
        })
    </script>
@endsection
