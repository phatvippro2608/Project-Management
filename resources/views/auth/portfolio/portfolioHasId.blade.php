@extends('auth.main')

@section('head')
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        .no-select {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        .card-header {
            background-color: #f8f9fa;
        }

        .nav-link.active {
            border-bottom: 3px solid #007bff;
        }

        .status-dot {
            height: 10px;
            width: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        #content,
        #card,
        #bank-content {
            display: none;
        }

        #content.active,
        #card.active,
        #bank-content.active {
            display: flex;
        }
    </style>
@endsection

@section('contents')
    <div class="pagetitle">
        <h1>Portfolio</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('portfolio') }}">Portfolio</a>
                </li>
                <li class="breadcrumb-item active">{{ $employee->employee_code }}</li>
            </ol>
        </nav>
    </div>

    <div class="card mb-4">
        <div class="card-body p-3 d-flex justify-content-between align-items-center mt-3 mx-3">
            <div class="col-4 mt-3 border-end">
                <div class="d-flex align-items-center">
                    @if (empty($employee->photo) || !file_exists(public_path($employee->photo)))
                        <img src="{{ asset('assets/img/avt.png') }}" alt="Profile Image" class="profile-img">
                    @else
                        <img src="{{ asset($employee->photo) }}" alt="Profile Image" class="profile-img">
                    @endif
                    <div class="ms-3">
                        <h4>{{ $employee->last_name . ' ' . $employee->first_name }}</h4>
                        <p>
                            @if (empty($department->department_name))
                                No information
                            @else
                                {{ $department->department_name }}
                            @endif
                            <br>
                            @if (empty($job_title->job_title))
                                No information
                            @else
                                {{ $job_title->job_title }}
                            @endif
                            <br>
                            Employee ID: {{ $employee->employee_code }}
                            <br>
                            Date of Join:
                            @if (empty($dateOfJoin->start_date))
                                No information
                            @else
                                {{ $dateOfJoin->start_date }}
                            @endif
                            <br>
                            Status:
                            @if ($status == 1)
                                <i class="bi bi-circle-fill text-primary"></i> In project
                            @elseif ($status == 2)
                                <i class="bi bi-circle-fill text-success"></i> Free
                            @elseif ($status == 3)
                                <i class="bi bi-circle-fill text-warning"></i> On leave
                            @elseif ($status == 4)
                                <i class="bi bi-circle-fill text-secondary"></i> Quit
                            @endif
                        </p>
                    </div>
                </div>
                <button class="btn btn-primary mt-3">Send Message</button>
                <a href="{{ route('certificate') }}">
                    <button class="btn btn-success mt-3" id="ButtonGetCettificate">Get Certificate</button>
                </a>
            </div>
            <div class="col">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Phone:</strong>
                        @if (!empty($contact->phone_number))
                            {{ $contact->phone_number }}
                        @else
                            No information
                        @endif
                    </li>
                    <li class="list-group-item">
                        <strong>Email:</strong>
                        @if (!empty($account->email))
                            {{ $account->email }}
                        @else
                            No information
                        @endif
                    </li>
                    <li class="list-group-item">
                        <strong>Birthday:</strong>
                        @if (!empty($employee->date_of_birth))
                            {{ \Carbon\Carbon::parse($employee->date_of_birth)->format('jS F Y') }}
                        @else
                            No information
                        @endif
                    </li>
                    <li class="list-group-item">
                        <strong>Address:</strong>
                        @if (!empty($contact->permanent_address))
                            {{ $contact->permanent_address }}
                        @else
                            No information
                        @endif
                    </li>
                    <li class="list-group-item"><strong>Gender:</strong>
                        @if ($employee->gender == '0')
                            Male
                        @else
                            Female
                        @endif
                    </li>
                    <li class="list-group-item"><strong>Degree:</strong>
                        @if (!empty($employee_degree->employee_degrees_name))
                            {{ $employee_degree->employee_degrees_name }}
                        @else
                            No information
                        @endif
                    </li>
                </ul>
            </div>
        </div>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <span class="nav-link active no-select" id="profile" data-target="#content">Profile</span>
            </li>
            <li class="nav-item">
                <span class="nav-link no-select" id="projects" data-target="#card">Projects</span>
            </li>
            <li class="nav-item">
                <span class="nav-link no-select" id="bank" data-target="#bank-content">Bank & Statutory</span>
            </li>
        </ul>
    </div>

    <div class="row content" id="content">

        <div class="card mb-4">
            <div class="card-header">
                <h5>Recognitions</h5>
            </div>
            <div class="card-body p-3">
                <table class="table table-hover" id="recognitionsTable">
                    <thead>
                        <tr>
                            <th scope="col">Department</th>
                            <th scope="col">Date</th>
                            <th scope="col">Evaluate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recognitions as $recognition)
                            @if ($recognition->recognition_type && $recognition->recognition_date && $recognition->department)
                                <tr>
                                    <td>{{ $recognition->department->department_name }}</td>
                                    <td>{{ $recognition->recognition_date }}
                                    </td>
                                    <td>{{ $recognition->recognition_type->recognition_type_name }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Disciplinaries</h5>
            </div>
            <div class="card-body p-3">
                <table class="table table-hover" id="disciplinariesTable">
                    <thead>
                        <tr>
                            <th scope="col">Department</th>
                            <th scope="col">Date</th>
                            <th scope="col">Evaluate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($disciplinaries as $disciplinarie)
                            @if ($disciplinarie->disciplinarie_type && $disciplinarie->disciplinary_date && $disciplinarie->department)
                                <tr>
                                    <td>{{ $disciplinarie->department->department_name }}</td>
                                    <td>{{ $disciplinarie->disciplinary_date }}
                                    </td>
                                    <td>{{ $disciplinarie->disciplinarie_type->disciplinarie_type_name }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Bank Information</h5>
            </div>
            <div class="card-body p-3">
                <p>Bank name: ICICI Bank</p>
                <p>Bank account No: 159843014641</p>
                <p>IFSC Code: ICI24504</p>
                <p>PAN No: TC000Y56</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Personal Information</h5>
            </div>
            <div class="card-body p-3">
                <p>Passport No.: 9876543210</p>
                <p>Passport Exp Date: 9876543210</p>
                <p>Nationality: Indian</p>
                <p>Marital status: Married</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Experience</h5>
            </div>
            <div class="card-body p-3">
                <p>
                    <strong>...</strong><br>...
                </p>
                <p>
                    <strong>Web Designer at Ron-tech</strong><br>Jan 2013 - Present (6 years 2 months)
                </p>
                <p>
                    <strong>Web Designer at Dalt Technology</strong><br>Jan 2013 - Present (6 years 2 months)
                </p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Education Information</h5>
            </div>
            <div class="card-body p-3">
                <p>
                    <strong>...</strong><br>
                    ...<br>
                    ...
                </p>
                <p>
                    <strong>International College of Arts and Science (PG)</strong><br>
                    MSc Computer Science<br>
                    2000 - 2003
                </p>
            </div>
        </div>
    </div>

    <div class="card" id="card">
        <div class="card-body">
            <div class="col p-3">
                <table id="table--project" class="table table-hover no-select">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col">Project ID</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">StartDate</th>
                            <th scope="col">EndDate</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr data-href="{{ url('project/' . $project->project_id) }}" role="button">
                                <td class="text-center">{{ $project->project_id }}</td>
                                <td>{{ $project->project_name }}</td>
                                <td>{{ $project->project_date_start }}</td>
                                <td>{{ $project->project_date_end }}</td>
                                <th>
                                    <span
                                        class="badge rounded-pill
                                    @switch($project->phase_id)
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
                                        {{ $project->phase_name_eng }}
                                    </span>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bank-content" id="bank-content">
    </div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        function content() {
            // Tạo hai thẻ div với lớp col-md-6
            var $col1 = $('<div class="col-md-6"></div>');
            var $col2 = $('<div class="col-md-6"></div>');

            // Thêm các thẻ div vào row
            $('.content').prepend($col1).append($col2);
            $col1.css('height', 'max-content');
            $col2.css('height', 'max-content');
            Array.from(document.querySelectorAll('.content .card')).forEach(element => {
                var height1 = $col1.outerHeight();
                var height2 = $col2.outerHeight();
                if (height1 > height2) {
                    $col2.append(element);
                } else {
                    $col1.append(element);
                }
            });
        }

        // Khởi tạo DataTable
        $('#recognitionsTable').DataTable({
            "pagingType": "simple_numbers",
            "pageLength": 10,
            "lengthChange": false,
            "searching": false,
            "order": [
                [1, 'desc']
            ]
        });

        $('#disciplinariesTable').DataTable({
            "pagingType": "simple_numbers",
            "pageLength": 10,
            "lengthChange": false,
            "searching": false,
            "order": [
                [1, 'desc']
            ]
        });

        $('#table--project').DataTable({
            "order": [
                [3, 'desc']
            ]
        });

        $('#content').addClass('active');
        content();

        $('.card .nav-link').click(function() {
            $('.nav-link').removeClass('active');
            $('.row.content, .card, .bank-content').removeClass('active');
            $(this).addClass('active');
            var target = $(this).data('target');
            $(target).addClass('active');
        });

        $('#table--project').on('click', 'tr', function() {
            var url = $(this).data('href');
            if (url) {
                window.location.href = url;
            }
        });

        $('#ButtonGetCettificate').click(function() {

        })
    });
</script>
@endsection