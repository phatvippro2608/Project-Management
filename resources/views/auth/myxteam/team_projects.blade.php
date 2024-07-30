@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Project List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/myxteam/teams">Team List</a></li>
                <li class="breadcrumb-item active">Project List</li>
            </ol>
        </nav>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">Team list</h5>

                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable table-hover">
                        <thead>
                        <tr>
                            <th>Project ID</th>
                            <th>Project Name</th>
                            <th>Description</th>
                            <th>Create Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($projects as $project)
                            <tr onclick="window.location.href='{{ url("/myxteam/team/" . $WorkspaceId . "/project/".$project['ProjectId']."/tasks") }}'" style="cursor: pointer;">
                                <td>{{ $project['ProjectId'] }}</td>
                                <td>{{ $project['ProjectName'] }}</td>
                                <td>{{ $project['Description'] }}</td>
                                <td>{{ $project['CreateDate'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

    </script>
@endsection
