@extends('auth.main');
@section('contents')
    <div class="container">
        <h1 class="text-center mb-4">Departments</h1>

        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
            Add Department
        </button>

        <div class="modal fade" id="addDepartmentModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Add Department</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <form id="addDepartmentForm">
                            @csrf
                            <div class="mb-3">
                                <label for="department_name" class="form-label">Department Name</label>
                                <input type="text" class="form-control" id="department_name" name="department_name"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Department</button>
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editDepartmentForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="editDepartmentId" name="department_id">
                            <div class="mb-3">
                                <label for="edit_department_name" class="form-label">Department Name</label>
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

        <div class="card shadow-sm p-3 mb-5 bg-white rounded">
            <h3 class="text-left mb-4">Departments List</h3>
            <table id="departmentsTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Department Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="departmentsTableBody">
                    @foreach ($departments as $department)
                        <tr>
                            <td>{{ $department->department_name }}</td>
                            <td>
                                <button
                                    class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                    data-id="{{ $department->department_id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                |
                                <button
                                    class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
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
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#departmentsTable').DataTable();

            $('#addDepartmentForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('departments.store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#addDepartmentModal').modal('hide');
                            $('#successModal').modal('show');

                            table.row.add([
                                response.department.department_name,
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn" data-id="' +
                                response.department.department_id + '">' +
                                '<i class="bi bi-pencil-square"></i>' +
                                '</button>' +
                                ' | ' +
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn" data-id="' +
                                response.department.department_id + '">' +
                                '<i class="bi bi-trash3"></i>' +
                                '</button>'
                            ]).draw();
                            $('#addDepartmentForm')[0].reset();
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred.');
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
                            $('#successModal').modal('show');

                            var row = table.row($('button[data-id="' + departmentId + '"]')
                                .parents('tr'));
                            row.data([
                                response.department.department_name,
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn" data-id="' +
                                response.department.department_id + '">' +
                                '<i class="bi bi-pencil-square"></i>' +
                                '</button>' +
                                ' | ' +
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn" data-id="' +
                                response.department.department_id + '">' +
                                '<i class="bi bi-trash3"></i>' +
                                '</button>'
                            ]).draw();
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred.');
                    }
                });
            });

            $('#departmentsTable').on('click', '.delete-btn', function() {
                var departmentId = $(this).data('id');
                var row = $(this).parents('tr');

                if (confirm('Are you sure you want to delete this department?')) {
                    $.ajax({
                        url: '{{ url('departments') }}/' + departmentId,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                table.row(row).remove().draw();
                            }
                        },
                        error: function(xhr) {
                            alert('An error occurred.');
                        }
                    });
                }
            });

            function reindexRows() {
                $('#departmentsTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
        });
    </script>
@endsection
