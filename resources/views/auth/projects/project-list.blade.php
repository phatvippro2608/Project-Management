@extends('auth.main')

@section('contents')
    <div class="container mt-5">
        <h1 class="mb-4">Project List</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addProjectModal">Add</button>
        <button class="btn btn-primary mb-4">Import</button>
        <button class="btn btn-primary mb-4">Export</button>
        <button class="btn btn-primary mb-4">Filter</button>
        <button class="btn btn-primary mb-4">Sort</button>
        <button class="btn btn-primary mb-4">Search</button>

        <!-- Table to display materials -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">Đang triển khai</h5>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">Sale</th>
                            <th scope="col">StartDate</th>
                            <th scope="col">EndDate</th>
                            <th scope="col">Budget</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($inprogress_projects as $project)
                            <tr>
                                <td>
                                    <a href="{{ route('project.details', ['id' => $project->project_id]) }}" class="btn btn-primary">Details and Cost</a>
                                </td>
                                <td>{{ $project->project_name }}</td>
                                <td><b>...</b></td>
                                <td>{{ $project->project_date_start }}</td>
                                <td>{{ $project->project_date_end }}</td>
                                <td><b>...</b></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-success">Nghiệm thu - Defect</h5>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">Sale</th>
                            <th scope="col">StartDate</th>
                            <th scope="col">EndDate</th>
                            <th scope="col">Budget</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($inspection_projects as $project)
                            <tr>
                                <td>
                                    <a href="{{ route('project.details', ['id' => $project->project_id]) }}" class="btn btn-primary">Details and Cost</a>
                                </td>
                                <td>{{ $project->project_name }}</td>
                                <td><b>...</b></td>
                                <td>{{ $project->project_date_start }}</td>
                                <td>{{ $project->project_date_end }}</td>
                                <td><b>...</b></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-info">Khảo sát - Thiết kế (3)</h5>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">Sale</th>
                            <th scope="col">StartDate</th>
                            <th scope="col">EndDate</th>
                            <th scope="col">Budget</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($survey_projects as $project)
                            <tr>
                                <td>
                                    <a href="{{ route('project.details', ['id' => $project->project_id]) }}" class="btn btn-primary">Details and Cost</a>
                                </td>
                                <td>{{ $project->project_name }}</td>
                                <td><b>...</b></td>
                                <td>{{ $project->project_date_start }}</td>
                                <td>{{ $project->project_date_end }}</td>
                                <td><b>...</b></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-warning">Support - Hổ trợ (3)</h5>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">Sale</th>
                            <th scope="col">StartDate</th>
                            <th scope="col">EndDate</th>
                            <th scope="col">Budget</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($support_projects as $project)
                            <tr>
                                <td>
                                    <a href="{{ route('project.details', ['id' => $project->project_id]) }}" class="btn btn-primary">Details and Cost</a>
                                </td>
                                <td>{{ $project->project_name }}</td>
                                <td><b>...</b></td>
                                <td>{{ $project->project_date_start }}</td>
                                <td>{{ $project->project_date_end }}</td>
                                <td><b>...</b></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-danger">Close (4)</h5>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">Sale</th>
                            <th scope="col">StartDate</th>
                            <th scope="col">EndDate</th>
                            <th scope="col">Budget</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($closed_projects as $project)
                            <tr>
                                <td>
                                    <a href="{{ route('project.details', ['id' => $project->project_id]) }}" class="btn btn-primary">Details and Cost</a>
                                </td>
                                <td>{{ $project->project_name }}</td>
                                <td><b>...</b></td>
                                <td>{{ $project->project_date_start }}</td>
                                <td>{{ $project->project_date_end }}</td>
                                <td><b>...</b></td>
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
                    <form action="" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="project_title" class="form-label">Project Title</label>
                                <input type="text" class="form-control" id="project_title" name="project_title" required>
                                <label for="project_date_start" class="form-label">Project Start Date</label>
                                <input type="text" class="form-control" id="project_date_start" name="project_date_start" required>
                                <label for="project_date_end" class="form-label">Project End Date</label>
                                <input type="text" class="form-control" id="project_date_end" name="project_date_end" required>
                                <label for="project_summanry" class="form-label">Summary</label>
                                <textarea name="project_summanry" id="project_summanry" rows="3" class="form-control"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="project_details" class="form-label">Details</label>
                                <textarea name="project_details" id="project_details" rows="8" class="form-control"></textarea>
                                <label for="project_phase" class="form-label">Phase</label>
                                <select name="project_phase" id="project_phase" class="form-control">
                                    <option value="">Upcoming</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <input type="submit" value="Close" class="btn btn-danger">
                        <input type="submit" value="Submit" class="btn btn-success">
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Custom JavaScript can be added here
    </script>

    <style>
        .custom-td {
            display: block;
            margin-top: 5px;
        }
    </style>
@endsection
