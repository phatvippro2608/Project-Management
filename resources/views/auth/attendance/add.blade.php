@extends('auth.main')

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
            <li class="breadcrumb-item"><a href="{{ action('App\Http\Controllers\AttendanceController@getView') }}">Attendance</a></li>
            <li class="breadcrumb-item active">Add Attendance</li>
        </ol>
    </nav>
</div>
<div class="container">
    <div class="card border rounded-4 p-2">
        <div class="card-body">
            <form action="" class="m-2" method="post">
                @csrf
                <div class="row">
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
                                <input id="employee_id" class="form-control" name="employee_id" onkeyup="searchDropdown(this)" onclick="displayDropdown(this)" required>
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
                <div class="form-group d-flex justify-content-end mt-4">
                    <!-- button reset from -->
                    <button type="reset" class="btn btn-danger me-2"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
                </div>
            </form>
            <div class="employees-dropdown fs-5">
                @foreach($employees as $employee)
                @php
                $photoPath = asset($employee->photo);
                $defaultPhoto = asset('assets/img/avt.png');
                $photoExists = !empty($employee->photo) && file_exists(public_path($employee->photo));
                @endphp
                <div class="employee-item d-flex align-items-center py-1 px-2" data-id="{{ $employee->employee_id }}" data-value="{{ $employee->first_name  . ' ' . $employee->last_name }}">
                    <img src="{{ $photoExists ? $photoPath : $defaultPhoto }}" class="rounded-circle object-fit-cover" width="22" height="22">
                    <div class="empl_val ms-1"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- event off dropdown employee -->
<script>
    $('#date').val(new Date().toISOString().slice(0, 10));
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

    //ajax when from submit
    $('form').submit(function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '{{ action('App\Http\Controllers\AttendanceController@addAttendance') }}',
            data: data,
            success: function(response) {
                console.log(response);
                if (response.success) {
                    toastr.success(response.message);
                    $('form').trigger('reset');
                } else {
                    toastr.error(response.message);
                }

            }
        });
    });

</script>
@endsection
