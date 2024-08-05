@extends('auth.main')
@section('contents')
    <div class="pagetitle">
        <h1>Employees</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item "><a href="{{action('App\Http\Controllers\EmployeesController@getView')}}">Employees List</a></li>
                <li class="breadcrumb-item active">Update Employee</li>
            </ol>
        </nav>
    </div>
    <div class="card mb-0 shadow-none">
        <div class="card-header bg-light fw-semibold">Personal Details</div>
        <div class="card-body p-3">
            <div class="row gx-3">
                <div class="col-lg-6">
                    <div class="row mb-3">
                        <input type="hidden" class="employee_id" value="{{ optional($item)->employee_id ? optional($item)->employee_id : '' }}">
                        <label for="inputText" class="col-sm-4 col-form-label">Employee Code</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control employee_code" name=""
                                   @if(\App\Http\Controllers\AccountController::permission() != '1') disabled @endif
                                   value="{{ optional($item)->employee_code }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">First Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control first_name" name="" value="{{$item->first_name ?? ''}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">Last Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control last_name" name="" value="{{$item->last_name ?? ''}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">English Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control en_name" name="" value="{{$item->en_name ?? ''}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputText" class="col-lg-4 col-form-label">Photo</label>
                        <div class="col-lg-2 text-center">
                            <img class="border rounded-pill object-fit-cover" width="100px" height="100px" id="profileImage" src="{{asset('/assets/img/avt.png')}}" alt="Profile" data="{{$item->photo ?? asset('/assets/img/avt.png')}}">
                        </div>
                        <div class="col-lg-6">
                            <form class="border rounded-4 p-2 text-center" action="{{route('img-store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file"
                                       id="image"
                                       name="image"
                                       data-max-files="1"
                                       data-pdf-preview-height="320"
                                       data-pdf-component-extra-params="toolbar=0&navpanes=0&scrollbar=0&view=fitH">
                                <button class="btn btn-primary mx-auto" type="submit"><i class="bi bi-upload me-2"></i>Upload</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <fieldset class="row mb-3">
                        <legend class="col-form-label col-sm-4 pt-0">Gender</legend>
                        <div class="col-sm-8">
                            <div class="d-flex">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="0" @if($item->gender == "0") checked @endif>
                                    <label class="form-check-label" for="male">
                                        Male
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="1" @if($item->gender == "1") checked @endif>
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
                                    <input class="form-check-input" type="radio" name="marital_status" id="single" value="Single" @if($item->marital_status ?? '' == "Single") checked @endif>
                                    <label class="form-check-label" for="single">
                                        Single
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="marital_status" id="married" value="Married" @if($item->marital_status ?? '' == "Married") checked @endif>
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
                                    <input class="form-check-input" type="radio" name="military_service" id="Done" value="Done" @if($item->military_service ?? '' == "Done") checked @endif>
                                    <label class="form-check-label" for="Done">
                                        Done
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="military_service" id="Noyet" value="No yet" @if($item->military_service ?? '' == "No yet") checked @endif>
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
                            <input type="date" class="form-control date_of_birth" name="date_of_birth" value="{{$item->date_of_birth ?? ''}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Nation</label>
                        <div class="col-sm-8">
                            <select class="form-select selectpicker national" aria-label="Default select example" id="national" name="" data="{{$item->national ?? ''}}">
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-0 shadow-none">
        <div class="card-header bg-light fw-semibold">
            Personal Contacts
        </div>
        <div class="card-body bg-white p-3">
            <form>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-4 col-form-label">Phone Number</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control phone_number" name="" value="{{$item->phone_number ?? ''}}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="inputText" class="col-sm-4 col-form-label">Passport Number</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control passport_number" name="" value="{{ optional(optional($item)->passport)->passport_number ?? '' }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="inputText" class="col-sm-4 col-form-label">Passport Place of Issue</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control passport_place_issue" name="" value="{{ optional(optional($item)->passport)->passport_place_issue ?? '' }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="inputDate" class="col-sm-4 col-form-label">Passport Date of Issue</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control passport_issue_date" name="" value="{{ optional(optional($item)->passport)->passport_issue_date ?? '' }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="inputDate" class="col-sm-4 col-form-label">Passport Date of Expiry</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control passport_expiry_date" name="" value="{{ optional(optional($item)->passport)->passport_expiry_date ?? '' }}">
                    </div>
                </div>

                <div class="row mb-4">
                    <label for="inputText" class="col-sm-4 col-form-label">Citizen identity Card Number</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control cic_number" name="" value="{{$item->cic_number ?? ''}}">
                    </div>
                </div>
                <div class="row mb-4">
                    <label for="inputText" class="col-sm-4 col-form-label">Citizen place of issue</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control cic_place_issue" name="" value="{{$item->cic_place_issue ?? ''}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputDate" class="col-sm-4 col-form-label">CIC date of Issue</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control cic_issue_date" name="" value="{{$item->cic_issue_date ?? ''}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputDate" class="col-sm-4 col-form-label">CIC date of Expiry</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control cic_expiry_date" name="" value="{{$item->cic_expiry_date ?? ''}}">
                    </div>
                </div>
                <div class="row mb-4">
                    <label for="inputText" class="col-sm-4 col-form-label">Place of Residence</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control current_residence" name="" value="{{$item->current_residence ?? ''}}">
                    </div>
                </div>
                <div class="row mb-4">
                    <label for="inputText" class="col-sm-4 col-form-label">Permanent Address</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control permanent_address" name="" value="{{$item->permanent_address ?? ''}}">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mb-0 shadow-none">
        <div class="card-header bg-light fw-semibold">
            Job Details
        </div>
        <div class="card-body p-3">
            <form>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Job Title</label>
                    <div class="col-sm-8">
                        <select class="form-select job_title" aria-label="Default select example" name="">
                            @foreach($jobdetails['jobTitles'] as $itemJob)
                                <option value="{{ optional($itemJob)->job_title_id }}" @if(optional($itemJob)->job_title_id == optional($item)->job_title_id) selected @endif>
                                    {{ optional($itemJob)->job_title ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Job Category</label>
                    <div class="col-sm-8">
                        <select class="form-select job_category" aria-label="Default select example" name="">
                            @foreach($jobdetails['jobCategories'] as $itemJob)
                                <option value="{{ optional($itemJob)->job_category_id }}" @if(optional($itemJob)->job_category_id == optional($item)->job_category_id) selected @endif>
                                    {{ optional($itemJob)->job_category_name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Position</label>
                    <div class="col-sm-8">
                        <select class="form-select job_position" aria-label="Default select example" name="">
                            @foreach($jobdetails['jobPositions'] as $itemJob)
                                <option value="{{ optional($itemJob)->position_id }}" @if(optional($itemJob)->position_id == optional($item)->job_position_id) selected @endif>
                                    {{ optional($itemJob)->position_name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Team</label>
                    <div class="col-sm-8">
                        <select class="form-select job_team" aria-label="Default select example" name="">
                            @foreach($jobdetails['jobTeams'] as $itemJob)
                                <option value="{{ optional($itemJob)->team_id }}" @if(optional($itemJob)->team_id == optional($item)->job_team_id) selected @endif>
                                    {{ optional($itemJob)->team_name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Level</label>
                    <div class="col-sm-8">
                        <select class="form-select job_level" aria-label="Default select example" name="">
                            @foreach($jobdetails['jobLevels'] as $itemJob)
                                <option value="{{ optional($itemJob)->id_level }}" @if(optional($itemJob)->id_level == optional($item)->job_level_id) selected @endif>
                                    {{ optional($itemJob)->level_name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputEmail" class="col-sm-4 col-form-label">Email</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control email" name="" disabled value="{{$item->email ?? ''}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputDate" class="col-sm-4 col-form-label">Start Date</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control start_date" name="" value="{{$item->start_date ?? ''}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputDate" class="col-sm-4 col-form-label">End Date</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control end_date" name="" value="{{$item->end_date ?? ''}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Type of Contract</label>
                    <div class="col-sm-8">
                        <select class="form-select job_type_contract" aria-label="Default select example" name="">
                            @foreach($jobdetails['jobTypeContract'] as $itemJob)
                                <option value="{{ optional($itemJob)->type_contract_id }}" @if(optional($itemJob)->type_contract_id == optional($item)->job_type_contract_id) selected @endif>
                                    {{ optional($itemJob)->type_contract_name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Country</label>
                    <div class="col-sm-8">
                        <select class="form-select job_country" aria-label="Default select example" name="">
                            @foreach($jobdetails['jobCountry'] as $itemJob)
                                <option value="{{ optional($itemJob)->country_id }}" @if(optional($itemJob)->country_id == optional($item)->job_country_id) selected @endif>
                                    {{ optional($itemJob)->country_name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Location</label>
                    <div class="col-sm-8">
                        <select class="form-select job_location" aria-label="Default select example">
                            @foreach($jobdetails['jobLocation'] as $itemJob)
                                <option value="{{ optional($itemJob)->location_id }}" @if(optional($itemJob)->location_id == optional($item)->job_location_id) selected @endif>
                                    {{ optional($itemJob)->location_name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row gx-0">
        <div class="col-6">
            <div class="card mb-0 shadow-none">
                <div class="card-header bg-light fw-semibold">Personal Details</div>
                <div class="card-body p-3 pb-0 table-overflow">
                    <table class="table table-hover mb-0 table-borderless">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Filename</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody class="cv-list">
                        @if(optional($item)->cv)
                            @foreach(json_decode(optional($item)->cv) as $index => $row)
                                <tr>
                                    <td>
                                        {{ $loop->index + 1 }}
                                    </td>
                                    <td>
                                        <a href="{{ asset('/uploads/' . optional($item)->employee_id . '/' . $row) }}">{{ $row }}</a>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn p-0 border-0 bg-transparent shadow-none btn_delete_cv" data_filename="{{ $row }}">
                                            <i class="bi bi-trash3 text-danger"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-3">
                            <span class="fw-bold">File upload</span>
                        </div>
                        <div class="col-8 text-center upload-section border rounded-4 p-3" style="margin-top: 46px;">
                            <input id="personal" class="form-control visually-hidden personal" type="file" multiple="multiple">
                            <div file-input-target="personal" class="file-selector">
                                <i class="bi bi-file-earmark fs-1"></i>
                            </div>
                            <button class="btn btn-primary mt-3 btn_upload_personal_profile"><i class="bi bi-file-earmark-arrow-up me-3"></i>Upload</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card mb-0 shadow-none">
                <div class="card-header bg-light fw-semibold">Medical CheckUp</div>
                <div class="card-body p-3 pb-0 table-overflow">
                    <table class="table table-hover mb-0 table-borderless">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Filename</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody class="medical_list">
                        @php
                            $medicalRecords = json_decode(optional($item)->medical);
                        @endphp

                        @if($medicalRecords)
                            @foreach($medicalRecords as $index => $row)
                                <tr>
                                    <td>
                                        {{ $loop->index + 1 }}
                                    </td>
                                    <td>
                                        <a href="{{ asset('/uploads/' . optional($item)->employee_id . '/' . optional($row)->medical_checkup_file) }}">
                                            {{ optional($row)->medical_checkup_file }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ optional($row)->medical_checkup_issue_date }}
                                    </td>
                                    <td class="text-center">
                                        <button class="btn p-0 border-0 bg-transparent shadow-none btn_delete_medical"
                                                data_id_medical="{{ optional($row)->medical_checkup_id }}"
                                                data_filename="{{ optional($row)->medical_checkup_file }}">
                                            <i class="bi bi-trash3 text-danger"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-3">
                            <span class="fw-bold">File upload</span>
                        </div>
                        <div class="col-8 text-center upload-section ">
                            <input type="date" class="form-control medical_checkupdate mb-2">
                            <div class="border rounded-4 p-3">
                                <input id="medical_checkup" class="form-control visually-hidden medical_checkup" type="file" multiple="multiple">
                                <div file-input-target="medical_checkup" class="file-selector">
                                    <i class="bi bi-file-earmark fs-1"></i>
                                </div>
                                <button class="btn btn-primary mt-3 btn_upload_medical"><i class="bi bi-file-earmark-arrow-up me-3"></i>Upload</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-5 shadow-none">
        <div class="card-header bg-light fw-semibold">Certificate</div>
        <div class="card-body p-3 table-overflow">
            <table class="table table-hover table-borderless">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">File Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Expiry Date</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody class="certificate_list">
                @php
                    $certificates = json_decode(optional($item)->certificates);
                @endphp

                @if($certificates)
                    @foreach($certificates as $index => $row)
                        <tr>
                            <td>
                                {{ $loop->index + 1 }}
                            </td>
                            <td>
                                <a href="{{ asset('/uploads/' . optional($item)->employee_id . '/' . optional($row)->certificate) }}">
                                    {{ optional($row)->certificate }}
                                </a>
                            </td>
                            <td>
                                {{ optional($row)->certificate_type_name }}
                            </td>
                            <td>
                                {{ optional($row)->end_date_certificate }}
                            </td>
                            <td class="text-center">
                                <button class="btn p-0 border-0 bg-transparent shadow-none btn_delete_certificate"
                                        data_id_certificate="{{ optional($row)->certificate_id }}"
                                        data_filename="{{ optional($row)->certificate }}">
                                    <i class="bi bi-trash3 text-danger"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="row g-4">
                <div class="col-2" style="width: 139px;">
                    <span class="fw-bold">File upload</span>
                </div>
                <div class="col-4 text-center upload-section border rounded-4 p-3">
                    <input id="certificate_file" class="form-control visually-hidden certificate_file" type="file" multiple="multiple">
                    <div file-input-target="certificate_file" class="file-selector">
                        <i class="bi bi-file-earmark fs-1"></i>
                    </div>
                    <button class="btn btn-primary mt-3 btn_upload_certificate"><i class="bi bi-file-earmark-arrow-up me-3"></i>Upload</button>
                </div>
                <div class="col-4">
                    <select class="form-select type_certificate mb-3" aria-label="Default select example">
                        @foreach($type_certificate as $item)
                            <option value="{{$item->certificate_type_id}}">{{$item->certificate_type_name}}</option>
                        @endforeach
                    </select>
                    <input class="form-control certificate_end_date" type="date" id="">
                </div>
            </div>
        </div>
        <div class="card mb-0 shadow-none">
            <div class="card-header text-end">
                <button type="submit" class="btn btn-primary btn-update"><i class="bi bi-floppy me-3"></i>Update</button>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
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
        populateCountrySelect('national',$('#national').attr('data'));
        let _post = "{{action('App\Http\Controllers\EmployeesController@post')}}";
        let _upload_photo = "{{action('App\Http\Controllers\UploadFileController@uploadPhoto')}}";
        let _upload_personal_profile = "{{action('App\Http\Controllers\UploadFileController@uploadPersonalProfile')}}";
        let _upload_medical_checkup = "{{action('App\Http\Controllers\UploadFileController@uploadMedicalCheckUp')}}";
        let _upload_certificate = "{{action('App\Http\Controllers\UploadFileController@uploadCertificate')}}";
        let _check_file_exists = "{{action('App\Http\Controllers\EmployeesController@checkFileExists')}}";
        let _delete_file = "{{action('App\Http\Controllers\EmployeesController@deleteFile')}}";

        let defaultImage = "{{ asset('assets/img/avt.png') }}";
        $.ajax({
            url: _check_file_exists,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { path: $('#profileImage').attr('data') },
            success: function(response) {
                if (response.exists) {
                    $('#profileImage').attr('src', '{{asset('')}}' + $('#profileImage').attr('data'));
                } else {
                    $('#profileImage').attr('src', defaultImage);
                }
            }
        });

        $('.btn_photo').click(function () {
            event.preventDefault();
            let filePhoto = $('#photoUpdate')[0].files[0];
            if(!filePhoto){
                toastr.error('Please choose file!','Error');
                return;
            }
            let formData = new FormData();

            if (filePhoto) {
                formData.append('photo', filePhoto);
            }

            formData.append('employee_code', $(".employee_code").val());

            $.ajax({
                url: _upload_photo,
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
        })

        $('.btn_upload_personal_profile').click(function () {
            event.preventDefault();
            let files = $('.personal')[0].files;
            if (files.length === 0) {
                toastr.error('Please choose a file!', 'Error');
                return;
            }
            let formData = new FormData();

            if (files.length > 0) {
                $.each(files, function(i, file) {
                    formData.append('personal_profile[]', file);
                });
            }

            formData.append('employee_code', $(".employee_code").val());

            $.ajax({
                url: _upload_personal_profile, // Đảm bảo rằng _upload_personal_profile là URL hợp lệ
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (typeof result === 'string') {
                        result = JSON.parse(result); // Nếu result là chuỗi JSON, chuyển nó thành object
                    }
                    if (result.status === 200) {
                        toastr.success(result.message, "Thao tác thành công");
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        toastr.error(result.message, "Thao tác thất bại");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    toastr.error("An error occurred during the upload process", "Thao tác thất bại");
                }
            });
        });
        $('.btn_upload_medical').click(function () {
            event.preventDefault();
            let file = $('.medical_checkup')[0].files[0];
            if(!file){
                toastr.error('Please choose file!','Error');
                return;
            }
            let formData = new FormData();
            formData.append('medical_file', file);
            formData.append('employee_code', $(".employee_code").val());
            formData.append('medical_checkup_date', $('.medical_checkupdate').val());
            $.ajax({
                url: _upload_medical_checkup,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (typeof result === 'string') {
                        result = JSON.parse(result); // Nếu result là chuỗi JSON, chuyển nó thành object
                    }
                    if (result.status === 200) {
                        toastr.success(result.message, "Thao tác thành công");
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        toastr.error(result.message, "Thao tác thất bại");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    toastr.error("An error occurred during the upload process", "Thao tác thất bại");
                }
            });
        })
        $('.btn_upload_certificate').click(function () {
            event.preventDefault();
            let file = $('.certificate_file')[0].files[0];
            if(!file){
                toastr.error('Please choose file!','Error');
                return;
            }
            let formData = new FormData();
            formData.append('certificate_file', file);
            formData.append('employee_code', $(".employee_code").val());
            formData.append('certificate_end_date', $('.certificate_end_date').val());
            formData.append('type_certificate', $('.type_certificate').val());
            $.ajax({
                url: _upload_certificate,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (result) {
                    if (typeof result === 'string') {
                        result = JSON.parse(result);
                    }
                    if (result.status === 200) {
                        toastr.success(result.message, "Thao tác thành công");
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        toastr.error(result.message, "Thao tác thất bại");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    toastr.error("An error occurred during the upload process", "Thao tác thất bại");
                }
            });
        })
        $('.btn_delete_cv').click(function (event) {
            event.preventDefault();
            let employee_code = $(".employee_code").val();
            let filename = $(this).attr('data_filename');
            let formData = new FormData();
            formData.append('employee_code', employee_code);
            formData.append('filename', filename);
            formData.append('file_of', "cv");
            if(confirm('Are you sure DELETE this file?')){
                $.ajax({
                    url: _delete_file,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (result) {
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

        $('.btn_delete_medical').click(function () {
            event.preventDefault();
            let employee_code = $(".employee_code").val();
            let medical_checkup_id = $(this).attr('data_id_medical');
            let filename = $(this).attr('data_filename');
            let formData = new FormData();
            formData.append('employee_code', employee_code);
            formData.append('medical_checkup_id', medical_checkup_id);
            formData.append('filename', filename);
            formData.append('file_of', "medical");
            if(confirm('Are you sure DELETE this file?')){
                $.ajax({
                    url: _delete_file,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (result) {
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
        })

        $('.btn_delete_certificate').click(function () {
            event.preventDefault();
            let employee_code = $(".employee_code").val();
            let certificate_id = $(this).attr('data_id_certificate');
            let filename = $(this).attr('data_filename');
            let formData = new FormData();
            formData.append('employee_code', employee_code);
            formData.append('certificate_id', certificate_id);
            formData.append('filename',filename);
            formData.append('file_of', "certificate");
            if(confirm('Are you sure DELETE this file ?')){
                $.ajax({
                    url: _delete_file,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (result) {
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
        })

        $('.btn-update').click(function () {
            event.preventDefault();
            let dataEmployee = {
                'employee_code': $('.employee_code').val(),
                'first_name': $('.first_name').val(),
                'last_name': $('.last_name').val(),
                'en_name': $('.en_name').val(),
                'gender': $('input[name="gender"]:checked').val(),
                'marital_status': $('input[name="marital_status"]:checked').val(),
                'military_service': $('input[name="military_service"]:checked').val(),
                'date_of_birth': $('.date_of_birth').val(),
                'national': $('.national :checked').val()
            };
            let dataPassport = {
                'passport_number': $('.passport_number').val(),
                'passport_place_issue': $('.passport_place_issue').val(),
                'passport_issue_date': $('.passport_issue_date').val(),
                'passport_expiry_date': $('.passport_expiry_date').val(),
            };
            let dataContact = {
                'phone_number' : $('.phone_number').val(),
                'cic_number' : $('.cic_number').val(),
                'cic_place_issue' : $('.cic_place_issue').val(),
                'cic_issue_date': $('.cic_issue_date').val(),
                'cic_expiry_date':$('.cic_expiry_date').val(),
                'current_residence' : $('.current_residence').val(),
                'permanent_address':$('.permanent_address').val(),
            };
            let dataJob = {
                'job_title_id': $('.job_title').val(),
                'job_category_id': $('.job_category').val(),
                'job_position_id': $('.job_position').val(),
                'job_team_id': $('.job_team').val(),
                'job_level_id': $('.job_level').val(),
                'start_date': $('.start_date').val(),
                'end_date': $('.end_date').val(),
                'job_type_contract_id': $('.job_type_contract').val(),
                'job_country_id': $('.job_country').val(),
                'job_location_id': $('.job_location').val(),
            };

            let formData = new FormData();
            // formData.append('employee_id', data.employee_id);
            // formData.append('id_contact', data.id_contact);
            formData.append('employee_code', $('.employee_code').val());
            formData.append('dataEmployee', JSON.stringify(dataEmployee));
            formData.append('dataContact', JSON.stringify(dataContact));
            formData.append('dataJob', JSON.stringify(dataJob));
            formData.append('dataPassport', JSON.stringify(dataPassport));
            $.ajax({
                url: _post,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                processData: false,
                contentType: false,
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
        })


        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
        );

        FilePond.create(
            document.querySelector('#image'),
            {
                allowPdfPreview: true,
                pdfPreviewHeight: 320,
                pdfComponentExtraParams: 'toolbar=0&view=fit&page=1',
                server: {
                    process: {
                        url: '{{route('img-upload')}}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        ondata: (formData) => {
                            formData.append('_token', csrfToken);
                            formData.append('employee_code',$('.employee_code').val())
                            return formData;
                        },
                    },
                    revert:{
                        url: '{{route('img-delete')}}',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    }
                },
            }
        );

    </script>
    <script src="{{asset('assets/js/upload.js')}}"></script>
@endsection
