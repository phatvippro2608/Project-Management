@extends('auth.main')

@section('contents')
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
    <div class="from-group col-2">
        <input type="text" id="ProjectSearch" class="form-control" placeholder="Search project">
    </div>
    <div class="from-group" style="margin-top: 2%;">
        <div id="visualization"></div>
    </div>
</section>
<script type="text/javascript">
    //https://visjs.github.io/vis-timeline/docs/timeline/
    var container = document.getElementById('visualization');
    var groups = new vis.DataSet([{
            content: "Project 1",
            id: "p1",
        },
        {
            content: "Project 2",
            id: "p2",
        },
        {
            content: "Project 3",
            id: "p3",
        },
        {
            content: "Create",
            id: "asd",
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
        showCurrentTime: false,
        zoomMin: 604800000,
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
            var label = document.createElement("span");
            label.innerHTML = "";
            container.insertAdjacentElement("afterBegin", label);
            var button = document.createElement("button");
            if (group.content == "Create") {
                button.innerHTML = '<i class="bi bi-plus"></i> ' + group.content;
            } else {
                button.innerHTML = group.content;
            }
            button.classList.add("btn");
            button.addEventListener("click", function() {
                console.log(group.id);
            });
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
        //convert groups to array
        var group = groups.get();
        //filter groups
        var filteredGroups = group.filter(function(group) {
            if (group.content == "Create") {
                return true;
            }
            return group.content.toLowerCase().includes(searchQuery);
        });
        timeline.setGroups(new vis.DataSet(filteredGroups));
    });
</script>
@endsection