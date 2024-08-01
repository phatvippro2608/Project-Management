@extends('auth.main')

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
@endsection

@section('contents')
    <div class="pagetitle">
        <h1>Add New Customer</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Add New Customer</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="w-100 text-center fw-bolder">Customer Info</h5>
                </div>
                <div class="col-md-6" style="margin-top: 1rem">
                    <label for="">
                        First name
                    </label>
                    <input type="text" class="form-control name1">
                </div>
                <div class="col-md-6" style="margin-top: 1rem">
                    <label for="">
                        Last name
                    </label>
                    <input type="text" class="form-control name2">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-top: 1rem">
                    <label for="">
                        Date of birth
                    </label>
                    <input type="date" class="form-control name4">
                </div>
                <div class="col-md-6" style="margin-top: 1rem">
                    <label for="">
                        Phone number
                    </label>
                    <input type="text" class="form-control name8">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" style="margin-top: 1rem">
                    <label for="">
                        Email
                    </label>
                    <input type="text" class="form-control name3">
                </div>
            </div>

            <div class="col-md-12" style="margin-top: 1rem">
                <label for="">
                    Address
                </label>
                <input type="text" class="form-control name6">
            </div>

            <div class="col-md-12" style="margin-top: 1rem">
                <label for="">
                    Company name
                </label>
                <input type="text" class="form-control name7">
            </div>
            <div class="col-md-12" style="margin-top: 1rem">
                <label for="">
                    Status
                </label>
                <select type="text" class="form-select name5">
                    @foreach($status as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 vertical-divider">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="w-100 text-center fw-bolder">Contract Info</h5>
                </div>
                <div class="col-md-12" style="margin-top: 1rem">
                    <label for="">
                        Contract Name
                    </label>
                    <input type="text" class="form-control name21">
                </div>

                <div class="col-md-6" style="margin-top: 1rem">
                    <label for="">
                        Contract Date
                    </label>
                    <input type="date" class="form-control name22">
                </div>
                <div class="col-md-6" style="margin-top: 1rem">
                    <label for="">
                        Contract End Day
                    </label>
                    <input type="date" class="form-control name23">
                </div>
                <div class="col-md-12" style="margin-top: 1rem">
                    <label for="">
                        Contract Details
                    </label>
                    <input type="text" class="form-control name24">
                </div>
                <div class="col-md-12" style="margin-top: 1rem">
                    <label for="">
                        Contract File
                    </label>
                    <input type="file" class="form-control name25" multiple>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-upload at1">Create</button>
    </div>
@endsection

@section('script')
    <script>
        $('.at1').click(function () {
            $.ajax({
                url: `{{action('App\Http\Controllers\CustomerController@add')}}`,
                type: "PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'first_name': $('.name1').val(),
                    'last_name': $('.name2').val(),
                    'email': $('.name3').val(),
                    'date_of_birth': $('.name4').val(),
                    'status': $('.name5').val(),
                    'address': $('.name6').val(),
                    'company_name': $('.name7').val(),
                    'phone_number': $('.name8').val(),

                    'contract_name': $('.name21').val(),
                    'contract_date': $('.name22').val(),
                    'contract_end_date': $('.name23').val(),
                    'contract_details': $('.name24').val()
                },
                success: function (result) {
                    result = JSON.parse(result);
                    if (result.status === 200) {
                        toastr.success(result.message, "Thao tác thành công");
                        setTimeout(function () {
                            window.location.reload();
                        }, 300);
                    } else {
                        toastr.error(result.message, "Thao tác thất bại");
                    }
                }
            });
        });
    </script>
@endsection
