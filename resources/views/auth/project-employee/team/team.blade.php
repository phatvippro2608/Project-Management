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
        .hover-details{
            text-decoration: none;
            color: var(--bs-primary);
        }
        .hover-details:hover{
            text-decoration: underline;
            opacity: 1;
            color: var(--bs-primary);
        }
        label {
                 font-weight: bolder;
                 margin-left: 5px;
                 margin-top: 20px;
             }

        tr{
            border-bottom: 1px solid #E8E8E8;
        }
    </style>
@endsection
@section('contents')
    <div class="pagetitle">
        <h1>{{ __('messages.team') }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">{{ __('messages.team') }}</li>
            </ol>
        </nav>
    </div>
    <div class="row gx-3 my-3">
        <div class="col-md-6 m-0">
            <div class="btn btn-primary me-2 btn-add">
                <div class="d-flex align-items-center">
                    <i class="bi bi-file-earmark-plus-fill pe-2"></i>
                        {{ __('messages.add') }}
                </div>
            </div>
        </div>
    </div>
    <div class="card border rounded-4 p-2">
        <div class="card-body">
            <div class="table-responsive">
                <table id="teamListTable" class="table table-hover table-borderless">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">{{ __('messages.team_name') }}</th>
                            <th class="text-center">Created by</th>
                            <th class="text-center">Created at</th>
                            <th class="text-center">Last update</th>
                            <th class="text-center">{{ __('messages.status') }}</th>
                            <th class="text-center" style="width: 60px!important;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="account-list">
                        @foreach($team as $item)
                            <tr class="account-item " style="height: 80px" >

                                <td class="text-left pt-1 pb-1 w-25">
                                    <a href="{{ route('team.employees', ['team_id' => $item->team_id]) }}" class="hover-details fw-bold ">
                                        {{$item->team_name}}
                                    </a>
                                    <div class="text-truncate fw-normal">{{$item->team_description}}</div>
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
                                    @if($status[$item->status] == 'Offline')
                                        <i class="bi bi-circle-fill account-status offline"></i>
                                    @elseif($status[$item->status] == 'Locked')
                                        <i class="bi bi-circle-fill account-status" style="color:red;"></i>
                                    @else
                                        <i class="bi bi-circle-fill account-status"></i>
                                    @endif

                                    {{$status[$item->status]}}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="d-flex align-items-center">
                                            <a class=" edit" style="cursor: pointer">
                                                <i class="bi bi-pencil-square ic-update ic-btn at2"
                                                   data="{{(\App\Http\Controllers\AccountController::toAttrJson($item))}}"></i>
                                            </a>
                                            <a class=" delete" style="cursor: pointer">
                                                <i class="bi bi-trash ic-delete ic-btn at3" aria-hidden="true"
                                                   data="{{$item->team_id}}"></i>
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
    </div>
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
        var table = $('#teamListTable').DataTable({
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
