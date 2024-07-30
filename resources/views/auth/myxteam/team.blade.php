@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Team List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item active">Team List</li>
            </ol>
        </nav>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive mt-3">
                    <table class="table table-hover card-table table-vcenter text-nowrap datatable">
                        <thead class="table-primary">
                        <tr>
                            <th>Workspace ID</th>
                            <th>Workspace Name</th>
                            <th>Description</th>
                            <th>Owner ID</th>
                            <th>Create Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($teams as $team)
                            <tr onclick="window.location.href='{{ url("/myxteam/team/" . $team['WorkspaceId'] . "/projects") }}'" style="cursor: pointer;">
                                <td>{{ $team['WorkspaceId'] }}</td>
                                <td>{{ $team['WorkspaceName'] }}</td>
                                <td>{{ $team['Description'] }}</td>
                                <td>{{ $team['OwnerId'] }}</td>
                                <td>{{ $team['CreateDate'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        .table thead th {
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
        }
        .table-hover tbody tr {
            transition: all 0.3s ease;
            border-radius: 0.25rem;
        }
        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
            transform: scale(1.02);
        }
        .table-hover tbody tr td {
            vertical-align: middle;
        }
    </style>
@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const rows = document.querySelectorAll("table.table-hover tbody tr");
            rows.forEach(row => {
                row.addEventListener("click", function() {
                    window.location.href = `/myxteam/team/${this.querySelector('td:first-child').innerText}/project`;
                });
            });
        });
    </script>
@endsection
