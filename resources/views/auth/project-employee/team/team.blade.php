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
    </style>
@endsection
@section('contents')
    <div class="pagetitle">
        <h1>Team List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Team List</li>
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
                                    Add Team
                                </div>
                            </div>
                            <div class="ms-auto text-secondary">
                                <div class="search-container w-100">
                                    <form method="GET" action="{{ action('App\Http\Controllers\AccountController@getView') }}" class="d-flex w-100">
                                        <input name="keyw" type="text"
                                               value="{{ request()->input('keyw') }}"
                                               class="form-control form-control-md" aria-label="Search invoice"
                                               placeholder="Search ...">
                                        <button type="submit" class="btn btn-link p-0"><i class="bi bi-search search-button"></i></button>
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
                                    <th class="text-center">Team name</th>
                                    <th class="text-center">Team Description</th>
                                    <th class="text-center">Created by</th>
                                    <th class="text-center">Created at</th>
                                    <th class="text-center">Last update</th>
                                    <th class="text-center">Status</th>
                                </tr>

                                </thead>
                                <tbody class="account-list">
                                    @foreach($team as $item)
                                        <tr class="account-item">
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="d-flex align-items-center">
{{--                                                        <input type="checkbox" name="" id="" class="custom-checkbox-lg">--}}
                                                        <a class=" edit">
                                                            <i class="bi bi-pencil-square ic-update ic-btn at2"
                                                               data="{{(\App\Http\Controllers\AccountController::toAttrJson($item))}}"></i>
                                                        </a>
                                                        <a class=" delete">
                                                            <i class="bi bi-trash ic-delete ic-btn at3" aria-hidden="true"
                                                               data="{{$item->team_id}}"></i>
                                                        </a>
                                                    </div>

                                                </div>

                                            </td>
                                            <td class="text-center">
                                                {{$item->team_name}}
                                            </td>
                                            <td class="text-center">
                                                {{$item->team_description}}
                                            </td>
                                            <td class="text-center">
                                                {{$item->first_name.' '.$item->last_name}}
                                            </td>
                                            <td class="text-center">
                                                {{\App\Http\Controllers\AccountController::format($item->created_at)}}
                                            </td>
                                            <td class="text-center">
                                                {{\App\Http\Controllers\AccountController::format($item->updated_at)}}
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
    <div class="modal fade md1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-bold"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="">
                                Team name
                            </label>
                            <input type="text" class="form-control name1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="">
                                Status
                            </label>
                            <select type="text" class="form-select name2">
                                @foreach($status as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="">
                                Description
                            </label>
                            <textarea type="text" class="form-control name3" aria-multiline="true" rows="5"></textarea>
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
            $('.md1 .modal-title').text('Add New Team');
            $('.md1').modal('show');

            $('.at1').click(function () {
                if ($('.name1').val().trim() === '') {
                    alert('Please enter a team name.');
                    return;
                }

                $.ajax({
                    url: `{{action('App\Http\Controllers\TeamController@add')}}`,
                    type: "PUT",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'team_name': $('.name1').val(),
                        'status': $('.name2').val(),
                        'team_description': $('.name3').val(),
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
        $('.at2').click(function () {
            $('.md1 .modal-title').text('Update Team');
            var data = JSON.parse($(this).attr('data'));
            $('.name1').val(data.team_name);
            $('.name2').val(data.status);
            $('.name3').val(data.team_description);
            $('.at1').text('Update');
            $('.md1').modal('show');

            $('.at1').click(function () {
                if ($('.name1').val().trim() === '') {
                    alert('Please enter a team name.');
                    return;
                }

                $.ajax({
                    url: `{{action('App\Http\Controllers\TeamController@update')}}`,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'team_id' : data.team_id,
                        'team_name': $('.name1').val(),
                        'status': $('.name2').val(),
                        'team_description': $('.name3').val(),
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

        $('.at3').click(function () {
            if (!confirm("Chọn vào 'YES' để xác nhận xóa thông tin?\nSau khi xóa dữ liệu sẽ không thể phục hồi lại được.")) {
                return;
            }
            var id = $(this).attr('data');
            $.ajax({
                url: `{{action('App\Http\Controllers\TeamController@delete')}}`,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'team_id': id,
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
        })

    </script>
@endsection
