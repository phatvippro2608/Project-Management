@extends('auth.main')

@section('head')
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
@endsection

@section('contents')

<style type="text/css">
    .employees-dropdown {
        min-width: 10%;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
        display: none;
        position: absolute;
        z-index: 1;
    }
</style>
<div class="pagetitle">
    <h1>Attendance</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{action('App\Http\Controllers\DashboardController@getViewDashboard')}}">Home</a></li>
            <li class="breadcrumb-item active">Attendance</li>
        </ol>
    </nav>
</div>
<div class="section employees">
    <div class="card">
        <div class="card-header">
            <a href="{{ action('App\Http\Controllers\AttendanceController@addAttendanceView') }}" class="btn btn-info text-white"><i class="bi bi-plus"></i> Add Attendance</a>
            <a href="{{ action('App\Http\Controllers\AttendanceController@addAttendanceView') }}" class="btn btn-info text-white"><i class="bi bi-file-earmark-fill"></i> Attendance Report</a>
        </div>
        <div class="card-body">
            <table id="attendanceTable" class="display">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Employee Name</th>
                        <th scope="col" style="width: 12%;">Employee ID</th>
                        <th scope="col">Date</th>
                        <th scope="col">Sign In</th>
                        <th scope="col">Sign Out</th>
                        <th scope="col">Working</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendance as $data)
                    @php
                    $working = null;
                    if ($data->sign_in && $data->sign_out) {
                    $working = strtotime($data->sign_out) - strtotime($data->sign_in);
                    $working = date('H:i', $working);
                    }
                    $name=DB::table('employees')->where('id_employee', $data->id_employee)->first()->first_name;
                    $name .= ' ' . DB::table('employees')->where('id_employee', $data->id_employee)->first()->last_name;
                    @endphp
                    <tr>
                        <td>{{ $data->id_attendance }}</td>
                        <td>{{ $name }}</td>
                        <td>{{ $data->id_employee }}</td>
                        <td>{{ $data->date }}</td>
                        <td>{{ $data->sign_in }}</td>
                        <td>{{ $data->sign_out }}</td>
                        <td>{{ $working }}</td>
                        <td>
                            <button data-attendance="{{ $data->id_attendance }}" class="btn btn-primary text-white" onclick="viewAttendanceByID(this)"><i class="bi bi-pencil-square"></i></button>
                            <button data-attendance="{{ $data->id_attendance }}" class="btn btn-danger text-white" onclick="deleteAttendanceByID(this)" ><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="modalViewAttendance" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="edit-attendance-form">
                @csrf
                <div class="modal-header">
                    <input type="text" name="id_attendance" id="id_attendance" hidden>
                    <div class="dropdown">
                        <button class="btn" type="button" id="dropdownMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots" style="font-size: 3vh;"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
                            <li><a class="dropdown-item" id="dl_attendance" data-attendance="" href="#">Delete</a></li>
                        </ul>
                    </div>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x" style="font-size: 3vh;"></i></button>
                </div>
                <div class="modal-body row">
                    <div class="col-6 mt-3 align-self-center">
                        <div class="form-group text-center">
                            <img id="employee-img" src="{{ asset('assets/img/avt.png') }}" class="rounded-circle object-fit-cover" width="100" height="100">
                        </div>
                        <div class="row form-group mt-3">
                            <div class="col-9">
                                <label class="fw-bolder" for=""><i class="bi bi-person"></i> Employee</label>
                                <input id="employee_name" class="form-control" onkeyup="searchDropdown(this)" onclick="displayDropdown(this)" required>
                            </div>
                            <div class="col-3">
                                <label class="fw-bolder" for=""><i class="bi bi-person"></i> ID</label>
                                <input id="employee_id" class="form-control" name="id_employee" onkeyup="searchDropdown(this)" onclick="displayDropdown(this)" required>
                            </div>
                            <div class="employees-dropdown fs-5">
                                @foreach($employees as $employee)
                                @php
                                $photoPath = asset($employee->photo);
                                $defaultPhoto = asset('assets/img/avt.png');
                                $photoExists = !empty($employee->photo) && file_exists(public_path($employee->photo));
                                @endphp
                                <div class="employee-item d-flex align-items-center" data-id="{{ $employee->id_employee }}" data-value="{{ $employee->first_name  . ' ' . $employee->last_name }}">
                                    <img src="{{ $photoExists ? $photoPath : $defaultPhoto }}" class="rounded-circle object-fit-cover" width="22" height="22">
                                    <div class="empl_val ms-1"></div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mt-3" style="border-left: 1px solid black;">
                        <div class="form-group">
                            <label class="fw-bolder" for="date"><i class="bi bi-calendar"></i> Date</label>
                            <input type="date" class="form-control" name="date" id="date" required>
                        </div>
                        <div class="form-group mt-3">
                            <label class="fw-bolder" for="sign_in"><i class="bi bi-clock"></i> Sign In</label>
                            <input type="time" class="form-control" name="sign_in" id="sign_in" onchange="timeCheck(this)" required>
                        </div>
                        <div class="form-group mt-3">
                            <label class="fw-bolder" for="sign_out"><i class="bi bi-clock"></i> Sign Out</label>
                            <input type="time" class="form-control" name="sign_out" id="sign_out" onchange="timeCheck(this)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    var table = $('#attendanceTable').DataTable({
        responsive: true,
        dom: '<"d-flex justify-content-between align-items-center mt-2 mb-2"<"mr-auto"l><"d-flex justify-content-center mt-2 mb-2"B><"ml-auto mt-2 mb-2"f>>rtip',
        buttons: [{
                extend: 'csv',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'excelHtml5',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: ':not(:last-child)'
                },
            },
            {
                extend: 'pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'print',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            }
        ],
        lengthMenu: [10, 25, 50, 100, -1],
        pageLength: 10
    });
    $('.dt-search').addClass('d-flex align-items-center');
    $('.dt-search label').addClass('d-flex align-items-center');
    $('.dt-search input').addClass('form-control ml-2');
