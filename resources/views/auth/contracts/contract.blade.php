@extends('auth.main')

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
                        <th>No.</th>
                        <th>Contract name</th>
                        <th>Contact date</th>
                        <th>Contact end date</th>
                        <th>Contact details</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contracts as $contract)
                        <tr>
                            <td>{{$contract->contract_id}}</td>
                            <td class="text-truncate" style="max-width: 50px">{{$contract->contract_name}}</td>
                            <td>{{$contract->contract_date}}</td>
                            <td>{{$contract->contract_end_date}}</td>
                            <td class="text-truncate" style="max-width: 50px">{{$contract->contract_details}}</td>
                            <td>{{$contract->amount}}</td>
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
