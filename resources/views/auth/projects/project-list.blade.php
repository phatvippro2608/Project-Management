@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Project List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{action('App\Http\Controllers\DashboardController@getViewDashboard')}}">Home</a></li>
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
                        <th>Tags</th>
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
                                @foreach($project->team_members as $employee)
                                    @php
                                        $photoPath = asset($employee->photo);
                                        $defaultPhoto = asset('assets/img/avt.png');
                                        $photoExists = !empty($employee->photo) && file_exists(public_path($employee->photo));
                                    @endphp
                                    <img src="{{ $photoExists ? $photoPath : $defaultPhoto }}" alt="Profile" class="rounded-circle object-fit-cover" width="36" height="36">
                                @endforeach
                            </td>
                            <td><span class="badge rounded-pill bg-light text-dark">Web Development</span></td>
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
                                <a href="{{ route('project.details', ['id' => $project->project_id]) }}" class="btn btn-primary fw-bold p-1" style="font-size: 12px">Details and Cost</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        <div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProjectModalLabel">Add new project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- Form trong view -->
                    <div class="modal-body">
                        <form id="projectForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="project_name" class="form-label">Project Name</label>
                                    <input type="text" class="form-control" id="project_name" name="project_name" required>
                                    <label for="project_description" class="form-label">Description</label>
                                    <textarea name="project_description" id="project_description" rows="8" class="form-control"></textarea>
                                    <label for="project_address" class="form-label">Project Address</label>
                                    <textarea name="project_address" id="project_address" rows="2" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="project_date_start" class="form-label">Project Start Date</label>
                                    <input type="date" class="form-control" id="project_date_start" name="project_date_start" required>
                                    <label for="project_date_end" class="form-label">Project End Date</label>
                                    <input type="date" class="form-control" id="project_date_end" name="project_date_end" required>
                                    <label for="project_main_contractor" class="form-label">Main Contractor</label>
                                    <input type="text" class="form-control" id="project_main_contractor" name="project_main_contractor" required>
                                    <label for="project_contact_name" class="form-label">Contact Name</label>
                                    <input type="text" class="form-control" id="project_contact_name" name="project_contact_name" required>
                                    <label for="project_contact_phone" class="form-label">Contact Phone</label>
                                    <input type="text" class="form-control" id="project_contact_phone" name="project_contact_phone" required>
                                    <label for="project_contact_address" class="form-label">Contact Address</label>
                                    <input type="text" class="form-control" id="project_contact_address" name="project_contact_address" required>
                                    <label for="project_contact_website" class="form-label">Contact Website</label>
                                    <input type="text" class="form-control" id="project_contact_website" name="project_contact_website">
                                    <label for="project_contract_amount" class="form-label">Contract Amount</label>
                                    <input type="number" class="form-control" id="project_contract_amount" name="project_contract_amount">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="btnSubmitProject">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var table = $('#projectListTable').DataTable({
            language: { search: "" },
            initComplete: function (settings, json) {
                $('.dt-search').addClass('input-group');
                $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
            },
            responsive: true
        });
        document.getElementById('btnSubmitProject').addEventListener('click', function (event) {
            let form = document.getElementById('projectForm');
            let startDate = form.querySelector('#project_date_start').value;
            let endDate = form.querySelector('#project_date_end').value;

            // Kiểm tra nếu người dùng không nhập ngày bắt đầu hoặc ngày kết thúc
            if (!startDate || !endDate) {
                event.preventDefault(); // Ngăn chặn việc gửi form
                toastr.error('Vui lòng nhập ngày bắt đầu và ngày kết thúc của dự án.', "Lỗi nhập liệu");
                return;
            }

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
                        toastr.success(data.message, "Lưu thành công");
                        setTimeout(function () {
                            location.reload();
                        }, 500);
                    } else {
                        let errorMessage = data.message;
                        if (data.error) {
                            errorMessage += ': ' + data.error;
                            console.error('Error:', data.error);  // Log lỗi cụ thể ra console
                        }
                        toastr.error(errorMessage, "Thao tác thất bại");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);  // Log lỗi mạng hoặc lỗi khác ra console
                    toastr.error('Có lỗi xảy ra. Vui lòng thử lại sau.', "Thao tác thất bại");
                });
        });
    </script>
@endsection
