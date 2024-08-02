@extends('auth.main')

@section('contents')
    <style>
        #leavereportsTable tbody tr:last-child{
            text-align: center;
        }
    </style>
    <div class="pagetitle">
        <h1>Leave Report</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Leave Report</li>
            </ol>
        </nav>
    </div>
    <div class="rounded-4">
        <div class="card p-2">
            <div class="card-header py-0">
                <div class="card-title my-3 p-0">Leave Report</div>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a
                            href="{{ action('App\Http\Controllers\DashboardController@getViewDashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Leave Report</li>
                </ol>
            </nav>
        </div>

        <div class="container mt-3">
            <form id="searchForm" class="d-flex justify-content-start align-items-center mb-3">
                @csrf
                <div class="form-group me-2">
                    <label for="reportMonthYear" class="sr-only">Month-Year</label>
                    <input type="month" class="form-control" id="reportMonthYear" name="report_month_year" required>
                </div>
                <div class="form-group me-2">
                    <label for="employee" class="sr-only">Employee</label>
                    <select class="form-control" id="employee" name="employee_id" required>
                        <option value="" selected disabled>Select Employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id_employee }}">{{ $employee->last_name }}
                                {{ $employee->first_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Search</button>
                <div class="form-group me-2" style="margin-left: 0.5rem">
                    <button type="button" class="btn btn-secondary" id="refreshButton">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="folded-corner bg-white p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Report List</h5>
                <a class="btn btn-success mx-2" href="{{ route('leave-report.export') }}">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-file-earmark-arrow-down-fill pe-2"></i>
                        Export
                    </div>
                </div>
                <table id="leavereportsTable" class="table table-borderless table-hover align-middle">
                    <thead>
                    <tr>
                        <th>Employee_Code</th>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Apply Date</th>
                        <th>Duration</th>
                        <th>Start Leave</th>
                        <th>End Leave</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#leavereportsTable').DataTable();
            $('#refreshButton').on('click', function() {
                location.reload();
            });

            table.buttons().container().appendTo('#leavereportsTable_wrapper .col-md-6:eq(0)');

            function loadLeaveReports() {
                $.ajax({
                    url: '{{ route('leave-report.data') }}',
                    method: 'GET',
                    success: function(response) {
                        response.forEach(function(report) {
                            var applyDate = new Date(report.apply_date);
                            var formattedApplyDate = applyDate.getFullYear() + '-' +
                                ('0' + (applyDate.getMonth() + 1)).slice(-2) + '-' +
                                ('0' + applyDate.getDate()).slice(-2);

                            var statusClass = report.leave_status === 'approved' ?
                                'text-success' : 'text-danger';
                            var statusText = report.leave_status === 'approved' ?
                                '<strong>approved</strong>' : '<strong>not approved</strong>';

                            table.row.add([
                                report.employee.employee_code,
                                report.employee.last_name + ' ' + report.employee
                                .first_name,
                                report.leave_type.leave_type,
                                formattedApplyDate,
                                report.duration,
                                report.start_date,
                                report.end_date,
                                '<span class="' + statusClass + '">' + statusText +
                                '</span>',
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-success shadow-none accept-btn" data-id="' +
                                report.id +
                                '"><i class="bi bi-check-circle"></i></button>'
                            ]).draw(false);
                        });
                    },
                    error: function(xhr) {
                        toastr.error("An error occurred while loading data.", "Operation Failed");
                    }
                });
            }

            loadLeaveReports();

            $('#searchForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('leave-report.search') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        table.clear().draw();
                        response.forEach(function(report) {
                            var applyDate = new Date(report.apply_date);
                            var formattedApplyDate = applyDate.getFullYear() + '-' +
                                ('0' + (applyDate.getMonth() + 1)).slice(-2) + '-' +
                                ('0' + applyDate.getDate()).slice(-2);

                            var statusClass = report.leave_status === 'approved' ?
                                'text-success' : 'text-danger';
                            var statusText = report.leave_status === 'approved' ?
                                '<strong>approved</strong>' :
                                '<strong>not approved</strong>';

                            table.row.add([
                                report.employee.employee_code,
                                report.employee.last_name + ' ' + report
                                .employee.first_name,
                                report.leave_type.leave_type,
                                formattedApplyDate,
                                report.duration,
                                report.start_date,
                                report.end_date,
                                '<span class="' + statusClass + '">' +
                                statusText + '</span>',
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-success shadow-none accept-btn text-center" data-id="' +
                                report.id +
                                '"><i class="bi bi-check-circle"></i></button>'
                            ]).draw(false);
                        });
                    },
                    error: function(xhr) {
                        toastr.error("An error occurred.", "Error");
                    }
                });
            });

            $('#leavereportsTable').on('click', '.accept-btn', function() {
                var reportId = $(this).data('id');
                var row = $(this).closest('tr');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to approve this leave request?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url = "{{ route('leave-applications.approve', ':id') }}";
                        url = url.replace(':id', reportId);
                        $.ajax({
                            url: url,
                            method: 'PUT',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    var applyDate = new Date(response.leave_application
                                        .apply_date);
                                    var formattedDate = applyDate.getFullYear() + '-' +
                                        ('0' + (applyDate.getMonth() + 1)).slice(-2) +
                                        '-' +
                                        ('0' + applyDate.getDate()).slice(-2);

                                    row.find('td').eq(3).html(formattedDate);
                                    row.find('td').eq(7).html(
                                        '<span class="text-success"><strong>approved</strong></span>'
                                    );
                                    toastr.success(response.message,
                                        "Approved successfully");
                                } else {
                                    toastr.error("Failed to approve the leave request.",
                                        "Operation Failed");
                                }
                            },
                            error: function(xhr) {
                                toastr.error("An error occurred.", "Operation Failed");
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
@section('head')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.dataTables.css" />

    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.flash.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.print.min.js">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
