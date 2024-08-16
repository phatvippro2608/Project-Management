<?php

use App\Http\Controllers\AccountController;

$token = 'position';
?>
@extends('auth.main')
@section('head')
    <link rel="stylesheet" href="{{ asset('assets/css/dhtmlxgantt.css') }}" type="text/css">
    <script src="{{ asset('assets/js/dhtmlxgantt.js') }}"></script>
@endsection
@section('contents')
    <style>
        .employees-dropdown {
            min-width: 10%;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            display: none;
            position: absolute;
            z-index: 1;
        }
    </style>
    <div class="pagetitle">
        <h1>Progress</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('project.projects') }}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{ route('project.details', ['project_id' => $p_id]) }}">Details</a>
                </li>
                <li class="breadcrumb-item active">Progress</li>
            </ol>
        </nav>
    </div>
    <div class="section employees">
        <div class="card rounded-4 p-2">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    @php
                        $data = \Illuminate\Support\Facades\DB::table('accounts')
                            ->join('employees', 'accounts.employee_id', '=', 'employees.employee_id')
                            ->join('contacts', 'employees.contact_id', '=', 'contacts.contact_id')
                            ->join('job_details', 'job_details.employee_id', '=', 'employees.employee_id')

                            ->where(
                                'accounts.account_id',
                                \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID),
                            )
                            ->first();
                        $empl_id = (int) $data->employee_id;
                        $leader_arr = [];
                        foreach ($leaders as $l) {
                            array_push($leader_arr, $l->employee_id);
                        }
                    @endphp
                    @if (in_array($empl_id, $leader_arr) ||
                            in_array(AccountController::permissionStr(), ['super', 'admin', 'project_manager']))
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalCreateTask"><i class="bi bi-plus-lg me-1"></i>Create</button>
                    @endif
                    <div class="btn-group">
                        <button class="btn btn-primary" id="btnD">Day</button>
                        <button class="btn btn-primary" id="btnM">Month</button>
                        <button class="btn btn-primary" id="btnY">Year</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="gantt_here" style='width:100%; height: calc(100vh - 200px);'></div>
            </div>
        </div>
    </div>
    @if (in_array($empl_id, $leader_arr) ||
            in_array(AccountController::permissionStr(), ['super', 'admin', 'project_manager']))
        <div class="modal fade" id="modalCreateTask" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <form id="create-task-form" method="post">
                        @csrf
                        <div class="modal-header bg-white">
                            <div class="input-group">
                                <input name="allmarkdone" type="checkbox" class="btn-check" id="btn-check-c"
                                    autocomplete="off">
                                <label class="btn btn-outline-success me-2" for="btn-check-c"><i
                                        class="bi bi-check"></i></label>
                                <input type="text" class="form-control modal-title fs-5 no-background" name="taskname"
                                    placeholder="[Task Name]" required>
                            </div>
                            <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x" style="font-size: 3vh;"></i></button>
                        </div>
                        <div class="modal-body row">
                            <div class="form-group col-6">
                                <div class="form-group">
                                    <div class="form-group"><strong><i class="bi bi-diagram-2"></i> Add a
                                            sub-task</strong></div>
                                    <div id="c-subtasks-holder">
                                    </div>
                                    <a class="btn" onclick="c_addSubTask()"><i class="bi bi-plus"></i>Add</a>
                                </div>
                                <div class="form-group" style="margin: 2% 0;">
                                    <label class="form-group"><strong><i class="bi bi-body-text"></i>
                                            Request</strong></label>
                                    <textarea class="form-control" placeholder="Enter your request" style="resize: none;" name="request"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-6" style="border-left: 1px solid;">
                                <div class="form-group">
                                    <label for="users"><strong><i class="bi bi-person-circle"></i>
                                            Assigned</strong></label>
                                    <div class="row form-group mt-3">
                                        <div class="d-flex justify-content-between">
                                            <input class="form-control empl_name" onkeyup="searchDropdown(this)"
                                                onclick="displayDropdown(this)" required>
                                            <input class="form-control empl_code" style="width: 25%;"
                                                onkeyup="searchDropdown(this)" onclick="displayDropdown(this)" required>
                                            <input type="text" class="empl_id" name="employee_id" hidden>
                                        </div>
                                    </div>
                                    <div class="employees-dropdown fs-5">
                                        @foreach ($employees as $employee)
                                            @php
                                                $photoPath = asset($employee->photo);
                                                $defaultPhoto = asset('assets/img/avt.png');
                                                $photoExists =
                                                    !empty($employee->photo) &&
                                                    file_exists(public_path($employee->photo));
                                            @endphp
                                            <div class="employee-item d-flex align-items-center"
                                                data-id="{{ $employee->employee_id }}"
                                                data-value="{{ $employee->last_name . ' ' . $employee->first_name }}"
                                                data-code={{ $employee->employee_code }}>
                                                <img src="{{ $photoExists ? $photoPath : $defaultPhoto }}"
                                                    class="rounded-circle object-fit-cover" width="22" height="22">
                                                <div class="empl_val ms-1"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group" style="margin: 2% 0">
                                    <label for="s_date"><strong><i class="bi bi-calendar"></i> Start
                                            date</strong></label>
                                    <input class="form-control" type="date" onchange="checkDate('create-task-form')"
                                        name="s_date" required>
                                </div>
                                <div class="form-group" style="margin: 2% 0">
                                    <label for="e_date"><strong><i class="bi bi-calendar"></i> End date</strong></label>
                                    <input class="form-control" type="date" onchange="checkDate('create-task-form')"
                                        name="e_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <div class="modal fade" id="modalViewTask" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form id="edit-task-form">
                    @csrf
                    <div class="modal-header bg-white">
                        @if (in_array($empl_id, $leader_arr) ||
                                in_array(AccountController::permissionStr(), ['super', 'admin', 'project_manager']))
                            <input type="text" id="task_id" name="task_id" style="display:none;" required>
                            <div class="input-group">
                                <input name="allmarkdone" type="checkbox" class="btn-check" id="btn-check-e"
                                    autocomplete="off">
                                <label class="btn btn-outline-success me-2" for="btn-check-e"><i
                                        class="bi bi-check"></i></label>
                                <input type="text" class="form-control modal-title fs-5" id="taskname"
                                    name="taskname" required>
                            </div>
                        @else
                            <input type="text" id="task_id" name="task_id" style="display:none;" readonly required>
                            <div class="input-group">
                                <input name="allmarkdone" type="checkbox" class="btn-check" id="btn-check-e"
                                    autocomplete="off">
                                <label class="btn btn-outline-success me-2" for="btn-check-e"><i
                                        class="bi bi-check"></i></label>
                                <input type="text" class="form-control modal-title fs-5" id="taskname"
                                    name="taskname" readonly required>
                            </div>
                        @endif
                        @if (in_array($empl_id, $leader_arr) ||
                                in_array(AccountController::permissionStr(), ['super', 'admin', 'project_manager']))
                            <div class="dropdown">
                                <div class="btn" type="button" id="dropdownMenuTask" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="bi bi-three-dots" style="font-size: 3vh;"></i>
                                </div>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuTask">
                                    <li><a class="dropdown-item" id="dl_task_id" href="#"
                                            onclick="delTask(this)">Delete</a></li>
                                </ul>
                            </div>
                        @endif
                        <div type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i
                                class="bi bi-x" style="font-size: 3vh;"></i></div>
                    </div>
                    <div class="modal-body row">
                        <div class="form-group col-6">
                            <div class="form-group" id="subTaskHol">
                                <div><strong><i class="bi bi-diagram-2"></i> Sub task</strong></div>
                                <div id="e-subtasks-holder">
                                </div>
                                <a class="btn" onclick="e_addSubTask()"><i class="bi bi-plus"></i>Add</a>
                            </div>
                            <div class="form-group" style="margin: 2% 0;">
                                <label><strong><i class="bi bi-body-text"></i> Request</strong></label>
                                <textarea class="form-control" placeholder="Enter your request" style="resize: none;" id="request"
                                    name="request"></textarea>
                            </div>
                        </div>
                        <div class="form-group col-6" style="border-left: 1px solid;">
                            <div class="form-group">
                                <label><strong><i class="bi bi-person-circle"></i> Assigned</strong></label>
                                <div class="row form-group mt-3">
                                    <div class="d-flex justify-content-between">
                                        <input id="empl_name" class="form-control empl_name"
                                            onkeyup="searchDropdown(this)" onclick="displayDropdown(this)" required>
                                        <input id="empl_code" class="form-control empl_code" style="width: 25%;"
                                            onkeyup="searchDropdown(this)" onclick="displayDropdown(this)" required>
                                        <input id="empl_id" type="text" class="empl_id" name="employee_id" hidden
                                            required>
                                    </div>
                                </div>
                                <div class="employees-dropdown fs-5">
                                    @foreach ($employees as $employee)
                                        @php
                                            $photoPath = asset($employee->photo);
                                            $defaultPhoto = asset('assets/img/avt.png');
                                            $photoExists =
                                                !empty($employee->photo) && file_exists(public_path($employee->photo));
                                        @endphp
                                        <div class="employee-item d-flex align-items-center"
                                            data-id="{{ $employee->employee_id }}"
                                            data-value="{{ $employee->last_name . ' ' . $employee->first_name }}"
                                            data-code={{ $employee->employee_code }}>
                                            <img src="{{ $photoExists ? $photoPath : $defaultPhoto }}"
                                                class="rounded-circle object-fit-cover" width="22" height="22">
                                            <div class="empl_val ms-1"></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group" style="margin: 2% 0">
                                <label><strong><i class="bi bi-calendar"></i> Start date</strong></label>
                                <input class="form-control" type="date" id="s_date"
                                    onchange="checkDate('edit-task-form')" name="s_date" required>
                            </div>
                            <div class="form-group" style="margin: 2% 0">
                                <label><strong><i class="bi bi-calendar"></i> End date</strong></label>
                                <input class="form-control" type="date" id="e_date"
                                    onchange="checkDate('edit-task-form')" name="e_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $('#btnD').click(function() {
            gantt.config.scale_unit = "day";
            gantt.config.step = 1;
            gantt.config.date_scale = "%d %M";
            gantt.config.scale_width = 50;
            gantt.init("gantt_here");
        });
        $('#btnM').click(function() {
            gantt.config.scale_unit = "month";
            gantt.config.step = 1;
            gantt.config.date_scale = "%M %y";
            gantt.config.scale_width = 50;
            gantt.init("gantt_here");
        });
        $('#btnY').click(function() {
            gantt.config.scale_unit = "year";
            gantt.config.step = 1;
            gantt.config.date_scale = "%Y";
            gantt.config.scale_width = 50;
            gantt.init("gantt_here");
        });

        gantt.config.date_format = "%Y-%m-%d";
        gantt.config.drag_move = false;
        gantt.config.drag_resize = false;
        gantt.config.drag_links = false;
        gantt.config.drag_progress = false;
        gantt.config.details_on_dblclick = false;
        gantt.config.details_on_create = false;
        gantt.config.scale_unit = "day";
        gantt.config.step = 1;
        gantt.config.date_scale = "%d %M";
        gantt.config.scale_width = 50;
        gantt.config.autosize = "y";

        gantt.templates.task_text = function(start, end, task) {
            return "<div style='text-align:center;' data-column='task'>" + task.progress * 100 + "%</div>";
        };

        gantt.config.columns = [{
                name: "text",
                label: "Task name",
                tree: true,
                width: 150,
                template: function(task) {
                    return "<div data-column='task'>" + task.text + "</div>";
                }
            },
            {
                name: "start_date",
                label: "Start",
                align: "center",
                width: 100,

            },
            {
                name: "duration",
                label: "Duration",
                align: "center",
                width: "*",
            }
        ];
        gantt.init("gantt_here");

        loadData();

        function loadData() {
            gantt.clearAll();
            $.ajax({
                type: 'post',
                url: "{{ route('tasks.getTasks', ['project_id' => $p_id, 'location_id' => $id]) }}",
                data: {
                    id: {{ $id }},
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    response.tasks.forEach(function(task) {
                        var dur = Math.ceil((new Date(task.end_date) - new Date(task.start_date)) / (
                            1000 * 60 * 60 * 24));
                        var parent = task.parent_id ? task.parent_id : 0;
                        gantt.addTask({
                            id: task.task_id,
                            text: task.task_name,
                            start_date: task.start_date,
                            parent: parent,
                            duration: dur,
                            progress: task.progress,
                        });
                        if (parent == 0) {
                            gantt.open(task.task_id);
                        }
                    });
                },
            });
        }
        //sự kiện click vào task sẽ chọn task đó nếu click lần nữa sẽ hiển thị modal

        gantt.attachEvent("onTaskDblClick", function(id, e) {
            var column = e.target.getAttribute("data-column");
            if (column == 'task') {
                $.ajax({
                    type: 'post',
                    url: "{{ route('task.getTask', ['project_id' => $p_id, 'location_id' => $id]) }}",
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        response.tasks.forEach(function(task) {
                            $('#task_id').val(task.task_id);
                            $('#taskname').val(task.task_name);
                            $('#request').val(task.request);
                            var emlp_name = task.last_name ? ' ' + task.last_name : '';
                            emlp_name += ' ';
                            emlp_name += task.first_name ? task.first_name : '';
                            emlp_name = emlp_name.trim();
                            $('#empl_name').val(emlp_name);
                            $('#empl_code').val(task.employee_code);
                            $('#empl_id').val(task.employee_id);
                            $('#s_date').val(task.start_date);
                            $('#e_date').val(task.end_date);
                            $('#e_date').attr('min', task.start_date);
                            $('#dl_task_id').attr('data-task-id', task.task_id);
                            $('#btn-check-e').prop('checked', task.progress == 1);
                        });
                        $('#e-subtasks-holder').html('');
                        if (response.tasks[0].parent_id != null) {
                            $('#subTaskHol').hide();
                        } else {
                            $('#subTaskHol').show();
                            response.subtasks.forEach(function(subtask_data) {
                                var subtask = document.createElement('div');
                                subtask.classList.add('input-group');
                                subtask.style.marginTop = '1%';
                                subtask.style.marginBottom = '1%';
                                subtask.innerHTML = `
                            <input name="markdone_${subtask_data.task_id}" type="checkbox" class="btn-check" id="btn-check-${subtask_data.task_id}" autocomplete="off" ${subtask_data.progress == 1 ? 'checked' : ''}>
                            <label class="btn btn-outline-success me-2" for="btn-check-${subtask_data.task_id}"><i class="bi bi-check"></i></label>
                            <input name="subtask_${subtask_data.task_id}" type="text" class="form-control" placeholder="What need to be done?" value="${subtask_data.task_name}">
                            <a class="btn btn-danger" onclick="removeSubTask(this)"><i class="bi bi-x"></i></a>`;
                                document.getElementById('e-subtasks-holder').appendChild(
                                    subtask);
                            });
                        }
                    },
                });
                $('#modalViewTask').modal('show');
            }
        });

        $('#edit-task-form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();
            data += '&id=' + {{ $id }};
            $.ajax({
                type: 'post',
                url: "{{ route('task.update', ['project_id' => $p_id, 'location_id' => $id]) }}",
                data: data,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadData();
                        $('#modalViewTask').modal('hide');
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        });

        @if (in_array($empl_id, $leader_arr) ||
                in_array(AccountController::permissionStr(), ['super', 'admin', 'project_manager']))
            $('#create-task-form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();
                data += '&id=' + {{ $id }};
                $.ajax({
                    type: 'post',
                    url: "{{ route('task.create', ['project_id' => $p_id, 'location_id' => $id]) }}",
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            loadData();
                            $('#modalCreateTask').modal('hide');
                        } else {
                            toastr.error(response.message);
                        }
                    },
                });
            });

            function delTask(e) {
                var task_id = $(e).attr('data-task-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'post',
                            url: "{{ route('task.delete', ['project_id' => $p_id, 'location_id' => $id]) }}",
                            data: {
                                id: task_id,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    loadData();
                                    $('#modalViewTask').modal('hide');
                                } else {
                                    toastr.error(response.message);
                                }
                            },
                        });
                    }
                });
            };
        @endif

        function displayDropdown(e) {
            $('.employees-dropdown').css('display', 'block');
            var position = $(e).position();
            $('.employees-dropdown').css('top', position.top + 40);
            $('.employees-dropdown').css('left', position.left);
            $('.employees-dropdown').css('width', $(e).width());
            if ($(e).hasClass('empl_code')) {
                $('.employee-item').each(function() {
                    $(this).find('.empl_val').html($(this).attr('data-code'));
                });
            } else {
                $('.employee-item').each(function() {
                    $(this).find('.empl_val').html($(this).attr('data-value'));
                });
            }
            var value = $(e).val().toLowerCase();
            $('.employee-item').filter(function() {
                if ($(e).hasClass('empl_code')) {
                    if ($(this).attr('data-code').toLowerCase().indexOf(value) > -1) {
                        $(this).attr('style', 'display: flex !important');
                    } else {
                        $(this).attr('style', 'display: none !important');
                    }
                } else {
                    if ($(this).attr('data-value').toLowerCase().indexOf(value) > -1) {
                        $(this).attr('style', 'display: flex !important');
                    } else {
                        $(this).attr('style', 'display: none !important');
                    }
                }
            });
        }

        function searchDropdown(e) {
            var value = $(e).val().toLowerCase();
            $('.employee-item').filter(function() {
                if ($(e).hasClass('empl_code')) {
                    if ($(this).attr('data-code').toLowerCase().indexOf(value) > -1) {
                        $(this).attr('style', 'display: flex !important');
                    } else {
                        $(this).attr('style', 'display: none !important');
                    }
                } else {
                    if ($(this).attr('data-value').toLowerCase().indexOf(value) > -1) {
                        $(this).attr('style', 'display: flex !important');
                    } else {
                        $(this).attr('style', 'display: none !important');
                    }
                }
            });
        }

        $('.employee-item').click(function() {
            $('.empl_name').val($(this).attr('data-value'));
            $('.empl_id').val($(this).attr('data-id'));
            $('.empl_code').val($(this).attr('data-code'));
            $('.employees-dropdown').css('display', 'none');
        });
        c_subtaskCount = 0;

        function c_addSubTask() {
            c_subtaskCount++;
            var subtask = document.createElement('div');
            subtask.classList.add('input-group');
            subtask.style.marginTop = '1%';
            subtask.style.marginBottom = '1%';
            subtask.innerHTML = `
            <input name="markdone_${c_subtaskCount}" type="checkbox" class="btn-check" id="btn-check-${c_subtaskCount}" autocomplete="off">
            <label class="btn btn-outline-success me-2" for="btn-check-${c_subtaskCount}"><i class="bi bi-check"></i></label>
            <input name="subtask_${c_subtaskCount}" type="text" class="form-control" placeholder="What need to be done?">
            <a class="btn btn-danger" onclick="removeSubTask(this)"><i class="bi bi-x"></i></a>
            `;
            document.getElementById('c-subtasks-holder').appendChild(subtask);
        }
        var e_subtaskCount = 0;

        function e_addSubTask() {
            e_subtaskCount++;
            var subtask = document.createElement('div');
            subtask.classList.add('input-group');
            subtask.style.marginTop = '1%';
            subtask.style.marginBottom = '1%';
            subtask.innerHTML = `
            <input name="markdone_n${e_subtaskCount}" type="checkbox" class="btn-check" id="btn-check-${e_subtaskCount}" autocomplete="off">
            <label class="btn btn-outline-success me-2" for="btn-check-${e_subtaskCount}"><i class="bi bi-check"></i></label>
            <input name="subtask_n${e_subtaskCount}" type="text" class="form-control" placeholder="What need to be done?">
            <a class="btn btn-danger" onclick="removeSubTask(this)"><i class="bi bi-x"></i></a>`;
            document.getElementById('e-subtasks-holder').appendChild(subtask);
        }

        function removeSubTask(e) {
            e.parentElement.remove();
        }

        function checkDate(formId) {
            var form = document.querySelector(`#${formId}`);
            var startDateInput = form.querySelector('input[name="s_date"]');
            var endDateInput = form.querySelector('input[name="e_date"]');

            var startDate = startDateInput.value;
            var endDate = endDateInput.value;

            if (startDate) {
                endDateInput.min = startDate;
            } else {
                endDateInput.min = "";
            }

            if (endDate) {
                startDateInput.max = endDate;
            } else {
                startDateInput.max = "";
            }
        }

        $('#btn-check-e').change(function() {
            if ($(this).is(':checked')) {
                $('input[name^="markdone_n"]').prop('checked', true);
            }
        });
        $('#btn-check-c').change(function() {
            if ($(this).is(':checked')) {
                $('input[name^="markdone_"]').prop('checked', true);
            }
        });
    </script>
@endsection
