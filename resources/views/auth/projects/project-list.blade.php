@extends('auth.prm')
@section('head')
    <style>
        label {
            font-weight: bolder;
            margin-left: 5px;
            margin-top: 20px;
        }

        tr {
            border-bottom: 1px solid #E8E8E8;
        }

        .bg-hover:hover {
            background: #E2E3E5 !important;
        }

        .dropdown-toggle::after {
            display: none !important;
        }

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

        .hover-details {
            text-decoration: none;
            color: var(--bs-primary);
        }

        .hover-details:hover {
            text-decoration: underline;
            opacity: 1;
            color: var(--bs-primary);
        }
    </style>
@endsection
@section('contents')
    <div class="pagetitle">
        <h1>{{ __('messages.project') }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{action('App\Http\Controllers\DashboardController@getViewDashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Project List</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (in_array(\App\Http\Controllers\AccountController::permissionStr(), ['super','admin','director', 'project_manager']))
{{--        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addProjectModal">--}}
{{--            <i class="bi bi-plus-lg"></i>--}}
{{--            {{ __('messages.add') }}--}}
{{--        </button>--}}
    @endif
    {{--    <button class="btn btn-primary mb-4">--}}
    {{--        <i class="bi bi-file-earmark-arrow-up"></i>--}}
    {{--        {{ __('messages.import') }}--}}
    {{--    </button>--}}

    {{--    <button class="btn btn-primary mb-4">--}}
    {{--        <i class="bi bi-file-earmark-arrow-down"></i>--}}
    {{--        {{ __('messages.export') }}--}}
    {{--    </button>--}}

    <!-- Table to display materials -->
    <div class="card border rounded-4 p-2">
        <div class="card-body">
            <table id="projectListTable" class="table table-hover table-borderless">
                <thead class="table-light">
                <tr>

                    <th class="text-center">#</th>
                    <th scope="col" class="text-center">{{ __('messages.project_name') }}</th>
                    <th scope="col" class="text-center">{{ __('messages.customer') }}</th>
                    <th scope="col" class="text-center">{{ __('messages.team_members') }}</th>

                    <th scope="col" class="text-center">{{ __('messages.status') }}</th>
                    <th scope="col" class="text-center">{{ __('messages.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @php $d = 1@endphp
                @foreach($project as $item)
                    <tr style="height: 80px;">

                        <td class="text-center">{{ $d }}</td>
                        @php $d++ @endphp
                        <td class="w-25">
                            <span class="text-truncate fw-bold">{{ $item->project_name }}</span><br>
                            <span>{{ \Carbon\Carbon::parse($item->project_date_start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($item->project_date_end)->format('d M Y') }}</span>
                        </td>

                        <td>{{ $item->customer_info }}</td>
                        <td>
                            <div style="display: flex; align-items: center">
                                @php $i = 1 @endphp
                                @foreach($item->team_members as $employee)
                                    @php $i++ @endphp
                                    @if($i>4)
                                        @break
                                    @endif
                                    @php
                                        $photoPath = asset($employee->photo);
                                        $defaultPhoto = asset('assets/img/avt.png');
                                        $photoExists = !empty($employee->photo) && file_exists(public_path($employee->photo));
                                    @endphp
                                    <img src="{{ $photoExists ? $photoPath : $defaultPhoto }}" alt="Profile"
                                         class="@if($employee->team_permission == 1){{"border-admin"}}@endif rounded-circle object-fit-cover ms-1"
                                         width="36" height="36"
                                         title="{{$employee->last_name." ".$employee->first_name.' - '.\App\Http\Controllers\TeamDetailsController::getPermissionName($employee->team_permission)}}"
                                         style="cursor:pointer">
                                @endforeach
                                @if(count($item->team_members)>3)
                                    <div
                                        class="d-flex align-items-center justify-content-center ms-1 position-relative show-more"
                                        style="width: 36px; height: 36px; background: #FFC107; color: white; font-weight: normal;border-radius: 50%; border: 1px solid #FFC107; cursor: pointer">
                                        <i class="bi bi-plus position-absolute center" style="left: 1px;"></i>
                                        <span class="position-absolute center"
                                              style="left:15px; ">{{count($item->team_members)-3}}</span>
                                        <div class="more-em" style="">
                                            @php $i=1 @endphp
                                            @foreach($item->team_members as $employee)
                                                @php $i++ @endphp
                                                @if($i>4)
                                                    @php
                                                        $photoPath = asset($employee->photo);
                                                        $defaultPhoto = asset('assets/img/avt.png');
                                                        $photoExists = !empty($employee->photo) && file_exists(public_path($employee->photo));
                                                    @endphp
                                                    <img src="{{ $photoExists ? $photoPath : $defaultPhoto }}"
                                                         alt="Profile"
                                                         class="@if($employee->team_permission == 1){{"border-admin"}}@endif rounded-circle object-fit-cover ms-2 mt-2"
                                                         width="36" height="36"
                                                         title="{{$employee->last_name." ".$employee->first_name.' - '.\App\Http\Controllers\TeamDetailsController::getPermissionName($employee->team_permission)}}"
                                                         style="cursor:pointer">
                                                @endif
                                            @endforeach
                                            <div class="arrow-f"></div>
                                        </div>
                                    </div>

                                @endif
                                @if (in_array(\App\Http\Controllers\AccountController::permissionStr(), ['super','admin','director', 'project_manager']))
                                    <div
                                        class="d-flex align-items-center justify-content-center ms-1 team-select-employee"
                                        style="width: 36px; height: 36px; background: transparent; border-radius: 50%; border: 1px dashed black; cursor: pointer"
                                        data="{{\App\Http\Controllers\AccountController::toAttrJson($item->all_employees)}}"
                                        team-id="{{$item->team_id}}"
                                    >
                                        <i class="bi bi-person-fill-add fs-4"></i>
                                    </div>
                                @endif
                            </div>
                        </td>

                        {{--                            <td>{{ \Carbon\Carbon::parse($project->project_date_start)->format('d M Y') }}</td>--}}
                        {{--                            <td>{{ \Carbon\Carbon::parse($project->project_date_end)->format('d M Y') }}</td>--}}

                        <td class="text-center">
                            <span class="badge rounded-pill
                                @switch($item->phase_id)
                                    @case(1)
                                        bg-primary
                                        @break
                                    @case(2)
                                        bg-info
                                        @break
                                    @case(3)
                                        bg-success
                                        @break
                                    @case(4)
                                        bg-warning
                                        @break
                                    @case(5)
                                        bg-danger
                                        @break
                                    @default
                                        bg-secondary
                                @endswitch">
                                {{ $item->phase_name_eng }}
                            </span>
                        </td>

                        <td class="text-center">
                            <div class="dropdown">
                                <div class="dropdown-toggle" role="button" data-bs-toggle="dropdown"
                                     aria-expanded="false">
                                    <img src="/assets/icons/settings.ico" alt="">
                                </div>

                                <ul class="dropdown-menu">
                                    @if (in_array(\App\Http\Controllers\AccountController::permissionStr(), ['super','admin','director', 'project_manager']))
{{--                                        <li style="border-bottom: 1px solid #E2E3E5; cursor:pointer" class="fw-bold">--}}
{{--                                            <a class="dropdown-item bg-hover add-location d-flex align-items-center"--}}
{{--                                               data="{{$item->project_id}}">--}}
{{--                                                <i class="bi bi-plus fs-4"></i><span style="padding: 0px!important;">Add Location</span></a>--}}
{{--                                        </li>--}}
                                        <li style="border-bottom: 1px solid #E2E3E5"><a class="dropdown-item bg-hover"
                                                                                        href="{{ route('project.details', ['project_id' => $item->project_id]) }}">Details
                                                and Cost</a></li>
                                        </li>
                                    @endif
                                    <li><a class="dropdown-item bg-hover"
                                           href="{{ action('App\Http\Controllers\ProjectController@getAttachmentView', $item->project_id) }}">Attachments</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h5 class="modal-title" id="addProjectModalLabel">Add new project</h5>
                    <i class="bi bi-x-lg fs-4" style="cursor:pointer" data-bs-dismiss="modal" aria-label="Close"></i>
                </div>
                <!-- Form trong view -->
                <div class="modal-body">
                    <form id="projectForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="project_name" class="form-label">Project Name</label>
                                <input type="text" class="form-control" id="project_name" name="project_name" required>
                            </div>
                            <div class="col-md-3">
                                <label for="project_date_start" class="form-label">Project Start Date</label>
                                <input type="date" class="form-control" id="project_date_start"
                                       name="project_date_start" required>
                            </div>
                            <div class="col-md-3">
                                <label for="project_date_end" class="form-label">Project End Date</label>
                                <input type="date" class="form-control" id="project_date_end" name="project_date_end"
                                       required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="project_address" class="form-label">Project Address</label>
                                <textarea name="project_address" id="project_address" rows="2"
                                          class="form-control"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="project_contact_name" class="form-label">Contact Name</label>
                                <input type="text" class="form-control" id="project_contact_name"
                                       name="project_contact_name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="project_description" class="form-label">Description</label>
                                <textarea name="project_description" id="project_description" rows="2"
                                          class="form-control"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="project_contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" class="form-control" id="project_contact_phone"
                                       name="project_contact_phone" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="select_contract" class="form-label">Contract</label>
                                <div class="input-group">
                                    <select class="form-select contract_id"
                                            name="contract_id"
                                            aria-label="Example select with button addon">
                                        <option selected>Choose...</option>
                                        @foreach($contracts as $contract)
                                            <option
                                                value="{{$contract->contract_id}}">{{$contract->contract_name}}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-secondary add-contract" type="button"><i
                                            class="bi bi-plus"></i></button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="project_contact_address" class="form-label">Contact Address</label>
                                <input type="text" class="form-control" id="project_contact_address"
                                       name="project_contact_address" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="" class="form-label">Select Team</label>
                                <div class="input-group">
                                    <select class="form-select select_team"
                                            name="select_team"
                                            aria-label="Example select with button addon">
                                        <option selected>Choose...</option>
                                        @foreach($teams as $team)
                                            <option value="{{$team->team_id}}">{{$team->team_name}}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-secondary add-team" type="button"><i
                                            class="bi bi-plus"></i></button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="project_contact_website" class="form-label">Contact Website</label>
                                <input type="text" class="form-control" id="project_contact_website"
                                       name="project_contact_website">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnSubmitProject">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade md1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="" class="fs-5" style="margin-bottom: 0.3rem">
                                Team name
                            </label>
                            <input type="text" class="form-control mt-1 val-team-name">
                        </div>
                    </div>
                    <div class="row mt-3 d-flex justify-content-end">
                        <button type="button" class="w-auto btn btn-danger btn-upload" data-bs-dismiss="modal"
                                aria-label="Close">Close
                        </button>
                        <button type="button" class="w-auto btn btn-primary btn-upload at1 ms-2 me-3 btn-create-team">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade location-modal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between">
                    <h4>Add Location</h4>
                    <i class="bi bi-x-lg fs-4" style="cursor:pointer" data-bs-dismiss="modal" aria-label="Close"></i>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="" style="margin-bottom: 0.3rem">
                                Location name
                            </label>
                            <input type="text" class="form-control mt-1 location-name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="project_date_start" class="form-label">Start Date</label>
                            <input type="date" class="form-control location-start-date">


                        </div>
                        <div class="col-lg-6">
                            <label for="project_date_end" class="form-label">End Date</label>
                            <input type="date" class="form-control location-end-date">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="" style="margin-bottom: 0.3rem">
                                Amount
                            </label>
                            <input type="number" class="form-control mt-1 location-amount">
                        </div>
                    </div>
                    <div class="row mt-3 d-flex justify-content-end">
                        <button type="button" class="w-auto btn btn-danger btn-upload" data-bs-dismiss="modal"
                                aria-label="Close">Close
                        </button>
                        <button type="button"
                                class="w-auto btn btn-primary btn-upload at1 ms-2 me-3 btn-create-location">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade team-project-modal modal-xl">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-bold"></h4>
                </div>
                <div class="modal-body">

                    <div class="table-responsive">
                        <table id="teamListTable" class="table table-hover table-borderless">
                            <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 112px">Avatar</th>
                                <th class="text-center">Employee Code</th>
                                <th class="text-center">Full Name</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Position</th>
                                <th class="text-center" data-orderable="false"><input type="checkbox" name="" id=""
                                                                                      class="custom-checkbox-lg check-all">
                                </th>
                            </tr>
                            </thead>
                            <tbody class="team-manager-list">

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        {{--$('.add-location').off('click').click(function () {--}}
        {{--    var id = $(this).attr('data');--}}
        {{--    $('.location-modal .modal-title').text('Add New Location');--}}
        {{--    $('.location-modal').modal('show');--}}
        {{--    $('.btn-create-location').off('click').click(function () {--}}
        {{--        $.ajax({--}}
        {{--            url: `{{action('App\Http\Controllers\ProjectLocationController@addLocation')}}`,--}}
        {{--            type: "PUT",--}}
        {{--            headers: {--}}
        {{--                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--            },--}}
        {{--            data: {--}}
        {{--                'project_location_name': $('.location-name').val(),--}}
        {{--                'start_date': $('.location-start-date').val(),--}}
        {{--                'end_date': $('.location-end-date').val(),--}}
        {{--                'location_amount': $('.location-amount').val(),--}}
        {{--                'project_id': id--}}
        {{--            },--}}
        {{--            success: function (result) {--}}
        {{--                result = JSON.parse(result);--}}
        {{--                if (result.status === 200) {--}}
        {{--                    toastr.success(result.message, "Successfully");--}}
        {{--                    setTimeout(function () {--}}
        {{--                        location.reload();--}}
        {{--                    }, 500);--}}
        {{--                } else {--}}
        {{--                    toastr.error(result.message, "Failed Action");--}}
        {{--                }--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}
        {{--});--}}
    </script>
    <script>
        var table = $('#teamListTable').DataTable({
            language: {search: ""},
            lengthMenu: [
                [10, 30, 50, 100, -1],
                [10, 30, 50, 100, "All"]
            ],
            pageLength: {{env('ITEM_PER_PAGE')}},
            responsive: true
        });

        function renderEmployeeList(employees, positions) {
            var table = $('#teamListTable').DataTable();
            table.clear().draw(); // Clear the existing rows

            employees.forEach(function (item) {
                var positionOptions = '<option value="100">Member</option>';

                positions.forEach(function (position) {
                    if (position.team_permission !== 100) {
                        var selected = item.team_permission === position.team_permission ? 'selected' : '';
                        positionOptions += `<option value="${position.team_permission}" ${selected}>${position.position_name}</option>`;
                    }
                });

                // Add a new row to the DataTable
                table.row.add([
                    `<div class="d-flex align-items-center justify-content-center">
                <img src="${item?.photo ? item?.photo : ''}" alt="" onerror="this.onerror=null;this.src='/assets/img/not-found.svg'" class="account-photo2 rounded-circle p-0 m-0">
            </div>`,
                    item.employee_code,
                    `${item.first_name} ${item.last_name}`,
                    item.email,
                    `<select class="form-select position" data="${item.employee_id}">
                ${positionOptions}
            </select>`,
                    `<input type="checkbox" class="custom-checkbox-lg check-item" data="${item.employee_id}" ${item.isAtTeam ? 'checked' : ''}>`
                ]).draw();
            });
        }

        $('.team-select-employee').off('click').click(function () {
            var all_employees = JSON.parse($(this).attr('data'));
            var team_id = JSON.parse($(this).attr('team-id'));
            console.log(all_employees);
            var team_positions = @json($team_positions);
            //** them vào day
            renderEmployeeList(all_employees, team_positions);

            $('.team-project-modal .modal-title').text('Team Manager');
            $('.team-project-modal').modal('show');


            $(document).on('change', '.check-all', function () {
                $('.check-item').prop('checked', $('.check-all').prop('checked')).trigger('change');
            });
            $(document).on('change', '.check-item', function () {
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
                        'team_id': team_id,
                        'team_permission': team_permission,
                        'checked': $(this).prop('checked') ? 1 : 0
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

            $('.team-project-modal').on('hidden.bs.modal', function () {
                window.location.reload();
            });

            $('.position').change(function () {
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
                        'team_id': team_id,
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
        });


    </script>
    <script>
        var table = $('#projectListTable').DataTable({
            language: {search: ""},
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

        $('.md1').on('hidden.bs.modal', function () {
            $('#addProjectModal').css('opacity', '1');
        });

        $('.add-team').click(function () {
            $('.md1 .modal-title').text('Add New Team');

            $('.md1').modal('show');
            $('#addProjectModal').css('opacity', '0.95');

            $('.btn-create-team').off('click').click(function () {
                let team_name = $('.val-team-name').val();
                $.ajax({
                    url: `{{action('App\Http\Controllers\TeamController@addFromProject')}}`,
                    type: "PUT",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'team_name': team_name,
                    },
                    success: function (result) {
                        result = JSON.parse(result);
                        if (result.status === 200) {
                            toastr.success("Added a new team", "Successfully");
                            $('.form-select.select_team').append(`<option value="${result.message}">${team_name}</option>`);
                            $('.form-select.select_team').val(result.message);
                            $('.val-team-name').val("");
                            $('.md1').modal('hide');
                        } else {
                            toastr.error(result.message, "Failed Action");
                        }
                    }
                });
            })
        });

        document.getElementById('btnSubmitProject').addEventListener('click', function (event) {
            let form = document.getElementById('projectForm');
            let startDate = form.querySelector('#project_date_start').value;
            let endDate = form.querySelector('#project_date_end').value;

            let formData = new FormData(form);

            fetch('{{ route('projects.insert') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 200) {
                        toastr.success(data.message, "Successfully");
                        setTimeout(function () {
                            location.reload();
                        }, 500);
                    } else {
                        const errorRes = JSON.parse(data.message);
                        let strMes = "";
                        if (errorRes.project_name) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_name}</div>`;
                        if (errorRes.project_description) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_description}</div>`;
                        if (errorRes.project_address) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_address}</div>`;
                        if (errorRes.project_date_start) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_date_start}</div>`;
                        if (errorRes.project_date_end) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_date_end}</div>`;
                        if (errorRes.project_contact_name) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_contact_name}</div>`;
                        if (errorRes.project_contact_phone) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_contact_phone}</div>`;
                        if (errorRes.project_contact_address) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_contact_address}</div>`;
                        if (errorRes.project_contact_website) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_contact_website}</div>`;
                        if (errorRes.contract_id) strMes += `<div style="color: red; text-align: left;">-${errorRes.contract_id}</div>`;
                        if (errorRes.select_team) strMes += `<div style="color: red; text-align: left;">-${errorRes.select_team}</div>`;

                        Swal.fire({
                            html: strMes,
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Back and continue edit!'
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        });


                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error detected. Please try again!', "Failed");
                });
        });

        $('.add-contract').click(function () {
            let timerInterval;
            Swal.fire({
                html: 'You will redirect to the contract page to add a new contract in <b>4</b> seconds.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Redirect Now',
                didOpen: () => {
                    const content = Swal.getHtmlContainer();
                    const b = content.querySelector('b');
                    let countdown = 3;
                    timerInterval = setInterval(() => {
                        b.textContent = countdown;
                        countdown--;
                        if (countdown < 0) {
                            clearInterval(timerInterval);
                            Swal.close();
                            window.location.href = `{{action('App\Http\Controllers\ContractController@getView')}}`;
                        }
                    }, 1000);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Handle the confirmation button click if needed
                    window.location.href = `{{action('App\Http\Controllers\ContractController@getView')}}`;
                }
            });
        })
    </script>
@endsection
