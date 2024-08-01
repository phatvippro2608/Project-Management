
<?php

use App\StaticString; ?>


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
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">


    <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/account_custom.css')}}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.js')}}"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{asset('assets/js/toastr.min.js')}}"></script>


    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/account_custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.esc.js') }}"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>

    <script src="{{asset('assets/js/datatables.js')}}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('head')
</head>

<body>
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="{{action('App\Http\Controllers\DashboardController@getViewDashboard')}}"
           class="d-flex align-items-center logo justify-content-center">
            <img class="d-none d-lg-block" src="{{ asset('assets/img/logo.png') }}" alt="">
            <img class="d-lg-none" src="{{ asset('assets/img/logo2.png') }}" alt="">
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle " href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li>

            <li class="nav-item dropdown">

                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-primary badge-number">4</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header">
                        You have 4 new notifications
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="notification-item">
                        <i class="bi bi-exclamation-circle text-warning"></i>
                        <div>
                            <h4>Lorem Ipsum</h4>
                            <p>Quae dolorem earum veritatis oditseno</p>
                            <p>30 min. ago</p>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="notification-item">
                        <i class="bi bi-x-circle text-danger"></i>
                        <div>
                            <h4>Atque rerum nesciunt</h4>
                            <p>Quae dolorem earum veritatis oditseno</p>
                            <p>1 hr. ago</p>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="notification-item">
                        <i class="bi bi-check-circle text-success"></i>
                        <div>
                            <h4>Sit rerum fuga</h4>
                            <p>Quae dolorem earum veritatis oditseno</p>
                            <p>2 hrs. ago</p>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="notification-item">
                        <i class="bi bi-info-circle text-primary"></i>
                        <div>
                            <h4>Dicta reprehenderit</h4>
                            <p>Quae dolorem earum veritatis oditseno</p>
                            <p>4 hrs. ago</p>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="dropdown-footer">
                        <a href="#">Show all notifications</a>
                    </li>

                </ul>

            </li>

            <li class="nav-item dropdown">

                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-chat-left-text"></i>
                    <span class="badge bg-success badge-number">3</span>
                </a><!-- End Messages Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                    <li class="dropdown-header">
                        You have 3 new messages
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="message-item">
                        <a href="#">
                            <img src="{{ 'assets/img/messages-1.jpg' }}" alt="" class="rounded-circle">
                            <div>
                                <h4>Maria Hudson</h4>
                                <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                <p>4 hrs. ago</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="message-item">
                        <a href="#">

                            <img src="{{ asset('assets/img/messages-2.jpg') }}" alt=""
                                 class="rounded-circle">

                            <div>
                                <h4>Anna Nelson</h4>
                                <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                <p>6 hrs. ago</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="message-item">
                        <a href="#">
                            <img src="{{ 'assets/img/messages-3.jpg' }}" alt="" class="rounded-circle">
                            <div>
                                <h4>David Muldon</h4>
                                <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                <p>8 hrs. ago</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="dropdown-footer">
                        <a href="#">Show all messages</a>
                    </li>

                </ul>

            </li>
            @php

                $data = \Illuminate\Support\Facades\DB::table('account')
                            ->join('employees', 'account.employee_id', '=', 'employees.employee_id')
                            ->join('contacts', 'employees.id_contact', '=', 'contacts.id_contact')
                            ->join('job_detail', 'job_detail.employee_id', '=', 'employees.employee_id')

                            ->where(
                            'account.id_account',
                            \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID),
                            )
                            ->first();
                $info = \Illuminate\Support\Facades\DB::table('job_detail')
                            ->join('job_position', 'job_detail.id_job_position', '=', 'job_position.id_position')
                            ->where(
                                'job_detail.employee_id', $data->employee_id
                            )
                            ->first();
            @endphp
            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="{{$data->photo}}" alt="Profile" class="rounded-circle object-fit-cover" width="36"
                         height="36">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                   data-bs-toggle="dropdown">
                    @php
                        $photoPath = asset($data->photo);
                        $defaultPhoto = asset('assets/img/avt.png');
                        $photoExists =
                            !empty($data->photo) &&
                            file_exists(public_path($data->photo));
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
                        <span>@if($info)
                                {{$info->position_name}}
                            @endif</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center"

                           href="{{ action('App\Http\Controllers\ProfileController@getViewProfile', ['employee_id'=>$data->employee_id]) }}">

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
        <li class="nav-item">
            <a class="nav-link "
               href="{{ action('App\Http\Controllers\DashboardController@getViewDashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">

            @if(in_array(\Illuminate\Support\Facades\Session::get(StaticString::PERMISSION), array(1,2)))
                <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Employees</span><i
                        class="bi bi-chevron-down ms-auto"></i>

            <a class="nav-link collapsed" data-bs-target="#organization-nav" data-bs-toggle="collapse"
               href="#">
                <i class="bi bi-building"></i><span>Organization</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="organization-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-sub-link" href="{{ action('App\Http\Controllers\DepartmentController@getView') }}">
                        <i class="bi bi-circle"></i><span>Deparment</span>
                    </a>
                </li>
                <li>
                    <a class="nav-sub-link" href="#">
                        <i class="bi bi-circle"></i><span>Designation</span>
                    </a>
                </li>
            </ul>
        </li>


        @if (in_array(\Illuminate\Support\Facades\Session::get(StaticString::PERMISSION), [1, 2]))
            <li class="nav-heading">HR Manager</li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse"
                   href="#">
                    <i class="bi bi-people"></i><span>Employees</span><i class="bi bi-chevron-down ms-auto"></i>

                </a>
                <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a class="nav-sub-link" href="{{ action('App\Http\Controllers\EmployeesController@getView') }}">
                            <i class="bi bi-circle"></i><span>Employees</span>
                        </a>
                    </li>
                    <li>

                        <a href="{{action('App\Http\Controllers\AccountController@getView')}}">
                            <i class="bi bi-circle"></i><span>History</span>
                        </a>
                    </li>
                </ul>
        @endif

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#projects-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Projects</span><i class="bi bi-chevron-down ms-auto"></i>

                        <a class="nav-sub-link" href="#">
                            <i class="bi bi-circle"></i><span>Disciplinary</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-sub-link" href="#">
                            <i class="bi bi-circle"></i><span>Inactive User</span>
                        </a>
                    </li>
                </ul>
            </li>
            @if (\Illuminate\Support\Facades\Session::get(StaticString::PERMISSION) == 1)

                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#account-nav" data-bs-toggle="collapse"
                       href="#">
                        <i class="bi bi-person"></i><span>Account</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="account-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <li>

                            <a class="nav-sub-link"
                               href="{{ action('App\Http\Controllers\AccountController@getView') }}">

                                <i class="bi bi-circle"></i><span>Info</span>
                            </a>
                        </li>
                        <li>

                            <a class="nav-sub-link"
                               href="{{ action('App\Http\Controllers\AccountController@getView') }}">

                                <i class="bi bi-circle"></i><span>History</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        @endif

        @php
            $data = \Illuminate\Support\Facades\DB::table('account')
                ->join('employees', 'account.employee_id', '=', 'employees.employee_id')
                ->join('job_detail', 'job_detail.employee_id', '=', 'employees.employee_id')
                ->where(
                    'id_account',
                    \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID),
                )
                ->first();
        @endphp

        <li class="nav-item">

            <a class="nav-link collapsed" data-bs-target="#attendance-nav" data-bs-toggle="collapse"
               href="#">
                <i class="bi bi-calendar-check"></i><span>Attendance</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="attendance-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-sub-link" href="{{ action('App\Http\Controllers\AttendanceController@getView') }}">
                        <i class="bi bi-circle"></i><span>Attendance List</span>
                    </a>
                </li>
                <li>
                    <a class="nav-sub-link"
                       href="{{ action('App\Http\Controllers\AttendanceController@addAttendanceView') }}">
                        <i class="bi bi-circle"></i><span>Add Attendance</span>
                    </a>
                </li>
                <li>
                    <a class="nav-sub-link" href="#">
                        <i class="bi bi-circle"></i><span>Attendance Report</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#leave-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person-fill-x"></i><span>Leave</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="leave-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-sub-link" href="{{route('holidays.index')}}">
                        <i class="bi bi-circle"></i><span>Holiday</span>
                    </a>
                </li>
                <li>
                    <a class="nav-sub-link" href="{{route('leave-type.index')}}">
                        <i class="bi bi-circle"></i><span>Leave Type</span>
                    </a>
                </li>
                <li>
                    <a class="nav-sub-link" href="{{route('leave-application.index')}}">
                        <i class="bi bi-circle"></i><span>Leave Application</span>
                    </a>
                </li>
                <li>
                    <a class="nav-sub-link" href="{{route('earn-leave.index')}}">
                        <i class="bi bi-circle"></i><span>Earned Leave</span>
                    </a>
                </li>

                <li>
                    <a class="nav-sub-link" href="{{route('leave-report.index')}}">
                        <i class="bi bi-circle"></i><span>Leave Report</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#kpi-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person-fill-x"></i><span>KPI</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="kpi-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-sub-link" href="">
                        <i class="bi bi-circle"></i><span>KPI</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ action('App\Http\Controllers\TeamController@getView') }}">
                <i class="bi bi-people"></i><span>Team List</span>
            </a>
        </li>

        <li class="nav-heading">Customer Manager</li>
        <li class="nav-item">
            <div class="nav-link collapsed" data-bs-target="#customer-nav" data-bs-toggle="collapse"
               href="">
                <i class="bi bi-person"></i><span>Customer</span><i class="bi bi-chevron-down ms-auto"></i>
            </div>
            <ul id="customer-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-sub-link" href="{{action('App\Http\Controllers\CustomerController@getView')}}">
                        <i class="bi bi-circle"></i><span>Customers</span>
                    </a>
                </li>
                <li>
                    <a class="nav-sub-link" href="#">
                        <i class="bi bi-circle"></i><span>Customer Accounts</span>
                    </a>
                </li>
                <li>
                    <a class="nav-sub-link" href="#">
                        <i class="bi bi-circle"></i><span>Customer Support</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-heading">Project Management</li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#projects-nav" data-bs-toggle="collapse"
               href="#">
                <i class="bi bi-folder"></i><span>Projects</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="projects-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-sub-link" href="{{ action('\App\Http\Controllers\ProjectController@getView') }}">

                        <i class="bi bi-circle"></i><span>Projects</span>
                    </a>
                </li>
                <li>
                    <a href="{{ action([\App\Http\Controllers\ProjectController::class, 'getView']) }}">
                        <i class="bi bi-circle"></i><span>Project Manager</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-circle"></i><span>Task List</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="bi bi-circle"></i><span>Field Visit</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-heading">myXteam Manager</li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#myxteam-nav" data-bs-toggle="collapse"
               href="#">
                <i class="bi bi-folder"></i><span>myXteam</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="myxteam-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-sub-link" href="{{ action('\App\Http\Controllers\MyXteamController@getView') }}">

                        <i class="bi bi-circle"></i><span>Teams</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-heading">Warehouse Management</li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#inventory-nav" data-bs-toggle="collapse"
               href="#">
                <i class="bi bi-boxes"></i><span>Inventory</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="inventory-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-sub-link"
                       href="{{ action('App\Http\Controllers\InventoryManagementController@getView') }}">
                        <i class="bi bi-circle"></i><span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a class="nav-sub-link"
                       href="{{ action('App\Http\Controllers\MaterialsController@getView') }}">
                        <i class="bi bi-basket-fill"></i><span>Material Management</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-heading">Education</li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#performance-nav" data-bs-toggle="collapse"
               href="#">
                <i class="bi bi-mortarboard"></i><span>Education</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="performance-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-sub-link" href="">
                        <i class="bi bi-circle"></i><span>Workshops</span>
                    </a>
                </li>
                <li>
                    <a class="nav-sub-link" href="">
                        <i class="bi bi-circle"></i><span>Guidelines</span>
                    </a>
                </li>
                <li>
                    <a class="nav-sub-link" href="">
                        <i class="bi bi-circle"></i><span>Tests</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#internal-certificates-nav" data-bs-toggle="collapse"
               href="#">
                <i class="bi bi-clipboard"></i><span>Internal Certificates</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="internal-certificates-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-sub-link" href="">
                        <i class="bi bi-circle"></i><span>Internal Certificates</span>
                    </a>
                </li>
                <li>
                    <a class="nav-sub-link" href="">
                        <i class="bi bi-circle"></i><span>Internal Certificates Types</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-heading">Pages</li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#proposal-nav" data-bs-toggle="collapse"
               href="#">
                <i class="bi bi-gear-wide-connected"></i><span>Proposal</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="proposal-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-sub-link" href="">
                        <i class="bi bi-circle"></i><span>Proposal Application</span>
                    </a>
                </li>
                <li>
                    <a class="nav-sub-link" href="">
                        <i class="bi bi-circle"></i><span>Proposal Types</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#utilities-nav" data-bs-toggle="collapse"
               href="#">
                <i class="bi bi-gear-wide-connected"></i><span>Utilities</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="utilities-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-sub-link" href="{{action('App\Http\Controllers\AccountController@loginHistory')}}">
                        <i class="bi bi-circle"></i><span>Activity Log</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link " href="#">
                <i class="bi bi-clipboard2-fill"></i>
                <span>Notice</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link " href="{{ route('settings.view') }}">
                <i class="bi bi-gear-fill"></i>
                <span>Settings</span>
            </a>
            <ul id="projects-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ action([\App\Http\Controllers\ProjectListController::class, 'getView']) }}">
                        <i class="bi bi-circle"></i><span>Project List</span>
                    </a>
                </li>
                <li>
                    <a href="{{ action([\App\Http\Controllers\ProjectListController::class, 'getView']) }}">
                        <i class="bi bi-circle"></i><span>Project Manager</span>
                    </a>
                </li>
            </ul>
        </li>


        <a class="nav-link collapsed" href="{{action('App\Http\Controllers\MaterialsController@getView')}}">
            <i class="bi bi-basket-fill"></i><span>Material Management</span>
        </a>
        </li>
    </ul>

</aside>

<main id="main" class="main">
    @yield('contents')
</main>


{{-- <footer id="footer" class="footer"> --}}
{{-- <div class="copyright"> --}}
{{-- &copy; Copyright <strong><span>Ventech</span></strong>. All Rights Reserved --}}
{{-- </div> --}}
{{-- </footer> --}}


<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>


<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

</body>
<script src="{{ asset('assets/js/main.js') }}"></script>

</html>
@yield('script')
<script>
    crsf = $('meta[name="csrf-token"]').attr('content');
    account_id = {{ \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID) }};
</script>
