@extends('auth.main')
@section('head')
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs"></script>

<script src="https://unpkg.com/gantt-elastic/dist/GanttElastic.umd.js"></script>
<script src="https://unpkg.com/gantt-elastic-header/dist/Header.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gantt-schedule-timeline-calendar"></script>
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
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Progress</li>
        </ol>
    </nav>
</div>
<div class="section employees">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="btn-group">
                    <button class="btn btn-primary" id="btnD">Day</button>
                    <button class="btn btn-primary" id="btnM">Month</button>
                    <button class="btn btn-primary" id="btnY">Year</button>
                </div>
                <!-- button mở model modalViewTask -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateTask">test 1</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalViewTask">test 2</button>
            </div>
        </div>
        <div class="card-body">
            <div style="width:100%;height:100%;">
                <div id="timeline" v-if="!destroy">
                    <gantt-elastic :tasks="tasks" :options="options" :dynamic-style="dynamicStyle"></gantt-elastic>
                </div>
            </div>
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
                    <button type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x" style="font-size: 3vh;"></i></button>
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
                                    <img src="{{ asset('assets/img/avt.png') }}" class="rounded-circle object-fit-cover me-1 empl_img" minwidth="25" width="25" minheight="25" height="25">
                                    <input class="form-control empl_name" onkeyup="searchDropdown(this)" onclick="displayDropdown(this)" required>
                                    <input class="form-control empl_id" style="width: 25%;" name="employee_id" onkeyup="searchDropdown(this)" onclick="displayDropdown(this)" required>
                                </div>
                            </div>
                            <div class="employees-dropdown fs-5">
                                @foreach($employees as $employee)
                                @php
                                $photoPath = asset($employee->photo);
                                $defaultPhoto = asset('assets/img/avt.png');
                                $photoExists = !empty($employee->photo) && file_exists(public_path($employee->photo));
                                @endphp
                                <div class="employee-item d-flex align-items-center" data-id="{{ $employee->employee_id }}" data-value="{{ $employee->first_name  . ' ' . $employee->last_name }}">
                                    <img src="{{ $photoExists ? $photoPath : $defaultPhoto }}" class="rounded-circle object-fit-cover" width="22" height="22">
                                    <div class="empl_val ms-1"></div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group" style="margin: 2% 0">
                            <label for="s_date"><strong><i class="bi bi-calendar"></i> Start
                                    date</strong></label>
                            <input class="form-control" type="date" onchange="checkDate('create-task-form')" name="s_date" required>
                        </div>
                        <div class="form-group" style="margin: 2% 0">
                            <label for="e_date"><strong><i class="bi bi-calendar"></i> End date</strong></label>
                            <input class="form-control" type="date" onchange="checkDate('create-task-form')" name="e_date" required>
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
<div class="modal fade" id="modalViewTask" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="edit-task-form">
                @csrf
                <div class="modal-header">
                    <input type="text" id="task_id" name="task_id" style="display:none;" required>
                    <input type="text" class="form-control modal-title fs-5" id="taskname" name="taskname" required>
                    <div class="dropdown">
                        <button class="btn text-white" type="button" id="dropdownMenuTask" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots" style="font-size: 3vh;"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuTask">
                            <li><a class="dropdown-item" id="dl_task_id" href="#">Delete</a></li>
                        </ul>
                    </div>
                    <button type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x" style="font-size: 3vh;"></i></button>
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
                            <div class="row form-group mt-3">
                                <div class="d-flex justify-content-between">
                                    <img src="{{ asset('assets/img/avt.png') }}" class="rounded-circle object-fit-cover me-1 empl_img" minwidth="25" width="25" minheight="25" height="25">
                                    <input class="form-control empl_name" onkeyup="searchDropdown(this)" onclick="displayDropdown(this)" required>
                                    <input class="form-control empl_id" style="width: 25%;" name="employee_id" onkeyup="searchDropdown(this)" onclick="displayDropdown(this)" required>
                                </div>
                            </div>
                            <div class="employees-dropdown fs-5">
                                @foreach($employees as $employee)
                                @php
                                $photoPath = asset($employee->photo);
                                $defaultPhoto = asset('assets/img/avt.png');
                                $photoExists = !empty($employee->photo) && file_exists(public_path($employee->photo));
                                @endphp
                                <div class="employee-item d-flex align-items-center" data-id="{{ $employee->employee_id }}" data-value="{{ $employee->first_name  . ' ' . $employee->last_name }}">
                                    <img src="{{ $photoExists ? $photoPath : $defaultPhoto }}" class="rounded-circle object-fit-cover" width="22" height="22">
                                    <div class="empl_val ms-1"></div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group" style="margin: 2% 0">
                            <label><strong><i class="bi bi-calendar"></i> Start date</strong></label>
                            <input class="form-control" type="date" id="s_date" onchange="checkDate('edit-task-form')" name="s_date" required>
                        </div>
                        <div class="form-group" style="margin: 2% 0">
                            <label><strong><i class="bi bi-calendar"></i> End date</strong></label>
                            <input class="form-control" type="date" id="e_date" onchange="checkDate('edit-task-form')" name="e_date" required>
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
<script>
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
                if(response.success) {
                    toastr.success(response.message);

                }else {
                    toastr.error(response.message);
                }
            },
        });
    });
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
                if(response.success) {
                    toastr.success(response.message);

                }else {
                    toastr.error(response.message);
                }
            },
        });
    });
    $('#dl_task_id').click(function(e) {
        e.preventDefault();
        var task_id = $(this).attr('data-task-id');
        $.ajax({
            type: 'post',
            url: '{{ url('task') }}/delete/' + task_id,
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);

                } else {
                    toastr.error(response.message);
                }
            },
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
                    if ('{{ $employee->employee_id }}' == task.engineers) {
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
</script>

<script>
    function displayDropdown(e) {
        $('.employees-dropdown').css('display', 'block');
        var position = $(e).position();
        $('.employees-dropdown').css('top', position.top + 40);
        $('.employees-dropdown').css('left', position.left);
        $('.employees-dropdown').css('width', $(e).width());
        if ($(e).attr('name') != 'employee_id') {
            $('.employee-item').each(function() {
                $(this).find('.empl_val').html($(this).attr('data-value'));
            });
        } else {
            $('.employee-item').each(function() {
                $(this).find('.empl_val').html($(this).attr('data-id'));
            });
        }
        var value = $(e).val().toLowerCase();
        $('.employee-item').filter(function() {
            if ($(e).attr('name') != 'employee_id') {
                if ($(this).attr('data-value').toLowerCase().indexOf(value) > -1) {
                    $(this).attr('style', 'display: flex !important');
                } else {
                    $(this).attr('style', 'display: none !important');
                }
            } else {
                if ($(this).attr('data-id').toLowerCase().indexOf(value) > -1) {
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
            if ($(e).attr('name') != 'employee_id') {
                if ($(this).attr('data-value').toLowerCase().indexOf(value) > -1) {
                    $(this).attr('style', 'display: flex !important');
                } else {
                    $(this).attr('style', 'display: none !important');
                }
            } else {
                if ($(this).attr('data-id').toLowerCase().indexOf(value) > -1) {
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
        $('.employees-dropdown').css('display', 'none');
        var photoPath = $(this).find('img').attr('src');
        $('.empl_img').attr('src', photoPath);
    });
    c_subtaskCount=0;
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

<script>
    let tasks = [
        @foreach($tasks as $data)
        @php
        $duration = strtotime($data->end_date) - strtotime($data->start_date) + 24 * 60 * 60;
        $duration = $duration * 1000;
        $photoPath = asset($data->photo);
        $defaultPhoto = asset('assets/img/avt.png');
        $photoExists = !empty($data->photo) && file_exists(public_path($data->photo));
        @endphp
        {
            id: {{ $data->task_id }},
            label: '{{ $data->task_name }}',
            user: '<img src="{{ $photoExists ? $photoPath : $defaultPhoto }}" class="rounded-circle object-fit-cover" width="20" height="20" alt="{{ $data->first_name }} {{ $data->last_name }}">',
            start: '{{ $data->start_date }}',
            duration: {{ $duration }},
            @if($data->parent_id !== null)
                parentId: {{ $data->parent_id }},
            @endif
            durationDay: '{{ $duration / 1000 / 60 / 60 / 24 }} days',
            progress: {{ $data->progress ?? 0 }},
            type: 'task',
            collapsed: false,
            style: {
                base: {
                    fill: '#0b5ed7',
                    stroke: '#0b5ed7',
                },
            },
        },
        @endforeach
    ];

    let options = {
        maxRows: 100,
        row: {
            height: 30,
        },
        calendar: {
            hour: {
                display: false,
            },
        },
        chart: {
            progress: {
                bar: false,
            },
            text: {
                display: false,
            },
        },
        times: {
            stepDuration: 'day',
            timeZoom: 19,
        },
        taskList: {
            columns: [{
                    id: 1,
                    label: 'Task name',
                    value: 'label',
                    width: 200,
                    expander: true,
                    events: {
                        click({
                            data,
                            column
                        }) {
                            alert('description clicked!\n' + data.id);
                        },
                    },
                },
                {
                    id: 2,
                    label: '',
                    value: 'user',
                    width: 35,
                    html: true,
                },
                {
                    id: 3,
                    label: 'Start',
                    value: (task) => dayjs(task.start).format('YYYY-MM-DD'),
                    width: 120,
                },
                {
                    id: 4,
                    label: 'Duration',
                    value: 'durationDay',
                    width: 100,
                },
                {
                    id: 5,
                    label: '%',
                    value: 'progress',
                    width: 45,
                },
            ],
        },
    };
    const app = new Vue({
        components: {
            'gantt-header': Header,
            'gantt-elastic': GanttElastic,
        },
        data: {
            tasks: tasks.map((task) => Object.assign({}, task)),
            options,
            dynamicStyle: {
                'task-list-header-label': {
                    'font-weight': 'bold',
                    'font-size': '18px',
                },
                'task-list-header-column': {
                    'height': '100%',
                    'margin-bottom': '20px',
                },
                'calendar-wrapper': {
                    'height': '100%',
                },
                'calendar': {
                    'height': '100%',
                },
                'task-list-item-value': {
                    'font-size': '16px',
                },
                'calendar-row-text': {
                    'font-size': '18px',
                }
            },
            destroy: false,
        },
    });


    let ganttState, ganttInstance;
    $(document).ready(function() {
        $('#btnD').click(function() {
            options.times.stepDuration = 'day';
            options.times.timeZoom = 19;
            app.options = options;
        });
        $('#btnM').click(function() {
            console.log(app.options);

            options.times.stepDuration = 'month';
            options.times.timeZoom = 23;
            console.log(options);
            app.options = options;
        });
        $('#btnY').click(function() {
            options.times.stepDuration = 'month';
            options.times.timeZoom = 25;
            app.options = options;
        });
    });

    app.$mount('#timeline');
</script>
@endsection