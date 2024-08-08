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
                                <i class="bi bi-book"></i>
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
                                <i class="bi bi-book-fill"></i>
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
                                <i class="fa-solid fa-certificate"></i>
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
                    <div class="col-md-6 ms-auto">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search Courses">
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
                            <td>{{$course->start_date}}</td>
                            <td>{{$course->end_date}}</td>
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
                <div class="col-md-6 ms-auto">
                        <input type="text" id="completedCoursesSearch" class="form-control" placeholder="Search Courses">
                    </div>
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
                                    <td>{{ $course->start_date }}</td>
                                    <td>{{ $course->end_date }}</td>
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
                <div class="col-md-6 ms-auto">
                        <input type="text" id="notCompletedCoursesSearch" class="form-control" placeholder="Search Courses">
                    </div>
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
                                    <td>{{ $course->start_date }}</td>
                                    <td>{{ $course->end_date }}</td>
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
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Search Functionality for Courses Completed
        $('#completedCoursesSearch').on('keyup', function() {
            let filter = $(this).val().toLowerCase();
            $('#completed-courses-table tbody tr').each(function() {
                let rowText = $(this).text().toLowerCase();
                $(this).toggle(rowText.indexOf(filter) > -1);
            });
        });

        // Search Functionality for Courses Not Completed
        $('#notCompletedCoursesSearch').on('keyup', function() {
            let filter = $(this).val().toLowerCase();
            console.log('Filtering completed courses with:', filter);

            $('#not-completed-courses-table tbody tr').each(function() {
                let rowText = $(this).text().toLowerCase();
                $(this).toggle(rowText.indexOf(filter) > -1);
            });
        });

        // Filter completed courses based on progress
        function filterCompletedCourses() {
            var rows = document.querySelectorAll('#completed-courses-table .course-row');
            rows.forEach(function (row) {
                var progress = row.getAttribute('data-progress');
                if (progress == '100') {
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
                if (progress < '100') {
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

        // Sorting Function
        function sortTable(table, column, order) {
            let rows = table.find('tbody tr').get();

            rows.sort(function(a, b) {
                let keyA = $(a).children('td').eq(column).text().toUpperCase();
                let keyB = $(b).children('td').eq(column).text().toUpperCase();

                // Convert to numbers if the column is 'progress'
                if (column === 2) { // Index of 'Progress'
                    keyA = parseInt(keyA);
                    keyB = parseInt(keyB);
                }
                // Convert to dates if the column is 'Start To' or 'From'
                else if (column === 3 || column === 4) { // Index of 'Start To' and 'From'
                    keyA = new Date(keyA);
                    keyB = new Date(keyB);
                }

                if (order === 'asc') {
                    return (keyA < keyB) ? -1 : (keyA > keyB) ? 1 : 0;
                } else {
                    return (keyA > keyB) ? -1 : (keyA < keyB) ? 1 : 0;
                }
            });

            $.each(rows, function(index, row) {
                table.children('tbody').append(row);
            });
        }

        // Click Event for Sorting
        $('.sortable').on('click', function() {
            let table = $(this).closest('table');
            let index = $(this).index();
            let currentOrder = $(this).data('order') || 'asc';
            let newOrder = currentOrder === 'asc' ? 'desc' : 'asc';

            $(this).data('order', newOrder);
            sortTable(table, index, newOrder);

            // Toggle sorting indicator
            $(this).find('i').removeClass('bi-sort bi-sort-up bi-sort-down');
            $(this).find('i').addClass(newOrder === 'asc' ? 'bi-sort-up' : 'bi-sort-down');
        });

        // General search functionality for all tabs
        $('#notCompletedCoursesSearch').on('keyup', function() {
            let filter = $(this).val().toLowerCase();
            $('#coursesNotCompleted tbody tr').each(function() {
                let rowText = $(this).text().toLowerCase();
                $(this).toggle(rowText.indexOf(filter) > -1);
            });
        });
        $('#completedCoursesSearch').on('keyup', function() {
            let filter = $(this).val().toLowerCase();
            $('#coursesCompleted tbody tr').each(function() {
                let rowText = $(this).text().toLowerCase();
                $(this).toggle(rowText.indexOf(filter) > -1);
            });
        });
        $('#searchInput').on('keyup', function() {
            let filter = $(this).val().toLowerCase();
            $('#CourseTable tbody tr').each(function() {
                let rowText = $(this).text().toLowerCase();
                $(this).toggle(rowText.indexOf(filter) > -1);
            });
        });
    });
</script>
@endsection
