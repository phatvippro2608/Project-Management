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
{{--                                    <select class="form-select selectpicker national" aria-label="Default select example" id="countrySelect" name="">--}}
{{--                                        <!-- Danh sách các quốc gia sẽ được thêm vào đây -->--}}
{{--                                    </select>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </form><!-- End General Form Elements -->
            </div>
        </div>
    </div>
    <div class="row p-3">
        <div class="card border border-primary rounded-4 p-0 mb-0">
            <div class="card-header bg-primary text-white rounded-top-4 fs-5 mb-2">
                Personal Contacts
            </div>
            <div class="card-body bg-white rounded-bottom-4">
                <!-- General Form Elements -->
                <form>
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">Phone Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control phone_number" name="" value="{{$data_contact->phone_number}}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">Passport Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control passport_number" name="" value="{{$data_contact->passport_number}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-4 col-form-label">Passport place of Issue</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control passport_place_issue" name="" value="{{$data_contact->passport_place_issue}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputDate" class="col-sm-4 col-form-label">Passport date of Issue</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control passport_issue_date" name="" value="{{ \Carbon\Carbon::parse($data_contact->passport_issue_date)->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputDate" class="col-sm-4 col-form-label">Passport date of Expiry</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control passport_expiry_date" name="" value="{{ \Carbon\Carbon::parse($data_contact->passport_expiry_date)->format('Y-m-d') }}">
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
                            <input type="date" class="form-control medical_checkup_date" name="" value="{{ \Carbon\Carbon::parse($data_contact->medical_checkup_date)->format('Y-m-d') }}">
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
                            <input type="text" name="" id="" class="form-control" value="{{ isset($jobdetails['jobTitles'][$data_job_detail->id_job_title]) ? $jobdetails['jobTitles'][$data_job_detail->id_job_title - 1 ]->job_title : '' }}">
{{--                            <select class="form-select job_title" aria-label="Default select example" name="">--}}
{{--                                @foreach($jobdetails['jobTitles'] as $item)--}}
{{--                                    <option value="{{$item->id_job_title}}">{{$item->job_title}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Job Category</label>
                        <div class="col-sm-8">
                            <input type="text" name="" id="" class="form-control" value="{{ isset($jobdetails['jobCategories'][$data_job_detail->id_job_category]) ? $jobdetails['jobCategories'][$data_job_detail->id_job_category -1 ]->job_category_name : '' }}">
{{--                            <select class="form-select job_category" aria-label="Default select example" name="">--}}
{{--                                @foreach($jobdetails['jobCategories'] as $item)--}}
{{--                                    <option value="{{$item->id_job_category}}">{{$item->job_category_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Position</label>
                        <div class="col-sm-8">
                            <input type="text" name="" id="" class="form-control" value="{{ isset($jobdetails['jobPositions'][$data_job_detail->id_job_position]) ? $jobdetails['jobPositions'][$data_job_detail->id_job_position - 1]->position_name : '' }}">
{{--                            <select class="form-select job_position" aria-label="Default select example" name="">--}}
{{--                                @foreach($jobdetails['jobPositions'] as $item)--}}
{{--                                    <option value="{{$item->id_position}}">{{$item->position_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Team</label>
                        <div class="col-sm-8">
                            <input type="text" name="" id="" class="form-control" value="{{ isset($jobdetails['jobTeams'][$data_job_detail->id_job_team]) ? $jobdetails['jobTeams'][$data_job_detail->id_job_team - 1]->team_name : '' }}">
{{--                            <select class="form-select job_team" aria-label="Default select example" name="">--}}
{{--                                @foreach($jobdetails['jobTeams'] as $item)--}}
{{--                                    <option value="{{$item->id_team}}">{{$item->team_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Level</label>
                        <div class="col-sm-8">
                            <input type="text" name="" id="" class="form-control" value="{{ isset($jobdetails['jobLevels'][$data_job_detail->id_job_level]) ? $jobdetails['jobLevels'][$data_job_detail->id_job_level - 1]->level_name : '' }}">
{{--                            <select class="form-select job_level" aria-label="Default select example" name="">--}}
{{--                                @foreach($jobdetails['jobLevels'] as $item)--}}
{{--                                    <option value="{{$item->id_level}}">{{$item->level_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail" class="col-sm-4 col-form-label">Email</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control email" name="" value="{{$data_job_detail->email}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputDate" class="col-sm-4 col-form-label">Start Date</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control start_date" name="" value="{{ \Carbon\Carbon::parse($data_job_detail->start_date)->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputDate" class="col-sm-4 col-form-label">End Date</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control end_date" name="" value="{{ \Carbon\Carbon::parse($data_job_detail->end_date)->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Type of Contract</label>
                        <div class="col-sm-8">
                            <input type="text" name="" id="" class="form-control" value="{{ isset($jobdetails['jobTypeContract'][$data_job_detail->id_job_type_contract]) ? $jobdetails['jobTypeContract'][$data_job_detail->id_job_type_contract - 1]->type_contract_name : '' }}">
{{--                            <select class="form-select job_type_contract" aria-label="Default select example" name="">--}}
{{--                                @foreach($jobdetails['jobTypeContract'] as $item)--}}
{{--                                    <option value="{{$item->id_type_contract}}">{{$item->type_contract_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Country</label>
                        <div class="col-sm-8">
                            <input type="text" name="" id="" class="form-control" value="{{ isset($jobdetails['jobCountry'][$data_job_detail->id_job_country]) ? $jobdetails['jobCountry'][$data_job_detail->id_job_country - 1]->country_name : '' }}">
{{--                            <select class="form-select job_country" aria-label="Default select example" name="">--}}
{{--                                @foreach($jobdetails['jobCountry'] as $item)--}}
{{--                                    <option value="{{$item->id_country}}">{{$item->country_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Location</label>
                        <div class="col-sm-8">
                            <input type="text" name="" id="" class="form-control" value="{{ isset($jobdetails['jobLocation'][$data_job_detail->id_job_location]) ? $jobdetails['jobLocation'][$data_job_detail->id_job_location - 1]->location_name : '' }}">
{{--                            <select class="form-select job_location" aria-label="Default select example">--}}
{{--                                @foreach($jobdetails['jobLocation'] as $item)--}}
{{--                                    <option value="{{$item->id_location}}">{{$item->location_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
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
                                <th scope="col">Type of Certificate</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr></tr>
                            </tbody>
                        </table>
                    </form><!-- End General Form Elements -->
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
