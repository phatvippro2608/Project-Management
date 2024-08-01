@extends('auth.main')
@section('head')
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs"></script>

<script src="https://unpkg.com/gantt-elastic/dist/GanttElastic.umd.js"></script>
<script src="https://unpkg.com/gantt-elastic-header/dist/Header.umd.js"></script>
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
            <button class="btn btn-primary" id="test">test</button>
        </div>
        <div class="card-body">
            <div style="width:100%;height:100%;">
                <div id="timeline" v-if="!destroy">
                    <gantt-elastic :tasks="tasks" :options="options" :dynamic-style="dynamicStyle">
                    </gantt-elastic>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function getDate(hours) {
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const currentMonth = currentDate.getMonth();
        const currentDay = currentDate.getDate();
        const timeStamp = new Date(currentYear, currentMonth, currentDay, 0, 0, 0).getTime();
        return new Date(timeStamp + hours * 60 * 60 * 1000).getTime();
    }

    let tasks = [{
            id: 1,
            label: 'Make some noise',
            user: '<a href="https://www.google.com/search?q=John+Doe" target="_blank" style="color:#0077c0;">John Doe</a>',
            start: Date(2024 - 01 - 01),
            duration: 15 * 24 * 60 * 60 * 1000,
            progress: 85,
            type: 'project',
            collapsed: false,
        },
        {
            id: 2,
            label: 'With great power comes great responsibility',
            user: '<a href="https://www.google.com/search?q=Peter+Parker" target="_blank" style="color:#0077c0;">Peter Parker</a>',
            parentId: 51,
            start: getDate(-24 * 4),
            duration: 4 * 24 * 60 * 60 * 1000,
            progress: 50,
            type: 'milestone',
            collapsed: true,
            style: {
                base: {
                    fill: '#0b5ed7',
                    stroke: '#0b5ed7',
                },
            },
        },
        {
            id: 5_1,
            label: 'Courage is being scared to death, but saddling up anyway.',
            user: '<a href="https://www.google.com/search?q=John+Wayne" target="_blank" style="color:#0077c0;">John Wayne</a>',
            start: getDate(-24 * 3),
            duration: 2 * 24 * 60 * 60 * 1000,
            progress: 100,
            type: 'task',
            dependentOn: [1]
        },
    ];

    let options = {
        maxRows: 100,
        maxHeight: 500,
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
                display: false,
            },
            text: {
                display: false,
            },
        },
        taskList: {
            expander: {
                straight: true,
            },
            columns: [{
                    id: 1,
                    label: 'ID',
                    value: 'id',
                    width: 40,
                },
                {
                    id: 2,
                    label: 'Description',
                    value: 'label',
                    width: 200,
                    expander: true,
                    html: true,
                    events: {
                        click({
                            data,
                            column
                        }) {
                            alert('description clicked!\n' + data.task_id);
                        },
                    },
                },
                {
                    id: 3,
                    label: 'Assigned to',
                    value: 'user',
                    width: 130,
                    html: true,
                },
                {
                    id: 3,
                    label: 'Start',
                    value: (task) => dayjs(task.start).format('YYYY-MM-DD'),
                    width: 78,
                },
                {
                    id: 4,
                    label: 'Type',
                    value: 'type',
                    width: 68,
                },
                {
                    id: 5,
                    label: '%',
                    value: 'progress',
                    width: 35,
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
        $('#test').click(function() {
            console.log(app.options);
            console.log(options);
            options.width = 100;
            app.options = options;
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
            //console.log('gantt view was updated');
        });
        ganttInstance.$on('destroyed', () => {
            //console.log('gantt was destroyed');
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