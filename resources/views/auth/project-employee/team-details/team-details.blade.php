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

    <a href="{{action('App\Http\Controllers\TeamController@getView')}}" class="btn btn-primary my-3 me-2">
        <div class="d-flex align-items-center">
                <i class="bi bi-reply-fill pe-2"></i>
                Back
        </div>
    </a>

    <div class="btn btn-primary my-3 btn-add">
        <div class="d-flex align-items-center">
            <i class="bi bi-file-earmark-plus-fill pe-2"></i>
            Select Employee
        </div>
    </div>

    <div class="card border rounded-4 p-2">
        <div class="card-body">
            <div class="table-responsive">
                <table id="teamListTable" class="table table-hover table-borderless">
                    <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 112px">Avatar</th>
                        <th class="text-center">Employee Code</th>
                        <th class="text-center">Full Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Position in team</th>
{{--                        <th class="text-right">Actions</th>--}}
                    </tr>
                    </thead>
                    <tbody class="account-list">
                    @foreach($employees as $item)
                        <tr class="account-item">
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <img src="{{$item->photo}}" alt="" onerror="this.onerror=null;this.src='{{ asset('assets/img/not-found.svg') }}';" class="account-photo2 rounded-circle p-0 m-0">
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
                                @if(!empty($item->position_name)){{$item->position_name}}@else {{"Member"}} @endif
                            </td>
{{--                            <td>--}}
{{--                                <div>--}}
{{--                                    <a class=" edit">--}}
{{--                                        <i class="bi bi-pencil-square ic-update ic-btn"--}}
{{--                                           data="{{(\App\Http\Controllers\AccountController::toAttrJson($item))}}"></i>--}}
{{--                                    </a>--}}
{{--                                    <a class="delete me-2">--}}
{{--                                        <i class="bi bi-trash ic-delete ic-btn" aria-hidden="true"--}}
{{--                                           data="{{ $item->employee_id }}"></i>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            </td>--}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade md1 modal-xl">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-bold"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 1rem">


                            <div class="table-responsive">
                                <table id="eListTable" class="table table-hover table-borderless">
                                    <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 112px">Avatar</th>
                                        <th class="text-center">Employee Code</th>
                                        <th class="text-center">Full Name</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Position</th>
                                        <th class="text-center" data-orderable="false"><input type="checkbox" name="" id="" class="custom-checkbox-lg check-all"></th>
                                    </tr>
                                    </thead>
                                    <tbody class="account-list">
                                    @foreach($all_employees as $item)
                                        <tr class="account-item">
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <img src="{{$item->photo}}" alt="" onerror="this.onerror=null;this.src='{{ asset('assets/img/not-found.svg') }}';" class="account-photo2 rounded-circle p-0 m-0">
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
                                            <td class="text-left">
                                                <select class="form-select position" data="{{$item->employee_id}}">
                                                    <option value="100">Member</option>
                                                    @foreach($team_positions as $position)
                                                        @if($position->team_permission !== 100)
                                                            <option value="{{$position->team_permission}}" @if($item->team_permission==$position->team_permission) selected @endif>{{$position->position_name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" class="custom-checkbox-lg check-item" data="{{$item->employee_id}}" @if($item->isAtTeam) checked @endif>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
{{--                <div class="modal-footer">--}}
{{--                    <button type="button" class="btn btn-primary btn-upload reload">Reload</button>--}}
{{--                    <button type="button" class="btn btn-primary btn-upload at1">Update</button>--}}
{{--                </div>--}}
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
            order:
        [1,'asc']
            ,
            initComplete: function (settings, json) {
                $('.dt-search').addClass('input-group');

            },
            responsive: true
        });
        var table2 = $('#eListTable').DataTable({
            language: { search: "" },
            lengthMenu: [
                [10, 30, 50, 100, -1],
                [10, 30, 50, 100, "All"]
            ],
            pageLength: -1,
            initComplete: function (settings, json) {
                $('.dt-search').addClass('input-group');
                $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
            },
            responsive: true
        });



        $('.btn-add').click(function () {
            $('.md1 .modal-title').text('Select Employee');
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
                            toastr.success(result.message, "Successfully");
                            setTimeout(function () {
                                window.location.reload();
                            }, 300);
                        } else {
                            toastr.error(result.message, "Failed");
                        }
                    }
                });
            });
        });
        $('.check-all').change(function () {
            $('.check-item').prop('checked', $('.check-all').prop('checked')).trigger('change');
        });

        $('.check-item').change(function (){
            let employee_id = $(this).attr('data');
            let team_permission = $('select.form-select.position[data="' + employee_id + '"]').val();
            console.log(employee_id)
            console.log(team_permission)
            $.ajax({
                url: `{{action('App\Http\Controllers\TeamDetailsController@update')}}`,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'employee_id': employee_id,
                    'team_id': {{$teams->team_id}},
                    'team_permission': team_permission,
                    'checked': $(this).prop('checked')?1:0
                },
                success: function (result) {
                    result = JSON.parse(result);
                    if (result.status === 200) {
                        toastr.success(result.message, "Successfully");
                    } else {
                        toastr.error(result.message, "Failed");
                    }
                }
            });
        })

        $('.md1').on('hidden.bs.modal', function () {
            window.location.reload();
        });

        $('.position').change(function (){
            let employee_id = $(this).attr('data');
            let team_permission = $(this).val();
            $.ajax({
                url: `{{action('App\Http\Controllers\TeamDetailsController@updatePosition')}}`,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'employee_id': employee_id,
                    'team_id': {{$teams->team_id}},
                    'team_permission': team_permission,
                },
                success: function (result) {
                    result = JSON.parse(result);
                    if (result.status === 200) {
                        toastr.success(result.message, "Successfully");
                    } else {
                        toastr.error(result.message, "Failed");
                    }
                }
            });
        })
    </script>
@endsection
