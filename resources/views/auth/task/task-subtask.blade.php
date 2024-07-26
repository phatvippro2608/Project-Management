<!-- resources/views/tasks/phase-tasks.blade.php -->
@extends('auth.main')

@section('contents')
    <div class="row mt-5">
        <div class="col-1 d-flex align-items-center">
            <button class="btn btn-link btn-outline-primary btn-lg" onclick="history.back()">
                <i class="bi bi-arrow-left"></i>
            </button>
        </div>
        <h1 class="text-center mb-4">Project Management</h1>
        <h2 class="text-center mb-4">Sub Task</h2>
        <div class="col-md-12">
            <h3><strong>Task 1</strong></h3>
            <div class="text-end">
                <button class="btn btn-primary mt-3" id="addTaskBtn" data-bs-toggle="modal"
                    data-bs-target="#addSubtaskModal">
                    <i class="bi bi-plus-circle"></i> Add Subtask
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
                        <th>Engineers</th>
                        <th>Interruptions of work</th>
                        <th>Action for next day</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Phase 1</td>
                        <td>Task 1</td>
                        <td>50%</td>
                        <td>2023-06-10</td>
                        <td>2023-09-10</td>
                        <td>100</td>
                        <td>Engineer A</td>
                        <td>Info A</td>
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

    {{-- Them subtask --}}
    <!-- Modal -->
    <div class="modal fade" id="addSubtaskModal" tabindex="-1" aria-labelledby="addSubtaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubtaskModalLabel">Add New Subtask</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addSubtaskForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phase" class="form-label">Phase</label>
                                <input type="text" class="form-control" id="phase" name="phase" required>
                            </div>
                            <div class="col-md-6">
                                <label for="task" class="form-label">Task</label>
                                <input type="text" class="form-control" id="task" name="task" required>
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
                                <label for="engineers" class="form-label">Engineers</label>
                                <input type="text" class="form-control" id="engineers" name="engineers" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="interruptions" class="form-label">Interruptions of work</label>
                                <input type="text" class="form-control" id="interruptions" name="interruptions" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nextDayAction" class="form-label">Action for next day</label>
                                <input type="text" class="form-control" id="nextDayAction" name="nextDayAction" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">Add Subtask</button>
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
