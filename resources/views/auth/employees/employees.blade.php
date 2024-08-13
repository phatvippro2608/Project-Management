@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>{{ __('messages.employee') }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">{{ __('messages.employee') }}</li>
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
            <div class="btn btn-primary mx-2">
                <a href="{{action('App\Http\Controllers\EmployeesController@importView')}}" class="d-flex align-items-center at2 text-white">
                    <i class="bi bi-file-earmark-arrow-up pe-2"></i>
                    {{ __('messages.import') }}
                </a>
            </div>
            <div class="btn btn-primary mx-2 btn-export">
                <a href="{{action('App\Http\Controllers\EmployeesController@export')}}" class="d-flex align-items-center text-white">
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
            <table id="employeesTable" class="table table-hover table-borderless">
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
                <tbody id="employeesTableBody">

                        @foreach($data as $item)
                            @if($item->fired == "false")
                                <tr>
                                    <td class="text-center"><a href="{{action('App\Http\Controllers\EmployeesController@getEmployee', $item->employee_id)}}">{{$item->employee_code}}</a></td>
                                    @php
                                        $imageUrl = asset('assets/img/avt.png');

                                        if($item->photo != null){
                                            $imagePath = public_path($item->photo);
                                            if(file_exists($imagePath)) {
                                                $imageUrl = asset($item->photo);
                                            }
                                        }
                                    @endphp
                                    <td class="text-center"><img class="rounded-pill object-fit-cover" src="{{ $imageUrl }}" alt="" width="75" height="75"></td>
                                    <td>{{$item->last_name . ' ' . $item->first_name}}</td>
                                    <td>{{$item->en_name}}</td>
                                    <td class="text-center">{{ $item->gender == 0 ? __('messages.male') : __('messages.female') }}</td>
                                    <td align="center">
                                            <?php
                                            $id = $item->employee_id;
                                            $item->medical = \App\Http\Controllers\EmployeesController::getMedicalInfo($id);
                                            $item->certificates = \App\Http\Controllers\EmployeesController::getCertificateInfo($id);
                                            $item->passport = \App\Http\Controllers\EmployeesController::getPassportInfo($id);
                                            $item->email = \Illuminate\Support\Facades\DB::table('accounts')->where('employee_id', $id)->value('email');
                                            ?>
                                        <a href="{{action('App\Http\Controllers\EmployeesController@updateView',$id)}}" class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none at3">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        |
                                        <button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none at4" data="{{$id}}">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    <div class="modal fade md1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add employee</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="modal-body bg-white p-0">
                    <div class="card mb-0">
                        <div class="card-body p-4">
                            <form>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-4 col-form-label">Employee Code</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control employee_code" name="" disabled value="{{\App\Http\Controllers\EmployeesController::generateEmployeeCode()}}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-4 col-form-label">First Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control first_name" name="" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-4 col-form-label">Last Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control last_name" name="" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-4 col-form-label">English Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control en_name" name="" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-4 col-form-label">Phone Number</label>
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control phone_number" name="" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <fieldset class="row mb-3">
                                            <legend class="col-form-label col-sm-4 pt-0">Gender</legend>
                                            <div class="col-sm-8">
                                                <div class="d-flex">
                                                    <div class="form-check me-3">
                                                        <input class="form-check-input" type="radio" name="gender" id="male" value="0" checked>
                                                        <label class="form-check-label" for="male">
                                                            Male
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="gender" id="female" value="1">
                                                        <label class="form-check-label" for="female">
                                                            Female
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <fieldset class="row mb-3">
                                            <legend class="col-form-label col-sm-4 pt-0">Marital Status</legend>
                                            <div class="col-sm-8">
                                                <div class="d-flex">
                                                    <div class="form-check me-3">
                                                        <input class="form-check-input" type="radio" name="marital_status" id="single" value="Single" checked>
                                                        <label class="form-check-label" for="single">
                                                            Single
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="marital_status" id="married" value="Married">
                                                        <label class="form-check-label" for="married">
                                                            Married
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <fieldset class="row mb-3">
                                            <legend class="col-form-label col-sm-4 pt-0">Military Service</legend>
                                            <div class="col-sm-8">
                                                <div class="d-flex">
                                                    <div class="form-check me-3">
                                                        <input class="form-check-input" type="radio" name="military_service" id="Done" value="Done" checked>
                                                        <label class="form-check-label" for="Done">
                                                            Done
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="military_service" id="Noyet" value="No yet">
                                                        <label class="form-check-label" for="Noyet">
                                                            No yet
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="row mb-3">
                                            <label for="inputDate" class="col-sm-4 col-form-label" >Date of Birth</label>
                                            <div class="col-sm-8">
                                                <input type="date" class="form-control date_of_birth" name="" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Nation</label>
                                            <div class="col-sm-8">
                                                <select class="form-select selectpicker national" aria-label="Default select example" id="countrySelect" name="">
                                                    <!-- Danh sách các quốc gia sẽ được thêm vào đây -->
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </form><!-- End General Form Elements -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-add">
                        <i class="bi bi-plus-lg me-2"></i>Add
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        var table = $('#employeesTable').DataTable({
            language: { search: "" },
            initComplete: function (settings, json) {
                $('.dt-search').addClass('input-group');
                $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
            },
            responsive: true
        });
        function validateNameInput(input) {
            const regex = /^[a-zA-Z\s]+$/;
            if (!regex.test(input.val())) {
                return false;
            }
            return true;
        }
        let _put = "{{action('App\Http\Controllers\EmployeesController@put')}}";
        let _post = "{{action('App\Http\Controllers\EmployeesController@post')}}";
        let _delete = "{{action('App\Http\Controllers\EmployeesController@delete')}}";
        let _upload = "{{ action('App\Http\Controllers\UploadFileController@uploadFile')}}";
        let _upload_photo = "{{action('App\Http\Controllers\UploadFileController@uploadPhoto')}}";
        let _upload_personal_profile = "{{action('App\Http\Controllers\UploadFileController@uploadPersonalProfile')}}";
        let _upload_medical_checkup = "{{action('App\Http\Controllers\UploadFileController@uploadMedicalCheckUp')}}";
        let _upload_certificate = "{{action('App\Http\Controllers\UploadFileController@uploadCertificate')}}";
        let _check_file_exists = "{{action('App\Http\Controllers\EmployeesController@checkFileExists')}}";
        let _delete_file = "{{action('App\Http\Controllers\EmployeesController@deleteFile')}}";
        let _export = "{{action('App\Http\Controllers\EmployeesController@export')}}";
        function validateForm() {
            const firstName = $('.first_name');
            const lastName = $('.last_name');
            const enName = $('.en_name');
            const phoneNumber = $('.phone_number');
            const dateOfBirth = $('.date_of_birth');
            let countError = 0;
            if (!validateNameInput(firstName)) {
                toastr.error('Name should not contain numbers or special characters.', 'Please enter the first name again!');
                countError++;
            }

            if (!validateNameInput(lastName)) {
                toastr.error('Name should not contain numbers or special characters.', 'Please enter the last name again!');
                countError++;
            }

            if (!validateNameInput(enName)) {
                toastr.error('Name should not contain numbers or special characters.', 'Please enter the English name again!');
                countError++;
            }

            if(phoneNumber.val().length === 0){
                toastr.error('Please enter phone number!', 'Please enter phone number!');
                countError++;
            }
            const dob = new Date(dateOfBirth.val());
            const today = new Date();

            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();

            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            if(dateOfBirth.val().length === 0){
                toastr.error('Please enter date of birth!', 'Please enter date of birth!');
                countError++;
            }else{
                if (age < 18) {
                    toastr.error('User is younger than 18.', 'Please choose a valid date of birth!');
                    countError++;
                }
            }

            return countError === 0;
        }
        $('.at1').click(function () {
            $('.md1').modal('show');

            $('.btn-add').click(function () {
                if(validateForm()){
                    let data = {
                        'employee_code': $('.md1 .employee_code').val(),
                        'first_name': $('.md1 .first_name').val(),
                        'last_name': $('.md1 .last_name').val(),
                        'en_name': $('.md1 .en_name').val(),
                        'gender': $('.md1 input[name="gender"]:checked').val(),
                        'marital_status': $('.md1 input[name="marital_status"]:checked').val(),
                        'military_service': $('.md1 input[name="military_service"]:checked').val(),
                        'date_of_birth': $('.md1 .date_of_birth').val(),
                        'national': $('.md1 .national :checked').val(),
                        'phone_number': $('.md1 .phone_number').val(),
                    };

                    $.ajax({
                        url: _put,
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: data,

                        success: function (result) {
                            result = JSON.parse(result);
                            if (result.status === 200) {
                                toastr.success(result.message, "Thao tác thành công");
                                setTimeout(function () {
                                    window.location.reload();
                                }, 500);
                            } else {
                                toastr.error(result.message, "Thao tác thất bại");
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', '.at4', function () {
            var id = $(this).attr('data');
            if (confirm("Do you want to remove this employee?")) {
                $.ajax({
                    url: _delete,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {'id': id},
                    success: function (result) {
                        result = JSON.parse(result);
                        if (result.status === 200) {
                            toastr.success(result.message, "Thao tác thành công");
                            setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        } else {
                            toastr.error(result.message, "Thao tác thất bại");
                        }
                    }
                });
            }
        });

        function populateCountrySelect(selectElementId, countrySelete) {
            $(document).ready(function() {
                $.ajax({
                    url: 'https://restcountries.com/v3.1/all',
                    method: 'GET',
                    success: function(data) {
                        $.each(data, function(index, country) {
                            const $option = $('<option></option>')
                                .val(country.name.common)
                                .text(country.name.common);
                            if (country.name.common === countrySelete) {
                                $option.prop('selected', true);
                            }

                            $(`#${selectElementId}`).append($option);
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error fetching countries:', textStatus, errorThrown);
                    }
                });
            });
        }
        populateCountrySelect('countrySelect','Vietnam');
    </script>
    <script src="{{asset('assets/js/upload.js')}}"></script>
@endsection
