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
    {{------------FONT------------}}
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    {{------------CSS------------}}
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/prm.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/account_custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/filepond.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/filepond-plugin-image-preview.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/filepond-plugin-image-overlay.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://unpkg.com/vis-timeline@latest/styles/vis-timeline-graph2d.min.css" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    {{--------------SCRIPT---------------}}
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.esc.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{asset('assets/js/datatables.js')}}"></script>
    <script src="{{asset('assets/js/filepond.min.js')}}"></script>
    <script src="{{asset('assets/js/filepond-plugin-image-preview.min.js')}}"></script>
    <script src="{{asset('assets/js/filepond-plugin-image-overlay.min.js')}}"></script>
    <script src="{{asset('assets/js/filepond-plugin-file-validate-type.min.js')}}"></script>
    <script type="text/javascript"
            src="https://unpkg.com/vis-timeline@latest/standalone/umd/vis-timeline-graph2d.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ action('App\Http\Controllers\DashboardController@getViewDashboard') }}"
           class="d-flex align-items-center logo justify-content-center">
            <img class="d-none d-lg-block" src="{{ asset('assets/img/logo.png') }}" alt="">
            <img class="d-lg-none" src="{{ asset('assets/img/logo2.png') }}" alt="">
        </a>
        <i class="bi bi-list toggle-sidebar-btn me-1"></i>
        <div class="dropdown" style="margin-left: 40px">
            <a class="btn btn-secondary dropdown-toggle" href="{{action('\App\Http\Controllers\ProjectController@getView')}}" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                PRM
            </a>

            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <li><a class="dropdown-item" href="{{action('App\Http\Controllers\EmployeesController@getView')}}">HRM</a></li>
                <li><a class="dropdown-item" href="{{action('App\Http\Controllers\CustomerController@getView')}}">CRM</a></li>
                <li><a class="dropdown-item active" href="{{action('\App\Http\Controllers\ProjectController@getView')}}">PRM</a></li>
            </ul>
        </div>
{{--        <ul class="nav nav-tabs nav-tabs-bordered d-flex justify-content-between border-0 ms-5" role="tablist">--}}
{{--            <li class="nav-item" role="presentation">--}}
{{--                <a href="{{action('App\Http\Controllers\EmployeesController@getView')}}" class="nav-link fw-bold">HRM--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="nav-item" role="presentation">--}}
{{--                <a href="{{action('App\Http\Controllers\CustomerController@getView')}}"--}}
{{--                   class="nav-link fw-bold">CRM </a>--}}
{{--            </li>--}}
{{--            <li class="nav-item" role="presentation">--}}
{{--                <a href="{{action('\App\Http\Controllers\ProjectController@getView')}}" class="nav-link fw-bold active">PRM--}}
{{--                </a>--}}
{{--            </li>--}}
{{--        </ul>--}}
        <div class="ms-3" style="display: flex; align-items: center">

            @php $i = 1; $item = $project[0] @endphp
            @foreach($item->team_members as $employee)
                @php $i++ @endphp
                @if($i>6)
                    @break
                @endif
                @php
                    $photoPath = asset($employee->photo);
                    $defaultPhoto = asset('assets/img/avt.png');
                    $photoExists = !empty($employee->photo) && file_exists(public_path($employee->photo));
                @endphp
                <img src="{{ $photoExists ? $photoPath : $defaultPhoto }}" alt="Profile"
                     class="@if($employee->team_permission == 1){{"border-admin"}}@endif rounded-circle object-fit-cover ms-1"
                     width="30" height="30"
                     title="{{$employee->last_name." ".$employee->first_name.' - '.\App\Http\Controllers\TeamDetailsController::getPermissionName($employee->team_permission)}}"
                     style="cursor:pointer">
            @endforeach
            @if(count($item->team_members)>6)
                <div
                    class="d-flex align-items-center justify-content-center ms-1 position-relative show-more"
                    style="width: 30px; height: 30px; background: #FFC107; color: white; font-weight: normal;border-radius: 50%; border: 1px solid #FFC107; cursor: pointer">
                    <i class="bi bi-plus position-absolute center" style="left: 1px; font-size: 11px"></i>
                    <span class="position-absolute center"
                          style="left:10px;  font-size: 12px">{{count($item->team_members)-5}}</span>
                    <div class="more-em" style="">
                        @php $i=1 @endphp
                        @foreach($item->team_members as $employee)
                            @php $i++ @endphp
                            @if($i>6)
                                @php
                                    $photoPath = asset($employee->photo);
                                    $defaultPhoto = asset('assets/img/avt.png');
                                    $photoExists = !empty($employee->photo) && file_exists(public_path($employee->photo));
                                @endphp
                                <img src="{{ $photoExists ? $photoPath : $defaultPhoto }}"
                                     alt="Profile"
                                     class="@if($employee->team_permission == 1){{"border-admin"}}@endif rounded-circle object-fit-cover ms-2 mt-2"
                                     width="30" height="30"
                                     title="{{$employee->last_name." ".$employee->first_name.' - '.\App\Http\Controllers\TeamDetailsController::getPermissionName($employee->team_permission)}}"
                                     style="cursor:pointer">
                            @endif
                        @endforeach
                        <div class="arrow-f"></div>
                    </div>
                </div>

            @endif
            <div
                class="d-flex align-items-center justify-content-center ms-1 team-select-employee"
                style="width: 30px; height: 30px; background: transparent; border-radius: 50%; border: 1px dashed black; cursor: pointer"
                data="{{\App\Http\Controllers\AccountController::toAttrJson($item->all_employees)}}"
                team-id="{{$item->team_id}}"
            >
                <i class="bi bi-person-fill-add fs-5"></i>
            </div>
        </div>
    </div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown-center my-2">
                <a class="nav-link nav-icon rounded-2 bg-light-hover" href="#"
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
    <ul class="sidebar-nav" id="sidebar-nav">
        @foreach($project as $item)
            <li class="nav-item">
                <a class="nav-link collapsed d-flex justify-content-between" data-bs-target="#projects-nav-{{$item->project_id}}"
                   data-bs-toggle="collapse"
                   href="#">
                    <i class="bi bi-folder ms-auto"></i>
                    <div class="nav-title w-100">
                        <span>{{$item->project_name}}</span>
                    </div>
                    <div class="actions text-nowrap" style="pointer-events: none">
                        <i class="bi bi-gear ms-auto"></i>
                        <i class="bi bi-plus-square ms-auto"></i>
                        <i class="bi bi-star ms-auto"></i>
                    </div>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="projects-nav-{{$item->project_id}}" class="nav-content collapse list-unstyled" data-bs-parent="#sidebar-nav">
                    @foreach($item->locations as $location)
                        <li>
                            <a
                                href="{{ action('\App\Http\Controllers\ProjectController@getView') }}">
                                <i class="bi bi-bullseye"></i>
                                <span>{{$location->project_location_name}}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</aside>

<main id="main" class="main">
    @yield('contents')
</main>


<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

</body>
<script src="{{ asset('assets/js/main.js') }}"></script>
</html>
@yield('script')
