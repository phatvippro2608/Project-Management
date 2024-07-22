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
<<<<<<< Updated upstream
                                    <tr>
                                        <th>Employee Code</th>
                                        <th>Photo</th>
                                        <th>Full Name</th>
                                        <th>English Name</th>
                                        <th>Gender</th>
                                        <th>Phone</th>
                                        <th>Action</th>
                                    </tr>
=======
                                <tr>
                                    <th>Full Name</th>
                                    <th>Photo</th>
                                    <th>Full Name</th>
                                    <th>English Name</th>
                                    <th>Gender</th>
                                    <th>Phone</th>
                                    <th></th>
                                </tr>

>>>>>>> Stashed changes
                                </thead>
                                <tbody>
                                @foreach($data as $item)
                                    <tr>
                                        <td>{{$item->employee_code}}</td>
                                        <td><img src="{{$item->photo}}" alt="" width="75" height="75"></td>
                                        <td>{{$item->first_name . ' ' . $item->last_name}}</td>
                                        <td>{{$item->english_name}}</td>
                                        <td>{{$item->gender}}</td>
                                        <td></td>
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
                    <div class="row">
                        <div class="col-6">
                            <div class="card border border-primary rounded-4">
                                <div class="card-header bg-primary text-white rounded-top-4 fs-5 mb-2">
                                    Personal Details
                                </div>
                                <div class="card-body bg-white rounded-bottom-4">

                                    <!-- General Form Elements -->
                                    <form>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-4 col-form-label">Employee Code</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-4 col-form-label">First Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-4 col-form-label">Last Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="inputText" class="col-sm-4 col-form-label">English Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                        <fieldset class="row mb-3">
                                            <legend class="col-form-label col-sm-4 pt-0">Gender</legend>
                                            <div class="col-sm-8">
                                                <div class="d-flex">
                                                    <div class="form-check me-3">
                                                        <input class="form-check-input" type="radio" name="Gender" id="male" value="male" checked>
                                                        <label class="form-check-label" for="male">
                                                            Male
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="Gender" id="female" value="female">
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
                                                        <input class="form-check-input" type="radio" name="Marital Status" id="single" value="single" checked>
                                                        <label class="form-check-label" for="single">
                                                            Single
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="Marital Status" id="married" value="married">
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
                                                        <input class="form-check-input" type="radio" name="MilitaryService" id="Done" value="Done" checked>
                                                        <label class="form-check-label" for="Done">
                                                            Done
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="MilitaryService" id="Noyet" value="No yet">
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
                                                <input type="date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Nation</label>
                                            <div class="col-sm-8">
                                                <select class="form-select selectpicker" aria-label="Default select example" id="countrySelect" name="National">
                                                    <!-- Danh sách các quốc gia sẽ được thêm vào đây -->
                                                </select>
                                            </div>
                                        </div>
                                    </form><!-- End General Form Elements -->
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border border-primary rounded-4">
                                <div class="card-header bg-primary text-white rounded-top-4 fs-5 mb-2">
                                    Personal Details
                                </div>
                                <div class="card-body bg-white rounded-bottom-4">
                                    <!-- General Form Elements -->
                                    <form>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-4 col-form-label">Phone Number</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-4 col-form-label">Passport Number</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-4 col-form-label">Place of Issue</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="inputText" class="col-sm-4 col-form-label">Citizen identity Card Number</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="inputText" class="col-sm-4 col-form-label">Place of Residence</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="inputText" class="col-sm-4 col-form-label">Permanent Address</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputDate" class="col-sm-4 col-form-label">Health Check-up Date</label>
                                            <div class="col-sm-8">
                                                <input type="date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputDate" class="col-sm-4 col-form-label">Date of Issue</label>
                                            <div class="col-sm-8">
                                                <input type="date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputDate" class="col-sm-4 col-form-label">Date of Expiry</label>
                                            <div class="col-sm-8">
                                                <input type="date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputDate" class="col-sm-4 col-form-label">Date of Issue</label>
                                            <div class="col-sm-8">
                                                <input type="date" class="form-control">
                                            </div>
                                        </div>
                                    </form><!-- End General Form Elements -->
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="card border border-primary rounded-4">
                                <div class="card-header bg-primary text-white rounded-top-4 fs-5 mb-2">
                                    Job Details
                                </div>
                                <div class="card-body bg-white rounded-bottom-4">

                                <!-- General Form Elements -->
                                <form>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Job Title</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" aria-label="Default select example">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Job Category</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" aria-label="Default select example">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Position</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" aria-label="Default select example">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Team</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" aria-label="Default select example">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Level</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" aria-label="Default select example">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputDate" class="col-sm-4 col-form-label">Start Date</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Type of Contract</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" aria-label="Default select example">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputDate" class="col-sm-4 col-form-label">End Date</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputEmail" class="col-sm-4 col-form-label">Email</label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Country</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" aria-label="Default select example">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Location</label>
                                        <div class="col-sm-8">
                                            <select class="form-select" aria-label="Default select example">
                                            </select>
                                        </div>
                                    </div>
                                </form><!-- End General Form Elements -->
                                </div>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border border-primary rounded-4">
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
                                            <input class="form-control" type="file" id="formFile">
                                        </div>
                                    </div>
                                </form><!-- End General Form Elements -->
                                </div>
                            </div>
                            <div class="card border border-primary rounded-4">
                                <div class="card-header bg-primary text-white rounded-top-4 fs-5 mb-2">
                                    Certificate
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
                                            <input class="form-control" type="file" id="formFile">
                                        </div>
                                    </div>
                                </form><!-- End General Form Elements -->
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-add">Save</button>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-add">Upload</button>
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
    </script>
@endsection
