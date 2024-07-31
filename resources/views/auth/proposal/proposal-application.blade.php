@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Proposal</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Proposal Application</li>
            </ol>
        </nav>
    </div>

    <section class="section employees">
        <div class="card">
            <div class="card-header py-0">
                <div class="card-title my-3 p-0">Proposal Application</div>
            </div>
            <div class="card-body">
                <div class="row gx-3 my-3">
                    <div class="col-md-6 m-0">
                        <div class="btn btn-primary mx-2">
                            <div class="d-flex align-items-center at1">
                                <i class="bi bi-file-earmark-plus pe-2"></i>
                                Add
                            </div>
                        </div>
                        <div class="btn btn-success mx-2 btn-export">
                            <a href="" class="d-flex align-items-center text-white">
                                <i class="bi bi-file-earmark-arrow-down pe-2"></i>
                                Export
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 m-0">
                        <div class="input-group ms-sm-auto w-50">
                            <button class="input-group-text bg-transparent border-secondary rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>
                            <input type="text" class="form-control border-start-0 border-secondary rounded-end-4">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable table-hover table-borderless">
                        <thead>
                        <tr>
                            <th style="width: 10%">No</th>
                            <th class="text-center">Employee Name</th>
                            <th>Proposal Name Type</th>
                            <th>Description</th>
                            <th>Progress</th>
                        </tr>

                        </thead>
                        <tbody>
{{--                        @foreach($data as $item)--}}
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
{{--                        @endforeach--}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                @if ($data->hasPages())
                    <div class="">
                        {{$data->links('auth.component.pagination')}}
                    </div>
                @endif
            </div>
        </div>
    </section>
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
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-4 col-form-label">Phone Number</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control phone_number" name="">
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
    </div>

    <div class="modal fade md3">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Update Employee</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="modal-body bg-light p-0">
                    <div class="card mb-0 shadow-none">
                        <div class="card-header bg-light fw-semibold">Personal Details</div>
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-6">
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-4 col-form-label">Employee Code</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control employee_code" name="" disabled>
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
                                            <div class="row">
                                                <div class="col-md-2 position-relative text-center">
                                                    <img
                                                        id="profileImage"
                                                        src="{{asset('/assets/img/avt.png')}}"
                                                        alt="Profile" class="rounded-pill object-fit-cover photo_show" width="100"
                                                        height="100">
                                                    <div class="overlay-upload position-absolute d-flex justify-content-center align-items-center">
                                                        <i class="bi bi-camera text-white fw-bold fs-2"></i>
                                                        <input type="file" id="fileInput" class="form-control photo visually-hidden" name="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mt-2 text-center">
                                                    <button class="btn btn-primary btn_photo rounded-4 d-none">
                                                        Upload
                                                    </button>
                                                </div>
                                            </div>
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
                                            <input type="date" class="form-control date_of_birth" name="date_of_birth">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Nation</label>
                                        <div class="col-sm-8">
                                            <select class="form-select selectpicker national" aria-label="Default select example" id="national" name="">
                                                <!-- Danh sách các quốc gia sẽ được thêm vào đây -->
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
                                        <input type="email" class="form-control email" name="" disabled>
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
                                        <tbody class="cv-list"></tbody>
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
                                        <tbody class="medical_list"></tbody>
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
                    <div class="card mb-0 shadow-none">
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
                                <tbody class="certificate_list"></tbody>
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
                                            <option value="{{$item->id_certificate_type}}">{{$item->certificate_type_name}}</option>
                                        @endforeach
                                    </select>
                                    <input class="form-control certificate_end_date" type="date" id="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-update"><i class="bi bi-floppy me-3"></i>Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

    </script>
@endsection
