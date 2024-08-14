@extends('auth.main');

@section('contents')
<div class="pagetitle">
    <h1>{{ __('messages.department') }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">{{ __('messages.department') }}</li>
        </ol>
    </nav>
</div>

    <div class="modal fade" id="addDepartmentModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('messages.add_department') }}</h4>
                    @component('auth.component.btnCloseModal')@endcomponent
                </div>

                <div class="modal-body">
                    <form id="addDepartmentForm">
                        @csrf
                        <div class="mb-3">
                            <label for="department_name" class="form-label">Department Name</label>
                            <input type="text" class="form-control" id="department_name" name="department_name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('messages.add_department') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                    @component('auth.component.btnCloseModal')
                    @endcomponent
                </div>
                <div class="modal-body">
                    <form id="editDepartmentForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editDepartmentId" name="department_id">
                        <div class="mb-3">
                            <label for="edit_department_name" class="form-label">{{ __('messages.department_name') }}</label>
                            <input type="text" class="form-control" id="edit_department_name" name="department_name"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Department added successfully!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm p-3 mb-5 bg-white rounded-4">
        {{--        <h3 class="text-left mb-4">Departments List</h3> --}}
        <div class="table-responsive">
            <table id="departmentsTable" class="table table-hover table-borderless">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Department Name</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="departmentsTableBody">
                @foreach ($departments as $department)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $department->department_name }}</td>
                        <td>
                            <button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                    data-id="{{ $department->department_id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            |
                            <button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                    data-id="{{ $department->department_id }}">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


<div class="modal fade" id="addDepartmentModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('messages.add_department') }}</h4>
                @component('auth.component.btnCloseModal')@endcomponent
            </div>

            <div class="modal-body">
                <form id="addDepartmentForm">
                    @csrf
                    <div class="mb-3">
                        <label for="department_name" class="form-label">{{ __('messages.department_name') }}</label>
                        <input type="text" class="form-control" id="department_name" name="department_name"
                               required>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('messages.add_department') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                @component('auth.component.btnCloseModal')@endcomponent
            </div>
            <div class="modal-body">
                <form id="editDepartmentForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editDepartmentId" name="department_id">
                    <div class="mb-3">
                        <label for="edit_department_name" class="form-label">{{ __('messages.department_name') }}</label>
                        <input type="text" class="form-control" id="edit_department_name" name="department_name"
                               required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Department added successfully!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#departmentsTable').DataTable({
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

            $('#addDepartmentForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('departments.store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#addDepartmentModal').modal('hide');
                            // $('#successModal').modal('show');
                            toastr.success(response.message, "Successful");

                            // table.row.add([
                            //     response.department.department_name,
                            //     '<button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn" data-id="' +
                            //     response.department.department_id + '">' +
                            //     '<i class="bi bi-pencil-square"></i>' +
                            //     '</button>' +
                            //     ' | ' +
                            //     '<button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn" data-id="' +
                            //     response.department.department_id + '">' +
                            //     '<i class="bi bi-trash3"></i>' +
                            //     '</button>'
                            // ]).draw();
                            // $('#addDepartmentForm')[0].reset();
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        toastr.error(response.message, "Error");
                    }
                });
            });


            $('#departmentsTable').on('click', '.edit-btn', function() {
                var departmentId = $(this).data('id');

                if (!departmentId) {
                    alert('Department ID not found.');
                    return;
                }

                $.ajax({
                    url: '{{ url('departments') }}/' + departmentId + '/edit',
                    method: 'GET',
                    success: function(response) {
                        $('#editDepartmentId').val(response.department.department_id);
                        $('#edit_department_name').val(response.department.department_name);
                        $('#editDepartmentModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('An error occurred.');
                    }
                });
            });

            $('#editDepartmentForm').submit(function(e) {
                e.preventDefault();
                var departmentId = $('#editDepartmentId').val();

                $.ajax({
                    url: '{{ url('departments') }}/' + departmentId,
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#editDepartmentModal').modal('hide');
                            toastr.success("Edit successful");

                            // var row = table.row($('button[data-id="' + departmentId + '"]')
                            //     .parents('tr'));
                            // row.data([
                            //     response.department.department_name,
                            //     '<button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn" data-id="' +
                            //     response.department.department_id + '">' +
                            //     '<i class="bi bi-pencil-square"></i>' +
                            //     '</button>' +
                            //     ' | ' +
                            //     '<button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn" data-id="' +
                            //     response.department.department_id + '">' +
                            //     '<i class="bi bi-trash3"></i>' +
                            //     '</button>'
                            // ]).draw();
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Error");
                    }
                });
            });

            $('#departmentsTable').on('click', '.delete-btn', function() {
                var departmentId = $(this).data('id');
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
                        $.ajax({
                            url: '{{ url('departments') }}/' + departmentId,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    table.row(row).remove().draw();
                                    toastr.success("Delete successful");
                                }
                            },
                            error: function(xhr) {
                                alert('An error occurred.');
                            }
                        });
                    }
                });
            });

            function reindexRows() {
                $('#departmentsTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
        });
    </script>
@endsection
