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
                            <table class="table card-table table-vcenter text-nowrap datatable table-hover">
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
                                    @if($item->fired == "false")
                                        <tr>
                                            <td><a href="{{action('App\Http\Controllers\EmployeesController@getEmployee', $item->id_employee)}}">{{$item->employee_code}}</a></td>
                                            @php
                                                $imagePath = public_path('uploads/' . $item->id_employee . '/' . $item->photo);
                                                $imageUrl = file_exists($imagePath) ? asset('uploads/' . $item->id_employee . '/' . $item->photo) : asset('assets/img/logo2.png');
                                            @endphp

                                            <td><img src="{{ $imageUrl }}" alt="" width="75" height="75"></td>
                                            <td>{{$item->last_name . ' ' . $item->first_name}}</td>
                                            <td>{{$item->en_name}}</td>
                                            <td>{{$item->gender == 0 ? "Nam" : "Nữ"}}</td>
                                            <td>{{$item->phone_number}}</td>
                                            <td>
                                                <?php
                                                    $id = $item->id_employee;
                                                    $item->medical = \App\Http\Controllers\EmployeesController::getMedicalInfo($id);
                                                    $item->certificates = \App\Http\Controllers\EmployeesController::getCertificateInfo($id);
                                                ?>
                                                <a href="#" class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none at3" data="{{\App\Http\Controllers\AccountController::toAttrJson($item)}}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                |
                                                <button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none at4">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
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
    </div>

    <div class="modal fade md3  ">
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
                                                    <div class="row p-0 m-0">
                                                        <div class="col-4">
                                                            <img class="photo_show" src="" alt="" width="100" height="100">
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="row p-0 mx-1 mb-2">
                                                                <input type="file" class="form-control form-control-sm photo" name="">
                                                            </div>
                                                            <div class="row p-0 mx-1">
                                                                <button class="btn btn-primary btn_photo">
                                                                    Upload
                                                                </button>
                                                            </div>
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
                                                    <input type="date" class="form-control date_of_birth" name="">
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

                                </form><!-- End General Form Elements -->
                            </div>
                        </div>
                    </div>
                    <div class="row p-3">
                        <div class="card border border-primary rounded-4 p-0 m-0">
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
                                                    <th>#</th>
                                                    <th>Filename</th>
                                                </tr>
                                            </thead>
                                            <tbody class="cv-list">

                                            </tbody>
                                        </table>
                                        <div class="row mb-3">
                                            <label for="inputNumber" class="col-sm-2 col-form-label">Upload</label>
                                            <div class="col-sm-10">
                                                <div class="row p-0 mx-1 mb-2">
                                                    <input class="form-control personal" type="file" id="" multiple="multiple">
                                                </div>
                                                <div class="row p-0 mx-1 mb-2">
                                                    <button class="btn btn-primary btn_upload_personal_profile">Upload</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form><!-- End General Form Elements -->
                                </div>
                            </div>
                        </div>
                        <div class="col-6 p-0 pe-2">
                            <div class="card border border-primary rounded-4 p-0">
                                <div class="card-header bg-primary text-white rounded-top-4 fs-5 mb-2">
                                    Medical CheckUp
                                </div>
                                <div class="card-body bg-white rounded-bottom-4">

                                    <!-- General Form Elements -->
                                    <form>
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Filename</th>
                                                <th>Date</th>
                                            </tr>
                                            </thead>
                                            <tbody class="medical_list">

                                            </tbody>
                                        </table>
                                        <div class="row mb-3">
                                            <label for="inputNumber" class="col-sm-2 col-form-label">Upload</label>
                                            <div class="col-sm-10">
                                                <div class="row p-0 mx-1 mb-2">
                                                    <input type="date" class="form-control medical_checkupdate mb-2">
                                                    <input class="form-control medical_checkup" type="file" id="" multiple="multiple">
                                                </div>
                                                <div class="row p-0 mx-1 mb-2">
                                                    <button class="btn btn-primary btn_upload_medical">Upload</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form><!-- End General Form Elements -->
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col">
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
                                                <th scope="col">Type</th>
                                                <th scope="col">Expiry Date</th>
                                            </tr>
                                            </thead>
                                            <tbody class="certificate_list">
                                            </tbody>
                                        </table>
                                        <div class="row mb-3">
                                            <label for="inputNumber" class="col-sm-1 col-form-label me-0">Upload</label>
                                            <div class="col-sm-11">
                                                <div class="row">
                                                    <div class="col-4 p-0">
                                                        <select class="form-select type_certificate" aria-label="Default select example">
                                                            @foreach($type_certificate as $item)
                                                                <option value="{{$item->id_certificate_type}}">{{$item->certificate_type_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-3 ps-2 p-0">
                                                        <input class="form-control certificate_end_date" type="date" id="">
                                                    </div>
                                                    <div class="col-4 ps-2 p-0">
                                                        <input class="form-control certificate_file" type="file" id="" multiple="multiple">
                                                    </div>
                                                    <div class="col-1 ps-2 p-0">
                                                        <button class="btn btn-primary btn_upload_certificate pb-0">
                                                            <span class="material-symbols-outlined">
                                                                upload
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form><!-- End General Form Elements -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-update">Update</button>
                    </div>
                </div>
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
        let _put = "{{action('App\Http\Controllers\EmployeesController@put')}}";
        let _post = "{{action('App\Http\Controllers\EmployeesController@post')}}";
        let _delete = "{{action('App\Http\Controllers\EmployeesController@delete')}}";
        let _upload = "{{ action('App\Http\Controllers\UploadFileController@uploadFile')}}";
        let _upload_photo = "{{action('App\Http\Controllers\UploadFileController@uploadPhoto')}}";
        let _upload_personal_profile = "{{action('App\Http\Controllers\UploadFileController@uploadPersonalProfile')}}";
        let _upload_medical_checkup = "{{action('App\Http\Controllers\UploadFileController@uploadMedicalCheckUp')}}";
        let _upload_certificate = "{{action('App\Http\Controllers\UploadFileController@uploadCertificate')}}";
        $('.at1').click(function () {
            $('.md1 .modal-title').text('Add Employee');
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
                    type: 'POST',
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
        $('.at2').click(function () {
            $('.md2 .modal-title').text('Import Employee');
            $('.md2').modal('show');
        });
        let nation ="";
        $(document).on('click', '.at3', function() {
            var data = JSON.parse($(this).attr('data'));
            console.log(data);
            $('.btn_photo').click(function () {
                let filePhoto = $('.md3 .photo')[0].files[0];
                let formData = new FormData();

                if (filePhoto) {
                    formData.append('photo', filePhoto);
                }

                formData.append('id_employee', data.id_employee);

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

                formData.append('id_employee', data.id_employee);

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

            $('.md3 .btn_upload_medical').click(function () {
                event.preventDefault();
                let file = $('.md3 .medical_checkup')[0].files[0];
                let formData = new FormData();


                formData.append('medical_file', file);
                formData.append('id_employee', data.id_employee);
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
                formData.append('id_employee', data.id_employee);
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
            $('.md3 .photo_show').attr('src','{{asset('/uploads/')}}'+ '/' + data.id_employee + '/' + data.photo);
            let dataCV = JSON.parse(data.cv);

            dataCV.forEach(function(filename, index) {
                let cvLink = '{{ asset('/uploads/') }}' + '/' + data.id_employee + '/' + filename;
                let row = '<tr><td>' + (index + 1) + '</td><td><a href="' + cvLink + '" target="_blank">' + filename + '</a></td></tr>';
                $('.cv-list').append(row);
            });

            let dataMedical = JSON.parse(data.medical);
            dataMedical.forEach(function(item, index) {
                let medicalLink = '{{ asset('/uploads/') }}' + '/' + data.id_employee + '/' + item.medical_checkup_file;
                let row = '<tr><td>' + (index + 1) + '</td><td><a href="' + medicalLink + '" target="_blank">' + item.medical_checkup_file + '</a></td><td>'+ item.medical_checkup_issue_date+'</td></tr>';
                $('.medical_list').append(row);
            });

            let dataCertificate = JSON.parse(data.certificates);
            console.log(dataCertificate);
            dataCertificate.forEach(function(item, index) {
                let certificateLink = '{{ asset('/uploads/') }}' + '/' + data.id_employee + '/' + item.certificate;
                let row = '<tr><td>' + (index + 1) + '</td><td><a href="' + certificateLink + '" target="_blank">' + item.certificate + '</a></td><td>'+item.certificate_type_name+'</td><td>'+ item.end_date_certificate+'</td></tr>';
                $('.certificate_list').append(row);
            });
            $('.md3 input[name="gender"][value="' + data.gender + '"]').prop('checked', true);
            $('.md3 input[name="marital_status"][value="' + data.marital_status + '"]').prop('checked', true);
            $('.md3 input[name="military_service"][value="' + data.military_service + '"]').prop('checked', true);
            $('.md3 .date_of_birth').val(data.date_of_birth);
            $('.md3 .phone_number').val(data.phone_number);
            $('.md3 .passport_number').val(data.passport_number);
            $('.md3 .passport_place_issue').val(data.passport_place_issue);
            $('.md3 .passport_issue_date').val(data.passport_issue_date);
            $('.md3 .passport_expiry_date').val(data.passport_expiry_date);
            $('.md3 .cic_number').val(data.cic_number);
            $('.md3 .cic_place_issue').val(data.cic_place_issue);
            $('.md3 .cic_issue_date').val(data.cic_issue_date);
            $('.md3 .cic_expiry_date').val(data.cic_expiry_date);
            $('.md3 .current_residence').val(data.current_residence);
            $('.md3 .permanent_address').val(data.permanent_address);
            $('.md3 .medical_checkup_date').val(data.medical_checkup_date);
            $('.md3 .job_title select').val(data.job_title);
            $('.md3 .job_category select').val(data.job_category);
            $('.md3 .job_position select').val(data.job_position);
            $('.md3 .job_team select').val(data.job_team);
            $('.md3 .job_level select').val(data.job_level);
            $('.md3 .email').val(data.email);
            $('.md3 .start_date').val(data.start_date);
            $('.md3 .end_date').val(data.end_date);
            $('.md3 .job_type_contract select').val(data.job_type_contract);
            $('.md3 .job_country select').val(data.job_country);
            $('.md3 .job_location select').val(data.job_location);
            $('.md3 .modal-title').text('Update Employee');
            $('.md3').modal('show');

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
@endsection
