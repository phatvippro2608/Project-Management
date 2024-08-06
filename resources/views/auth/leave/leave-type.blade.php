@extends('auth.main')

@section('contents')
<div class="pagetitle">
    <h1>Leave Types</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Leave Types</li>
        </ol>
    </nav>
</div>
<div class="row gx-3 my-3">
    <div class="col-md-6 m-0">
        <button id="addHolidayBtn"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#addLeaveTypesModal"
        >
            <i class="bi bi-plus-lg"></i> Add Leave Types
        </button>
        <a id="leaveApplicationBtn"
           class="btn btn-secondary"
           href="{{ action('App\Http\Controllers\DashboardController@getViewDashboard') }}"
        >
            <i class="bi bi-list me-2"></i>Leave Application
        </a>
    </div>
</div>
<div class="card p-2 rounded-4 border">
    <div class="card-header py-0">
        <div class="card-title my-3 p-0">Leave List</div>
    </div>
    <div class="card-body">
        <table id="leavetypesTable" class="table table-hover table-borderless">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Leave Type</th>
                <th>Number Of Days</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($leave_types as $leave_type)
                <tr>
                    <td>{{ $leave_type->leave_type_id }}</td>
                    <td>{{ $leave_type->leave_type }}</td>
                    <td>{{ $leave_type->number_of_days }}</td>
                    <td>
                        <button
                            class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                            data-id="{{ $leave_type->leave_type_id }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        |
                        <button
                            class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                            data-id="{{ $leave_type->leave_type_id }}">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
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
                        <input type="hidden" id="editHolidayId" name="leave_type_id">
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
            var table = $('#leavetypesTable').DataTable({
                language: { search: "" },
                initComplete: function (settings, json) {
                    $('.dt-search').addClass('input-group');
                    $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
                },
                responsive: true
            });

            table.buttons().container().appendTo('#leaveReportTable_wrapper .col-md-6:eq(0)');

            $('#addLeaveTypeForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('leave-type.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#addLeaveTypesModal').modal('hide');
                            $('#successModal').modal('show');
                            toastr.success(response.response, "Add successful");
                            table.row.add([
                                response.leave_type.leave_type_id,
                                response.leave_type.leave_type,
                                response.leave_type.number_of_days,
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn" data-id="' +
                                response.leave_type.leave_type_id + '">' +
                                '<i class="bi bi-pencil-square"></i>' +
                                '</button>' +
                                ' | ' +
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn" data-id="' +
                                response.leave_type.leave_type_id + '">' +
                                '<i class="bi bi-trash3"></i>' +
                                '</button>'
                            ]).draw();

                            $('#addLeaveTypeForm')[0].reset();
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Error");
                    }
                });
            });

            $('#leavetypesTable').on('click', '.edit-btn', function() {
                var leavetypesID = $(this).data('id');

                if (!leavetypesID) {
                    alert('Department ID not found.');
                    return;
                }

                var url = "{{ route('leave-type.edit', ':leavetype') }}";
                url = url.replace(':leavetype', leavetypesID);

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        $('#editHolidayId').val(response.leave_type.leave_type_id);
                        $('#edit_leave_type').val(response.leave_type.leave_type);
                        $('#edit_number_of_days').val(response.leave_type.number_of_days);
                        $('#editLeaveTypesModal').modal('show');
                    },
                    error: function(xhr) {}
                });
            });

            $('#editHolidayForm').submit(function(e) {
                e.preventDefault();
                var leavetypesID = $('#editHolidayId').val();

                var url = "{{ route('leave-type.update', ':leavetype') }}";
                url = url.replace(':leavetype', leavetypesID);

                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#editLeaveTypesModal').modal('hide');
                            $('#successModal').modal('show');
                            toastr.success(response.response, "Edit successful");
                            var row = table.row($('button[data-id="' + leavetypesID + '"]')
                                .parents('tr'));
                            row.data([
                                response.leave_type.leave_type_id,
                                response.leave_type.leave_type,
                                response.leave_type.number_of_days,
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn" data-id="' +
                                response.leave_type.leave_type_id + '">' +
                                '<i class="bi bi-pencil-square"></i>' +
                                '</button>' +
                                ' | ' +
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn" data-id="' +
                                response.leave_type.leave_type_id + '">' +
                                '<i class="bi bi-trash3"></i>' +
                                '</button>'
                            ]).draw();
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Error");
                    }
                });
            });

            $('#leavetypesTable').on('click', '.delete-btn', function() {
                var leavetypesID = $(this).data('id');
                var row = $(this).parents('tr');

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
                        var url = "{{ route('leave-type.destroy', ':leavetype') }}";
                        url = url.replace(':leavetype', leavetypesID);
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
                                    table.row(row).remove().draw();
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
@section('head')
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
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
