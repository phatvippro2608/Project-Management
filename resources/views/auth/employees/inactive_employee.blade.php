@extends('auth.main')
@section('contents')
    <div class="pagetitle">
        <h1>Employees</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{action('App\Http\Controllers\EmployeesController@getView')}}">Employees List</a></li>
                <li class="breadcrumb-item active">Inactive List</li>
            </ol>
        </nav>
    </div>
        <div class="card  p-2 rounded-4 border">
            <div class="card-header py-0">
                <div class="card-title my-3 p-0">Inactive List</div>
            </div>
            <div class="card-body">
                <table id="employeesTable" class="table table-hover table-borderless">
                    <thead class="table-light">
                    <tr>
                        <th class="text-start">Employee Code</th>
                        <th class="text-center">Photo</th>
                        <th>Full Name</th>
                        <th>English Name</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="inactiveEmployeesTableBody">
                        @foreach($data as $index => $item)
                            <tr>
                                <td class="text-start"><a href="{{action('App\Http\Controllers\EmployeesController@getEmployee',$item->employee_id)}}">{{$item->employee_code ?? ''}}</a></td>
                                <td class="text-center"><img src="@if( $item->photo ) {{asset('') . $item->photo}} @else {{asset('assets/img/avt.png')}} @endif" alt="" width="75" height="75"></td>
                                <td>{{$item->last_name . ' ' . $item->first_name ?? ''}}</td>
                                <td>{{$item->en_name ?? ''}}</td>
                                <td>{{$item->gender == '0' ? 'Nam' : 'Nữ'}}</td>
                                <td>{{$item->phone ?? ''}}</td>
                                <td>
                                    <a href="{{action('App\Http\Controllers\EmployeesController@updateView',$item->employee_id)}}" class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none at3">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    </div>
@endsection
@section('script')
    <script>
        var table = $('#employeesTable').DataTable({
            language: { search: "" },
            initComplete: function (settings, json) {
                $('.dt-search').addClass('input-group');
                $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
            },
            responsive: true
        });
    </script>
@endsection
