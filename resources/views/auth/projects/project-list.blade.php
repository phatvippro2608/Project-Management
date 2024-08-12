@extends('auth.main')
@section('head')
    <style>
        label {
            font-weight: bolder;
            margin-left: 5px;
            margin-top: 20px;
        }
    </style>
@endsection
@section('contents')
    <div class="pagetitle">
        <h1>{{ __('messages.projects') }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{action('App\Http\Controllers\DashboardController@getViewDashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Project List</li>
            </ol>
        </nav>
    </div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addProjectModal">
        <i class="bi bi-plus-lg"></i>
        Add
    </button>
    <button class="btn btn-primary mb-4">
        <i class="bi bi-file-earmark-arrow-up"></i>
        Import
    </button>
    <button class="btn btn-primary mb-4">
        <i class="bi bi-file-earmark-arrow-down"></i>
        Export
    </button>

    <!-- Table to display materials -->
    <div class="card border rounded-4 p-2">
        <div class="card-body">
            <div class="table-responsive">
                <table id="projectListTable" class="table table-hover table-borderless">
                    <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th scope="col">Project Name</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Team Memebers</th>
                        {{--                        <th>Tags</th>--}}
                        <th scope="col">StartDate</th>
                        <th scope="col">EndDate</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($projects as $project)
                        <tr>
                            <td>{{ $project->project_id }}</td>
                            <td>{{ $project->project_name }}</td>
                            <td>{{ $project->customer_info }}</td>
                            <td>
                                <div style="display: flex; align-items: center">
                                    @foreach($project->team_members as $employee)
                                        @php
                                            $photoPath = asset($employee->photo);
                                            $defaultPhoto = asset('assets/img/avt.png');
                                            $photoExists = !empty($employee->photo) && file_exists(public_path($employee->photo));
                                        @endphp
                                        <img src="{{ $photoExists ? $photoPath : $defaultPhoto }}" alt="Profile"
                                             class="@if($employee->team_permission == 1){{"border-admin"}}@endif rounded-circle object-fit-cover ms-1"
                                             width="36" height="36"
                                             title="{{$employee->last_name." ".$employee->first_name}}"
                                             style="cursor:pointer">
                                    @endforeach
                                    <div
                                        class="d-flex align-items-center justify-content-center ms-1"
                                        style="width: 36px; height: 36px; background: transparent; border-radius: 50%; border: 2px dashed black; cursor: pointer">
                                        <i class="bi bi-person-fill-add fs-4 "></i>
                                    </div>
                                </div>
                            </td>
                            {{--                            <td><span class="badge rounded-pill bg-light text-dark">Web Development</span></td>--}}
                            <td>{{ $project->project_date_start }}</td>
                            <td>{{ $project->project_date_end }}</td>
                            <td>
                                <span class="badge rounded-pill
                                    @switch($project->phase_id)
                                        @case(1)
                                            bg-primary
                                            @break
                                        @case(2)
                                            bg-info
                                            @break
                                        @case(3)
                                            bg-success
                                            @break
                                        @case(4)
                                            bg-warning
                                            @break
                                        @case(5)
                                            bg-danger
                                            @break
                                        @default
                                            bg-secondary
                                    @endswitch">
                                    {{ $project->phase_name_eng }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('project.details', ['id' => $project->project_id]) }}"
                                   class="btn btn-primary fw-bold p-1" style="font-size: 12px">Details and Cost</a>
                                <a href="{{ route('project.report', ['project_id' => $project->project_id]) }}"
                                   class="btn btn-primary fw-bold p-1" style="font-size: 12px">Report</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h5 class="modal-title" id="addProjectModalLabel">Add new project</h5>
                    <i class="bi bi-x-lg fs-4" style="cursor:pointer" data-bs-dismiss="modal" aria-label="Close"></i>
                </div>
                <!-- Form trong view -->
                <div class="modal-body">
                    <form id="projectForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="project_name" class="form-label">Project Name</label>
                                <input type="text" class="form-control" id="project_name" name="project_name" required>
                            </div>
                            <div class="col-md-3">
                                <label for="project_date_start" class="form-label">Project Start Date</label>
                                <input type="date" class="form-control" id="project_date_start"
                                       name="project_date_start" required>
                            </div>
                            <div class="col-md-3">
                                <label for="project_date_end" class="form-label">Project End Date</label>
                                <input type="date" class="form-control" id="project_date_end" name="project_date_end"
                                       required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="project_address" class="form-label">Project Address</label>
                                <textarea name="project_address" id="project_address" rows="2"
                                          class="form-control"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="project_contact_name" class="form-label">Contact Name</label>
                                <input type="text" class="form-control" id="project_contact_name"
                                       name="project_contact_name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="project_description" class="form-label">Description</label>
                                <textarea name="project_description" id="project_description" rows="2"
                                          class="form-control"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="project_contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" class="form-control" id="project_contact_phone"
                                       name="project_contact_phone" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="select_contract" class="form-label">Contract</label>
                                <div class="input-group">
                                    <select class="form-select contract_id"
                                            name="contract_id"
                                            aria-label="Example select with button addon">
                                        <option selected>Choose...</option>
                                        @foreach($contracts as $contract)
                                            <option
                                                value="{{$contract->contract_id}}">{{$contract->contract_name}}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-secondary add-contract" type="button"><i
                                            class="bi bi-plus"></i></button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="project_contact_address" class="form-label">Contact Address</label>
                                <input type="text" class="form-control" id="project_contact_address"
                                       name="project_contact_address" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="" class="form-label">Select Team</label>
                                <div class="input-group">
                                    <select class="form-select select_team"
                                            name="select_team"
                                            aria-label="Example select with button addon">
                                        <option selected>Choose...</option>
                                        @foreach($teams as $team)
                                            <option value="{{$team->team_id}}">{{$team->team_name}}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-secondary add-team" type="button"><i
                                            class="bi bi-plus"></i></button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="project_contact_website" class="form-label">Contact Website</label>
                                <input type="text" class="form-control" id="project_contact_website"
                                       name="project_contact_website">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnSubmitProject">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade md1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="" class="fs-5" style="margin-bottom: 0.3rem">
                                Team name
                            </label>
                            <input type="text" class="form-control mt-1 val-team-name">
                        </div>
                    </div>
                    <div class="row mt-3 d-flex justify-content-end">
                        <button type="button" class="w-auto btn btn-danger btn-upload" data-bs-dismiss="modal"
                                aria-label="Close">Close
                        </button>
                        <button type="button" class="w-auto btn btn-primary btn-upload at1 ms-2 me-3 btn-create-team">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src=""></script>
    <script>
        var table = $('#projectListTable').DataTable({
            language: {search: ""},
            initComplete: function (settings, json) {
                $('.dt-search').addClass('input-group');
                $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
            },
            responsive: true
        });

        $('.md1').on('hidden.bs.modal', function () {
            $('#addProjectModal').css('opacity', '1');
        });

        $('.add-team').click(function () {
            $('.md1 .modal-title').text('Add New Team');

            $('.md1').modal('show');
            $('#addProjectModal').css('opacity', '0.95');

            $('.btn-create-team').off('click').click(function () {
                let team_name = $('.val-team-name').val();
                $.ajax({
                    url: `{{action('App\Http\Controllers\TeamController@addFromProject')}}`,
                    type: "PUT",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'team_name': team_name,
                    },
                    success: function (result) {
                        result = JSON.parse(result);
                        if (result.status === 200) {
                            toastr.success("Added a new team", "Successfully");
                            $('.form-select.select_team').append(`<option value="${result.message}">${team_name}</option>`);
                            $('.form-select.select_team').val(result.message);
                            $('.val-team-name').val("");
                            $('.md1').modal('hide');
                        } else {
                            toastr.error(result.message, "Failed Action");
                        }
                    }
                });
            })
        });

        document.getElementById('btnSubmitProject').addEventListener('click', function (event) {
            let form = document.getElementById('projectForm');
            let startDate = form.querySelector('#project_date_start').value;
            let endDate = form.querySelector('#project_date_end').value;

            let formData = new FormData(form);

            fetch('{{ route('projects.insert') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 200) {
                        toastr.success(data.message, "Successfully");
                        setTimeout(function () {
                            location.reload();
                        }, 500);
                    } else {
                        const errorRes = JSON.parse(data.message);
                        let strMes = "";
                        if (errorRes.project_name) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_name}</div>`;
                        if (errorRes.project_description) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_description}</div>`;
                        if (errorRes.project_address) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_address}</div>`;
                        if (errorRes.project_date_start) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_date_start}</div>`;
                        if (errorRes.project_date_end) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_date_end}</div>`;
                        if (errorRes.project_contact_name) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_contact_name}</div>`;
                        if (errorRes.project_contact_phone) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_contact_phone}</div>`;
                        if (errorRes.project_contact_address) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_contact_address}</div>`;
                        if (errorRes.project_contact_website) strMes += `<div style="color: red; text-align: left;">-${errorRes.project_contact_website}</div>`;
                        if (errorRes.contract_id) strMes += `<div style="color: red; text-align: left;">-${errorRes.contract_id}</div>`;
                        if (errorRes.select_team) strMes += `<div style="color: red; text-align: left;">-${errorRes.select_team}</div>`;

                        Swal.fire({
                            html: strMes,
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Back and continue edit!'
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        });


                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error detected. Please try again!', "Failed");
                });
        });

        $('.add-contract').click(function () {
            let timerInterval;
            Swal.fire({
                html: 'You will redirect to the contract page to add a new contract in <b>4</b> seconds.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Redirect Now',
                didOpen: () => {
                    const content = Swal.getHtmlContainer();
                    const b = content.querySelector('b');
                    let countdown = 3;
                    timerInterval = setInterval(() => {
                        b.textContent = countdown;
                        countdown--;
                        if (countdown < 0) {
                            clearInterval(timerInterval);
                            Swal.close();
                            window.location.href = `{{action('App\Http\Controllers\ContractController@getView')}}`;
                        }
                    }, 1000);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Handle the confirmation button click if needed
                    window.location.href = `{{action('App\Http\Controllers\ContractController@getView')}}`;
                }
            });
        })
    </script>
@endsection