</script>
<script>
    function displayDropdown(e) {
        $('.employees-dropdown').css('display', 'block');
        var position = $(e).position();
        $('.employees-dropdown').css('top', position.top + 40);
        $('.employees-dropdown').css('left', position.left);
        $('.employees-dropdown').css('width', $(e).width());
        if ($(e).attr('id') == 'employee_name') {
            $('.employee-item').each(function() {
                $(this).find('.empl_val').html($(this).attr('data-value'));
            });
        } else {
            $('.employee-item').each(function() {
                $(this).find('.empl_val').html($(this).attr('data-id'));
            });
        }
        var value = $(e).val().toLowerCase();
        $('.employee-item').filter(function() {
            if ($(e).attr('id') == 'employee_name') {
                if ($(this).attr('data-value').toLowerCase().indexOf(value) > -1) {
                    $(this).attr('style', 'display: flex !important');
                } else {
                    $(this).attr('style', 'display: none !important');
                }
            } else {
                if ($(this).attr('data-id').toLowerCase().indexOf(value) > -1) {
                    $(this).attr('style', 'display: flex !important');
                } else {
                    $(this).attr('style', 'display: none !important');
                }
            }
        });
    }

    function searchDropdown(e) {
        var value = $(e).val().toLowerCase();
        $('.employee-item').filter(function() {
            if ($(e).attr('id') == 'employee_name') {
                if ($(this).attr('data-value').toLowerCase().indexOf(value) > -1) {
                    $(this).attr('style', 'display: flex !important');
                } else {
                    $(this).attr('style', 'display: none !important');
                }
            } else {
                if ($(this).attr('data-id').toLowerCase().indexOf(value) > -1) {
                    $(this).attr('style', 'display: flex !important');
                } else {
                    $(this).attr('style', 'display: none !important');
                }
            }
        });
    }

    $('.employee-item').click(function() {
        $('#employee_name').val($(this).attr('data-value'));
        $('#employee_id').val($(this).attr('data-id'));
        $('.employees-dropdown').css('display', 'none');
        var photoPath = $(this).find('img').attr('src');
        $('#employee-img').attr('src', photoPath);
    });

    function timeCheck(e) {
        if ($(e).attr('id') == 'sign_in') {
            $('#sign_out').attr('min', $(e).val());
        } else {
            $('#sign_in').attr('max', $(e).val());
        }
    }
