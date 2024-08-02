@extends('auth.customer.main')

@section('head')
    <style>
        .custom-checkbox-lg {
            width: 22px;
            height: 22px;
            margin-bottom: 1px;
            margin-right: 5px;
        }

        .custom-checkbox-lg input[type="checkbox"] {
            width: 22px;
            height: 22px;
            margin-bottom: 1px;
            margin-right: 5px;
        }

        .vertical-divider {
            border-left: 1px solid #000;
            height: inherit;
        }

        @media (max-width: 992px) {
            .vertical-divider {
                border: none;
                width: 100%;
                margin-top: 2rem;
            }
        }

        label {
            font-weight: bold;
        }
    </style>
    <link href="{{ asset('assets/css/customer-custom.css') }}" rel="stylesheet">

@endsection

@section('contents')
    <div class="pagetitle">
        <h1>Customer Datail</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Customer Detail</li>
            </ol>
        </nav>
    </div>
    <div class="container ">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-md-12 position-relative">
                            <h5 class="w-100 text-center fw-bolder">Customer Info</h5>
                            <div class="edit-customer position-absolute">
                                <i class="bi bi-pencil-fill"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6" style="margin-top: 1rem">
                                <label for="">
                                    First name
                                </label>
                                <input type="text" class="form-control name1" value="{{$customer['first_name']}}" disabled>
                            </div>
                            <div class="col-md-6" style="margin-top: 1rem">
                                <label for="">
                                    Last name
                                </label>
                                <input type="text" class="form-control name2" value="{{$customer['last_name']}}" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6" style="margin-top: 1rem">
                                <label for="">
                                    Date of birth
                                </label>
                                <input type="date" class="form-control name4" value="{{$customer['date_of_birth']}}" disabled>
                            </div>
                            <div class="col-md-6" style="margin-top: 1rem">
                                <label for="">
                                    Phone number
                                </label>
                                <input type="text" class="form-control name8" value="{{$customer['phone_number']}}" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12" style="margin-top: 1rem">
                                <label for="">
                                    Email
                                </label>
                                <input type="text" class="form-control name3" value="{{$customer['email']}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="">
                                Address
                            </label>
                            <input type="text" class="form-control name6" value="{{$customer['address']}}" disabled>
                        </div>

                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="">
                                Company name
                            </label>
                            <input type="text" class="form-control name7" value="{{$customer['company_name']}}" disabled>
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="">
                                Status
                            </label>
                            <select type="text" class="form-select name5" disabled>
                                @foreach($status as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-none border-danger at1 text-danger fw-bolder">Delete
                            Customer
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 gx-5">
                <div class="card ">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="w-100 text-center fw-bolder">Contract List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-2">

                        @foreach($contracts as $item)
                            <div
                                class="contract-item mt-2 d-flex align-items-center justify-content-between p-2 ps-3 pe-3">
                                <div
                                    class="contract-body w-100 h-100 d-flex justify-content-between align-items-center">
                                    <div class="contract-name fw-semibold text-truncate me-2">
                                        {{$item->contract_name}}
                                    </div>
                                    <div class="contract-actions d-flex align-items-center justify-content-between">
                                        <i class="bi bi-eye text-secondary fs-4 me-2" style="cursor:pointer"
                                           data-contract="{{\App\Http\Controllers\AccountController::toAttrJson($item)}}"></i>
                                        <i class="bi bi-pencil-square text-primary fs-4 me-2" style="cursor:pointer"
                                           data-contract="{{\App\Http\Controllers\AccountController::toAttrJson($item)}}"></i>
                                        <i class="bi bi-trash text-danger fs-4" style="cursor:pointer"
                                           id-contract="{{$item->contract_id}}"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach


                    </div>

                </div>

            </div>
        </div>

    </div>
@endsection

@section('script')
    <script>
        $('.name5').val({{$customer['status']}});
        {{--$('.at1').click(function () {--}}
        {{--    $.ajax({--}}
        {{--        url: `{{action('App\Http\Controllers\CustomerController@add')}}`,--}}
        {{--        type: "PUT",--}}
        {{--        headers: {--}}
        {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--        },--}}
        {{--        data: {--}}
        {{--            'first_name': $('.name1').val(),--}}
        {{--            'last_name': $('.name2').val(),--}}
        {{--            'email': $('.name3').val(),--}}
        {{--            'date_of_birth': $('.name4').val(),--}}
        {{--            'status': $('.name5').val(),--}}
        {{--            'address': $('.name6').val(),--}}
        {{--            'company_name': $('.name7').val(),--}}
        {{--            'phone_number': $('.name8').val(),--}}

        {{--            'contract_name': $('.name21').val(),--}}
        {{--            'contract_date': $('.name22').val(),--}}
        {{--            'contract_end_date': $('.name23').val(),--}}
        {{--            'contract_details': $('.name24').val()--}}
        {{--        },--}}
        {{--        success: function (result) {--}}
        {{--            result = JSON.parse(result);--}}
        {{--            if (result.status === 200) {--}}
        {{--                toastr.success(result.message, "Thao tác thành công");--}}
        {{--                setTimeout(function () {--}}
        {{--                    window.location.reload();--}}
        {{--                }, 300);--}}
        {{--            } else {--}}
        {{--                toastr.error(result.message, "Thao tác thất bại");--}}
        {{--            }--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}
    </script>
@endsection


