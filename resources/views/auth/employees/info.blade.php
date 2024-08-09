@extends('auth.main')
@section('contents')
    <div class="pagetitle">
        <h1>Employees</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Employees</li>
            </ol>
        </nav>
    </div>
    <div class="">
        <div class="card mb-0 shadow-none">
            <div class="card-header fw-semibold text-white border-4 border-secondary-subtle rounded-4" style="background: var(--clr-1)">Personal Details</div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-6">
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-4 col-form-label">Employee Code</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control employee_code" name="" value="{{$data_employee->employee_code}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-4 col-form-label">First Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control first_name" name="" value="{{$data_employee->first_name}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-4 col-form-label">Last Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control last_name" name="" value="{{$data_employee->last_name}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputText" class="col-sm-4 col-form-label">English Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control en_name" name="" value="{{$data_employee->en_name}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <fieldset class="row mb-3">
                            <legend class="col-form-label col-sm-4 pt-0">Gender</legend>
                            <div class="col-sm-8">
                                <div class="d-flex">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" name="gender" id="male" value="0" @if($data_employee->gender == 0) checked @endif>
                                        <label class="form-check-label" for="male">
                                            Male
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="1" @if($data_employee->gender == 1) checked @endif>
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
                                        <input class="form-check-input" type="radio" name="marital_status" id="single" value="Single" @if($data_employee->marital_status == 'Single') checked @endif>
                                        <label class="form-check-label" for="single">
                                            Single
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="marital_status" id="married" value="Married" @if($data_employee->marital_status == 'Married') checked @endif>
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
                                        <input class="form-check-input" type="radio" name="military_service" id="Done" value="Done" @if($data_employee->military_service == 'Done') checked @endif>
                                        <label class="form-check-label" for="Done">
                                            Done
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="military_service" id="Noyet" value="No yet" @if($data_employee->military_service == 'No yet') checked @endif>
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
                                <input type="date" class="form-control date_of_birth" name="" value="{{ \Carbon\Carbon::parse($data_employee->date_of_birth)->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Nation</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{$data_employee->national}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-0 shadow-none">
            <div class="card-header fw-semibold text-white border-4 border-secondary-subtle rounded-4" style="background: var(--clr-1)"> Personal Contacts</div>
            <div class="card-body p-3">
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-4 col-form-label">Phone Number</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control phone_number" name="" value="{{$data_contact->phone_number}}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="inputText" class="col-sm-4 col-form-label">Passport Number</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control passport_number" name="" value="{{$data_passport ? $data_passport->passport_number : ''}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-4 col-form-label">Passport place of Issue</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control passport_place_issue" name="" value="{{$data_passport ? $data_passport->passport_place_issue : ''}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputDate" class="col-sm-4 col-form-label">Passport date of Issue</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control passport_issue_date" name="" value="{{$data_passport ?  \Carbon\Carbon::parse($data_passport->passport_issue_date)->format('Y-m-d') : '' }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputDate" class="col-sm-4 col-form-label">Passport date of Expiry</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control passport_expiry_date" name="" value="{{$data_passport ?  \Carbon\Carbon::parse($data_passport->passport_expiry_date)->format('Y-m-d') : '' }}}}">
                    </div>
                </div>
                <div class="row mb-4">
                    <label for="inputText" class="col-sm-4 col-form-label">Citizen identity Card Number</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control cic_number" name="" value="{{$data_contact->cic_number}}">
                    </div>
                </div>
                <div class="row mb-4">
                    <label for="inputText" class="col-sm-4 col-form-label">Citizen place of issue</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control cic_place_issue" name="" value="{{$data_contact->cic_place_issue}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputDate" class="col-sm-4 col-form-label">CIC date of Issue</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control cic_issue_date" name="" value="{{ \Carbon\Carbon::parse($data_contact->cic_issue_date)->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputDate" class="col-sm-4 col-form-label">CIC date of Expiry</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control cic_expiry_date" name="" value="{{ \Carbon\Carbon::parse($data_contact->cic_expiry_date)->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="row mb-4">
                    <label for="inputText" class="col-sm-4 col-form-label">Place of Residence</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control current_residence" name="" value="{{$data_contact->current_residence}}">
                    </div>
                </div>
                <div class="row mb-4">
                    <label for="inputText" class="col-sm-4 col-form-label">Permanent Address</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control permanent_address" name="" value="{{$data_contact->permanent_address}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputDate" class="col-sm-4 col-form-label">Medical Check-up Date</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control medical_checkup_date" name=""
                               value="{{ $data_medical_checkup && $data_medical_checkup->isNotEmpty() ? $data_medical_checkup->first()->medical_checkup_issue_date : '' }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-0 shadow-none">
            <div class="card-header fw-semibold text-white border-4 border-secondary-subtle rounded-4" style="background: var(--clr-1)"> Personal Job Detail</div>
            <div class="card-body p-3">
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Job Title</label>
                    <div class="col-sm-8">
                        <input type="text" name="" id="" class="form-control" value="{{ $jobdetails->isNotEmpty() ? $jobdetails->first()->job_title : '' }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Job Category</label>
                    <div class="col-sm-8">
                        <input type="text" name="" id="" class="form-control" data="" value="{{ $jobdetails->isNotEmpty() ? $jobdetails->first()->job_category_name : ''}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Position</label>
                    <div class="col-sm-8">
                        <input type="text" name="" id="" class="form-control" value="{{ $jobdetails->isNotEmpty() ? $jobdetails->first()->position_name : ''}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Team</label>
                    <div class="col-sm-8">
                        <input type="text" name="" id="" class="form-control" value="{{ $jobdetails->isNotEmpty() ? $jobdetails->first()->team_name : ''}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Level</label>
                    <div class="col-sm-8">
                        <input type="text" name="" id="" class="form-control" value="{{ $jobdetails->isNotEmpty() ? $jobdetails->first()->level_name : ''}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputEmail" class="col-sm-4 col-form-label">Email</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control email" name="" value="{{$email}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputDate" class="col-sm-4 col-form-label">Start Date</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control start_date" name="" value="{{ optional($data_job_detail)->start_date ? \Carbon\Carbon::parse(optional($data_job_detail)->start_date)->format('Y-m-d') : '' }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputDate" class="col-sm-4 col-form-label">End Date</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control end_date" name="" value="{{ optional($data_job_detail)->end_date ? \Carbon\Carbon::parse(optional($data_job_detail)->end_date)->format('Y-m-d') : '' }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Type of Contract</label>
                    <div class="col-sm-8">
                        <input type="text" name="" id="" class="form-control" value="{{ $jobdetails->isNotEmpty() ? $jobdetails->first()->type_contract_name : ''}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Country</label>
                    <div class="col-sm-8">
                        <input type="text" name="" id="" class="form-control" value="{{ $jobdetails->isNotEmpty() ? $jobdetails->first()->country_name : ''}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Location</label>
                    <div class="col-sm-8">
                        <input type="text" name="" id="" class="form-control" value="{{ $jobdetails->isNotEmpty() ? $jobdetails->first()->location_name : ''}}">
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-0">
            <div class="col-6 d-flex flex-column">
                <div class="card mb-0 shadow-none flex-grow-1">
                    <div class="card-header fw-semibold text-white border-4 rounded-start-4 rounded-end-0" style="background: var(--clr-1)">Personal Profile</div>
                    <div class="card-body p-3">
                        <table class="table table-hover table-borderless">
                            <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Filename</th>
                            </tr>
                            </thead>
                            <tbody class="cv-list">
                            @php
                                $dataCV = json_decode($data_cv);
                            @endphp
                            @if($dataCV)
                                @foreach ($dataCV as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><a target="_blank" href="{{ asset('/uploads/' . $data_employee->employee_id . '/' . $item) }}">{{ $item }}</a></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-6 d-flex flex-column">
                <div class="card mb-0 shadow-none flex-grow-1">
                    <div class="card-header fw-semibold text-white border-4 rounded-end-4 rounded-start-0" style="background: var(--clr-1)">Medical CheckUp</div>
                    <div class="card-body p-3">
                        <table class="table table-hover table-borderless">
                            <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Filename</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody class="medical_list">
                                @php
                                    $dataMedical = json_decode($data_medical_checkup);
                                @endphp
                                @if($dataMedical)
                                    @foreach ($dataMedical as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><a target="_blank" href="{{ asset('/uploads/' . $item->employee_id . '/' . $item->medical_checkup_file) }}">{{ $item->medical_checkup_file }}</a></td>
                                            <td>{{ $item->medical_checkup_issue_date }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card mb-0 shadow-none">
                    <div class="card-header fw-semibold text-white border-4 border-secondary-subtle rounded-4" style="background: var(--clr-1)">Certificates</div>
                    <div class="card-body p-3">
                        <table class="table table-hover table-borderless">
                            <thead class="table-light">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">File Name</th>
                                <th scope="col">Type</th>
                                <th scope="col">Expiry Date</th>
                            </tr>
                            </thead>
                            <tbody class="certificate_list">
                                @php
                                    $dataCertificate = json_decode($data_certificate);
                                @endphp
                                @if($dataCertificate)
                                    @foreach($dataCertificate as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><a target="_blank" href="{{ asset('/uploads/' . $item->employee_id . '/' . $item->certificate) }}">{{ $item->certificate }}</a></td>
                                            <td>{{ $item->certificate_type_name}}</td>
                                            <td>{{ $item->end_date_certificate }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card mb-0 shadow-none">
                    <div class="card-header fw-semibold text-white border-4 border-secondary-subtle rounded-4" style="background: var(--clr-1)">Employment Contract</div>
                    <div class="card-body p-3">
                        <table class="table table-hover table-borderless">
                            <thead class="table-light">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Employment Contract</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                            </tr>
                            </thead>
                            <tbody class="employment_contract_list">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const elements = document.querySelectorAll('input, select, textarea');
        elements.forEach(element => {
            element.disabled = true;
        });
    </script>
@endsection
