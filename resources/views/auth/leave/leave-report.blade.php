@extends('auth.main')

@section('contents')
    <style>
        table th,
        table td {
            text-align: center !important;
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
    <div class="row gx-3 my-3">
        <div class="col-md-9 d-flex m-0">
            <form id="searchForm" class="d-flex align-items-center">
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
                            <option value="{{ $employee->employee_id }}">{{ $employee->last_name }}
                                {{ $employee->first_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success"><i class="bi bi-search me-2"></i>Search</button>
                <button style="margin-left: 10px" type="button" class="btn btn-primary" onclick="location.reload();"><i
                        class="bi bi-arrow-clockwise me-2"></i>Refresh</button>
                <button style="margin-left: 10px" class="btn btn-success" id="exportExcelButton">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export
                </button>
            </form>

        </div>
    </div>
    <div class="card p-2 rounded-4 border">
        <div class="card-header py-0">
            <div class="card-title my-3 p-0">Leave Report</div>
        </div>
        <div class="card-body">
            <table id="leavereportsTable" class="table table-borderless table-hover">
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
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#leavereportsTable').DataTable({
                language: {
                    search: ""
                },
                initComplete: function(settings, json) {
                    $('.dt-search').addClass('input-group');
                    $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
                },
                responsive: true
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

                            var statusClass = report.leave_status === 'Approved' ?
                                'text-success' : 'text-danger';
                            var statusText = report.leave_status === 'Approved' ?
                                '<strong>Approved</strong>' : '<strong>Not approved</strong>';

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
                                report.leave_application_id +
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

                            var statusClass = report.leave_status === 'Approved' ?
                                'text-success' : 'text-danger';
                            var statusText = report.leave_status === 'Approved' ?
                                '<strong>Approved</strong>' :
                                '<strong>Not approved</strong>';

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
                                report.leave_application_id +
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
                                        '<span class="text-success"><strong>Approved</strong></span>'
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


            $('#exportExcelButton').on('click', function() {
                window.location.href = '{{ route('leave-report.export') }}';
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
