@extends('auth.main')

@section('contents')
    <style type="text/css">
        #visualization {
            width: 100%;
            height: 80vh;
        }

        .vis-subTask {
            background-color: var(--bg-light) !important;
            border: none;
        }

        .vis-subTask .vis-inner {
            padding-left: 20px !important;
        }

        .vis-label.vis-nesting-group:before {
            transition: translateX 2s;
            transform: translateX(0);

        }

        .vis-label.vis-nesting-group.expanded:before {
            transform: translateX(90deg);
        }

        .no-background {
            background-color: transparent !important;
            border: none;
        }

        .no-background:focus {
            background-color: rgb(246, 249, 255) !important;
        }

        .no-background:hover {
            background-color: rgb(246, 249, 255) !important;
        }

        .dropdown-content {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
            display: none;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 5px;
            cursor: pointer;
        }

        .dropdown-item img {
            margin-right: 10px;
            /* Optional: Add some space between the image and the text */
        }
    </style>
    <div class="pagetitle">
        <h1>Progress</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Progress</li>
            </ol>
        </nav>
    </div>
    <div class="section employees">
        <div class="card">
            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="from-group col-xs-6 col-sm-6 col-md-5 col-lg-3 d-inline-flex">
                        <input type="text" id="TaskSearch" class="form-control" placeholder="Search Task">
                        <div class="btn-group btn-type" role="group">
                            <button id="btnTL" type="button" style="margin: 0 1% 0 5%"
                                class="btn btn-primary active">Timeline</button>
                            <button id="btnTB" type="button" style="margin: 0 1%"
                                class="btn btn-primary">Table</button>
                        </div>
                    </div>
                    <div class="from-group col-2 float-end d-inline-flex">
                        <div class="btn-group btn-type" role="group">
                            <button id="btnD" type="button" class="btn btn-primary">Day</button>
                            <button id="btnM" type="button" class="btn btn-primary">Month</button>
                            <button id="btnY" type="button" class="btn btn-primary active">Year</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="from-group time-line" style="margin-top: 1%;">
                    <div id="visualization"></div>
                </div>
                
            </div>
        </div>

        <div class="modal fade" id="modalViewTask" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form id="edit-task-form">
                        @csrf
                        <div class="modal-header">
                            <input type="text" id="task_id" name="task_id" style="display:none;" required>
                            <input type="text" class="form-control modal-title fs-5 no-background" id="taskname"
                                name="taskname" required>
                            <div class="dropdown">
                                <button class="btn" type="button" id="dropdownMenuTask" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="bi bi-three-dots" style="font-size: 3vh;"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuTask">
                                    <li><a class="dropdown-item" id="dl_task_id" href="#">Delete</a></li>
                                </ul>
                            </div>
                            <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="bi bi-x" style="font-size: 3vh;"></i></button>
                        </div>
                        <div class="modal-body row">
                            <div class="form-group col-6">
                                <div class="form-group" id="subTaskHol">
                                    <div><strong><i class="bi bi-diagram-2"></i> Add a sub-task</strong></div>
                                    <div id="e-subtasks-holder">
                                    </div>
                                    <a class="btn" onclick="e_addSubTask()"><i class="bi bi-plus"></i>Add</a>
                                </div>
                                <div class="form-group" style="margin: 2% 0;">
                                    <label><strong><i class="bi bi-body-text"></i> Request</strong></label>
                                    <textarea class="form-control" placeholder="Enter your request" style="resize: none;" id="request" name="request"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-6" style="border-left: 1px solid;">
                                <div class="form-group">
                                    <label><strong><i class="bi bi-person-circle"></i> Assigned</strong></label>
                                    <input class="form-control assign-user" type="text" id="users" data-user-id=""
                                        name="e_users" autocomplete="off" placeholder="Search..."
                                        onclick="showDropdown(this)" onfocus="showDropdown(this)">
                                    <div class="dropdown-content">
                                        @foreach ($employees as $employee)
                                            <div class="dropdown-item" data-value="{{ $employee->id_employee }}">
                                                <img src="{{ asset($employee->photo) }}" width="20"
                                                    height="20">{{ $employee->last_name }} {{ $employee->first_name }}
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
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalCreateTask" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form id="create-task-form" method="post">
                        @csrf
                        <div class="modal-header">
                            <input type="text" class="form-control modal-title fs-5 no-background" name="taskname"
                                placeholder="[Task Name]" required>
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
                                    <input class="form-control" type="text" name="c_users" data-user-id=""
                                        autocomplete="off" placeholder="Search..." onclick="showDropdown(this)"
                                        onfocus="showDropdown(this)">
                                    <div class="dropdown-content">
                                        @foreach ($employees as $employee)
                                            <div class="dropdown-item" data-value="{{ $employee->id_employee }}">
                                                <img src="{{ asset($employee->photo) }}" width="20"
                                                    height="20">{{ $employee->last_name }} {{ $employee->first_name }}
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
    </div>
    <script type="text/javascript">
        var container = document.getElementById('visualization');
        var groups = new vis.DataSet([
            @foreach ($tasks as $task)
                @php
                    $taskSubtasks = $subtasks->filter(function ($subtask) use ($task) {
                        return $subtask->task_id == $task->task_id;
                    });
                @endphp {
                    content: '{{ $task->task_name }}',
                    id: 'task_{{ $task->task_id }}',
                    @if ($taskSubtasks->isNotEmpty())
                        nestedGroups: [
                            @foreach ($taskSubtasks as $subtask)
                                'subtask_{{ $subtask->sub_task_id }}',
                            @endforeach
                        ],
                    @endif
                },
            @endforeach
            @foreach ($subtasks as $subtask)
                {
                    content: '{{ $subtask->sub_task_name }}',
                    id: 'subtask_{{ $subtask->sub_task_id }}',
                    className: 'vis-subTask',
                },
            @endforeach {
                content: 'Create',
                id: 'create',
            },
        ]);
        var items = new vis.DataSet([
            @foreach ($tasks as $item)
                @if ($item->start_date && $item->end_date)
                    {
                        start: new Date('{{ $item->start_date }}'),
                        end: new Date('{{ $item->end_date }}'),
                        group: 'task_{{ $item->task_id }}',
                        title: 'Start: {{ $item->start_date }}<br>End: {{ $item->end_date }}'
                    },
                @endif
            @endforeach
            @foreach ($subtasks as $item)
                @if ($item->start_date && $item->end_date)
                    {
                        start: new Date('{{ $item->start_date }}'),
                        end: new Date('{{ $item->end_date }}'),
                        group: 'subtask_{{ $item->sub_task_id }}',
                        title: 'Start: {{ $item->start_date }}<br>End: {{ $item->end_date }}'
                    },
                @endif
            @endforeach
        ]);
        var options = {
            format: {
                minorLabels: {
                    weekday: 'D',
                    day: 'D',
                    week: 'w',
                    month: 'MMM',
                    year: 'YYYY'
                },
                majorLabels: {
                    weekday: 'YYYY',
                    day: 'MMMM YYYY',
                    week: 'MMMM YYYY',
                    month: 'YYYY',
                    year: ''
                }
            },
            orientation: 'top',
            showCurrentTime: true,
            zoomMin: 365.25 * 24 * 60 * 60 * 1000 * 2,
            zoomMax: 365.25 * 24 * 60 * 60 * 1000 * 5,
            editable: {
                add: false,
                updateTime: false,
                updateGroup: false,
            },
            itemsAlwaysDraggable: {
                item: false,
                range: true,
            },
            groupEditable: true,
            groupTemplate: function(group) {
                var container = document.createElement("div");
                var button = document.createElement("button");
                if (group.id.split('_')[0] == 'task') {
                    button.setAttribute("data-task-id", group.id.split('_')[1]);
                    button.setAttribute("onclick", "getTask(this)");
                    button.innerHTML = group.content;
                }
                if (group.id.split('_')[0] == 'subtask') {
                    button.setAttribute("data-subtask-id", group.id.split('_')[1]);
                    button.setAttribute("onclick", "getSubTask(this)");
                    button.innerHTML = group.content;
                }
                if (group.id == "create") {
                    button.setAttribute("data-bs-toggle", "modal");
                    button.setAttribute("data-bs-target", "#modalCreateTask");
                    button.innerHTML = '<i class="bi bi-plus"></i>' + group.content;
                }
                button.classList.add("btn");
                button.classList.add("btn-light");
                container.insertAdjacentElement("beforeEnd", button);
                return container;
            },
            // onMove: function(e) {
            //         var csrfToken = document.getElementById('csrf-token')?.value;
            //         if (!csrfToken) {
            //             console.error('CSRF token is missing.');
            //             return;
            //         }
            //         var itemId = e.group;
            //         if (!itemId) {
            //             console.error('Item ID is missing.');
            //             return;
            //         }
            //         var startDate = e.start?.toISOString().slice(0, 10);
            //         var endDate = e.end?.toISOString().slice(0, 10);

            //         var data = {
            //             item_id: itemId,
            //             start_date: startDate,
            //             end_date: endDate,
            //         };
            //         fetch('/update-item', {
            //                 method: 'POST',
            //                 headers: {
            //                     'Content-Type': 'application/json',
            //                     'X-CSRF-TOKEN': csrfToken,
            //                 },
            //                 body: JSON.stringify(data),
            //             })
            //             .then(response => response.json())
            //             .catch(error => {
            //                 console.error('Error:', error);
            //             });
            //     },

        };
        var timeline = new vis.Timeline(container);
        timeline.setOptions(options);
        timeline.setGroups(groups);
        timeline.setItems(items);

        document.getElementById('TaskSearch').addEventListener('input', function(e) {
            var searchQuery = e.target.value.toLowerCase();
            var group = groups.get();
            var filteredGroups = group.filter(function(group) {
                if (group.id == "create") {
                    return true;
                }
                return group.content.toLowerCase().includes(searchQuery);
            });
            filteredGroups = filteredGroups.filter(function(group) {
                if (group.nestedGroups) {
                    group.nestedGroups = group.nestedGroups.filter(function(nestedGroup) {
                        return filteredGroups.some(function(filteredGroup) {
                            return filteredGroup.id == nestedGroup;
                        });
                    });
                }
                return true;
            });
            timeline.setGroups(new vis.DataSet(filteredGroups));
        });

        document.getElementById('btnD').addEventListener('click', function() {
            timeline.setOptions({
                zoomMin: 7 * 24 * 60 * 60 * 1000 * 3,
                zoomMax: 7 * 24 * 60 * 60 * 1000 * 5,
            });
            var window = timeline.getWindow();
            var startOfWeek = window.start;
            var endOfWeek = window.end;

            timeline.setWindow(startOfWeek, endOfWeek);
        });
        document.getElementById('btnM').addEventListener('click', function() {
            timeline.setOptions({
                zoomMin: 30 * 24 * 60 * 60 * 1000 * 3,
                zoomMax: 30 * 24 * 60 * 60 * 1000 * 5,
            });
            var window = timeline.getWindow();
            var startOfMonth = window.start;
            var endOfMonth = window.end;

            timeline.setWindow(startOfMonth, endOfMonth);
        });
        document.getElementById('btnY').addEventListener('click', function() {
            timeline.setOptions({
                zoomMin: 365.25 * 24 * 60 * 60 * 1000 * 2,
                zoomMax: 365.25 * 24 * 60 * 60 * 1000 * 5,
            });
            var window = timeline.getWindow();
            var startOfYear = window.start;
            var endOfYear = window.end;

            timeline.setWindow(startOfYear, endOfYear);
        });

        function showDropdown(e) {
            var dropdown = e.nextElementSibling;
            dropdown.style.display = 'block';

            dropdown.addEventListener('click', function(e) {
                if (e.target.classList.contains('dropdown-item')) {
                    var name = e.target.textContent;
                    name = name.trim();
                    e.target.parentElement.previousElementSibling.value = name;
                    e.target.parentElement.previousElementSibling.setAttribute('data-user-id', e.target
                        .getAttribute('data-value'));
                    e.target.parentElement.style.display = 'none';
                }
            });
        }


        var c_subtaskCount = 0;

        function c_addSubTask() {
            c_subtaskCount++;
            var subtask = document.createElement('div');
            subtask.classList.add('row');
            subtask.style.marginTop = '1%';
            subtask.style.marginBottom = '1%';
            subtask.innerHTML = `
            <div class="col-10">
                <input name="subtask${c_subtaskCount}" type="text" class="form-control" placeholder="What need to be done?">
            </div>
            <div class="col-1">
                <a class="btn" onclick="removeSubTask(this)"><i class="bi bi-x"></i></a>
            </div>
            `;
            document.getElementById('c-subtasks-holder').appendChild(subtask);
        }
        var e_subtaskCount = 0;

        function e_addSubTask() {
            e_subtaskCount++;
            var subtask = document.createElement('div');
            subtask.classList.add('row');
            subtask.style.marginTop = '1%';
            subtask.style.marginBottom = '1%';
            subtask.innerHTML = `
                <div class="col-10">
                    <input name="subtask_n${e_subtaskCount}" type="text" class="form-control" placeholder="What need to be done?">
                </div>
                <div class="col-1">
                    <a class="btn" onclick="removeSubTask(this)"><i class="bi bi-x"></i></a>
                </div>
            `;
            document.getElementById('e-subtasks-holder').appendChild(subtask);
        }

        function removeSubTask(e) {
            e.parentElement.parentElement.remove();
        }

        $('#create-task-form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();
            data += '&id=' + {{ $id }};
            data += '&user_id=' + $('input[name="c_users"]').attr('data-user-id');
            $.ajax({
                type: 'post',
                url: '{{ route('task.create') }}',
                data: data,
                success: function(response) {
                    var tasks = response.tasks;
                    var subtasks = response.subtasks;
                    var newgroups = new vis.DataSet();
                    var newitems = new vis.DataSet();
                    for (var i = 0; i < tasks.length; i++) {
                        let newGroup = {
                            content: tasks[i].task_name,
                            id: 'task_' + tasks[i].task_id,
                            nestedGroups: subtasks.filter(function(subtask) {
                                return subtask.task_id == tasks[i].task_id;
                            }).map(function(subtask) {
                                return 'subtask_' + subtask.sub_task_id;
                            }),
                        };
                        if (newGroup.nestedGroups.length == 0) {
                            console.log("asd");
                            delete newGroup.nestedGroups;
                        }
                        newgroups.add(newGroup);
                        newitems.add({
                            start: new Date(tasks[i].start_date),
                            end: new Date(tasks[i].end_date),
                            group: 'task_' + tasks[i].task_id,
                            title: 'Start: ' + tasks[i].start_date + '<br>End: ' + tasks[i]
                                .end_date
                        });
                    }
                    for (var i = 0; i < subtasks.length; i++) {
                        newgroups.add({
                            content: subtasks[i].sub_task_name,
                            id: 'subtask_' + subtasks[i].sub_task_id,
                            className: 'vis-subTask',
                        });
                        newitems.add({
                            start: new Date(subtasks[i].start_date),
                            end: new Date(subtasks[i].end_date),
                            group: 'subtask_' + subtasks[i].sub_task_id,
                            title: 'Start: ' + subtasks[i].start_date + '<br>End: ' + subtasks[
                                i].end_date
                        });
                    }
                    newgroups.add({
                        content: 'Create',
                        id: 'create',
                    });
                    groups = newgroups;
                    timeline.setGroups(newgroups);
                    timeline.setItems(newitems);
                    c_subtaskCount = 0;
                    form[0].reset();
                    $('#c-subtasks-holder').html('');
                    $('#modalCreateTask').modal('hide');
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        function getTask(e) {
            var task_id = e.getAttribute('data-task-id');
            $.ajax({
                type: 'get',
                url: '{{ url('task') }}/task/' + task_id,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var task = response.task;
                    var subtasks = response.subtasks;
                    $('#subTaskHol').show();
                    $('#task_id').val("task_" + task_id);
                    $('#dl_task_id').attr('data-task-id', "task_" + task_id);
                    $('#taskname').val(task.task_name);
                    $('#request').val(task.request);
                    $('#users').attr('data-user-id', task.engineers);
                    @foreach ($employees as $employee)
                        if ('{{ $employee->id_employee }}' == task.engineers) {
                            $('#users').val('{{ $employee->last_name }} {{ $employee->first_name }}');
                        }
                    @endforeach
                    $('#s_date').val(task.start_date);
                    $('#e_date').val(task.end_date);
                    $('#e-subtasks-holder').html('');
                    for (var i = 0; i < subtasks.length; i++) {
                        var subtask = document.createElement('div');
                        subtask.classList.add('row');
                        subtask.style.marginTop = '1%';
                        subtask.style.marginBottom = '1%';
                        subtask.innerHTML = `
                    <div class="col-10">
                        <input name="subtask_${subtasks[i].sub_task_id}" type="text" class="form-control" value="${subtasks[i].sub_task_name}">
                    </div>
                    <div class="col-1">
                        <a class="btn" onclick="removeSubTask(this)"><i class="bi bi-x"></i></a>
                    </div>`;
                        document.getElementById('e-subtasks-holder').appendChild(subtask);
                    }
                    $('#modalViewTask').modal('show');
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function getSubTask(e) {
            var subtask_id = e.getAttribute('data-subtask-id');
            $.ajax({
                type: 'get',
                url: '{{ url('task') }}/subtask/' + subtask_id,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var subtask = response.subtask;
                    $('#task_id').val("subtask_" + subtask.sub_task_id);
                    $('#dl_task_id').attr('data-task-id', "subtask_" + subtask.sub_task_id);
                    $('#subTaskHol').hide();
                    $('#e-subtasks-holder').html('');
                    $('#taskname').val(subtask.sub_task_name);
                    $('#request').val(subtask.request);
                    $('#users').attr('data-user-id', subtask.engineers);
                    @foreach ($employees as $employee)
                        if ('{{ $employee->id_employee }}' == subtask.engineers) {
                            $('#users').val('{{ $employee->last_name }} {{ $employee->first_name }}');
                        }
                    @endforeach
                    $('#s_date').val(subtask.start_date);
                    $('#e_date').val(subtask.end_date);
                    $('#modalViewTask').modal('show');
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        $('#edit-task-form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();
            data += '&id=' + {{ $id }};
            data += '&user_id=' + $('input[name="e_users"]').attr('data-user-id');
            $.ajax({
                type: 'post',
                url: '{{ route('task.update') }}',
                data: data,
                success: function(response) {
                    var tasks = response.tasks;
                    var subtasks = response.subtasks;
                    var newgroups = new vis.DataSet();
                    var newitems = new vis.DataSet();
                    for (var i = 0; i < tasks.length; i++) {
                        let newGroup = {
                            content: tasks[i].task_name,
                            id: 'task_' + tasks[i].task_id,
                            nestedGroups: subtasks.filter(function(subtask) {
                                return subtask.task_id == tasks[i].task_id;
                            }).map(function(subtask) {
                                return 'subtask_' + subtask.sub_task_id;
                            }),
                        };
                        if (newGroup.nestedGroups.length == 0) {
                            delete newGroup.nestedGroups;
                        }
                        newgroups.add(newGroup);
                        newitems.add({
                            start: new Date(tasks[i].start_date),
                            end: new Date(tasks[i].end_date),
                            group: 'task_' + tasks[i].task_id,
                            title: 'Start: ' + tasks[i].start_date + '<br>End: ' + tasks[i]
                                .end_date
                        });
                    }
                    for (var i = 0; i < subtasks.length; i++) {
                        newgroups.add({
                            content: subtasks[i].sub_task_name,
                            id: 'subtask_' + subtasks[i].sub_task_id,
                            className: 'vis-subTask',
                        });
                        newitems.add({
                            start: new Date(subtasks[i].start_date),
                            end: new Date(subtasks[i].end_date),
                            group: 'subtask_' + subtasks[i].sub_task_id,
                            title: 'Start: ' + subtasks[i].start_date + '<br>End: ' + subtasks[
                                i].end_date
                        });
                    }
                    newgroups.add({
                        content: 'Create',
                        id: 'create',
                    });
                    groups = newgroups;
                    timeline.setGroups(newgroups);
                    timeline.setItems(newitems);
                    $('#modalViewTask').modal('hide');
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });
        //khi nút delete được click thì sẽ gọi hàm delete qua link task.delete
        $('#dl_task_id').click(function(e) {
            e.preventDefault();
            //'{{ url('task') }}/task/'+task_id,
            var task_id = $(this).attr('data-task-id');
            $.ajax({
                type: 'post',
                url: '{{ url('task') }}/delete/' + task_id,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var tasks = response.tasks;
                    var subtasks = response.subtasks;
                    var newgroups = new vis.DataSet();
                    var newitems = new vis.DataSet();
                    for (var i = 0; i < tasks.length; i++) {
                        let newGroup = {
                            content: tasks[i].task_name,
                            id: 'task_' + tasks[i].task_id,
                            nestedGroups: subtasks.filter(function(subtask) {
                                return subtask.task_id == tasks[i].task_id;
                            }).map(function(subtask) {
                                return 'subtask_' + subtask.sub_task_id;
                            }),
                        };
                        if (newGroup.nestedGroups.length == 0) {
                            delete newGroup.nestedGroups;
                        }
                        newgroups.add(newGroup);
                        newitems.add({
                            start: new Date(tasks[i].start_date),
                            end: new Date(tasks[i].end_date),
                            group: 'task_' + tasks[i].task_id,
                            title: 'Start: ' + tasks[i].start_date + '<br>End: ' + tasks[i]
                                .end_date
                        });
                    }
                    for (var i = 0; i < subtasks.length; i++) {
                        newgroups.add({
                            content: subtasks[i].sub_task_name,
                            id: 'subtask_' + subtasks[i].sub_task_id,
                            className: 'vis-subTask',
                        });
                        newitems.add({
                            start: new Date(subtasks[i].start_date),
                            end: new Date(subtasks[i].end_date),
                            group: 'subtask_' + subtasks[i].sub_task_id,
                            title: 'Start: ' + subtasks[i].start_date + '<br>End: ' + subtasks[
                                i].end_date
                        });
                    }
                    newgroups.add({
                        content: 'Create',
                        id: 'create',
                    });
                    groups = newgroups;
                    timeline.setGroups(newgroups);
                    timeline.setItems(newitems);
                    $('#modalViewTask').modal('hide');
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

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
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.btn-group').on('click', '.btn', function() {
                // Xóa lớp 'active' khỏi tất cả các nút trong nhóm nút hiện tại
                $(this).siblings().removeClass('active');

                // Thêm lớp 'active' vào nút đã nhấp
                $(this).addClass('active');
            });
        });
    </script>
@endsection
