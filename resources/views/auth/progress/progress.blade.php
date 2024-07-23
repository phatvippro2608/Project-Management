@extends('auth.main')

@section('contents')
<style type="text/css">
    #visualization {
        width: 100%;
        height: 70vh;
        border: 1px solid lightgray;
    }

    /* .vis-selected {
            border-color: red !important;
            background-color: blue !important;
        } */
    .vis-subproject {
        background-color: var(--bg-light) !important;
        border: none;
        border-bottom: 1px solid lightgray;
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
            <input type="text" id="ProjectSearch" class="form-control" placeholder="Search project">
        </div>
        <div class="from-group col-xs-6 col-sm-6 col-md-5 col-lg-2 float-end">
            <button id="btnW" type="button" class="btn btn-secondary">Week</button>
            <button id="btnM" type="button" class="btn btn-secondary">Month</button>
            <button id="btnY" type="button" class="btn btn-secondary">Year</button>
        </div>
    </div>

    <div class="from-group" style="margin-top: 2%;">
        <div id="visualization"></div>
    </div>

    <div class="modal fade" id="modalViewProject" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">project name</h1>
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

    <div class="modal fade" id="modalCreateProject" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">create project</h1>
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
    var groups = new vis.DataSet([{
            content: "Project 1",
            id: "p1",
            nestedGroups: ["sub1"],
        },
        {
            content: "Project 2",
            id: "p2",
            nestedGroups: ["sub2"],
        },
        {
            content: "Project 3",
            id: "p3",
        },
        {
            content: "Create",
            id: "Create",
        },
        {
            content: "SubProject 1",
            id: "sub1",
            className: "vis-subproject",
        },
        {
            content: "SubProject 2",
            id: "sub2",
            className: "vis-subproject",
        }
    ]);
    var items = new vis.DataSet([{
            start: new Date(2024, 0, 10),
            end: new Date(2024, 0, 20),
            group: "p1",
        },
        {
            start: new Date(2024, 0, 28),
            end: new Date(2024, 2, 26),
            group: "p2",
        },
        {
            start: new Date(2024, 1, 22),
            end: new Date(2024, 4, 26),
            group: "p3",
        },
        {
            start: new Date(2024, 2, 22),
            end: new Date(2024, 5, 29),
            group: "sub2",
        },
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
            add: true,
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
            if (group.content == "Create") {
                button.setAttribute("data-bs-toggle", "modal");
                button.setAttribute("data-bs-target", "#modalCreateProject");
                button.innerHTML = '<i class="bi bi-plus"></i> ' + group.content;
            } else {
                button.setAttribute("data-bs-toggle", "modal");
                button.setAttribute("data-bs-target", "#modalViewProject");
                button.innerHTML = group.content;
            }
            button.classList.add("btn");
            container.insertAdjacentElement("beforeEnd", button);
            return container;
        },

    };
    var timeline = new vis.Timeline(container);
    timeline.setOptions(options);
    timeline.setGroups(groups);
    timeline.setItems(items);

    //hàm search timeline
    document.getElementById('ProjectSearch').addEventListener('input', function(e) {
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