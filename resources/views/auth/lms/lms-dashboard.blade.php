@extends('auth.main-lms')

@section('head')
@endsection

@section('contents')
<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>
<div class="row gx-5">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-xxl-3 col-xl-4 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Total Courses</b></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-list-task"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{$course_c}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-4 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Your Course</b></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{$count}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-4 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Your Certificates</b></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{$certificate_c}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card p-2 rounded-4 border">
    <div class="card-header py-0">
        <div class="card-title my-3 p-0">View Summary</div>
    </div>
    
    <div class="card-body">
        <div class="row gx-3 my-3">
            <div class="col-md-6 m-0">
                <div class="btn btn-success mx-2 btn-export">
                    <i class="bi bi-file-earmark-arrow-down pe-2"></i>
                    Export
                </a>
                </div>
            </div>
            <div class="col-md-6">
                <input type="text" id="searchInput" class="form-control" placeholder="Search Courses">
            </div>
        </div>
        <table id="employeesTable" class="table table-hover table-borderless">
            <thead class="table-light">
                <tr>
                    <th class="text-center">ID Source</th>
                    <th>Name source</th>
                    <th>Progress</th>
                    <th>Start To</th>
                    <th>From</th>
                    <th>Certificate</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="employeesTableBody">
                @foreach($data as $course)
                <tr class="clickable-row" data-href="/lms/course/{{ $course->course_id }}">
                    <td class="text-center">{{$course->course_id}}</td>
                    <td>{{$course->course_name}}</td>
                    <td>{{$course->progress}}%</td>
                    <td>{{$course->start_date}}</td>
                    <td>{{$course->end_date}}</td>
                    <td></td>
                    <td>
                        <button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none at4">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@endsection
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#employeesTable tbody tr').forEach(row => {
            row.addEventListener('click', function() {
                const href = this.getAttribute('data-href');
                if (href) {
                    window.location.href = href;
                }
            });
        });

        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            const tableRows = document.querySelectorAll('#employeesTable tbody tr');

            tableRows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                let match = false;

                for (let i = 0; i < cells.length; i++) {
                    if (cells[i].innerText.toLowerCase().includes(filter)) {
                        match = true;
                        break;
                    }
                }

                row.style.display = match ? '' : 'none';
            });
        });
    });
</script>
@endsection
