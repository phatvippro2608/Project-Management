@extends('auth.main')

@section('contents')
<style type="text/css">
    #visualization {
        width: 100%;
        height: 100vh;
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
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }
    .dropdown-item {
        padding: 12px 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
    }
    .dropdown-item img {
        margin-right: 10px;
    }
    .dropdown-item:hover {
        background-color: #f1f1f1;
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
<section class="section employees">
    <div class="row justify-content-between">
        <div class="from-group col-xs-6 col-sm-6 col-md-5 col-lg-2">
            <input type="text" id="TaskSearch" class="form-control" placeholder="Search Task">
        </div>
        <div class="from-group col-3 float-end d-inline-flex">
            <button id="btnW" type="button" style="margin: 0 1%" class="btn btn-secondary">Week</button>
            <button id="btnM" type="button" style="margin: 0 1%" class="btn btn-secondary">Month</button>
            <button id="btnY" type="button" style="margin: 0 1%" class="btn btn-secondary">Year</button>
        </div>
    </div>

    <div class="from-group" style="margin-top: 2%;">
        <div id="visualization"></div>
    </div>

    <div class="modal fade" id="modalViewTask" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="edit-task-form">
                    @csrf
                    <div class="modal-header">
                        <input type="text" id="task_id" name="task_id" hidden required>
                        <input type="text" class="form-control modal-title fs-5 no-background" id="taskname" name="taskname" required>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                <input class="form-control assign-user" type="text" id="users" name="users" autocomplete="off" placeholder="Search..." onfocus="showDropdown(this)">
                                <div class="dropdown-content">
                                    <div class="dropdown-item" data-value="Edge">
                                        <img src="path/to/edge.png" alt="Edge" width="20" height="20"> Edge
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="margin: 2% 0">
                                <label><strong><i class="bi bi-calendar"></i> Start date</strong></label>
                                <input class="form-control" type="date" id="s_date" name="s_date">
                            </div>
                            <div class="form-group" style="margin: 2% 0">
                                <label><strong><i class="bi bi-calendar"></i> End date</strong></label>
                                <input class="form-control" type="date" id="e_date" name="e_date">
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
                        <input type="text" class="form-control modal-title fs-5 no-background" name="taskname" placeholder="[Task Name]" required>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row">
                        <div class="form-group col-6">
                            <div class="form-group">
                                <div class="form-group"><strong><i class="bi bi-diagram-2"></i> Add a sub-task</strong></div>
                                <div id="c-subtasks-holder">
                                </div>
                                <a class="btn" onclick="c_addSubTask()"><i class="bi bi-plus"></i>Add</a>
                            </div>
                            <div class="form-group" style="margin: 2% 0;">
                                <label class="form-group"><strong><i class="bi bi-body-text"></i> Request</strong></label>
                                <textarea class="form-control" placeholder="Enter your request" style="resize: none;" name="request"></textarea>
                            </div>
                        </div>
                        <div class="form-group col-6" style="border-left: 1px solid;">
                            <div class="form-group">
                                <label for="users"><strong><i class="bi bi-person-circle"></i> Assigned</strong></label>
                                <input class="form-control" type="text" name="users" autocomplete="off" placeholder="Search..." onfocus="showDropdown(this)">
                                <div class="dropdown-content">
                                    <div class="dropdown-item" data-value="Edge">
                                        <img src="path/to/edge.png" alt="Edge" width="20" height="20"> Edge
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="margin: 2% 0">
                                <label for="s_date"><strong><i class="bi bi-calendar"></i> Start date</strong></label>
                                <input class="form-control" type="date" name="s_date">
                            </div>
                            <div class="form-group" style="margin: 2% 0">
                                <label for="e_date"><strong><i class="bi bi-calendar"></i> End date</strong></label>
                                <input class="form-control" type="date" name="e_date">
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
</section>
<script type="text/javascript">
    //https://visjs.github.io/vis-timeline/docs/timeline/
    var container = document.getElementById('visualization');

    var groups = new vis.DataSet([
        @foreach ($tasks as $task)
            @php
                $taskSubtasks = $subtasks->filter(function($subtask) use ($task) {
                    return $subtask->task_id == $task->task_id;
                });
            @endphp
            {
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
        @foreach($subtasks as $subtask) {
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
        @foreach($tasks as $item)
            @if($item->start_date && $item->end_date)
                {
                    start: new Date('{{ $item->start_date }}'),
                    end: new Date('{{ $item->end_date }}'),
                    group: 'task_{{  $item->task_id  }}',
                },
            @endif
        @endforeach
        @foreach($subtasks as $item)
            @if($item->start_date && $item->end_date)
                {
                    start: new Date('{{ $item->start_date }}'),
                    end: new Date('{{ $item->end_date }}'),
                    group: 'subtask_{{  $item->sub_task_id  }}',
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
            updateTime: true,
            // updateGroup: true,
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
                button.innerHTML = '<i class="bi bi-plus"></i> ' + group.content;
            }
            button.classList.add("btn");
            container.insertAdjacentElement("beforeEnd", button);
            return container;
        },
        // onMove: function(e) {
        //     var csrfToken = document.getElementById('csrf-token')?.value;
        //     if (!csrfToken) {
        //         console.error('CSRF token is missing.');
        //         return;
        //     }
        //     var itemId = e.group;
        //     if (!itemId) {
        //         console.error('Item ID is missing.');
        //         return;
        //     }
        //     var startDate = e.start?.toISOString().slice(0, 10);
        //     var endDate = e.end?.toISOString().slice(0, 10);

        //     var data = {
        //         item_id: itemId,
        //         start_date: startDate,
        //         end_date: endDate,
        //     };
        //     console.log(data);
        //     fetch('/update-item', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-CSRF-TOKEN': csrfToken,
        //         },
        //         body: JSON.stringify(data),
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.success) {
        //             console.log(data.message);
        //         } else {
        //             console.log(data.message);
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Error:', error);
        //     });
        // },
    };
    var timeline = new vis.Timeline(container);
    timeline.setOptions(options);
    timeline.setGroups(groups);
    timeline.setItems(items);

    document.getElementById('TaskSearch').addEventListener('input', function(e) {
        var searchQuery = e.target.value.toLowerCase();
        var group = groups.get();
        var filteredGroups = group.filter(function(group) {
            if (group.content == "Create") {
                return true;
            }
            return group.content.toLowerCase().includes(searchQuery);
        });
        timeline.setGroups(new vis.DataSet(filteredGroups));
    });

    document.getElementById('btnW').addEventListener('click', function() {
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
        var input = e;
        var dropdown = input.nextElementSibling;
        var items = dropdown.getElementsByClassName('dropdown-item');

        input.addEventListener('focus', function() {
            dropdown.style.display = 'block';
        });

        input.addEventListener('input', function() {
            var filter = input.value.toLowerCase();
            for (var i = 0; i < items.length; i++) {
                var text = items[i].textContent || items[i].innerText;
                if (text.toLowerCase().indexOf(filter) > -1) {
                    items[i].style.display = '';
                } else {
                    items[i].style.display = 'none';
                }
            }
        });

        for (var i = 0; i < items.length; i++) {
            items[i].addEventListener('click', function() {
                input.value = this.getAttribute('data-value');
                dropdown.style.display = 'none';
            });
        }

        document.addEventListener('click', function(event) {
            if (!input.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    }


    var c_subtaskCount = 0;
    function c_addSubTask(){
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
    function e_addSubTask(){
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

    function removeSubTask(e){
        e.parentElement.parentElement.remove();
    }

    $('#create-task-form').submit(function(e){
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();
        $.ajax({
            type: 'post',
            url: '{{ route('task.create') }}',
            data: data,
            success: function(response){
                console.log(response);
                var tasks = response.tasks;
                var subtasks = response.subtasks;
                var newgroups=new vis.DataSet();
                var newitems=new vis.DataSet();
                for (var i = 0; i < tasks.length; i++) {
                    let newGroup = {
                        content: tasks[i].task_name,
                        id: 'task_' + tasks[i].task_id,
                        nestedGroups: subtasks.filter(function(subtask){
                            return subtask.task_id == tasks[i].task_id;
                        }).map(function(subtask){
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
                    });
                }
                newgroups.add({
                    content: 'Create',
                    id: 'create',
                });
                timeline.setGroups(newgroups);
                timeline.setItems(newitems);
                c_subtaskCount = 0;
                $('#modalCreateTask').modal('hide');
            },
            error: function(response){
                console.log(response);
            }
        });
    });

    function getTask(e){
        var task_id = e.getAttribute('data-task-id');
        $.ajax({
            type: 'get',
            url: '{{ url('task') }}/task/'+task_id,
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response){
                var task = response.task;
                var subtasks = response.subtasks;

                var task_id = task.task_id;
                var subtask_id = subtasks.map(function(subtask){
                    return subtask.sub_task_id;
                });
                $('#subTaskHol').show();
                $('#task_id').val("task_"+task_id);
                $('#taskname').val(task.task_name);
                $('#request').val(task.request);
                $('#users').val(task.engineers);
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
                        </div>
                    `;
                    document.getElementById('e-subtasks-holder').appendChild(subtask);
                }
                $('#modalViewTask').modal('show');
            },
            error: function(response){
                console.log(response);
            }
        });
    }

    function getSubTask(e){
        var subtask_id = e.getAttribute('data-subtask-id');
        $.ajax({
            type: 'get',
            url: '{{ url('task') }}/subtask/'+subtask_id,
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response){
                var subtask = response.subtask;
                $('#task_id').val("subtask_"+subtask.sub_task_id);
                $('#subTaskHol').hide();
                $('#e-subtasks-holder').html('');
                $('#taskname').val(subtask.sub_task_name);
                $('#request').val(subtask.request);
                $('#users').val(subtask.engineers);
                $('#s_date').val(subtask.start_date);
                $('#e_date').val(subtask.end_date);
                $('#modalViewTask').modal('show');
            },
            error: function(response){
                console.log(response);
            }
        });
    }

    $('#edit-task-form').submit(function(e){
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();
        $.ajax({
            type: 'post',
            url: '{{ route('task.update') }}',
            data: data,
            success: function(response){
                var tasks = response.tasks;
                var subtasks = response.subtasks;
                var newgroups=new vis.DataSet();
                var newitems=new vis.DataSet();
                for (var i = 0; i < tasks.length; i++) {
                    let newGroup = {
                        content: tasks[i].task_name,
                        id: 'task_' + tasks[i].task_id,
                        nestedGroups: subtasks.filter(function(subtask){
                            return subtask.task_id == tasks[i].task_id;
                        }).map(function(subtask){
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
                    });
                }
                newgroups.add({
                    content: 'Create',
                    id: 'create',
                });
                timeline.setGroups(newgroups);
                timeline.setItems(newitems);
                $('#modalViewTask').modal('hide');
            },
            error: function(response){
                console.log(response);
            }
        });
    });
</script>
@endsection