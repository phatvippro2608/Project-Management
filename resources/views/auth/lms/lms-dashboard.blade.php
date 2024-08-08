@extends('auth.main-lms')

@section('head')
@endsection

@section('contents')
<div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
    <div class="row gx-5">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-xxl-3 col-xl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title"><b>Courses</b></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-list-task"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{$course_c}}</h6>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title"><b>Your Course</b></h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$count}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title"><b>Your Certificates</b></h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$count}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="card p-2 rounded-4 border">
        <div class="card-header py-0">
            <div class="card-title my-3 p-0">View Summary</div>
        </div>
        <div class="card-body">
            <div class="row gx-3 my-3">
                <div class="col-md-6 m-0">
                    <div class="btn btn-success mx-2 btn-export">
                            <i class="bi bi-file-earmark-arrow-down pe-2"></i>
                            Export
                        </a>
                    </div>
                </div>
            </div>
            <table id="employeesTable" class="table table-hover table-borderless">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">ID Source</th>
                        <th>Name source</th>
                        <th>Progress</th>
                        <th>Start To</th>
                        <th>From</th>
                        <th>Certificate</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="employeesTableBody">
                @foreach($data as $course)
                    <tr class="clickable-row" data-href="/lms/course/{{ $course->course_id }}"> 
                    <td class="text-center">{{$course->course_id}}</td>
                                    <td>{{$course->course_name}}</td>
                                    <td>{{$course->progress}}%</td>
                                    <td>{{$course->start_date}}</td>
                                    <td>{{$course->end_date}}</td>
                                    <td></td>
                                    <td>
                                        <button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none at4">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                </tr>
                
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
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#employeesTable tbody tr').forEach(row => {
            row.addEventListener('click', function() {
                const href = this.getAttribute('data-href');
                if (href) {
                    window.location.href = href;
                }
            });
        });
    });
</script>
@endsection
