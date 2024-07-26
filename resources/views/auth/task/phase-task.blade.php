<!-- resources/views/tasks/phase-tasks.blade.php -->
@extends('auth.main')

@section('contents')
    <style>
        a {
            text-decoration: none;

        }
    </style>
    <div class="row mt-5">
        <div class="col-1 d-flex align-items-center">
            <button class="btn btn-link btn-outline-primary btn-lg" onclick="history.back()">
                <i class="bi bi-arrow-left"></i>
            </button>
        </div>
        <h1 class="text-center mb-4">Project Management</h1>
        <h2 class="text-center mb-4">Task</h2>
        <div class="col-md-12">
            <h3><strong>Phase 1</strong></h3>
            <p class="fst-italic">(Click on a Task to view its corresponding Subtasks)</p>
            <div class="text-end">
                <button class="btn btn-primary mt-2" id="addTaskBtn" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                    <i class="bi bi-plus-circle"></i> Add Task
                </button>
            </div>
            <table id="tasksTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Phase</th>
                        <th>Task</th>
                        <th>Progress</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Initial Quantity</th>
                        <th>Engineers</th>
                        <th>Report Information</th>
                        <th>Area</th>
                        <th>Actual Quantity</th>
                        <th>State</th>
                        <th>Difficulties</th>
                        <th>Request</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Phase 1</td>
                        <td><a href="{{ route('task.subtasks', 'Task 1') }}">Task 1</td>
                        <td>50%</td>
                        <td>2023-06-10</td>
                        <td>2023-09-10</td>
                        <td>100</td>
                        <td>Engineer A</td>
                        <td>Info A</td>
                        <td>Area 1</td>
                        <td>90</td>
                        <td>In Progress</td>
                        <td>Difficulties A</td>
                        <td>Request A</td>
                        <td>
                            <button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                data-id="">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            |
                            <button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                data-id="">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Thêm task --}}
    <!-- Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTaskForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="taskName" class="form-label">Task Name</label>
                                <input type="text" class="form-control" id="taskName" name="taskName" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phase" class="form-label">Phase</label>
                                <input type="text" class="form-control" id="phase" name="phase" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="progress" class="form-label">Progress</label>
                                <input type="number" class="form-control" id="progress" name="progress" required>
                            </div>
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="startDate" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" name="endDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="initialQuantity" class="form-label">Initial Quantity</label>
                                <input type="number" class="form-control" id="initialQuantity" name="initialQuantity"
                                    required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="engineers" class="form-label">Engineers</label>
                                <input type="text" class="form-control" id="engineers" name="engineers" required>
                            </div>
                            <div class="col-md-6">
                                <label for="reportInformation" class="form-label">Report Information</label>
                                <input type="text" class="form-control" id="reportInformation" name="reportInformation"
                                    required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="area" class="form-label">Area</label>
                                <input type="text" class="form-control" id="area" name="area" required>
                            </div>
                            <div class="col-md-6">
                                <label for="actualQuantity" class="form-label">Actual Quantity</label>
                                <input type="number" class="form-control" id="actualQuantity" name="actualQuantity"
                                    required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state" required>
                            </div>
                            <div class="col-md-6">
                                <label for="difficulties" class="form-label">Difficulties</label>
                                <input type="text" class="form-control" id="difficulties" name="difficulties"
                                    required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="request" class="form-label">Request</label>
                                <input type="text" class="form-control" id="request" name="request" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">Add Task</button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-secondary w-100"
                                    data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
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
    </script>
@endsection
