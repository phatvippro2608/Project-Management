@extends('auth.main')

@section('contents')
<style type="text/css">
#visualization {
    width: 100%;
    height: 100vh;
    /* border: 1px solid lightgray; */
}

/* .vis-selected {
                                        border-color: red !important;
                                        background-color: blue !important;
                                    } */
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
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Task name</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modalViewSubTask" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">SubTask name</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCreateTask" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">create Task</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
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
            @foreach ($subtasks as $subtask)
                {
                    content: '{{ $subtask->sub_task_name }}',
                    id: 'subtask_{{ $subtask->sub_task_id }}',
                    className: 'vis-subTask',
                },
            @endforeach
            {
                content: 'Create',
                id: 'create',
            },
            
        ]);
        var items = new vis.DataSet([
            @foreach ($tasks as $item)
                {
                    start: new Date('{{ $item->start_date }}'),
                    end: new Date('{{ $item->end_date }}'),
                    group: 'task_{{  $item->task_id  }}',
                },
            @endforeach
            @foreach ($subtasks as $item)
                {
                    start: new Date('{{ $item->start_date }}'),
                    end: new Date('{{ $item->end_date }}'),
                    group: 'subtask_{{  $item->sub_task_id  }}',
                },
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
                if (group.id == "create") {
                    button.setAttribute("data-bs-toggle", "modal");
                    button.setAttribute("data-bs-target", "#modalCreateTask");
                    button.innerHTML = '<i class="bi bi-plus"></i> ' + group.content;
                }
                if(group.id.split('_')[0] == 'task') {
                    button.setAttribute("data-bs-toggle", "modal");
                    button.setAttribute("data-bs-target", "#modalViewTask");
                    button.innerHTML = group.content;
                }
                if(group.id.split('_')[0] == 'subtask') {
                    button.setAttribute("data-bs-toggle", "modal");
                    button.setAttribute("data-bs-target", "#modalViewSubTask");
                    button.innerHTML = group.content;
                }
                button.innerHTML = group.content;
                button.classList.add("btn");
                container.insertAdjacentElement("beforeEnd", button);
                return container;
            },

        };
        var timeline = new vis.Timeline(container);
        timeline.setOptions(options);
        timeline.setGroups(groups);
        timeline.setItems(items);

        timeline.on('item:add', function(event, properties, senderId) {
            event.preventDefault();
        });

        //hàm search timeline
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
</script>
@endsection