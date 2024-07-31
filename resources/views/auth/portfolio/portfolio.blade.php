@extends('auth.main')

@section('head')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection

@section('contents')
    <div class="pagetitle">
        <h1>Portfolio</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Portfolio</li>
            </ol>
        </nav>
    </div>
    <div class="content col-11 m-auto">
        <table id="table" class="table table-hover">
            <thead>
                <tr>
                    <th data-field="employee_code">Employee Code</th>
                    <th data-field="photo">Photo</th>
                    <th data-field="full_name">Full Name</th>
                    <th data-field="en_name">English Name</th>
                    <th data-field="completed_projects">Completed Projects</th>
                    <th data-field="issues">Issues</th>
                    <th data-field="compliments">Compliments</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sql as $item)
                    <tr>
                        <td>{{ $item->employee_code }}</td>
                        <td>
                            <img src="{{ asset($item->photo) }}" style="width: 50px; height: 50px; border-radius: 50%"
                                alt="">
                        </td>
                        <td>{{ $item->last_name . ' ' . $item->first_name }}</td>
                        <td>{{ $item->en_name }}</td>
                        <td>5</td>
                        <td>3</td>
                        <td>2</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                "paging": true, 
                "searching": true,
                "info": true,
                "autoWidth": true
            });
        });
    </script>
@endsection
