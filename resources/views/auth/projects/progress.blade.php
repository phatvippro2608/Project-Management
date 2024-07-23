@extends('auth.main')

@section('contents')
    <style>
        /* Định nghĩa các lớp CSS cho màu sắc của các phần tử */
        .vis-item {
            border-radius: 4px;
        }

        .vis-item.low {
            background-color: red;
        }

        .vis-item.medium {
            background-color: orange;
        }

        .vis-item.high {
            background-color: green;
        }

        .progress-percentage {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 50%;
            color: white;
            font-weight: bold;
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
        <div class="form-group col-2">
            <input type="text" id="ProjectSearch" class="form-control" placeholder="Search project">
        </div>
        <div class="form-group" style="margin-top: 2%;">
            <div id="visualization"></div>
        </div>
    </section>

    <!-- Include vis.js library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js"></script>
    <!-- Đã bao gồm trong mã của bạn -->

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            var container = document.getElementById('visualization');
            var groups = new vis.DataSet([{
                    id: "p1",
                    content: "Project 1"
                },
                {
                    id: "p2",
                    content: "Project 2"
                },
                {
                    id: "p3",
                    content: "Project 3"
                },
                {
                    id: "create",
                    content: "Create"
                },
                {
                    id: "s1",
                    content: "Subtask 1",
                    group: "p1"
                }, // Subtask of project 1
                {
                    id: "s2",
                    content: "Subtask 2",
                    group: "p2"
                },
            ]);
            var items = new vis.DataSet([{
                    start: new Date(2024, 0, 10),
                    end: new Date(2024, 0, 20),
                    group: "p1"
                },
                {
                    start: new Date(2024, 0, 28),
                    end: new Date(2024, 2, 26),
                    group: "p2"
                },
                {
                    start: new Date(2024, 1, 22),
                    end: new Date(2024, 4, 26),
                    group: "p3"
                },
            ]);

            // function getProgressClass(percentage) {
            //     if (percentage < 33) {
            //         return 'low';
            //     } else if (percentage < 66) {
            //         return 'medium';
            //     } else {
            //         return 'high';
            //     }
            // }

            // // Cập nhật lớp CSS cho các phần tử sau khi timeline được tạo
            // function updateItemClasses() {
            //     var itemsElements = document.querySelectorAll('.vis-item');
            //     itemsElements.forEach(function(element) {
            //         var itemId = element.getAttribute('data-id');
            //         var item = items.get(itemId);
            //         var percentage = parseInt(item.title);
            //         var progressClass = getProgressClass(percentage);
            //         element.classList.add(progressClass);
            //     });
            // }

            var options = {
                format: {
                    minorLabels: {
                        weekday: 'D',
                        day: 'D',
                        week: 'w',
                        month: 'MMM',
                        quarter: 'Q%q',
                        year: 'YYYY',
                    },
                    majorLabels: {
                        weekday: 'YYYY',
                        day: 'MMMM YYYY',
                        week: 'MMMM YYYY',
                        month: 'YYYY',
                        quarter: 'YYYY Q%q',
                        year: ''
                    }
                },
                orientation: 'top',
                showCurrentTime: false,
                // Ngày
                // zoomMin: 864000000,
                // zoomMax: 1296000000,
                // Tháng
                zoomMin: 12960000000,
                zoomMax: 38880000000,
                // Quý
                // zoomMin: 38880000000,
                // zoomMax: 116640000000, 
                editable: {
                    add: true,
                    updateTime: true,
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
                // stack: false,
                // subgroupStack: false,
                subgroupOrder: 'first-show',
            };

            var timeline = new vis.Timeline(container);
            timeline.setOptions(options);
            timeline.setGroups(groups);
            timeline.setItems(items);

            // Cập nhật lớp CSS sau khi timeline được hiển thị
            timeline.on('afterDraw', updateItemClasses);

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
        });
    </script>
@endsection
