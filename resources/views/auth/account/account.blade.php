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

    <section class="section employees">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="d-inline-flex align-items-center">
                            <div class="btn btn-primary mx-2 btn-add">
                                <div class="d-flex align-items-center">
                                <span class="material-symbols-outlined">
                                    add
                                </span>
                                    Add Account
                                </div>
                            </div>
                            <div class="ms-auto text-secondary">
                                {{--                                <form action="">--}}
                                {{--                                    <div class="input-group input-group">--}}
                                {{--                                        <input name="keyw" type="text"--}}
                                {{--                                               value="{{ request()->input('keyw') }}"--}}
                                {{--                                               class="form-control"--}}
                                {{--                                               placeholder="Search ...">--}}
                                {{--                                        <span class="input-group-btn">--}}
                                {{--                                    <button type="submit" class="btn btn-info btn-flat">Search</button>--}}
                                {{--                                    </span>--}}
                                {{--                                    </div>--}}
                                {{--                                </form>--}}
                                <div class="search-container w-100">
                                    <input type="text" class="form-control form-control-md" aria-label="Search invoice"
                                           placeholder="Search ...">
                                    <i class="bi bi-search search-button"></i>
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
                                    <th class="text-center">Full Name</th>
                                    <th class="text-center">Gmail</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Last Active</th>
                                    <th class="text-center">Created At</th>
                                </tr>

                                </thead>
                                <tbody class="account-list">
                                @foreach($account as $item)
                                    <tr class="account-item">
                                        <td class="text-right">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="action-buttons ">
                                                    <a class=" edit">
                                                        <i class="bi bi-pencil-square ic-update ic-btn"
                                                           data="{{(\App\Http\Controllers\AccountController::toAttrJson($item))}}"></i>
                                                    </a>
                                                    <a class=" delete">
                                                        <i class="bi bi-trash ic-delete ic-btn" aria-hidden="true"
                                                           data="{{ $item->id_account }}"></i>
                                                    </a>
                                                    <a class=" key">
                                                        <i class="bi bi-key ic-key ic-btn"
                                                           data="{{ $item->id_account }}"></i>
                                                    </a>
                                                </div>
                                                <img src="http://ventech.local/assets/img/profile-img.jpg" alt=""
                                                     class="account-photo rounded-circle p-0 m-0">
                                            </div>

                                        </td>
                                        <td class="text-center">
                                            {{$item->employee_code}} - {{$item->first_name}} {{$item->last_name}}
                                        </td>
                                        <td class="text-center">
                                            {{$item->email}}
                                        </td>
                                        <td class="text-center">
                                            {{$item->username}}
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
                                            Active 16 hours ago
                                        </td>
                                        <td class="text-center">
                                            16 years ago
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
    <div class="modal fade md1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
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
                                    <option value="{{$employee->id_employee}}">{{$employee->employee_code}}
                                        - {{$employee->first_name}} {{$employee->last_name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="row">
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
                                @foreach($permission as $key => $val)
                                    <option value="{{$key}}">{{$val}}</option>
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
        $('.name3').prop('disabled', $('.auto_pwd').is(':checked'));
        $('.auto_pwd').change(function () {
            $('.name3').prop('disabled', $('.auto_pwd').is(':checked'));
        })

        $('.btn-add').click(function () {
            $('.md1 .modal-title').text('Add Account');
            $('.md1 .passName').text('Password');
            $('.name5').val(0);
            $('.md1').modal('show');

            $('.at2').click(function () {
                $.ajax({
                    url: `{{action('App\Http\Controllers\AccountController@add')}}`,
                    type: "PUT",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id_employee': $('.name1').val(),
                        'username': $('.name2').val(),
                        'password': $('.name3').val(),
                        'status': $('.name4').val(),
                        'permission': $('.name5').val(),
                        'auto_pwd': $('.auto_pwd').is(':checked')
                    },
                    success: function (result) {
                        result = JSON.parse(result);
                        if (result.status === 200) {
                            // toastr.success(result.message, "Thao tác thành công");
                            alert(result.message);
                            setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        } else {
                            // toastr.error(result.message, "Thao tác thất bại");
                            alert(result.message);
                        }
                    }
                });
            });
        });

        $(document).on('click', '.ic-update', function () {
            $('.md1 .modal-title').text('Update Account');
            $('.md1 .passName').text('New Password');
            var data = JSON.parse($(this).attr('data'));
            $('.name1').val(data.id_employee);
            $('.name2').val(data.username);
            $('.name4').val(data.status);
            $('.name5').val(data.permission);
            $('.md1').modal('show');

            $('.at2').click(function () {
                $.ajax({
                    url: `{{action('App\Http\Controllers\AccountController@update')}}`,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id_account': data.id_account,
                        'id_employee': $('.name1').val(),
                        'username': $('.name2').val(),
                        'password': $('.name3').val(),
                        'status': $('.name4').val(),
                        'permission': $('.name5').val(),
                        'auto_pwd': $('.auto_pwd').is(':checked')
                    },
                    success: function (result) {
                        result = JSON.parse(result);
                        if (result.status === 200) {
                            // toastr.success(result.message, "Thao tác thành công");
                            alert(result.message);
                            setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        } else {
                            // toastr.error(result.message, "Thao tác thất bại");
                            alert(result.message);
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
                    'id_account': id,
                },
                success: function (result) {
                    result = JSON.parse(result);
                    if (result.status === 200) {
                        alert(result.message);
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        alert(result.message);
                    }
                }
            });
        })

    </script>
@endsection

