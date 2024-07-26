@extends('auth.main');
@section('contents')
    <style>
        a {
            text-decoration: none;
        }

        #tasksTable {
            font-size: 0.875rem;
        }

        #tasksTable th,
        #tasksTable td {
            padding: 0.5rem;
            text-align: left;
        }

        #tasksTable .btn {
            font-size: 0.75rem;
        }

        #tasksTable .bi {
            font-size: 1rem;
        }
    </style>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Project Management</h1>
        <div class="row mb-3">
            <div class="col-md-8">
                <div class="row justify-content-end">
                    <div class="col-md-6">
                        <div class="form-inline justify-content-center">
                            <label for="reportDay" class="mr-2">Report Day:</label>
                            <input type="date" class="form-control" id="reportDay">
                        </div>
                        <div class="d-flex justify-content-center mt-2">
                            <button class="btn btn-primary">Watch</button>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mt-5">
                            <i><strong class="display-5"><b>DAY</b>:19-01-2003</strong></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h3>Project Engineers Information</h3>
                <div class="text-start">
                    <button class="btn btn-primary mt-1" id="addEngineerBtn" data-bs-toggle="modal"
                        data-bs-target="#addEngineerModal">
                        <i class="bi bi-plus-circle"></i> Add Engineer
                    </button>
                    <table id="engineersTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Admin</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Overtime Start</th>
                                <th>Overtime End</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="engineersTableBody">
                            <tr>
                                <td>1</td>
                                <td>Tran</td>
                                <td>Van A</td>
                                <td><input type="checkbox"></td>
                                <td><input type="time" class="form-control"></td>
                                <td><input type="time" class="form-control"></td>
                                <td><input type="time" class="form-control"></td>
                                <td><input type="time" class="form-control"></td>
                                <td>
                                    <button
                                        class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                        data-id="">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    |
                                    <button
                                        class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                        data-id="">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>

                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Nguyen</td>
                                <td>Van B</td>
                                <td><input type="checkbox"></td>
                                <td><input type="time" class="form-control"></td>
                                <td><input type="time" class="form-control"></td>
                                <td><input type="time" class="form-control"></td>
                                <td><input type="time" class="form-control"></td>
                                <td>
                                    <button
                                        class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                        data-id="">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    |
                                    <button
                                        class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                        data-id="">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12">
                    <h3>All Tasks</h3>
                    <p class="fst-italic">(Click on a Phase to view its corresponding Tasks)</p>
                    <div class="text-start">
                        <button class="btn btn-primary mt-1" id="addPhaseBtn" data-bs-toggle="modal"
                            data-bs-target="#addPhaseModal">
                            <i class="bi bi-plus-circle"></i> Add Phase
                        </button>
                    </div>
                    <table id="tasksTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Phase</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><a href="{{ route('phase.tasks', 'Phase 1') }}">Phase 1</td>
                                <td>
                                    <button
                                        class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                        data-id="">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    |
                                    <button
                                        class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                        data-id="">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><a href="{{ route('phase.tasks', 'Phase 1') }}">Phase 2</td>
                                <td>
                                    <button
                                        class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                        data-id="">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    |
                                    <button
                                        class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                        data-id="">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-5">
            <button class="btn btn-primary mx-2">
                <- Previous Day </button>

                    <button class="btn btn-primary mx-2">
                        Next Day ->
                    </button>
        </div>

        <!-- Modal add Phase-->
        <!-- Modal -->
        <div class="modal fade" id="addPhaseModal" tabindex="-1" aria-labelledby="addPhaseModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPhaseModalLabel">Add New Phase</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addPhaseForm">
                            @csrf
                            <div class="mb-3">
                                <label for="phaseName" class="form-label">Phase Name</label>
                                <input type="text" class="form-control" id="phaseName" name="phaseName" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Phase</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal thêm nv --}}
        <div class="modal fade" id="addEngineerModal" tabindex="-1" aria-labelledby="addEngineerModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEngineerModalLabel">Add New Engineer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addEngineerForm">
                            @csrf
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                            </div>
                            <div class="mb-3">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                            </div>
                            <div class="mb-3">
                                <label for="admin" class="form-label">Admin</label>
                                <input type="checkbox" class="form-check-input" id="admin" name="admin">
                            </div>
                            <div class="mb-3">
                                <label for="startTime" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="startTime" name="startTime">
                            </div>
                            <div class="mb-3">
                                <label for="endTime" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="endTime" name="endTime">
                            </div>
                            <div class="mb-3">
                                <label for="overtimeStart" class="form-label">Overtime Start</label>
                                <input type="time" class="form-control" id="overtimeStart" name="overtimeStart">
                            </div>
                            <div class="mb-3">
                                <label for="overtimeEnd" class="form-label">Overtime End</label>
                                <input type="time" class="form-control" id="overtimeEnd" name="overtimeEnd">
                            </div>
                            <button type="submit" class="btn btn-primary">Add Engineer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
        <script>
            $(document).ready(function() {
                $('#tasksTable').DataTable();
                $('#engineersTable').DataTable();
            });

            $('#tasksTable').on('click', '.delete-btn', function() {
                var row = $(this).closest('tr');
                row.remove();
                reindexRows();
            });

            function reindexRows() {
                $('#tasksTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }

            $('#engineersTable').on('click', '.delete-btn', function() {
                var row = $(this).closest('tr');
                row.remove();
                reindexRows();
            });

            function reindexRows() {
                $('#engineersTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
        </script>
    @endsection
