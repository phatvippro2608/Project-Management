@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Tasks</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/myxteam/teams">Team List</a></li>
                <li class="breadcrumb-item active">Project List</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body mt-3">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable table-hover">
                        <thead>
                        <tr>
                            <th>Task ID</th>
                            <th>Task Name</th>
                            <th>Description</th>
                            <th>Create Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tasks as $task)
                            <tr id="taskRow{{ $task['TaskId'] }}">
                                <td>{{ $task['TaskId'] }}</td>
                                <td id="taskName{{ $task['TaskId'] }}">{{ $task['TaskName'] }}</td>
                                <td id="description{{ $task['TaskId'] }}">{{ $task['Description'] }}</td>
                                <td>{{ $task['CreateDate'] }}</td>
                                <td>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $task['TaskId'] }}">Edit</button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="editTaskModal{{ $task['TaskId'] }}" tabindex="-1" aria-labelledby="editTaskModalLabel{{ $task['TaskId'] }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editTaskModalLabel{{ $task['TaskId'] }}">Edit Task</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST" id="editTaskForm{{ $task['TaskId'] }}" action="{{ url('/myxteam/team/'.$WorkspaceId.'/project/'.$ProjectId.'/task/'.$task['TaskId']) }}">
                                                    @csrf
                                                    @method('POST')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="taskName{{ $task['TaskId'] }}" class="form-label">Task Name</label>
                                                            <input type="text" class="form-control" id="taskNameInput{{ $task['TaskId'] }}" name="TaskName" value="{{ $task['TaskName'] }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="sectionId{{ $task['TaskId'] }}" class="form-label">Section ID</label>
                                                            <input type="number" class="form-control" id="sectionId{{ $task['TaskId'] }}" name="SectionId" value="{{ $task['SectionId'] }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="parentTaskId{{ $task['TaskId'] }}" class="form-label">Parent Task ID</label>
                                                            <input type="number" class="form-control" id="parentTaskId{{ $task['TaskId'] }}" name="ParentTaskId" value="{{ $task['ParentTaskId'] }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="description{{ $task['TaskId'] }}" class="form-label">Description</label>
                                                            <textarea class="form-control" id="descriptionInput{{ $task['TaskId'] }}" name="Description">{{ $task['Description'] }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="assignedId{{ $task['TaskId'] }}" class="form-label">Assigned ID</label>
                                                            <input type="number" class="form-control" id="assignedId{{ $task['TaskId'] }}" name="AssignedId" value="{{ $task['AssignedId'] }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="startDate{{ $task['TaskId'] }}" class="form-label">Start Date</label>
                                                            <input type="text" class="form-control" id="startDate{{ $task['TaskId'] }}" name="StartDate" value="{{ $task['StartDate'] }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="dueDate{{ $task['TaskId'] }}" class="form-label">Due Date</label>
                                                            <input type="text" class="form-control" id="dueDate{{ $task['TaskId'] }}" name="DueDate" value="{{ $task['DueDate'] }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="isComplete{{ $task['TaskId'] }}" class="form-label">Is Complete</label>
                                                            <input type="checkbox" class="form-check-input" id="isComplete{{ $task['TaskId'] }}" name="IsComplete" {{ isset($task['IsComplete']) && $task['IsComplete'] ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        .table th, .table td {
            white-space: nowrap;
        }
        .modal-dialog {
            max-width: 800px;
        }
        .modal-body {
            max-height: 500px;
            overflow-y: auto;
        }
    </style>
@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('form[id^="editTaskForm"]').forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault(); // Ngăn chặn việc gửi form mặc định

                    let formData = new FormData(this);
                    let taskId = this.id.replace('editTaskForm', ''); // Extract taskId from form ID

                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                        .then(response => {
                            return response.text().then(text => {
                                try {
                                    return JSON.parse(text);
                                } catch (error) {
                                    console.error('Error parsing JSON:', error);
                                    console.error('Response text:', text);
                                    throw new Error('Invalid JSON response');
                                }
                            });
                        })
                        .then(data => {
                            if (data.status === 'success') {
                                toastr.success(data.message, "Success");
                                document.getElementById('taskName' + taskId).innerText = formData.get('TaskName');
                                document.getElementById('description' + taskId).innerText = formData.get('Description');
                                setTimeout(() => {
                                    location.reload();
                                }, 500);
                            } else {
                                let errorMessage = data.message || 'Update failed';
                                if (data.error) {
                                    errorMessage += ': ' + JSON.stringify(data.error);
                                    console.error('Error:', data.error);
                                }
                                toastr.error(errorMessage, "Update Failed");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error(error.message, "Update Failed");
                        });
                });
            });
        });
    </script>
@endsection
