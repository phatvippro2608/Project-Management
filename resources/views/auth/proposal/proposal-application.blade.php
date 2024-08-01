@extends('auth.main');
@section('contents')
    <div class="pagetitle">
        <h1>Proposal Applicaiton</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Proposal Applicaiton</li>
            </ol>
        </nav>
    </div>
    <div class="row gx-3 my-3">
        <div class="col-md-6 m-0">
            <div class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#addProposalApplicationModal">
                <div class="d-flex align-items-center at1">
                    <i class="bi bi-file-earmark-plus pe-2"></i>
                    Add Proposal Applicaiton
                </div>
            </div>
            <div class="btn btn-success mx-2 btn-export">
                <a href="" class="d-flex align-items-center text-white">
                    <i class="bi bi-file-earmark-arrow-down pe-2"></i>
                    Export
                </a>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addProposalApplicationModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Add Proposal Application</h4>
                    @component('auth.component.btnCloseModal')@endcomponent
                </div>

                <div class="modal-body">
                    <form id="addProposalApplicationForm">
                        @csrf
                        <div class="mb-3">
                            <label for="department_name" class="form-label">Employee name</label>
                            <select class="form-select" aria-label="Default" name="employee_id">
                                    @foreach ($list_proposal as $item)
                                        <option value="{{ $item->employee_id }}">
                                            {{ $item->first_name }} {{ $item->last_name }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="department_name" class="form-label">Proposal Type</label>
                            <select class="form-select" aria-label="Default" name="id_proposal">
                                <option value="">No select</option>
                                {{--                                @foreach ($employee_name as $item)--}}
                                {{--                                    <option value="{{ $item->employee_id }}">{{ $item->employee_code }}--}}
                                {{--                                {{ $item->first_name }} {{ $item->last_name }}</option>--}}
                                {{--                                @endforeach--}}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="floatingTextarea2">Description</label>
                            <textarea class="form-control" placeholder="Leave a Description here" id="floatingTextarea2"
                                      style="height: 100px"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Proposal Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm p-3 mb-5 bg-white rounded-4">
        <h3 class="text-left mb-4">Proposal Application</h3>
        <table id="departmentsTable" class="table table-hover table-borderless">
            <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Employee Name</th>
                <th>Proposal Name Type</th>
                <th>Description</th>
                <th>Progress</th>
            </tr>
            </thead>
            <tbody id="departmentsTableBody">
            @php($stt=0)
            @foreach ($list_proposal as $item)
                <tr>
                    <td>{{$stt++}}</td>
                    <td>{{$item->last_name . ' ' . $item->first_name}}</td>
                    <td>{{$item->id_proposal}}</td>
                    <td>{{$item->description}}</td>
                    <td>{{$item->progress}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script>
        var table = $('#departmentsTable').DataTable({
            language: {search: ""},
            initComplete: function (settings, json) {
                $('.dt-search').addClass('input-group');
                $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
            }
        });

        {{--$(document).ready(function () {--}}
        {{--    var table = $('#departmentsTable').DataTable({--}}
        {{--        language: { search: "" },--}}
        {{--        initComplete: function (settings, json) {--}}
        {{--            $('.dt-search').addClass('input-group');--}}
        {{--            $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">--}}
        {{--                        <i class="bi bi-search"></i>--}}
        {{--                    </button>`)--}}
        {{--        }--}}
        {{--    });--}}

        {{--    $('#addProposalApplicationForm').submit(function (e) {--}}
        {{--        e.preventDefault();--}}

        {{--        $.ajax({--}}
        {{--            url: '{{ route('departments.store') }}',--}}
        {{--            method: 'POST',--}}
        {{--            data: $(this).serialize(),--}}
        {{--            success: function (response) {--}}
        {{--                if (response.success) {--}}
        {{--                    $('#addProposalApplicationModal').modal('hide');--}}
        {{--                    // $('#successModal').modal('show');--}}
        {{--                    toastr.success(response.message, "Successful");--}}

        {{--                    table.row.add([--}}
        {{--                        response.department.department_name,--}}
        {{--                        '<button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn" data-id="' +--}}
        {{--                        response.department.department_id + '">' +--}}
        {{--                        '<i class="bi bi-pencil-square"></i>' +--}}
        {{--                        '</button>' +--}}
        {{--                        ' | ' +--}}
        {{--                        '<button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn" data-id="' +--}}
        {{--                        response.department.department_id + '">' +--}}
        {{--                        '<i class="bi bi-trash3"></i>' +--}}
        {{--                        '</button>'--}}
        {{--                    ]).draw();--}}
        {{--                    $('#addProposalApplicationForm')[0].reset();--}}
        {{--                }--}}
        {{--            },--}}
        {{--            error: function (xhr) {--}}
        {{--                toastr.error(response.message, "Error");--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}

        {{--    $('#departmentsTable').on('click', '.edit-btn', function () {--}}
        {{--        var departmentId = $(this).data('id');--}}

        {{--        if (!departmentId) {--}}
        {{--            alert('Department ID not found.');--}}
        {{--            return;--}}
        {{--        }--}}

        {{--        $.ajax({--}}
        {{--            url: '{{ url('departments') }}/' + departmentId + '/edit',--}}
        {{--            method: 'GET',--}}
        {{--            success: function (response) {--}}
        {{--                $('#editDepartmentId').val(response.department.department_id);--}}
        {{--                $('#edit_department_name').val(response.department.department_name);--}}
        {{--                $('#editDepartmentModal').modal('show');--}}
        {{--            },--}}
        {{--            error: function (xhr) {--}}
        {{--                alert('An error occurred.');--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}

        {{--    $('#editDepartmentForm').submit(function (e) {--}}
        {{--        e.preventDefault();--}}
        {{--        var departmentId = $('#editDepartmentId').val();--}}

        {{--        $.ajax({--}}
        {{--            url: '{{ url('departments') }}/' + departmentId,--}}
        {{--            method: 'PUT',--}}
        {{--            data: $(this).serialize(),--}}
        {{--            success: function (response) {--}}
        {{--                if (response.success) {--}}
        {{--                    $('#editDepartmentModal').modal('hide');--}}
        {{--                    toastr.success("Edit successful");--}}

        {{--                    var row = table.row($('button[data-id="' + departmentId + '"]')--}}
        {{--                        .parents('tr'));--}}
        {{--                    row.data([--}}
        {{--                        response.department.department_name,--}}
        {{--                        '<button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn" data-id="' +--}}
        {{--                        response.department.department_id + '">' +--}}
        {{--                        '<i class="bi bi-pencil-square"></i>' +--}}
        {{--                        '</button>' +--}}
        {{--                        ' | ' +--}}
        {{--                        '<button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn" data-id="' +--}}
        {{--                        response.department.department_id + '">' +--}}
        {{--                        '<i class="bi bi-trash3"></i>' +--}}
        {{--                        '</button>'--}}
        {{--                    ]).draw();--}}
        {{--                }--}}
        {{--            },--}}
        {{--            error: function (xhr) {--}}
        {{--                toastr.error("Error");--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}

        {{--    $('#departmentsTable').on('click', '.delete-btn', function () {--}}
        {{--        var departmentId = $(this).data('id');--}}
        {{--        var row = $(this).parents('tr');--}}

        {{--        Swal.fire({--}}
        {{--            title: 'Are you sure?',--}}
        {{--            text: "You won't be able to revert this!",--}}
        {{--            icon: 'warning',--}}
        {{--            showCancelButton: true,--}}
        {{--            confirmButtonColor: '#3085d6',--}}
        {{--            cancelButtonColor: '#d33',--}}
        {{--            confirmButtonText: 'Yes, delete it!'--}}
        {{--        }).then((result) => {--}}
        {{--            if (result.isConfirmed) {--}}
        {{--                $.ajax({--}}
        {{--                    url: '{{ url('departments') }}/' + departmentId,--}}
        {{--                    method: 'DELETE',--}}
        {{--                    data: {--}}
        {{--                        _token: '{{ csrf_token() }}'--}}
        {{--                    },--}}
        {{--                    success: function (response) {--}}
        {{--                        if (response.success) {--}}
        {{--                            table.row(row).remove().draw();--}}
        {{--                        }--}}
        {{--                    },--}}
        {{--                    error: function (xhr) {--}}
        {{--                        alert('An error occurred.');--}}
        {{--                    }--}}
        {{--                });--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}

        {{--    function reindexRows() {--}}
        {{--        $('#departmentsTable tbody tr').each(function (index) {--}}
        {{--            $(this).find('td:first').text(index + 1);--}}
        {{--        });--}}
        {{--    }--}}
        {{--});--}}
    </script>
@endsection
