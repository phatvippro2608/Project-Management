<?php

use App\StaticString;
use App\Http\Controllers\AccountController;

$token = 'position';
?>
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Project Management</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('assets/img/logo2.png') }}" rel="icon">
    @include('auth/prm/libraries')
    @yield('head')
</head>

<body>
@php
    $data = \Illuminate\Support\Facades\DB::table('accounts')
        ->join('employees', 'accounts.employee_id', '=', 'employees.employee_id')
        ->join('job_details', 'job_details.employee_id', '=', 'employees.employee_id')
        ->where(
            'account_id',
            \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID),
        )
        ->first();


    $project = DB::table('projects')
        ->join('contracts', 'contracts.contract_id', '=', 'projects.contract_id')
        ->join('customers', 'customers.customer_id', '=', 'contracts.customer_id')
        ->join('phases', 'phases.phase_id', '=', 'projects.phase_id')
        ->join('project_teams', 'project_teams.project_id', '=', 'projects.project_id')
        ->select(
            'project_teams.*',
            'projects.*',
            DB::raw("CONCAT(customers.company_name, ' - ', customers.last_name, ' ', customers.first_name) AS customer_info"),
            'phases.phase_name_eng'
        )
        ->orderBy('project_teams.project_id', 'asc')
        ->get();
    foreach ($project as $item) {
        $item->team_members = DB::table('team_details')
            ->join('employees', 'employees.employee_id', '=', 'team_details.employee_id')
            ->where('team_id', $item->team_id)
            ->orderBy('team_permission', 'asc')
            ->get();


        $sql = "SELECT
                employees.*, accounts.*,team_details.team_permission as team_permission,
                CASE
                    WHEN team_details.employee_id IS NOT NULL THEN 1
                    ELSE 0
                END AS isAtTeam
            FROM
                employees
            JOIN
                accounts ON accounts.employee_id = employees.employee_id
            LEFT JOIN
                team_details ON team_details.employee_id = employees.employee_id AND team_details.team_id = $item->team_id
            ";

        $item->all_employees = DB::select($sql);

        $item->locations = DB::table('project_locations')->where('project_id', $item->project_id)->get();
    }
    $teams = DB::table('teams')->get();
    $team_positions = DB::table("team_positions")->get();
    $contracts = DB::table('contracts')
        ->leftjoin('projects', 'contracts.contract_id', '=', 'projects.contract_id')
        ->whereNull('projects.contract_id')->select('contracts.contract_id', 'contracts.contract_name')->get();
@endphp
<header id="header" class="header fixed-top d-flex align-items-center">

