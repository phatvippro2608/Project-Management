@extends('auth.main')

@section('contents')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/buttons.dataTables.min.css') }}">
    <style>
        .folded-corner {
            position: relative;
            background-color: white;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .folded-corner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 20px;
            height: 20px;
            background-color: #1472e5;
            clip-path: polygon(0 0, 100% 0, 0 100%);
        }

        .btn.custom-btn {
            background-color: #007bff !important;
            color: #fff !important;
            border-color: #007bff !important;
            border-radius: 5px !important;
        }

        .btn.custom-btn:hover {
            background-color: #0056b3 !important;
            border-color: #004085 !important;
        }

        .text-success {
          font-weight: bold !important;
        }
    </style>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center bg-white p-3 mb-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-bag-check text-primary me-2"></i>
                <h3 class="text-primary m-0">Leave Report</h3>
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
            </form>
        </div>

        <div class="folded-corner bg-white p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Report List</h5>
            </div>
            <hr>
            <div style="height: 100vh;">
                <table id="leavereportsTable" class="table table-bordered mt-3 mb-3">
                    <thead>
                        <tr>
                            <th>PIN</th>
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

    <!-- Add Leave Type Modal -->
    <div class="modal fade" id="addLeaveTypesModal" tabindex="-1" aria-labelledby="addLeaveTypesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLeaveTypesModalLabel">Add New Leave Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addLeaveTypeForm">
                        @csrf
                        <div class="mb-3">
                            <label for="leave_type" class="form-label">Leave Type</label>
                            <input type="text" class="form-control" id="leave_type" name="leave_type" required>
                        </div>
                        <div class="mb-3">
                            <label for="number_of_days" class="form-label">Number Of Days</label>
                            <input type="number" class="form-control" id="number_of_days" name="number_of_days"
                                min="0" step="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Leave Type</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="editLeaveTypesModal" tabindex="-1" aria-labelledby="editLeaveTypesModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLeaveTypesModalLabel">Edit Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editHolidayForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editHolidayId" name="id">
                        <div class="mb-3">
                            <label for="edit_leave_type" class="form-label">Leave Type</label>
                            <input type="text" class="form-control" id="edit_leave_type" name="leave_type" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_number_of_days" class="form-label">Number Of Days</label>
                            <input type="number" class="form-control" id="edit_number_of_days" name="number_of_days"
                                min="0" step="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Edit Holiday</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // var table = $('#leavetypesTable').DataTable();
            var table = $('#leavereportsTable').DataTable({
                dom: '<"d-flex justify-content-between align-items-center"<"left-buttons"B><"right-search"f>>rtip',
                buttons: [{
                        extend: 'copy',
                        text: 'Copy',
                        className: 'btn custom-btn me-2'
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'btn custom-btn me-2'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn custom-btn me-2'
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'btn custom-btn me-2'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn custom-btn'
                    }
                ]
            });

            table.buttons().container().appendTo('#leavereportsTable_wrapper .col-md-6:eq(0)');

            $('#searchForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('leave-report.search') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        table.clear().draw();
                        response.forEach(function(report) {
                            table.row.add([
                                report.pin,
                                report.employee.last_name + ' ' + report
                                .employee.first_name,
                                report.leave_type.leave_type,
                                report.apply_date,
                                report.duration,
                                report.start_date,
                                report.end_date,
                                '<span class="' + (report.leave_status ===
                                    'approved' ?
                                    'text-success' : '') +
                                '">' +
                                report.leave_status + '</span>',
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-success shadow-none accept-btn" data-id="' +
                                report.id +
                                '"><i class="bi bi-check-circle"></i></button> | ' +
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn" data-id="' +
                                report.id +
                                '"><i class="bi bi-trash3"></i></button>'
                            ]).draw();
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

            $('#leavereportsTable').on('click', '.delete-btn', function() {
                var reportId = $(this).data('id');
                var row = $(this).parents('tr');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to delete this leave request?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url('leave-applications') }}/' + reportId,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    table.row(row).remove().draw();
                                    toastr.success(response.message,
                                        "Deleted successfully");
                                } else {
                                    toastr.error("Failed to delete the leave request.",
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
