@extends('auth.main-lms')

@section('head')
<script src="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css"></script>
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
                <div class="card border rounded-4">
                    <div class="card-body">
                        <h5 class="card-title"><b>Total Courses</b></h5>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-book fs-2 me-3"></i>
                            <h2 class="m-0">{{$course_c}}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-4 col-md-6">
                <div class="card border rounded-4">
                    <div class="card-body">
                        <h5 class="card-title"><b>Your Course</b></h5>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-book fs-2 me-3"></i>
                            <h2 class="m-0">{{$count}}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-4 col-md-6">
                <div class="card border rounded-4">
                    <div class="card-body">
                        <h5 class="card-title"><b>Your Certificates</b></h5>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-patch-check fs-2 me-3"></i>
                            <h2 class="m-0">{{$certificate_c}}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card p-2 rounded-4 border">
    <div class="card-body">
        <ul class="nav nav-tabs" id="lmsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="view-summary-tab" data-bs-toggle="tab" href="#view-summary" role="tab" aria-controls="view-summary" aria-selected="true">Your Courses</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="course-completed-tab" data-bs-toggle="tab" href="#course-completed" role="tab" aria-controls="course-completed" aria-selected="false">Courses Completed</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="course-not-completed-tab" data-bs-toggle="tab" href="#course-not-completed" role="tab" aria-controls="course-not-completed" aria-selected="false">Courses Not Completed</a>
            </li>
        </ul>
        <div class="tab-content" id="lmsTabsContent">
            <div class="tab-pane fade show active" id="view-summary" role="tabpanel" aria-labelledby="view-summary-tab">
                <div class="d-flex">
                    <div class="col-md-6">
                        <a href="{{ route('courses.export') }}" class="btn btn-success btn-sm ">
                            <i class="bi bi-file-earmark-arrow-down pe-2"></i>
                            Export
                        </a>
                    </div>
                    
                </div>
                <table id="CourseTable" class="table table-hover table-borderless">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center sortable" data-sort="id_source">ID Source <i class="bi bi-sort"></i></th>
                            <th class="sortable" data-sort="name_source">Name Source <i class="bi bi-sort"></i></th>
                            <th class="sortable" data-sort="progress">Progress <i class="bi bi-sort"></i></th>
                            <th class="sortable" data-sort="start_date">Start To <i class="bi bi-sort"></i></th>
                            <th class="sortable" data-sort="end_date">From <i class="bi bi-sort"></i></th>
                            <th class="sortable" data-sort="certificate">Certificate <i class="bi bi-sort"></i></th>
                            <th class="sortable" data-sort="type">Type <i class="bi bi-sort"></i></th>
                            <th class="align-center text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="CourseTableBody">
                        @foreach($data as $course)
                        <tr class="clickable-row" data-href="/lms/course/{{ $course->course_id }}">
                            <td class="text-center">{{$course->course_id}}</td>
                            <td>{{$course->course_name}}</td>
                            <td>{{$course->progress}}%</td>
                            <td>{{\Carbon\Carbon::parse($course->start_date)->format('d M Y')}}</td>
                            <td>{{\Carbon\Carbon::parse($course->end_date)->format('d M Y')}}</td>
                            <td></td>
                            <td>{{$course->course_type}}</td>
                            <td class="align-center text-center">
                                <button class="btn p-0 btn-primary border-0 bg-transparent text-info shadow-none at4">
                                    <i class="bi bi-info-circle-fill"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="course-completed" role="tabpanel" aria-labelledby="course-completed-tab">
                <div class="card p-2 rounded-4 border">
                    <div class="card-body">
                        <table id="coursesCompleted" class="table table-hover table-borderless">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">ID Source</th>
                                    <th>Name Source</th>
                                    <th>Progress</th>
                                    <th>Start To</th>
                                    <th>From</th>
                                    <th>Certificate</th>
                                    <th>Type</th>
                                    <th class="align-center text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="completed-courses-table">
                                @foreach($data as $course)
                                <tr class="course-row" data-progress="{{ $course->progress }}">
                                    <td class="text-center">{{ $course->course_id }}</td>
                                    <td>{{ $course->course_name }}</td>
                                    <td>{{ $course->progress }}%</td>
                                    <td>{{ \Carbon\Carbon::parse($course->start_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($course->end_date)->format('d M Y') }}</td>
                                    <td></td>
                                    <td>{{ $course->course_type }}</td>
                                    <td class="align-center text-center">
                                        <button class="btn p-0 btn-primary border-0 bg-transparent text-info shadow-none at4">
                                            <i class="bi bi-info-circle-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="course-not-completed" role="tabpanel" aria-labelledby="course-not-completed-tab">
                <div class="card p-2 rounded-4 border">
                    <div class="card-body">
                        <table id="coursesNotCompleted" class="table table-hover table-borderless">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">ID Source</th>
                                    <th>Name Source</th>
                                    <th>Progress</th>
                                    <th>Start To</th>
                                    <th>From</th>
                                    <th>Certificate</th>
                                    <th>Type</th>
                                    <th class="align-center text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="not-completed-courses-table">
                                @foreach($data as $course)
                                <tr class="course-row" data-progress="{{ $course->progress }}">
                                    <td class="text-center">{{ $course->course_id }}</td>
                                    <td>{{ $course->course_name }}</td>
                                    <td>{{ $course->progress }}%</td>
                                    <td>{{ \Carbon\Carbon::parse($course->start_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($course->end_date)->format('d M Y') }}</td>
                                    <td></td>
                                    <td>{{ $course->course_type }}</td>
                                    <td class="align-center text-center">
                                        <button class="btn p-0 btn-primary border-0 bg-transparent text-info shadow-none at4">
                                            <i class="bi bi-info-circle-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Filter completed courses based on progress
        function filterCompletedCourses() {
            var rows = document.querySelectorAll('#completed-courses-table .course-row');
            rows.forEach(function (row) {
                var progress = row.getAttribute('data-progress');
                if (progress == 100) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Filter not completed courses based on progress
        function filterNotCompletedCourses() {
            var rows = document.querySelectorAll('#not-completed-courses-table .course-row');
            rows.forEach(function (row) {
                var progress = row.getAttribute('data-progress');
                if (progress < 100) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Event listeners for tab changes
        $('#course-completed-tab').on('shown.bs.tab', function () {
            filterCompletedCourses();
        });

        $('#course-not-completed-tab').on('shown.bs.tab', function () {
            filterNotCompletedCourses();
        });
        new DataTable('#CourseTable');
        new DataTable('#coursesCompleted');
        new DataTable('#coursesNotCompleted');
    });
</script>
@endsection
