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
    .gantt-elastic__task-list-header {
        margin-bottom: 10px !important;
        height: 60px !important;
    }

    .gantt-elastic__chart-calendar-container {
        margin-bottom: 10px !important;
        height: 60px !important;
    }
    .gantt-elastic__main-container-wrapper{
        height: 200% !important;
    }
    .gantt-elastic__main-container{
        height: 200% !important;
    }
    .gantt-elastic__calendar-row-rect-child{
        height: 100% !important;
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
            <button class="btn btn-primary" id="btnD">Day</button>
            <button class="btn btn-primary" id="btnM">Month</button>
            <button class="btn btn-primary" id="btnY">Year</button>
        </div>
        <div class="card-body">
            <div style="width:100%;height:100%;">
                <div id="timeline" v-if="!destroy">
                    <gantt-elastic :tasks="tasks" :options="options" :dynamic-style="dynamicStyle">
                    <gantt-header slot="header"></gantt-header>
                    </gantt-elastic>
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
        maxHeight: 500,
        title: {
          label: 'Your project title as asdasdasd (link or whatever...)',
          html: true,
        },
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
            expander: {
                display: true,
                size: 1,
            },
            text: {
                display: false,
            },
        },
        taskList: {
            expander: {
                straight: true,
            },
            columns: [
                {
                    id: 1,
                    label: 'Task name',
                    value: 'label',
                    width: 200,
                    expander: true,
                    html: true,
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

    // create instance
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

    // gantt state which will be updated in realtime
    let ganttState, ganttInstance;

    $(document).ready(function() {
        $('#btnD').click(function() {
            options.width = 10000;
            app.options = options;
            app.options.width = 10000;    
        });
        $('#btnM').click(function() {
        });
        $('#btnY').click(function() {
        });
    });

    // listen to 'gantt-elastic.ready' or 'gantt-elastic.mounted' event
    // to get the gantt state for real time modification
    app.$on('gantt-elastic-ready', (ganttElasticInstance) => {
        ganttInstance = ganttElasticInstance;

        ganttInstance.$on('tasks-changed', (tasks) => {
            app.tasks = tasks;
        });
        ganttInstance.$on('options-changed', (options) => {
            app.options = options;
        });
        ganttInstance.$on('dynamic-style-changed', (style) => {
            app.dynamicStyle = style;
        });

        ganttInstance.$on('chart-task-mouseenter', ({
            data,
            event
        }) => {
            console.log('task mouse enter', {
                data,
                event
            });
        });
        ganttInstance.$on('updated', () => {
            console.log(app.options)
        });
        ganttInstance.$on('destroyed', () => {
            // console.log('gantt was destroyed');
        });
        ganttInstance.$on('times-timeZoom-updated', () => {
            console.log('time zoom changed');
        });
        ganttInstance.$on('taskList-task-click', ({
            event,
            data,
            column
        }) => {
            console.log('task list clicked! (task)', {
                data,
                column
            });
        });
    });

    // mount gantt to DOM
    app.$mount('#timeline');
</script>
@endsection