</script>
<script>
    function viewAttendanceByID(e) {
        var id = $(e).attr('data-attendance');
        $.ajax({
            url: '{{ url('attendance')}}/'+id,
            type: 'get',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                var employee = data.employees[0];
                var attendance = data.attendance[0];
                $('#id_attendance').val(attendance.id_attendance);
                $('#employee_name').val(employee.first_name + ' ' + employee.last_name);
                $('#employee_id').val(attendance.id_employee);
                $('#date').val(attendance.date);
                $('#sign_in').val(attendance.sign_in);
                $('#sign_in').attr('max', attendance.sign_out);
                $('#sign_out').val(attendance.sign_out);
                $('#sign_out').attr('min', attendance.sign_in);
                var photoPath = employee.photo;
                $('#employee-img').attr('src', photoPath);
                $('#dl_attendance').attr('data-attendance', attendance.id_attendance);
                $('#modalViewAttendance').modal('show');
            }
        });
    }

    //update when form submit
    $('#edit-attendance-form').submit(function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '{{ route('attendance.update') }}',
            data: data,
            success: function(data) {
                $('#modalViewAttendance').modal('hide');
                if(data.success) {
                    toastr.success(data.message);
                    $('#edit-attendance-form').trigger('reset');
                    table.clear().draw();
                    $.each(data.attendance, function(index, value) {
                        var working = null;
                        if (value.sign_in && value.sign_out) {
                            working = new Date(value.date + ' ' + value.sign_out) - new Date(value.date + ' ' + value.sign_in);
                            working = new Date(working).toISOString().substr(11, 5);
                        }
                        var name = value.first_name + ' ' + value.last_name;
                        table.row.add([
                            value.id_attendance,
                            name,
                            value.id_employee,
                            value.date,
                            value.sign_in,
                            value.sign_out,
                            working,
                            '<button data-attendance="' + value.id_attendance + '" class="btn btn-primary text-white ms-1" onclick="viewAttendanceByID(this)"><i class="bi bi-pencil-square"></i></button>' +
                            '<button data-attendance="' + value.id_attendance + '" class="btn btn-danger text-white"><i class="bi bi-trash"></i></button>'
                        ]).draw();
                    });
                }else {
                    toastr.error(data.message);
                }
            }
        });
    });

    function deleteAttendanceByID(e) {
        var id = $(e).attr('data-attendance');
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
                url: '{{ route('attendance.delete') }}',
                type: 'delete',
                data: {
                    id_attendance: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if(data.success) {
                    toastr.success(data.message);
                    table.clear().draw();
                    $.each(data.attendance, function(index, value) {
                        var working = null;
                        if (value.sign_in && value.sign_out) {
                        working = new Date(value.date + ' ' + value.sign_out) - new Date(value.date + ' ' + value.sign_in);
                        working = new Date(working).toISOString().substr(11, 5);
                        }
                        var name = value.first_name + ' ' + value.last_name;
                        table.row.add([
                        value.id_attendance,
                        name,
                        value.id_employee,
                        value.date,
                        value.sign_in,
                        value.sign_out,
                        working,
                        '<button data-attendance="' + value.id_attendance + '" class="btn btn-primary text-white ms-1" onclick="viewAttendanceByID(this)"><i class="bi bi-pencil-square"></i></button>' +
                        '<button data-attendance="' + value.id_attendance + '" class="btn btn-danger text-white"><i class="bi bi-trash"></i></button>'
                        ]).draw();
                    });
                    }else {
                    toastr.error(data.message);
                    }
                }
                });
            }
        });
    }
</script>
@endsection