@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Leave Application</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Leave Application</li>
            </ol>
        </nav>
    </div>
    <div class="row gx-3 my-3">
        <div class="col-md-6 m-0">
            <button class="btn btn-primary me-2" id="addApplicationBtn" data-bs-toggle="modal"
                    data-bs-target="#addApplicationModal">
                <i class="bi bi-plus-lg me-2"></i> Add Application
            </button>
        </div>
    </div>
    <div class="card p-2 rounded-4 border">
        <div class="card-header py-0">
            <div class="card-title my-3 p-0">Application List</div>
        </div>
        <div class="card-body">
            <table id="applicationTable" class="table table-hover table-borderless">
                <thead class="table-light">
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
                @php
                    $data = \Illuminate\Support\Facades\DB::table('accounts')
                        ->join('employees', 'accounts.employee_id', '=', 'employees.employee_id')
                        ->join('job_details', 'job_details.employee_id', '=', 'employees.employee_id')
                        ->where(
                            'account_id',
                            \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID),
                        )
                        ->first();

//                    dd($data);
                @endphp
                @foreach ($leave_applications as $item)
                    @if($item->employee_id == $data->employee_id && $data->permission == 0)
                        <tr>
                            <td>{{ $item->employee->first_name }} {{ $item->employee->last_name }}</td>
                            <td>{{ $item->employee->employee_code }}</td>
                            <td>{{ $item->leaveType->leave_type }}</td>
                            <td>{{ $item->apply_date }}</td>
                            <td>{{ $item->start_date }}</td>
                            <td>{{ $item->end_date }}</td>
                            <td>{{ $item->duration }} days</td>
                            <td>{{ $item->leave_status }}</td>
                            <td>
                                <button
                                    class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                    data-id="{{ $item->leave_application_id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                |
                                <button
                                    class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                    data-id="{{ $item->leave_application_id }}">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </td>
                        </tr>
                    @elseif($data->permission != 0)
                        <tr>
                            <td>{{ $item->employee->first_name }} {{ $item->employee->last_name }}</td>
                            <td>{{ $item->employee->employee_code }}</td>
                            <td>{{ $item->leaveType->leave_type }}</td>
                            <td>{{ $item->apply_date }}</td>
                            <td>{{ $item->start_date }}</td>
                            <td>{{ $item->end_date }}</td>
                            <td>{{ $item->duration }} days</td>
                            <td>{{ $item->leave_status }}</td>
                            <td>
                                <button
                                    class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                    data-id="{{ $item->leave_application_id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                |
                                <button
                                    class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                    data-id="{{ $item->leave_application_id }}">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </td>
                        </tr>
                    @elseif($item->employee_id == null)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Application Modal -->
    <div class="modal fade" id="addApplicationModal" tabindex="-1" aria-labelledby="addApplicationModalLabel"
         aria-hidden="true">
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
                            {{--                            <input type="text" class="form-control" id="employee_name" name="employee_name" required> --}}
                            <select class="form-select" aria-label="Default" name="employee_id" id="add_employee_id">
                                @if($data->permission != 0)
                                    @foreach ($employee_name as $item)
                                        <option value="{{ $item->employee_id }}">{{ $item->employee_code }}
                                            - {{ $item->first_name }} {{ $item->last_name }}</option>
                                    @endforeach
                                @else
                                    <option value="{{ $data->employee_id }}">{{ $data->first_name }} {{ $data->last_name }}</option>
                                @endif
                            </select>
                        </div>


                        <div class="mb-3">
                            <label for="end_date" class="form-label">Leave Type</label>
                            {{--                            <input type="text" class="form-control" id="leave_type" name="leave_type" required> --}}
                            <select class="form-select" aria-label="Default" name="leave_type">
                                <option value="">No select</option>
                                @foreach ($leave_type as $item)
                                    <option value="{{ $item->leave_type_id }}">{{ $item->leave_type }}</option>
                                @endforeach
                            </select>
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
                            <input type="text" class="form-control" id="duration" name="duration" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="editApplicationModal" tabindex="-1" aria-labelledby="editApplicationModal"
         aria-hidden="true">
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

                            <label for="edit_employee_id" class="form-label">Employee Name</label>
                            <select class="form-select" aria-label="Default" name="employee_id" id="edit_employee_id">
                                <option value="">No select</option>
                                @foreach ($employee_name as $item)
                                    <option value="{{ $item->employee_id }}">{{ $item->employee_code }} -
                                        {{ $item->first_name }} {{ $item->last_name }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="mb-3">
                            <label for="edit_pin" class="form-label">Employee Code</label>
                            <input type="text" class="form-control" id="edit_pin" name="pin" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="edit_leave_type" class="form-label">Leave Type</label>
                            <select class="form-select" aria-label="Default" name="leave_type" id="edit_leave_type">
                                <option value="">No select</option>
                                @foreach ($leave_type as $item)
                                    <option value="{{ $item->leave_type_id }}">{{ $item->leave_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_apply_date" class="form-label">Apply Date</label>
                            <input type="date" class="form-control" id="edit_apply_date" name="apply_date" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="edit_start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="edit_start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="edit_end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_duration" class="form-label">Duration</label>
                            <input type="text" class="form-control" id="edit_duration" name="duration" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="edit_leave_status" class="form-label">Leave Status</label>
                            <input type="text" class="form-control" id="edit_leave_status" name="leave_status"
                                   readonly>
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
        // $('#edit_employee_id').val(data.employee_id);
        $(document).ready(function () {
            $('#add_employee_id').on('change', function () {
                var selectedOption = $(this).find('option:selected');
                var pin = selectedOption.data('');
                $('#pin').val(pin);
            });
        });

        $(document).ready(function () {
            var table = $('#applicationTable').DataTable({
                language: {
                    search: ""
                },
                initComplete: function (settings, json) {
                    $('.dt-search').addClass('input-group');
                    $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
                },
                responsive: true
            });

            $('#addApplicationForm').submit(function (e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('leave-application.add') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.success) {
                            $('#addApplicationModal').modal('hide');
                            toastr.success(response.message, "Successful");
                            setTimeout(function () {
                                location.reload()
                            }, 500);
                        } else {
                            toastr.error(response.message, "Error");
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 400) {
                            var response = xhr.responseJSON;
                            toastr.error(response.message, "Error");
                        } else {
                            toastr.error("An error occurred", "Error");
                        }
                    }
                });
            });

            $('#applicationTable').on('click', '.edit-btn', function () {
                var applicationID = $(this).data('id');

                $('#editApplicationForm').data('id', applicationID);
                var url = "{{ route('leave-application.edit', ':id') }}";
                url = url.replace(':id', applicationID);
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (response) {
                        var data = response.leave_app;
                        $('#edit_employee_id').val(data.employee_id);
                        $('#edit_pin').val(data.employee.employee_code);
                        $('#edit_leave_type').val(data.leave_type.leave_type_id);
                        $('#edit_apply_date').val(data.apply_date);
                        $('#edit_start_date').val(data.start_date);
                        $('#edit_end_date').val(data.end_date);
                        $('#edit_duration').val(data.duration);
                        $('#edit_leave_status').val(data.leave_status);
                        $('#editApplicationModal').modal('show');
                    },
                    error: function (xhr) {
                    }
                });
            });


            $('#editApplicationForm').submit(function (e) {
                e.preventDefault();
                var applicationID = $(this).data('id'); // Lấy ID từ form
                var url = "{{ route('leave-application.update', ':id') }}";
                url = url.replace(':id', applicationID);

                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.success) {
                            $('#editApplicationModal').modal('hide');
                            $('#successModal').modal('show');
                            toastr.success(response.response, "Edit successful");
                            setTimeout(function () {
                                location.reload()
                            }, 500);
                        }
                    },
                    error: function (xhr) {
                        toastr.error("Error");
                    }
                });
            });

            $('#applicationTable').on('click', '.delete-btn', function () {
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
                            success: function (response) {
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
                            error: function (xhr) {
                                toastr.error("An error occurred.", "Operation Failed");
                            }
                        });
                    }
                });
            });

            function addDateValidation(startDateInput, endDateInput, daysInput) {
                function calculateDays() {
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);

                    if (startDate && endDate && startDate <= endDate) {
                        const timeDiff = endDate.getTime() - startDate.getTime();
                        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                        daysInput.value = daysDiff;

                        endDateInput.setAttribute('min', startDateInput.value);
                    } else {
                        daysInput.value = '';
                        endDateInput.removeAttribute('min');
                    }
                }

                function validateDates() {
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0); // Set time to midnight for accurate comparison

                    if (startDate < today) {
                        toastr.error("Start date cannot be before today", "Error");
                        startDateInput.value = '';
                        daysInput.value = '';
                        endDateInput.removeAttribute('min');
                        return;
                    }

                    if (endDate < startDate) {
                        endDateInput.value = startDateInput.value;
                    }

                    calculateDays();
                }

                startDateInput.addEventListener('change', validateDates);
                endDateInput.addEventListener('change', validateDates);

                startDateInput.addEventListener('input', validateDates);
                endDateInput.addEventListener('input', validateDates);
            }

            addDateValidation(
                document.getElementById('start_date'),
                document.getElementById('end_date'),
                document.getElementById('duration')
            );

            addDateValidation(
                document.getElementById('edit_start_date'),
                document.getElementById('edit_end_date'),
                document.getElementById('edit_duration')
            );
        });
    </script>
@endsection
@section('head')
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.flash.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
    </script>
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js">
    </script>
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.print.min.js">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
