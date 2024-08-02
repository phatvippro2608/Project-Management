@extends('auth.main')

@section('head')
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
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
                            UI/UX Design Team<br>
                            Web Designer<br>
                            Employee ID: {{ $employee->employee_code }}<br>
                            Date of Join:
                            @if (empty($dateOfJoin->start_date))
                                No information
                            @else
                                {{ $dateOfJoin->start_date }}
                            @endif
                        </p>
                    </div>
                </div>
                <button class="btn btn-primary mt-3">Send Message</button>
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
                </ul>
            </div>
        </div>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Projects</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Bank & Statutory <small class="text-muted">(Admin Only)</small></a>
            </li>
        </ul>
    </div>

    <div class="row content">
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
                <h5>Experience</h5>
            </div>
            <div class="card-body p-3">
                <p><strong>Web Designer at Ron-tech</strong><br>Jan 2013 - Present (6 years 2 months)</p>
                <p><strong>Web Designer at Dalt Technology</strong><br>Jan 2013 - Present (6 years 2 months)</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Education Information</h5>
            </div>
            <div class="card-body p-3">
                <p><strong>International College of Arts and Science (PG)</strong><br>
                    MSc Computer Science<br>
                    2000 - 2003</p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
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

            // Xử lý khi nhấp vào các liên kết trong nav
            $('.nav-link').click(function() {
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>
@endsection
