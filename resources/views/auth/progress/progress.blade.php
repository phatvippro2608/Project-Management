@extends('auth.main')
@section('head')
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs"></script>

<script src="https://unpkg.com/gantt-elastic/dist/GanttElastic.umd.js"></script>
<script src="https://unpkg.com/gantt-elastic-header/dist/Header.umd.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/gantt-schedule-timeline-calendar"></script> -->
@endsection
@section('contents')
<style>
    .gantt-elastic__chart-calendar-container{
        height: 50px !important;
    }
    .gantt-elastic__task-list-header{
        height: 50px !important;
    }
    .gantt-elastic__task-list-items{
        height: fit-content !important;
    }
    .gantt-elastic__main-container-wrapper{
        height: fit-content !important;
    }
    .gantt-elastic__main-container{
        height: fit-content !important;
    }
    .gantt-elastic__task-list-container{
        height: fit-content !important;
    }
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
    <div class="card rounded-4 p-2">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateTask"><i class="bi bi-plus-lg me-1"></i>Create</button>
                <div class="btn-group">
                    <button class="btn btn-primary" id="btnD">Day</button>
                    <button class="btn btn-primary" id="btnM">Month</button>
                    <button class="btn btn-primary" id="btnY">Year</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="timeline" class="mt-2" v-if="!destroy">
                <gantt-elastic :tasks="tasks" :options="options" :dynamic-style="dynamicStyle"></gantt-elastic>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalCreateTask" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form id="create-task-form" method="post">
                @csrf
                <div class="modal-header bg-white">
                    <div class="input-group">
                        <input name="allmarkdone" type="checkbox" class="btn-check" id="btn-check-c" autocomplete="off">
                        <label class="btn btn-outline-success me-2" for="btn-check-c"><i class="bi bi-check"></i></label>
                        <input type="text" class="form-control modal-title fs-5 no-background" name="taskname" placeholder="[Task Name]" required>
                    </div>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x" style="font-size: 3vh;"></i></button>
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
                                <div class="employee-item d-flex align-items-center" data-id="{{ $employee->employee_id }}" data-value="{{ $employee->last_name . ' ' . $employee->first_name }}">
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
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form id="edit-task-form">
                @csrf
                <div class="modal-header bg-white">
                    <input type="text" id="task_id" name="task_id" style="display:none;" required>
                    <div class="input-group">
                        <input name="allmarkdone" type="checkbox" class="btn-check" id="btn-check-e" autocomplete="off">
                        <label class="btn btn-outline-success me-2" for="btn-check-e"><i class="bi bi-check"></i></label>
                        <input type="text" class="form-control modal-title fs-5" id="taskname" name="taskname" required>
                    </div>
                    <div class="dropdown">
                        <button class="btn" type="button" id="dropdownMenuTask" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots" style="font-size: 3vh;"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuTask">
                            <li><a class="dropdown-item" id="dl_task_id" href="#" onclick="delTask(this)" >Delete</a></li>
                        </ul>
                    </div>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x" style="font-size: 3vh;"></i></button>
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
                                    <input id="empl_name" class="form-control empl_name" onkeyup="searchDropdown(this)" onclick="displayDropdown(this)" required>
                                    <input id="empl_id" class="form-control empl_id" style="width: 25%;" name="employee_id" onkeyup="searchDropdown(this)" onclick="displayDropdown(this)" required>
                                </div>
                            </div>
                            <div class="employees-dropdown fs-5">
                                @foreach($employees as $employee)
                                @php
                                $photoPath = asset($employee->photo);
                                $defaultPhoto = asset('assets/img/avt.png');
                                $photoExists = !empty($employee->photo) && file_exists(public_path($employee->photo));
                                @endphp
                                <div class="employee-item d-flex align-items-center" data-id="{{ $employee->employee_id }}" data-value="{{ $employee->last_name . ' ' . $employee->first_name}}">
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
        $.ajax({
            type: 'post',
            url: '{{ route('task.create') }}',
            data: data,
            success: function(response) {
                console.log(response);
                if(response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        $('#modalCreateTask').modal('hide');
                        location.reload();
                    }, 200);
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
        $.ajax({
            type: 'post',
            url: '{{ route('task.update') }}',
            data: data,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        $('#modalViewTask').modal('hide');
                        location.reload();
                    }, 200);
                }else {
                    toastr.error(response.message);
                }
            },
        });
    });
    function delTask(e) {
        var task_id = $(e).attr('data-task-id');
        $.ajax({
            type: 'post',
            url: '{{ url('task') }}/delete/' + task_id,
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            },
        });
    };

    function viewTask(id){
        $('#modalViewTask').modal('show');
        var task = tasks.find(task => task.id == id);
        $('#dl_task_id').attr('data-task-id', task.id);
        $('#task_id').val(task.id);
        $('#taskname').val(task.label);
        $('#request').val(task.req);
        $('#empl_name').val(task.empl_name);
        $('#empl_id').val(task.empl_id);
        $('#s_date').val(task.start);
        $('#e_date').val(task.end);
        $('#btn-check-e').prop('checked', task.progress == 100);
        //nếu task là subtask thì ẩn phần subtask
        if(task.parentId !== undefined){
            $('#subTaskHol').hide();
            $('#e-subtasks-holder').html('');
        }else{
            var subtasks = tasks.filter(subtask => subtask.parentId == id);
            $('#subTaskHol').show();
            $('#e-subtasks-holder').html('');
            for (var i = 0; i < subtasks.length; i++) {
                var subtask = document.createElement('div');
                subtask.classList.add('input-group');
                subtask.style.marginTop = '1%';
                subtask.style.marginBottom = '1%';
                subtask.innerHTML = `
                    <input name="markdone_${subtasks[i].id}" type="checkbox" class="btn-check" id="btn-check-${subtasks[i].id}" autocomplete="off" ${subtasks[i].progress == 100 ? 'checked' : ''}>
                    <label class="btn btn-outline-success me-2" for="btn-check-${subtasks[i].id}"><i class="bi bi-check"></i></label>
                    <input name="subtask_${subtasks[i].id}" type="text" class="form-control" value="${subtasks[i].label}">
                    <a class="btn btn-danger" onclick="removeSubTask(this)"><i class="bi bi-x"></i></a>
                `;
                document.getElementById('e-subtasks-holder').appendChild(subtask);
            }
        }
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
    });
    c_subtaskCount=0;
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
            <a class="btn btn-danger" onclick="removeSubTask(this)"><i class="bi bi-x"></i></a>
        `;
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
    var tasks = [
        @foreach($tasks as $data)
        @php
        $duration = 0;
        $sdate='';
        $edate='';
        if ($data->start_date && $data->end_date) {
            $sdate = $data->start_date;
            $edate = $data->end_date;
            $duration = strtotime($data->end_date) - strtotime($data->start_date) + 24 * 60 * 60;
        }
        $duration = $duration * 1000;
        @endphp
        {
            id: {{ $data->task_id }},
            label: '{{ $data->task_name }}',
            empl_id: '{{ $data->employee_id }}',
            empl_name: '{{ $data->last_name }} {{ $data->first_name }}',
            req: '{{ $data->request }}',
            start: '{{ $sdate }}',
            end: '{{ $edate }}',
            duration: {{ $duration }},
            @if($data->parent_id !== null)
                parentId: {{ $data->parent_id }},
            @endif
            durationDay: '{{ $duration / 1000 / 60 / 60 / 24 }} days',
            progress: {{ round($data->progress ?? 0) }},
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
                            // alert('description clicked!\n' + data.id);
                            viewTask(data.id);
                        },
                    },
                },
                {
                    id: 2,
                    label: 'Start',
                    value: (task) => {
                        if (task.start && dayjs(task.start).isValid()) {
                            return dayjs(task.start).format('YYYY-MM-DD');
                        } else {
                            return '';
                        }
                    },
                    width: 120,
                },
                {
                    id: 3,
                    label: 'End',
                    value: (task) => {
                        if (task.end && dayjs(task.end).isValid()) {
                            return dayjs(task.end).format('YYYY-MM-DD');
                        } else {
                            return '';
                        }
                    },
                    width: 120,
                },
                {
                    id: 4,
                    label: 'Duration',
                    value: (task) => {
                        if (task.durationDay === '0 days') {
                            return '';
                        } else if (task.durationDay === '1 days') {
                            return '1 day';
                        } else {
                            return task.durationDay;
                        }
                    },
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
    var app = new Vue({
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
    function addTask() {
        const task = {
            id: 88,
            label:
            '<a href="https://images.pexels.com/photos/423364/pexels-photo-423364.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" target="_blank" style="color:#0077c0;">Yeaahh! you have added a task bro!</a>',
            user:
            '<a href="https://images.pexels.com/photos/423364/pexels-photo-423364.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" target="_blank" style="color:#0077c0;">Awesome!</a>',
            parentId: 1,
            start: '2021-09-01',
            end: '2021-09-10',
            duration: 1 * 24 * 60 * 60 * 1000,
            percent: 50,
            type: 'project'
        };
    app.tasks.push(task);
    }


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
