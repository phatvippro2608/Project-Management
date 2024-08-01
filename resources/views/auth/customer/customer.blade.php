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
        <h1>Customer List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Customer List</li>
            </ol>
        </nav>
    </div>

    <section class="section employees">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="d-inline-flex align-items-center">
                            <div class="btn btn-primary mx-2 btn-add">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-plus-fill pe-2"></i>
                                    Add Customer
                                </div>
                            </div>
                            <div class="ms-auto text-secondary">
                                <div class="search-container w-100">
                                    <form method="GET"
                                          action="{{ action('App\Http\Controllers\AccountController@getView') }}"
                                          class="d-flex w-100">
                                        <input name="keyw" type="text"
                                               value="{{ request()->input('keyw') }}"
                                               class="form-control form-control-md" aria-label="Search invoice"
                                               placeholder="Search ...">
                                        <button type="submit" class="btn btn-link p-0"><i
                                                class="bi bi-search search-button"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body m-1">
                        <div class="table-responsive mt-4">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                <tr>
                                    <th style="width: 112px"></th>
                                    <th class="text-center">Full name</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Company</th>
                                    <th class="text-center">Address</th>
                                    <th class="text-center">Contract</th>
                                </tr>

                                </thead>
                                <tbody class="account-list">
                                @foreach($customer as $item)
                                    <tr class="account-item">
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="d-flex align-items-center">
                                                    <a class=" edit">
                                                        <i class="bi bi-pencil-square ic-update ic-btn at2"
                                                           data="{{(\App\Http\Controllers\AccountController::toAttrJson([]))}}"></i>
                                                    </a>
                                                    <a class=" delete">
                                                        <i class="bi bi-trash ic-delete ic-btn at3" aria-hidden="true"
                                                           data="id"></i>
                                                    </a>
                                                </div>

                                            </div>

                                        </td>
                                        <td class="text-center">
                                            {{$item->first_name}} {{$item->last_name}}
                                        </td>
                                        <td class="text-center">
                                            {{$item->email}}
                                        </td>
                                        <td class="text-center">
                                            {{$item->company_name}}
                                        </td>
                                        <td class="text-center">
                                            {{$item->address}}
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="contract">
                                                Contract Detail
                                            </a>
                                        </td>
                                        <td class="text-center">

                                        </td>
                                        <td class="text-center">

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
    </section>
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
        $('.btn-add').click(function () {
            $('.md1 .modal-title').text('Add New Customer');
            $('.md1').modal('show');

            $('.at1').click(function () {
                // if ($('.name1').val().trim() === '') {
                //     alert('Please enter a team name.');
                //     return;
                // }

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
        });
        {{--$('.at2').click(function () {--}}
        {{--    $('.md1 .modal-title').text('Update Team');--}}
        {{--    var data = JSON.parse($(this).attr('data'));--}}
        {{--    $('.name1').val(data.team_name);--}}
        {{--    $('.name2').val(data.status);--}}
        {{--    $('.name3').val(data.team_description);--}}
        {{--    $('.md1').modal('show');--}}

        {{--    $('.at1').click(function () {--}}
        {{--        if ($('.name1').val().trim() === '') {--}}
        {{--            alert('Please enter a team name.');--}}
        {{--            return;--}}
        {{--        }--}}

        {{--        $.ajax({--}}
        {{--            url: `{{action('App\Http\Controllers\TeamController@update')}}`,--}}
        {{--            type: "POST",--}}
        {{--            headers: {--}}
        {{--                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--            },--}}
        {{--            data: {--}}
        {{--                'id_team' : data.id_team,--}}
        {{--                'team_name': $('.name1').val(),--}}
        {{--                'status': $('.name2').val(),--}}
        {{--                'team_description': $('.name3').val(),--}}
        {{--            },--}}
        {{--            success: function (result) {--}}
        {{--                result = JSON.parse(result);--}}
        {{--                if (result.status === 200) {--}}
        {{--                    toastr.success(result.message, "Thao tác thành công");--}}
        {{--                    setTimeout(function () {--}}
        {{--                        window.location.reload();--}}
        {{--                    }, 300);--}}
        {{--                } else {--}}
        {{--                    toastr.error(result.message, "Thao tác thất bại");--}}
        {{--                }--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}
        {{--});--}}

        {{--$('.at3').click(function () {--}}
        {{--    if (!confirm("Chọn vào 'YES' để xác nhận xóa thông tin?\nSau khi xóa dữ liệu sẽ không thể phục hồi lại được.")) {--}}
        {{--        return;--}}
        {{--    }--}}
        {{--    var id = $(this).attr('data');--}}
        {{--    $.ajax({--}}
        {{--        url: `{{action('App\Http\Controllers\TeamController@delete')}}`,--}}
        {{--        type: "DELETE",--}}
        {{--        headers: {--}}
        {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--        },--}}
        {{--        data: {--}}
        {{--            'id_team': id,--}}
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
        {{--})--}}

    </script>
@endsection
