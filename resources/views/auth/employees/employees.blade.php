@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Employees</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Employees List</li>
            </ol>
        </nav>
    </div>

    <section class="section employees">
        <div class="card">
            <div class="card-header py-0">
                <div class="card-title my-3 p-0">Employees List</div>
            </div>
            <div class="card-body">
                <div class="row gx-3 my-3">
                    <div class="col-md-6 m-0">
                        <div class="btn btn-primary me-2">
                            <div class="d-flex align-items-center at1">
                                <i class="bi bi-file-earmark-plus pe-2"></i>
                                Add
                            </div>
                        </div>
                        <div class="btn btn-success mx-2">
                            <a href="{{action('App\Http\Controllers\EmployeesController@importView')}}" class="d-flex align-items-center at2 text-white">
                                <i class="bi bi-file-earmark-arrow-up pe-2"></i>
                                Import
                            </a>
                        </div>
                        <div class="btn btn-success mx-2 btn-export">
                            <a href="{{action('App\Http\Controllers\EmployeesController@export')}}" class="d-flex align-items-center text-white">
                                <i class="bi bi-file-earmark-arrow-down pe-2"></i>
                                Export
                            </a>
                        </div>
                    </div>
                </div>
                <table id="employeesTable" class="table table-hover table-borderless">
                    <thead class="table-light">
                        <tr>
                            <th>Employee Code</th>
                            <th class="text-center">Photo</th>
                            <th>Full Name</th>
                            <th>English Name</th>
                            <th>Gender</th>
                            <th>Phone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="employeesTableBody">
                        @foreach($data as $item)
                            @if($item->fired == "false")
                                <tr>
                                    <td><a href="{{action('App\Http\Controllers\EmployeesController@getEmployee', $item->employee_id)}}">{{$item->employee_code}}</a></td>
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
                                    <td>{{$item->gender == 0 ? "Nam" : "Nữ"}}</td>
                                    <td>{{$item->phone_number}}</td>
                                    <td>
                                            <?php
                                            $id = $item->employee_id;
                                            $item->medical = \App\Http\Controllers\EmployeesController::getMedicalInfo($id);
                                            $item->certificates = \App\Http\Controllers\EmployeesController::getCertificateInfo($id);
                                            $item->passport = \App\Http\Controllers\EmployeesController::getPassportInfo($id);
                                            $item->email = \Illuminate\Support\Facades\DB::table('accounts')->where('employee_id', $id)->value('email');
                                            ?>
                                        <a href="#" class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none at3" data="{{\App\Http\Controllers\AccountController::toAttrJson($item)}}">
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
                </table>
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
                                                <input type="text" class="form-control employee_code" name="" disabled value="{{\App\Http\Controllers\EmployeesController::generateEmployeeCode()}}">
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
                                            <label for="inputText" class="col-sm-4 col-form-label">Phone Number</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control phone_number" name="">
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
                                            <input type="text" class="form-control employee_code" name="" @if(\App\Http\Controllers\AccountController::permission() != '1') disabled @endif>
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
                                                <div photo-input-target="photoUpdate" class="col-md-2 photo-upload">
                                                    <img id="profileImage" src="{{asset('/assets/img/avt.png')}}" alt="Profile">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mt-2 text-center">
                                                    <button class="btn btn-primary btn_photo rounded-4">Upload</button>
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

        $('.at1').click(function () {
            $('.md1').modal('show');

            $('.btn-add').click(function () {
                // Collect form data
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
            });
        });


        $(document).on('click', '.at3', function() {
            var data = JSON.parse($(this).attr('data'));
            $('.btn_photo').click(function () {
                event.preventDefault();
                let filePhoto = $('.md3 #photoUpdate')[0].files[0];
                let formData = new FormData();

                if (filePhoto) {
                    formData.append('photo', filePhoto);
                }

                formData.append('employee_id', data.employee_id);

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
                let files = $('.md3 .personal')[0].files;
                let formData = new FormData();

                if (files.length > 0) {
                    $.each(files, function(i, file) {
                        formData.append('personal_profile[]', file);
                    });
                }

                formData.append('employee_id', data.employee_id);

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

            //clear image when modal close
            {{--$('.md3').on('hidden.bs.modal', function () {--}}
            {{--    if ($('.md3').css('display') === 'none') {--}}
            {{--        $('.md3 #profileImage').attr('src','{{asset('assets/img/avt.png')}}');--}}
            {{--        $('.md3 .photo-upload').val('');--}}
            {{--        console.log($('#photoUpdate').val());--}}
            {{--    }--}}
            {{--});--}}

            $('.md3 .btn_upload_medical').click(function () {
                event.preventDefault();
                let file = $('.md3 .medical_checkup')[0].files[0];
                let formData = new FormData();


                formData.append('medical_file', file);
                formData.append('employee_id', data.employee_id);
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
            $('.md3 .btn_upload_certificate').click(function () {
                event.preventDefault();
                let file = $('.md3 .certificate_file')[0].files[0];
                let formData = new FormData();
                formData.append('certificate_file', file);
                formData.append('employee_id', data.employee_id);
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

            populateCountrySelect('national',data.national);
            $('.md3 .employee_code').val(data.employee_code);
            $('.md3 .first_name').val(data.first_name);
            $('.md3 .last_name').val(data.last_name);
            $('.md3 .en_name').val(data.en_name);
            let imagePath = "/uploads/" + data.employee_id;
            let defaultImage = "{{ asset('assets/img/avt.png') }}";
            console.log(data.photo)
            $.ajax({
                url: _check_file_exists, // Create a route to check if the file exists
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { path: data.photo },
                success: function(response) {
                    if (response.exists) {
                        console.log('{{asset('')}}' + data.photo);
                        $('.md3 .photo_show').attr('src', '{{asset('')}}' + data.photo);
                    } else {
                        $('.md3 .photo_show').attr('src', defaultImage);
                    }
                }
            });

            $('.md3 .photo_show').attr('src', '');
            $('.cv-list').empty();
            $('.certificate_list').empty();
            $('.medical_list').empty();
            let dataCV = JSON.parse(data.cv);
            if(dataCV){
                dataCV.forEach(function(filename, index) {
                    let cvLink = '{{ asset('/uploads/') }}' + '/' + data.employee_id + '/' + filename;
                    let row = '<tr><td >' + (index + 1) + '</td><td ><a href="' + cvLink + '" target="_blank">' + filename + '</a></td><td class="text-center"><button class="btn p-0 border-0 bg-transparent shadow-none btn_delete_cv"  data_filename="'+filename+'"><i class="bi bi-trash3 text-danger"></i></button></td></tr>';
                    $('.cv-list').append(row);
                });
            }


            let dataMedical = JSON.parse(data.medical);
            if(dataMedical){
                dataMedical.forEach(function(item, index) {
                    let medicalLink = '{{ asset('/uploads/') }}' + '/' + data.employee_id + '/' + item.medical_checkup_file;
                    let row = '<tr><td >' + (index + 1) + '</td><td ><a href="' + medicalLink + '" target="_blank">' + item.medical_checkup_file + '</a></td><td >'+ item.medical_checkup_issue_date+'</td><td class="text-center"><button class="btn p-0 border-0 bg-transparent shadow-none btn_delete_medical" data_id_medical="'+item.id_medical_checkup+'"  data_filename="'+item.medical_checkup_file+'"><i class="bi bi-trash3 text-danger"></i></button></td></tr>';
                    $('.medical_list').append(row);
                });
            }

            let dataCertificate = JSON.parse(data.certificates);
            if(dataCertificate){
                dataCertificate.forEach(function(item, index) {
                    let certificateLink = '{{ asset('/uploads/') }}' + '/' + data.employee_id + '/' + item.certificate;
                    let row = '<tr><td >' + (index + 1) + '</td><td ><a href="' + certificateLink + '" target="_blank">' + item.certificate + '</a></td><td >'+item.certificate_type_name+'</td><td >'+ item.end_date_certificate+'</td><td class="text-center"> <button class="btn p-0 border-0 bg-transparent shadow-none btn_delete_certificate" data_id_certificate="'+item.id_certificate+'" data_filename="'+item.certificate+'"><i class="bi bi-trash3 text-danger"></i></button></td></tr>';
                    $('.certificate_list').append(row);
                });
            }


            $('.md3 input[name="gender"][value="' + data.gender + '"]').prop('checked', true);
            $('.md3 input[name="marital_status"][value="' + data.marital_status + '"]').prop('checked', true);
            $('.md3 input[name="military_service"][value="' + data.military_service + '"]').prop('checked', true);
            $('.md3 .date_of_birth').val(data.date_of_birth);
            $('.md3 .phone_number').val(data.phone_number);
            let dataPassport = JSON.parse(data.passport);
            if(dataPassport[0]){
                $('.md3 .passport_number').val(dataPassport[0].passport_number);
                $('.md3 .passport_place_issue').val(dataPassport[0].passport_place_issue);
                $('.md3 .passport_issue_date').val(dataPassport[0].passport_issue_date);
                $('.md3 .passport_expiry_date').val(dataPassport[0].passport_expiry_date);
            }
            $('.md3 .cic_number').val(data.cic_number);
            $('.md3 .cic_place_issue').val(data.cic_place_issue);
            $('.md3 .cic_issue_date').val(data.cic_issue_date);
            $('.md3 .cic_expiry_date').val(data.cic_expiry_date);
            $('.md3 .current_residence').val(data.current_residence);
            $('.md3 .permanent_address').val(data.permanent_address);
            $('.md3 .medical_checkup_date').val(data.medical_checkup_date);
            $('.md3 .job_title').val(data.id_job_title);
            $('.md3 .job_category').val(data.id_job_category);
            $('.md3 .job_position').val(data.id_job_position);
            $('.md3 .job_team').val(data.id_job_team);
            $('.md3 .job_level').val(data.id_job_level);
            $('.md3 .email').val(data.email);
            $('.md3 .start_date').val(data.start_date);
            $('.md3 .end_date').val(data.end_date);
            $('.md3 .job_type_contract').val(data.id_job_type_contract);
            $('.md3 .job_country').val(data.id_job_country);
            $('.md3 .job_location').val(data.id_job_location);
            $('.md3').modal('show');

            $('.md3 .btn_delete_cv').click(function (event) {
                event.preventDefault();
                let employee_id = data.employee_id;
                let filename = $(this).attr('data_filename');
                let formData = new FormData();
                formData.append('employee_id', employee_id);
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

            $('.md3 .btn_delete_medical').click(function () {
                event.preventDefault();
                event.preventDefault();
                let employee_id = data.employee_id;
                let id_medical_checkup = $(this).attr('data_id_medical');
                let filename = $(this).attr('data_filename');
                let formData = new FormData();
                formData.append('employee_id', employee_id);
                formData.append('id_medical_checkup', id_medical_checkup);
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

            $('.md3 .btn_delete_certificate').click(function () {
                event.preventDefault();
                let employee_id = data.employee_id;
                let id_certificate = $(this).attr('data_id_certificate');
                let filename = $(this).attr('data_filename');
                let formData = new FormData();
                formData.append('employee_id', employee_id);
                formData.append('id_certificate', id_certificate);
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

            $('.md3 .btn-update').click(function () {
                event.preventDefault();
                let dataEmployee = {
                    'employee_code': $('.md3 .employee_code').val(),
                    'first_name': $('.md3 .first_name').val(),
                    'last_name': $('.md3 .last_name').val(),
                    'en_name': $('.md3 .en_name').val(),
                    'gender': $('.md3 input[name="gender"]:checked').val(),
                    'marital_status': $('.md3 input[name="marital_status"]:checked').val(),
                    'military_service': $('.md3 input[name="military_service"]:checked').val(),
                    'date_of_birth': $('.md3 .date_of_birth').val(),
                    'national': $('.md3 .national :checked').val()
                };
                let dataPassport = {
                    'passport_number': $('.md3 .passport_number').val(),
                    'passport_place_issue': $('.md3 .passport_place_issue').val(),
                    'passport_issue_date': $('.md3 .passport_issue_date').val(),
                    'passport_expiry_date': $('.md3 .passport_expiry_date').val(),
                };
                let dataContact = {
                    'phone_number' : $('.md3 .phone_number').val(),
                    'cic_number' : $('.md3 .cic_number').val(),
                    'cic_place_issue' : $('.md3 .cic_place_issue').val(),
                    'cic_issue_date': $('.md3 .cic_issue_date').val(),
                    'cic_expiry_date':$('.md3 .cic_expiry_date').val(),
                    'current_residence' : $('.md3 .current_residence').val(),
                    'permanent_address':$('.md3 .permanent_address').val(),
                };
                let dataJob = {
                    'id_job_title': $('.md3 .job_title').val(),
                    'id_job_category': $('.md3 .job_category').val(),
                    'id_job_position': $('.md3 .job_position').val(),
                    'id_job_team': $('.md3 .job_team').val(),
                    'id_job_level': $('.md3 .job_level').val(),
                    'start_date': $('.md3 .start_date').val(),
                    'end_date': $('.md3 .end_date').val(),
                    'id_job_type_contract': $('.md3 .job_type_contract').val(),
                    'id_job_country': $('.md3 .job_country').val(),
                    'id_job_location': $('.md3 .job_location').val(),
                };

                let formData = new FormData();
                formData.append('employee_id', data.employee_id);
                formData.append('id_contact', data.id_contact);
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
        })
        $('.at4').click(function () {
            var id = $(this).attr('data');
            if (confirm("Do you want to remove this employee?")){
                $.ajax({
                    url: _delete,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {'id':id},
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
        })

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
