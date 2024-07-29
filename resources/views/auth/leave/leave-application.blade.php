@extends('auth.main')

@section('contents')
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
    </style>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center bg-white p-3 mb-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-megaphone text-primary me-2"></i>
                <h3 class="text-primary m-0">Application</h3>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a
                            href="{{ action('App\Http\Controllers\DashboardController@getViewDashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Application</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <button class="btn btn-primary" id="addApplicationBtn" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
                    <i class="bi bi-plus-circle"></i> Add Application
                </button>
{{--                <button class="btn btn-secondary" id="leaveApplicationBtn"><i class="bi bi-list"></i> Leave--}}
{{--                    Application</button>--}}
            </div>

        </div>
        <div class="folded-corner bg-white p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Application List</h5>
            </div>
            <hr>
            <div class="d-flex justify-content-start mb-3">
                <button class="btn btn-primary me-2" id="copyBtn">Copy</button>
                <button class="btn btn-primary me-2" id="exportCsvBtn">CSV</button>
                <button class="btn btn-primary me-2" id="exportExcelBtn">Excel</button>
                <button class="btn btn-primary me-2" id="exportPdfBtn">PDF</button>
                <button class="btn btn-primary" id="printBtn">Print</button>
            </div>
            <div style="height: 100vh;">
            <table id="applicationTable" class="table table-bordered mt-3 mb-3">
                <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>PIN</th>
                    <th>Leave Type</th>
                    <th>Apply Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Duration</th>
                    <th>Leave Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($leave_applications as $item)
                    <tr>
                        <td>{{$item->employee->first_name }} {{ $item->employee->last_name }}</td>
                        <td>{{$item->pin}}</td>
                        <td>{{$item->leaveType->leave_type }}</td>
                        <td>{{$item->apply_date}}</td>
                        <td>{{$item->start_date}}</td>
                        <td>{{$item->end_date}}</td>
                        <td>{{$item->duration}}</td>
                        <td>{{$item->leave_status}}</td>
                        <td>
                            <button
                                class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                data-id="{{$item->id}}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button
                                class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                data-id="{{$item->id}}">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


    </div>

    <!-- Add Application Modal -->
    <div class="modal fade" id="addApplicationModal" tabindex="-1" aria-labelledby="addApplicationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addApplicationModalLabel">Add New Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addApplicationForm">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Employee Name</label>
{{--                            <input type="text" class="form-control" id="employee_name" name="employee_name" required>--}}
                            <select class="form-select" aria-label="Default" name="employee_id">
                                <option value="">No select</option>
                                @foreach($employee_name as $item)
                                    <option value="{{$item->id_employee}}">{{$item->employee_code}}
                                        - {{$item->first_name}} {{$item->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">PIN</label>
                            <input type="text" class="form-control" id="pin" name="pin" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Leave Type</label>
{{--                            <input type="text" class="form-control" id="leave_type" name="leave_type" required>--}}
                            <select class="form-select" aria-label="Default" name="leave_type">
                                <option value="">No select</option>
                                @foreach($leave_type as $item)
                                    <option value="{{$item->id}}">{{$item->leave_type}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Apply Date</label>
                            <input type="date" class="form-control" id="apply_date" name="apply_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Duration</label>
                            <input type="text" class="form-control" id="duration" name="duration" required>
                        </div>
                        <div class="mb-3">
                            <label for="days" class="form-label">Leaves Status</label>
                            <input type="number" class="form-control" id="leave_status" name="leave_status" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="editApplicationModal" tabindex="-1" aria-labelledby="editApplicationModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editApplicationModalLabel">Edit Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editApplicationForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Employee Name</label>
                            {{--                            <input type="text" class="form-control" id="employee_name" name="employee_name" required>--}}
                            <select class="form-select" aria-label="Default" name="employee_id" id="edit_employee_id">
                                <option value="">No select</option>
                                @foreach($employee_name as $item)
                                    <option value="{{$item->id_employee}}">{{$item->employee_code}}
                                        - {{$item->first_name}} {{$item->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">PIN</label>
                            <input type="text" class="form-control" id="edit_pin" name="pin" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Leave Type</label>
                            {{--                            <input type="text" class="form-control" id="leave_type" name="leave_type" required>--}}
                            <select class="form-select" aria-label="Default" name="leave_type" id="edit_leave_type">
                                <option value="">No select</option>
                                @foreach($leave_type as $item)
                                    <option value="{{$item->id}}">{{$item->leave_type}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Apply Date</label>
                            <input type="date" class="form-control" id="edit_apply_date" name="apply_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="edit_start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="edit_end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Duration</label>
                            <input type="text" class="form-control" id="edit_duration" name="duration" required>
                        </div>
                        <div class="mb-3">
                            <label for="days" class="form-label">Leaves Status</label>
                            <input type="number" class="form-control" id="edit_leave_status" name="leave_status" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Edit Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#applicationTable').DataTable();

            $('#addApplicationForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('leave-application.add') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#addApplicationModal').modal('hide');
                            toastr.success(response.message, "Successful");
                            setTimeout(function () {
                                location.reload()
                            }, 500);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 400) {
                            toastr.error(xhr.responseJSON.message, "Error");
                        } else {
                            toastr.error("An error occurred", "Error");
                        }
                    }
                });
            });

            $('#applicationTable').on('click', '.edit-btn', function() {
                var applicationID = $(this).data('id');

                $('#editApplicationForm').data('id', applicationID);  // Gán ID vào form
                var url = "{{ route('leave-application.edit', ':id') }}";
                url = url.replace(':id', applicationID);
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        var data = response.leave_app;
                        $('#edit_employee_id').val(data.employee_id);
                        $('#edit_pin').val(data.pin);
                        $('#edit_leave_type').val(data.leave_type);
                        $('#edit_apply_date').val(data.apply_date);
                        $('#edit_start_date').val(data.start_date);
                        $('#edit_end_date').val(data.end_date);
                        $('#edit_duration').val(data.duration);
                        $('#edit_leave_status').val(data.leave_status);
                        $('#editApplicationModal').modal('show');
                    },
                    error: function(xhr) {}
                });
            });

            $('#editApplicationForm').submit(function(e) {
                e.preventDefault();
                var applicationID = $(this).data('id');  // Lấy ID từ form
                var url = "{{ route('leave-application.update', ':id') }}";
                url = url.replace(':id', applicationID);

                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#editApplicationModal').modal('hide');
                            $('#successModal').modal('show');
                            toastr.success(response.response, "Edit successful");
                            setTimeout(function () {
                                location.reload()
                            }, 500);
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Error");
                    }
                });
            });

            $('#applicationTable').on('click', '.delete-btn', function() {
                var applicationID = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url = "{{ route('leave-application.destroy', ':id') }}";
                        url = url.replace(':id', applicationID);
                        $.ajax({
                            url: url,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message,
                                        "Deleted successfully");
                                    setTimeout(function () {
                                        location.reload()
                                    }, 500);
                                } else {
                                    toastr.error("Failed to delete holiday.",
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
