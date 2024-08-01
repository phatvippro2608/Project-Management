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
<script>
    console.log('progress');
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
        times:{
            stepDuration: 'day',
            timeZoom: 19,
        },
        taskList: {
            columns: [
                {
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
                'calendar-row-text':{
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