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
                border:none;
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
        <h1>{{ __('messages.customer') }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Customer List</li>
            </ol>
        </nav>
    </div>
    <div class="btn btn-primary mb-3 btn-add">
        <div class="d-flex align-items-center">
            <i class="bi bi-file-earmark-plus pe-2"></i>
            {{ __('messages.add') }}
        </div>
    </div>
    <div class="card border rounded-4 p-2">
        <div class="card-body">
            <div class="table-responsive">
                <table id="customerTable" class="table table-borderless table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 112px"></th>
                            <th class="text-center">{{ __('messages.full_name') }}</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">{{ __('messages.company') }}</th>
                            <th class="text-center">{{ __('messages.address') }}</th>
                            <th class="text-center">{{ __('messages.contract') }}</th>
                        </tr>
                    </thead>
                    <tbody class="account-list">
                    @php($i=1)
                    @foreach($customer as $item)
                        <tr class="account-item">
{{--                            <td class="text-center">--}}
{{--                                <div class="d-flex align-items-center justify-content-center">--}}
{{--                                    <div class="d-flex align-items-center">--}}
{{--                                        <a class=" edit">--}}
{{--                                            <i class="bi bi-pencil-square ic-update ic-btn at2"--}}
{{--                                               data="{{(\App\Http\Controllers\AccountController::toAttrJson([]))}}"></i>--}}
{{--                                        </a>--}}
{{--                                        <a class=" delete">--}}
{{--                                            <i class="bi bi-trash ic-delete ic-btn at3" aria-hidden="true"--}}
{{--                                               data="id"></i>--}}
{{--                                        </a>--}}
{{--                                    </div>--}}

{{--                                </div>--}}

{{--                            </td>--}}
                            <td class="text-center">
                                {{$i}}@php($i++)
                            </td>
                            <td class="text-left">
                                {{$item->last_name}} {{$item->first_name}}
                            </td>
                            <td class="text-left">
                                {{$item->email}}
                            </td>
                            <td class="text-center">
                                {{$item->company_name}}
                            </td>
                            <td class="text-left">
                                {{$item->address}}
                            </td>
                            <td class="text-center">
                                <a href="{{action('App\Http\Controllers\CustomerController@getUpdateView', ['customer_id'=>$item->customer_id])}}"
                                   class="contract btn btn-primary p-1" style="font-size: 12px">
                                    Contract Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal modal-xl fade md1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-bold"></h4>
                </div>
                <div class="modal-body">
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
                                    <input type="number" class="form-control name8">
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
        var table = $('#customerTable').DataTable({
            language: { search: "" },
            lengthMenu: [
                [10, 30, 50, 100, -1],
                [10, 30, 50, 100, "All"]
            ],
            pageLength: {{env('ITEM_PER_PAGE')}},
            initComplete: function (settings, json) {
                $('.dt-search').addClass('input-group');
                $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
            },
            responsive: true
        });
        function validateForm() {
            let isValid = true;

            const validCharRegex = /^[\p{L}\s]+$/u; // Matches letters (including those with diacritics), numbers, and spaces
            const firstName = $('.name1').val();
            const lastName = $('.name2').val();

            if (!validCharRegex.test(firstName)) {
                toastr.error("First Name contains special characters", "Failed Action");
                isValid = false;
            }

            if (!validCharRegex.test(lastName)) {
                toastr.error("Last Name contains special characters", "Failed Action");
                isValid = false;
            }


            const dob = new Date($('.name4').val());
            const age = (new Date().getFullYear()) - dob.getFullYear();
            if (isNaN(dob.getTime()) || age < 18) {
                toastr.error("Date of Birth must indicate an age of 18 years or older", "Failed Action");
                isValid = false;
            }

            const phoneNumber = $('.name8').val();
            const phoneRegex = /^[0-9]+$/;
            if (!phoneRegex.test(phoneNumber)) {
                toastr.error("Phone Number can only contain numbers", "Failed Action");
                isValid = false;
            }

            const email = $('.name3').val();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                toastr.error("Invalid email format", "Failed Action");
                isValid = false;
            }

            const contractDate = new Date($('.name22').val());
            const contractEndDay = new Date($('.name23').val());
            if (isNaN(contractDate.getTime()) || isNaN(contractEndDay.getTime()) || contractDate > contractEndDay) {
                toastr.error("Contract Date must be less than or equal to Contract End Day", "Failed Action");
                isValid = false;
            }

            return isValid;
        }
        $('.btn-add').click(function () {
            $('.md1 .modal-title').text('Add New Customer');
            $('.md1').modal('show');

            $('.at1').click(function () {
                if (!validateForm()) {
                    return;
                }

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
                            toastr.success(result.message, "Successfully");
                            setTimeout(function () {
                                window.location.reload();
                            }, 300);
                        } else {
                            toastr.error(result.message, "Failed Action");
                        }
                    }
                });
            });
        });
    </script>
@endsection
