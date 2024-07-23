@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Employees</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Employees</li>
            </ol>
        </nav>
    </div>

    <section class="section employees">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Employee List
                    </div>
                    <div class="card-body mt-2">
                        <div class="btn btn-primary mx-2">
                            <div class="d-flex align-items-center at1">
                                <i class="bi bi-file-earmark-plus-fill pe-2"></i>
                                Add
                            </div>
                        </div>
                        <div class="btn btn-success mx-2">
                            <div class="d-flex align-items-center at2">
                                <i class="bi bi-file-earmark-arrow-up-fill pe-2"></i>
                                Import
                            </div>
                        </div>
                        <div class="btn btn-success mx-2">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-arrow-down-fill pe-2"></i>
                                Export
                            </div>
                        </div>
                        <div class="card-body border-bottom py-3">
                            <div class="d-flex">
                                <div class="ms-auto text-secondary">
                                    Search:
                                    <div class="ms-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>

                                    <tr>
                                        <th>Employee Code</th>
                                        <th>Photo</th>
                                        <th>Full Name</th>
                                        <th>English Name</th>
                                        <th>Gender</th>
                                        <th>Phone</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                @foreach($data as $item)
                                    <tr>
                                        <td>{{$item->employee_code}}</td>
                                        <td><img src="{{asset('/uploads/'.$item->id_employee.'/'.$item->photo)}}" alt="" width="75" height="75"></td>
                                        <td>{{$item->first_name . ' ' . $item->last_name}}</td>
                                        <td>{{$item->en_name}}</td>
                                        <td>{{$item->gender == 0 ? "Nam" : "Nữ"}}</td>
                                        <td>{{$item->phone_number}}</td>
                                        <td>
                                            <button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            |
                                            <button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($data->hasPages())
                            <div class="box-footer">
                                {{$data->links('auth.component.pagination')}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade md1  ">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-bold"></h4>
                </div>
                <div class="modal-body bg-light">
                    <div class="row p-3">
                        <div class="card border border-primary rounded-4 p-0 m-0">
                            <div class="card-header bg-primary text-white rounded-top-4 fs-5 mb-2">
                                Personal Details
                            </div>
                            <div class="card-body bg-white rounded-bottom-4">
                                <form>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="row mb-3">
                                                <label for="inputText" class="col-sm-4 col-form-label">Employee Code</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control employee_code" name="">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputText" class="col-sm-4 col-form-label">First Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control first_name" name="">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputText" class="col-sm-4 col-form-label">Last Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control last_name" name="">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputText" class="col-sm-4 col-form-label">English Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control en_name" name="">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputText" class="col-sm-4 col-form-label">Photo</label>
                                                <div class="col-sm-8">
                                                    <input type="file" class="form-control photo" name="">
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
                                                <label for="inputDate" class="col-sm-4 col-form-label">Date of Birth</label>
                                                <div class="col-sm-8">
                                                    <input type="date" class="form-control date_of_birth" name="">
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
                    <div class="row p-3">
                        <div class="card border border-primary rounded-4 p-0">
                            <div class="card-header bg-primary text-white rounded-top-4 fs-5 mb-2">
                                Personal Contacts
                            </div>
                            <div class="card-body bg-white rounded-bottom-4">
                                <!-- General Form Elements -->
                                <form>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-4 col-form-label">Phone Number</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control phone_number" name="">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-4 col-form-label">Passport Number</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control passport_number" name="">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-4 col-form-label">Passport place of Issue</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control passport_place_issue" name="">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputDate" class="col-sm-4 col-form-label">Passport date of Issue</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control passport_issue_date" name="">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputDate" class="col-sm-4 col-form-label">Passport date of Expiry</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control passport_expiry_date" name="">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="inputText" class="col-sm-4 col-form-label">Citizen identity Card Number</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control cic_number" name="">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="inputText" class="col-sm-4 col-form-label">Citizen place of issue</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control cic_place_issue" name="">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputDate" class="col-sm-4 col-form-label">CIC date of Issue</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control cic_issue_date" name="">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputDate" class="col-sm-4 col-form-label">CIC date of Expiry</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control cic_expiry_date" name="">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="inputText" class="col-sm-4 col-form-label">Place of Residence</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control current_residence" name="">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="inputText" class="col-sm-4 col-form-label">Permanent Address</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control permanent_address" name="">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputDate" class="col-sm-4 col-form-label">Medical Check-up Date</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control medical_checkup_date" name="">
                                        </div>
                                    </div>
                                </form><!-- End General Form Elements -->
                            </div>
                        </div>
                    </div>
                    <div class="row p-3">
                        <div class="card border border-primary rounded-4 p-0 m-0">
                            <div class="card-header bg-primary text-white rounded-top-4 fs-5 mb-2">
                                Job Details
                            </div>
                            <div class="card-body bg-white rounded-bottom-4">

                            <!-- General Form Elements -->
                            <form>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Job Title</label>
                                    <div class="col-sm-8">
                                        <select class="form-select job_title" aria-label="Default select example" name="">
                                            @foreach($jobdetails['jobTitles'] as $item)
                                                <option value="{{$item->id_job_title}}">{{$item->job_title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Job Category</label>
                                    <div class="col-sm-8">
                                        <select class="form-select job_category" aria-label="Default select example" name="">
                                            @foreach($jobdetails['jobCategories'] as $item)
                                                <option value="{{$item->id_job_category}}">{{$item->job_category_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Position</label>
                                    <div class="col-sm-8">
                                        <select class="form-select job_position" aria-label="Default select example" name="">
                                            @foreach($jobdetails['jobPositions'] as $item)
                                                <option value="{{$item->id_position}}">{{$item->position_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Team</label>
                                    <div class="col-sm-8">
                                        <select class="form-select job_team" aria-label="Default select example" name="">
                                            @foreach($jobdetails['jobTeams'] as $item)
                                                <option value="{{$item->id_team}}">{{$item->team_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Level</label>
                                    <div class="col-sm-8">
                                        <select class="form-select job_level" aria-label="Default select example" name="">
                                            @foreach($jobdetails['jobLevels'] as $item)
                                                <option value="{{$item->id_level}}">{{$item->level_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputEmail" class="col-sm-4 col-form-label">Email</label>
                                    <div class="col-sm-8">
                                        <input type="email" class="form-control email" name="">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputDate" class="col-sm-4 col-form-label">Start Date</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control start_date" name="">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputDate" class="col-sm-4 col-form-label">End Date</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control end_date" name="">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Type of Contract</label>
                                    <div class="col-sm-8">
                                        <select class="form-select job_type_contract" aria-label="Default select example" name="">
                                            @foreach($jobdetails['jobTypeContract'] as $item)
                                                <option value="{{$item->id_type_contract}}">{{$item->type_contract_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Country</label>
                                    <div class="col-sm-8">
                                        <select class="form-select job_country" aria-label="Default select example" name="">
                                            @foreach($jobdetails['jobCountry'] as $item)
                                                <option value="{{$item->id_country}}">{{$item->country_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Location</label>
                                    <div class="col-sm-8">
                                        <select class="form-select job_location" aria-label="Default select example">
                                            @foreach($jobdetails['jobLocation'] as $item)
                                                <option value="{{$item->id_location}}">{{$item->location_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form><!-- End General Form Elements -->
                            </div>
                        </div>
                    </div>
                    <div class="row p-3">
                        <div class="col-6 p-0 pe-2">
                            <div class="card border border-primary rounded-4 p-0">
                                <div class="card-header bg-primary text-white rounded-top-4 fs-5 mb-2">
                                    Personal Details
                                </div>
                                <div class="card-body bg-white rounded-bottom-4">

                                    <!-- General Form Elements -->
                                    <form>
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">File Name</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr></tr>
                                            </tbody>
                                        </table>
                                        <div class="row mb-3">
                                            <label for="inputNumber" class="col-sm-2 col-form-label">Upload</label>
                                            <div class="col-sm-10">
                                                <input class="form-control personal" type="file" id="" multiple="multiple">
                                            </div>
                                        </div>
                                    </form><!-- End General Form Elements -->
                                </div>
                            </div>
                        </div>
                        <div class="col-6 p-0 ps-2">
                            <div class="card border border-primary rounded-4">
                                <div class="card-header bg-primary text-white rounded-top-4 fs-5 mb-2">
                                    Certificate
                                </div>
                                <div class="card-body bg-white rounded-bottom-4">
                                    <form>
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">File Name</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr></tr>
                                            </tbody>
                                        </table>
                                        <div class="row mb-3">
                                            <label for="inputNumber" class="col-sm-2 col-form-label">Upload</label>
                                            <div class="col-sm-10">
                                                <input class="form-control certificate" type="file" id="" multiple="multiple">
                                            </div>
                                        </div>
                                    </form><!-- End General Form Elements -->
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-add">Add</button>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
    <div class="modal fade md2">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-bold"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Choose file (*.xlsx) or download
                            </label>
                            <a href="">Example</a>
                            <input accept=".xlsx" name="file-excel" type="file" class="form-control">
                            <br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-upload">Upload</button>
                </div>
            </div>
        </div>
{{--    </div>--}}
{{--    <form id="uploadForm" enctype="multipart/form-data">--}}
{{--        @csrf--}}
{{--        <div class="mb-3">--}}
{{--            <label for="formFileSm" class="form-label">Small file input example</label>--}}
{{--            <input class="form-control form-control-sm" id="formFileSm" name="file" type="file">--}}
{{--        </div>--}}
{{--        <button class="btn btn-primary" type="submit">Upload</button>--}}
{{--    </form>--}}
@endsection
@section('script')
    <script>
        $('.at1').click(function () {
            $('.md1 .modal-title').text('Add Employee');
            $('.md1').modal('show');


        });
        $('.at2').click(function () {
            $('.md2 .modal-title').text('Import Employee');
            $('.md2').modal('show');
        });

        document.addEventListener('DOMContentLoaded', function () {
            const countrySelect = document.getElementById('countrySelect');

            fetch('https://restcountries.com/v3.1/all')
                .then(response => response.json())
                .then(data => {
                    data.forEach(country => {
                        const option = document.createElement('option');
                        option.value = country.name.common;
                        option.textContent = country.name.common;
                        if (country.name.common === 'Vietnam') {
                            option.selected = true;
                        }
                        countrySelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching countries:', error);
                });
        });

        let _put = "{{action('App\Http\Controllers\EmployeesController@put')}}";
        let _post = "{{action('App\Http\Controllers\EmployeesController@post')}}";
        let _delete = "{{action('App\Http\Controllers\EmployeesController@delete')}}";
        let _upload = "{{ action('App\Http\Controllers\UploadFileController@uploadFile')}}";
        $('.btn-add').click(function () {
            // Collect form data
            let data = {
                'employee_code': $('.employee_code').val(),
                'first_name': $('.first_name').val(),
                'last_name': $('.last_name').val(),
                'en_name': $('.en_name').val(),
                'gender': $('input[name="gender"]:checked').val(),
                'marital_status': $('input[name="marital_status"]:checked').val(),
                'military_service': $('input[name="military_service"]:checked').val(),
                'date_of_birth': $('.date_of_birth').val(),
                'national': $('.national :checked').val(),
                'phone_number': $('.phone_number').val(),
                'passport_number': $('.passport_number').val(),
                'passport_place_issue': $('.passport_place_issue').val(),
                'passport_issue_date': $('.passport_issue_date').val(),
                'passport_expiry_date': $('.passport_expiry_date').val(),
                'cic_number': $('.cic_number').val(),
                'cic_place_issue': $('.cic_place_issue').val(),
                'cic_issue_date': $('.cic_issue_date').val(),
                'cic_expiry_date': $('.cic_expiry_date').val(),
                'current_residence': $('.current_residence').val(),
                'permanent_address': $('.permanent_address').val(),
                'medical_checkup_date': $('.medical_checkup_date').val(),
                'job_title': $('.job_title').val(),
                'job_category': $('.job_category').val(),
                'job_position': $('.job_position').val(),
                'job_team': $('.job_team').val(),
                'job_level': $('.job_level').val(),
                'email': $('.email').val(),
                'start_date': $('.start_date').val(),
                'end_date': $('.end_date').val(),
                'job_type_contract': $('.job_type_contract').val(),
                'job_country': $('.job_country').val(),
                'job_location': $('.job_location').val()
            };

            $.ajax({
                url: _put,
                type: "PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                success: function (result) {
                    result = JSON.parse(result);

                    if (result.status === 200) {
                        // File upload handling
                        let filePhoto = $('.photo')[0].files[0];
                        let filePersonal = $('.personal')[0].files;
                        let fileCertificates = $('.certificate')[0].files;
                        let formData = new FormData();

                        if (filePhoto) {
                            formData.append('photo', filePhoto);
                        }

                        if (filePersonal.length > 0) {
                            for (let i = 0; i < filePersonal.length; i++) {
                                formData.append('personal[]', filePersonal[i]);
                            }
                        }

                        if (fileCertificates.length > 0) {
                            for (let i = 0; i < fileCertificates.length; i++) {
                                formData.append('certificate[]', fileCertificates[i]);
                            }
                        }
                        formData.append('id_employee', result.id_employee);

                        $.ajax({
                            url: _upload,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            contentType: false,
                            processData: false,
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
                    } else {
                        toastr.error(result.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred while saving employee data');
                }
            });
        });
    </script>
@endsection
