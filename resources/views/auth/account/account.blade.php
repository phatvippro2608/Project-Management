<?php
    use App\Http\Controllers\AccountController;
    ?>

@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Account</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Account</li>
            </ol>
        </nav>
    </div>

    <div class="row gx-3 my-3">
        <div class="col-md-6 m-0">
            <div class="btn btn-primary mx-2 btn-add">
                <div class="d-flex align-items-center">
                    <i class="bi bi-file-earmark-plus-fill pe-2"></i>
                    Add Account
                </div>
            </div>
            <a class="btn btn-danger mx-2" href="{{ action('App\Http\Controllers\AccountController@loginHistory') }}">
                <div class="d-flex align-items-center">
                    <i class="bi bi-flower3 pe-2"></i>
                    Log History
                </div>
            </a>
        </div>
    </div>

    <section class="section employees">
        <div class="row">
            <div class="col">
                <div class="card p-2 border rounded-4">
                    <div class="card-body m-1">
                        <table id="accountsTable" class="table table-borderless table-hover">
                            <thead class="table-light">
                            <tr>
                                <th style="width: 112px"></th>
                                <th class="text-center">Employee Code</th>
                                <th class="text-center">Full Name</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Username</th>
                                <th class="text-center">Permission</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Last Updated</th>
                            </tr>

                            </thead>
                            <tbody class="account-list">
                            @foreach($account as $item)
                                <tr class="account-item">
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="action-buttons ">
                                                <a class=" edit">
                                                    <i class="bi bi-pencil-square ic-update ic-btn"
                                                       data="{{(\App\Http\Controllers\AccountController::toAttrJson($item))}}"></i>
                                                </a>
                                                <a class="delete me-2">
                                                    <i class="bi bi-trash ic-delete ic-btn" aria-hidden="true"
                                                       data="{{ $item->account_id }}"></i>
                                                </a>
                                            </div>
                                            <img src="{{$item->photo}}" alt="" onerror="this.onerror=null;this.src='{{ asset('assets/img/not-found.svg') }}';" class="account-photo rounded-circle p-0 m-0">
                                        </div>

                                        </td>
                                        <td class="text-center">
                                            {{$item->employee_code}}
                                        </td>
                                        <td class="text-left">
                                            {{$item->first_name}} {{$item->last_name}}
                                        </td>
                                        <td class="text-left">
                                            {{$item->email}}
                                        </td>
                                        <td class="text-center">
                                            {{$item->username}}
                                        </td>
                                        <td class="text-center">
                                            {{AccountController::getPermissionName($item->permission)}}
                                        </td>
                                        <td class="text-center">
                                            @if($status[$item->status] == 'Offine')
                                                <i class="bi bi-circle-fill account-status offine"></i>
                                            @elseif($status[$item->status] == 'Locked')
                                                <i class="bi bi-circle-fill account-status" style="color:red;"></i>
                                            @else
                                                <i class="bi bi-circle-fill account-status"></i>
                                            @endif

                                        {{$status[$item->status]}}
                                    </td>
                                    <td class="text-center">
                                        {{$item->updated_at}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade md1">
        <div class="modal-dialog g-cemodal-dialontered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-bold"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">
                                Employee
                            </label>
                            <select class="form-select name1" aria-label="Default">
                                <option value="-1">No select</option>
                                @foreach($employees as $employee)
                                    <option value="{{$employee->employee_id}}">{{$employee->employee_code}}
                                        - {{$employee->first_name}} {{$employee->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="">
                                Email
                            </label>
                            <input type="email" class="form-control email">
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="">
                                Username
                            </label>
                            <input type="text" class="form-control name2">
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="" class="passName">Password</label> |
                            <label for="auto_pwd">Auto</label>
                            <input class="auto_pwd" type="checkbox" name="auto_pwd" checked>
                            <input class="form-control name3" type="text" placeholder="">
                        </div>
                        <div class="col-md-6" style="margin-top: 1rem">
                            <label for="">
                                Status
                            </label>
                            <select class="form-select name4" aria-label="Default">
                                @foreach($status as $key => $val)
                                    <option value="{{$key}}">{{$val}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6" style="margin-top: 1rem">
                            <label for="">
                                Permission
                            </label>
                            <select class="form-select name5" aria-label="Default">
                                @foreach($permission as $item)
                                    <option value="{{$item->permission_num}}">{{$item->permission_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-upload at2">Create</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function generatePassword(length) {
            const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const lowercase = 'abcdefghijklmnopqrstuvwxyz';
            const numbers = '0123456789';
            const specialCharacters = '!@#$%^&*()-_=+[]{}|;:,.<>?';
            const allCharacters = uppercase + lowercase + numbers + specialCharacters;

            let password = '';

            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * allCharacters.length);
                password += allCharacters[randomIndex];
            }

            return password;
        }

        var table = $('#accountsTable').DataTable({
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

        $('.name3').val(generatePassword(20));
        $('.name3').prop('disabled', $('.auto_pwd').is(':checked'));
        $('.auto_pwd').change(function () {
            $('.name3').prop('disabled', $('.auto_pwd').is(':checked'));
            if ($('.auto_pwd').is(':checked'))
                $('.name3').val(generatePassword(20));
            else
                $('.name3').val('');
        })
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        $('.btn-add').click(function () {
            $('.md1 .modal-title').text('Add Account');
            $('.md1 .passName').text('Password');
            $('.md1').modal('show');

            $('.at2').click(function () {

                if (!validateEmail($('.email').val())) {
                    alert('Please enter a valid email address.');
                    return;
                }

                if ($('.name2').val().trim() === '') {
                    alert('Please enter a username.');
                    return;
                }

                $.ajax({
                    url: `{{action('App\Http\Controllers\AccountController@add')}}`,
                    type: "PUT",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'employee_id': $('.name1').val(),
                        'username': $('.name2').val(),
                        'email': $('.email').val(),
                        'password': $('.name3').val(),
                        'status': $('.name4').val(),
                        'permission': $('.name5').val(),
                        'auto_pwd': $('.auto_pwd').is(':checked')
                    },
                    success: function (result) {
                        result = JSON.parse(result);
                        if (result.status === 200) {
                            toastr.success(result.message, "Successfully");
                            setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        } else {
                            toastr.error(result.message, "Failed");
                        }
                    }
                });
            });
        });

        $(document).on('click', '.ic-update', function () {
            $('.md1 .modal-title').text('Update Account');
            $('.md1 .passName').text('New Password');
            var data = JSON.parse($(this).attr('data'));
            $('.name1').val(data.employee_id);
            $('.name2').val(data.username);
            $('.email').val(data.email);
            $('.name3').val('');
            $('.name3').prop('disabled',false);
            $('.auto_pwd').prop('checked',false);
            $('.name4').val(data.status);
            $('.name5').val(data.permission);
            $('.at2').text('Update')
            $('.md1').modal('show');

            $('.at2').click(function () {

                if (!validateEmail($('.email').val())) {
                    alert('Please enter a valid email address.');
                    return;
                }

                if ($('.name2').val().trim() === '') {
                    alert('Please enter a username.');
                    return;
                }

                $.ajax({
                    url: `{{action('App\Http\Controllers\AccountController@update')}}`,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'account_id': data.account_id,
                        'employee_id': $('.name1').val(),
                        'username': $('.name2').val(),
                        'email': $('.email').val(),
                        'password': $('.name3').val(),
                        'status': $('.name4').val(),
                        'permission': $('.name5').val(),
                        'auto_pwd': $('.auto_pwd').is(':checked')
                    },
                    success: function (result) {
                        result = JSON.parse(result);
                        if (result.status === 200) {
                            toastr.success(result.message, "Successfully");
                            setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        } else {
                            toastr.error(result.message, "Failed");
                        }
                    }
                });
            });
        });


        $('.ic-delete').click(function () {
            if (!confirm("Chọn vào 'YES' để xác nhận xóa thông tin?\nSau khi xóa dữ liệu sẽ không thể phục hồi lại được.")) {
                return;
            }
            var id = $(this).attr('data');
            $.ajax({
                url: `{{action('App\Http\Controllers\AccountController@delete')}}`,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'account_id': id,
                },
                success: function (result) {
                    result = JSON.parse(result);
                    if (result.status === 200) {
                        toastr.success(result.message, "Successfully");
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        toastr.success(result.message, "Failed");
                    }
                }
            });
        })

    </script>
@endsection
