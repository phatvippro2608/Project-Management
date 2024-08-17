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
    @include('auth/links')
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
                <a href="{{action('App\Http\Controllers\EmployeesController@getView')}}" class="nav-link fw-bold" >HRM
                </a>
                {{--            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sidebar-tab"--}}
                {{--                    aria-selected="true" role="tab">Tab1--}}
                {{--            </button>--}}
            </li>

            <li class="nav-item" role="presentation">
                {{--            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sidebar-tab1"--}}
                {{--                    aria-selected="false" tabindex="-1" role="tab">Tab2--}}
                {{--            </button>--}}
                <a href="{{action('App\Http\Controllers\CustomerController@getView')}}" class="nav-link fw-bold active" >CRM </a>
            </li>


            <li class="nav-item" role="presentation">
                {{--            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password"--}}
                {{--                    aria-selected="false" tabindex="-1" role="tab">Tab3--}}
                {{--            </button>--}}
                <a href="{{action('\App\Http\Controllers\ProjectController@getView')}}" class="nav-link fw-bold " >PRM
                </a>
            </li>
        </ul>
    </div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            {{--     Change language      --}}
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
        @if (!in_array(AccountController::permissionStr(), []))
            <li class="nav-item">
                <a class="nav-link"
                   href="{{ action('App\Http\Controllers\CustomerController@getView') }}">
                    <i class="bi bi-person"></i><span>{{ __('messages.customer') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   href="{{action('App\Http\Controllers\ContractController@getView')}}">
                    <i class="bi bi-journal-bookmark"></i><span>{{ __('messages.contract') }}</span>
                </a>
            </li>
        @endif

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