{{--    /**HEADING--}}
    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ action('App\Http\Controllers\DashboardController@getViewDashboard') }}"
           class="d-flex align-items-center logo justify-content-center">
            <img class="d-none d-lg-block" src="{{ asset('assets/img/logo.png') }}" alt="">
            <img class="d-lg-none" src="{{ asset('assets/img/logo2.png') }}" alt="">
        </a>
        <i class="bi bi-list toggle-sidebar-btn me-5"></i>
        <ul class="nav nav-tabs nav-tabs-bordered d-flex justify-content-between border-0" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="{{action('App\Http\Controllers\EmployeesController@getView')}}" class="nav-link fw-bold">HRM
                </a>
            </li>

            @if (in_array(AccountController::permissionStr(), ['super', 'admin','director', 'customer_manager']))
            <li class="nav-item" role="presentation">
                <a href="{{action('App\Http\Controllers\CustomerController@getView')}}"
                   class="nav-link fw-bold">CRM </a>
            </li>
            @endif

            @if (in_array(AccountController::permissionStr(), ['employee','super','admin','director', 'project_manager']))
            <li class="nav-item" role="presentation">
                <a href="{{action('\App\Http\Controllers\ProjectController@getView')}}" class="nav-link fw-bold active">PRM
                </a>
            </li>
            @endif
        </ul>
    </div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown-center my-2">
                <a class="nav-link nav-icon rounded-2 bg-light-hover" style="padding: 0 7px" href="#"
                   data-bs-toggle="dropdown">
                    @if(\Illuminate\Support\Facades\Session::get('locale') === 'vi')
                        <img src="{{asset('assets/img/vietnam.png')}}" width="36" alt="">
                    @else
                        <img src="{{asset('assets/img/united-states.png')}}" width="36" alt="">
                    @endif
                </a>
                <div class="dropdown-menu">
                    <a class="d-flex align-items-center justify-content-between px-3 py-1 bg-light-hover"
                       href="{{url('lang/vi')}}">
                        <img class="" src="{{asset('assets/img/vietnam.png')}}" width="36" alt="">
                        <span class="text-dark mx-3">VIE</span>
                    </a>
                    <a class="d-flex align-items-center justify-content-between px-3 py-1 bg-light-hover"
                       href="{{url('lang/en')}}">
                        <img class="" src="{{asset('assets/img/united-states.png')}}" width="36" alt="">
                        <span class="text-dark mx-3">ENG</span>
                    </a>
                </div>
            </li>
            </li>

            @php

                $data = \Illuminate\Support\Facades\DB::table('accounts')
                            ->join('employees', 'accounts.employee_id', '=', 'employees.employee_id')
                            ->join('contacts', 'employees.contact_id', '=', 'contacts.contact_id')
                            ->join('job_details', 'job_details.employee_id', '=', 'employees.employee_id')

                            ->where(
                            'accounts.account_id',
                            \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID),
                            )
                            ->first();
                $info = \Illuminate\Support\Facades\DB::table('job_details')
                            ->join('job_positions', 'job_details.job_position_id', '=', 'job_positions.position_id')
                            ->where(
                                'job_details.employee_id', $data->employee_id
                            )
                            ->first();
            @endphp

            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                   data-bs-toggle="dropdown">
                    @php
                        $photoPath = asset($data->photo);
                        $defaultPhoto = asset('assets/img/avt.png');
                        $photoExists = !empty($data->photo) && file_exists(public_path($data->photo));
                    @endphp

                    <img src="{{ $photoExists ? $photoPath : $defaultPhoto }}" alt="Profile"
                         class="rounded-circle object-fit-cover" width="36" height="36">
                    <span class="d-none d-md-block dropdown-toggle ps-2">
                            {{ $data->last_name . ' ' . $data->first_name }}
                        </span>

                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>{{ $data->last_name . ' ' . $data->first_name }}</h6>
                        <span>
                                @if ($info)
                                {{ $info->position_name }}
                            @endif
                            </span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>

                        <a class="dropdown-item d-flex align-items-center"
                           href="{{ action('App\Http\Controllers\ProfileController@getViewProfile', ['employee_id' => $data->employee_id]) }}">

                            <i class="bi bi-person"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <i class="bi bi-gear"></i>
                            <span>Account Settings</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <i class="bi bi-question-circle"></i>
                            <span>Need Help?</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>

                        <a class="dropdown-item d-flex align-items-center"
                           href="{{ action('App\Http\Controllers\LoginController@logOut') }}">

                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>

                </ul>
            </li>
        </ul>
    </nav>

</header>


<aside id="sidebar" class="sidebar">
    @php
        $data = \Illuminate\Support\Facades\DB::table('accounts')
            ->join('employees', 'accounts.employee_id', '=', 'employees.employee_id')
            ->join('job_details', 'job_details.employee_id', '=', 'employees.employee_id')
            ->where(
                'account_id',
                \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID),
            )
            ->first();
    @endphp
    <ul class="sidebar-nav" id="sidebar-nav">
        @foreach($project as $item)
            <li class="nav-item position-relative" style="cursor: default">
                <a class="nav-link collapsed d-flex justify-content-between" style="cursor: default" data-bs-target="#projects-nav-{{$item->project_id}}"
                   data-bs-toggle="collapse"
                   href="">
                    <i class="bi bi-folder ms-auto"></i>
                    <div class="nav-title w-100">
                        <span>{{$item->project_name}}</span>
                    </div>
{{--                    <i class="bi bi-chevron-down ms-auto"></i>--}}
                </a>
                <div class="actions text-nowrap" style=" position: absolute; top: 10px; right: 16px; cursor: pointer;">
                    <i class="bi bi-gear ms-auto"></i>
                    <a class="add-location"  data="{{$item->project_id}}">
                        <i class="bi bi-plus-square ms-auto" ></i>
                    </a>

                    <i class="bi bi-star ms-auto"></i>
                </div>
                <ul id="projects-nav-{{$item->project_id}}" class="nav-content collapse list-unstyled" data-bs-parent="#sidebar-nav">
                    <li>
                        <a class="nav-sub-link"
                           href="{{ route('project.details', ['project_id' => $item->project_id]) }}">
                            <i class="bi bi-grid fs-6"></i>
                            <span>Overview</span>
                        </a>
                    </li>
                    @foreach($item->locations as $location)
                        <li>
                            <a class="nav-sub-link"
                                href="{{ route('project.details', ['project_id' => $item->project_id, 'location' => $location->project_location_id]) }}">
                                <i class="bi bi-pin-map fs-6"></i>
                                <span>{{$location->project_location_name}}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>

    <div class="position-absolute" style="bottom: 5px; width: 100%; border-top: 1px solid #c3c3c3">
        <button class="btn btn-primary bg-transparent text-black border-0 text-end w-100" data-bs-toggle="modal" data-bs-target="#addProjectModal">
            <i class="bi bi-plus"></i>
            <span class="fw-bold" >Add New Project</span>
        </button>
    </div>
</aside>

<main id="main" class="main">
    @yield('contents')
</main>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

{{--/**ADD PROJECT--}}
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
{{--/** LOCATION--}}
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
</body>
</html>
@yield('script')
<script>
    $('.add-location').off('click').click(function () {
        var id = $(this).attr('data');
        $('.location-modal .modal-title').text('Add New Location');
        $('.location-modal').modal('show');
        $('.btn-create-location').off('click').click(function () {
            $.ajax({
                url: `{{action('App\Http\Controllers\ProjectLocationController@addLocation')}}`,
                type: "PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'project_location_name': $('.location-name').val(),
                    'start_date': $('.location-start-date').val(),
                    'end_date': $('.location-end-date').val(),
                    'location_amount': $('.location-amount').val(),
                    'project_id': id
                },
                success: function (result) {
                    result = JSON.parse(result);
                    if (result.status === 200) {
                        toastr.success(result.message, "Successfully");
                        setTimeout(function () {
                            location.reload();
                        }, 500);
                    } else {
                        toastr.error(result.message, "Failed Action");
                    }
                }
            });
        });
    });
</script>
<script>
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
<script src="{{ asset('assets/js/main.js') }}"></script>
