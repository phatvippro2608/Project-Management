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
<header id="header" class="header fixed-top d-flex align-items-center">

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
            <li class="nav-item" role="presentation">
                <a href="{{action('App\Http\Controllers\CustomerController@getView')}}"
                   class="nav-link fw-bold">CRM </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{action('\App\Http\Controllers\ProjectController@getView')}}" class="nav-link fw-bold active">PRM
                </a>
            </li>
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
