@extends('auth.main')
@section('contents')
    <div class="pagetitle">
        <h1>{{ __('messages.department') }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item "><a href="{{route('departments.index')}}">{{ __('messages.department') }}</a></li>
                <li class="breadcrumb-item active"><a href="#">{{$department_name}}</a></li>
            </ol>
        </nav>
    </div>
    <div class="row gx-3 my-3">
        <div class="col-md-6 m-0">
            <div class="btn btn-primary me-2">
                <div class="d-flex align-items-center at1">
                    <i class="bi bi-file-earmark-plus pe-2"></i>
                    {{ __('messages.add') }}
                </div>
            </div>
            <div class="btn btn-primary mx-2 btn-export">
                <a href="{{action('App\Http\Controllers\DepartmentController@export',['id' => $department_id])}}"
                   class="d-flex align-items-center text-white">
                    <i class="bi bi-file-earmark-arrow-down pe-2"></i>
                    {{ __('messages.export') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card p-2 rounded-4 border">
        <div class="card-header py-0">
            <div class="card-title my-3 p-0">{{ __('messages.employee_list') }}</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="departmentTable" class="table table-hover table-borderless">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">{{ __('messages.employee_code') }}</th>
                            <th class="text-center">Photo</th>
                            <th class="text-center">{{ __('messages.full_name') }}</th>
                            <th class="text-center">English Name</th>
                            <th class="text-center">{{ __('messages.gender') }}</th>
                            <th class="text-center">{{ __('messages.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td class="text-center"><a
                                        href="{{action('App\Http\Controllers\EmployeesController@getEmployee', $item->employee_id)}}">{{$item->employee_code}}</a>
                                </td>
                                @php
                                    $imageUrl = asset('assets/img/avt.png');

                                    if($item->photo != null){
                                        $imagePath = public_path($item->photo);
                                        if(file_exists($imagePath)) {
                                            $imageUrl = asset($item->photo);
                                        }
                                    }
                                @endphp
                                <td class="text-center"><img class="rounded-pill object-fit-cover" src="{{ $imageUrl }}"
                                                             alt="" width="75" height="75"></td>
                                <td>{{$item->last_name . ' ' . $item->first_name}}</td>
                                <td>{{$item->en_name}}</td>
                                <td class="text-center">{{ $item->gender == 0 ? __('messages.male') : __('messages.female') }}</td>
                                <td class="text-center">
                                    <a href="{{action('App\Http\Controllers\EmployeesController@updateView',$item->employee_id)}}"
                                       class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none at3">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    |
                                    <button
                                        class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none at4"
                                        data="{{$item->employee_id}}">
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

    <div class="modal modal-add" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Employee to Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="select-employee">Choose Employee</label>
                    <select class="form-select form-select-lg employee-list" size="20" multiple id="select-employee">
                        @php
                            $data = \App\Http\Controllers\DepartmentController::getEmployeeNotInDepartment();
                        @endphp
                        @foreach($data as $item)
                            <option value="{{$item->employee_id}}">{{$item->employee_code}} - {{$item->last_name . ' ' . $item->first_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-add">Add</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var table = $('#departmentTable').DataTable({
            language: {search: ""},
            initComplete: function (settings, json) {
                $('.dt-search').addClass('input-group');
                $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
            },
            responsive: true
        });
        let _add = '{{action('App\Http\Controllers\DepartmentController@addEmployee',['id' => $department_id])}}';
        let _delete = '{{action('App\Http\Controllers\DepartmentController@deleteEmployee', ['id' => $department_id, 'employee_id' => ':employee_id'])}}';

        $('.at1').click(function () {
            $('.modal-add').modal('show');

            $('.btn-add').click(function () {
                let employees = $('.employee-list').val();
                if(employees.length === 0){
                    toastr.error('Please choose Employee to add to Department!', 'Error');
                    return;
                }
                let formData = new FormData();
                formData.append('employees', employees);
                $.ajax({
                    url: _add,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function (response) {
                        if (response.status === 200) {
                            toastr.success(response.message, response.message);
                            setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        } else {
                            toastr.error(response.message, response.message);
                        }
                    }
                });
            })
        })

        $('.at4').click(function () {
            let employee_id = $(this).attr('data');
            let url = _delete.replace(':employee_id', employee_id)
            if(confirm('Are you sure you want to remove this employee from the department?')){
                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        console.log(typeof response.status)
                        if (response.status === 200) {
                            toastr.success(response.message, response.message);
                            setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        } else {
                            toastr.error(response.message, response.messages);
                        }
                    }
                });
            }
        })


    </script>
@endsection
