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
    <div id="visualization"></div>
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
            content: "create",
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
        orientation: 'top',
        showCurrentTime: false,
        zoomMin: 604800000,
        editable: {
            add: true,
            updateTime: true,
            updateGroup: true
        },
        //add custom thay đổi tên các group thành các button primary
        groupEditable: true,
        groupTemplate: function(group){
            return `<button class="btn btn-primary">${group.content}</button>`;
        },
    };
    var timeline = new vis.Timeline(container);
    timeline.setOptions(options);
    timeline.setGroups(groups);
    timeline.setItems(items);
</script>
@endsection