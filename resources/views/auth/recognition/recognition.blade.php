@section('title', $title)

@extends('auth.main')

@section('head')
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
@endsection

@section('contents')
<div class="pagetitle">
    <h1>Recognitions</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{action('App\Http\Controllers\DashboardController@getViewDashboard')}}">Home</a></li>
            <li class="breadcrumb-item active">Recognitions</li>
        </ol>
    </nav>
</div>

<div class="section recognition">
    <div class="card">
        <div class="card-header">
            <a class="btn btn-info text-white"><i class="bi bi-plus"></i> Add Recognition</a>
            <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addRecognitionModal">Add</button>
            <a href="{{ action('App\Http\Controllers\AttendanceController@addAttendanceView') }}" class="btn btn-info text-white"><i class="bi bi-file-earmark-fill"></i> Recognition Report</a>
        </div>
        <div class="card-body">
            <table id="recognitionTable" class="display">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Employee Code</th>
                    <th scope="col">Employee Name</th>
                    <th scope="col">Recognition</th>
                    <th scope="col">Recognition Date</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($recognitions as $recognition)
                    <th scope="col">{{ $recognition->recognition_id }}</th>
                    <th scope="col">{{ $recognition->employee_code }}</th>
                    <th scope="col">{{ $recognition->last_name }} {{ $recognition->first_name }}</th>
                    <th scope="col">{{ $recognition->recognition_type_name }}</th>
                    <th scope="col">{{ $recognition->recognition_date }}</th>
                    <td>
                        <button data-attendance="{{ $recognition->recognition_id }}" class="btn btn-primary text-white" onclick="viewAttendanceByID(this)"><i class="bi bi-pencil-square"></i></button>
                        <button data-attendance="{{ $recognition->recognition_id }}" class="btn btn-danger text-white" onclick="deleteAttendanceByID(this)" ><i class="bi bi-trash"></i></button>
                    </td>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addRecognitionModal" tabindex="-1" aria-labelledby="addRecognitionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProjectModalLabel">Add new project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form trong view -->
            <div class="modal-body">
                <form id="projectForm">
                    @csrf
                    <div class="row">
                        <label for="employee_name" class="form-label">Employee Name</label>
                        <input type="text" class="form-control" id="employee_name" name="employee_name" required>
                        <label for="recognition_types">Recognition Types</label>
                        <select class="form-select" name="recognition_types" id="recognition_types" required>
                        @foreach($recognition_types as $type)
                            <option value='{{$type->recognition_type_id}}'>"{{$type->recognition_type_name}}"</option>
                        @endforeach
                        </select>

                        <label for="project_address" class="form-label">Project Address</label>
                        <textarea name="project_address" id="project_address" rows="2" class="form-control"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="btnSubmitProject">Submit</button>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script>
    var table = $('#recognitionTable').DataTable({
        responsive: true,
        dom: '<"d-flex justify-content-between align-items-center mt-2 mb-2"<"mr-auto"l><"d-flex justify-content-center mt-2 mb-2"B><"ml-auto mt-2 mb-2"f>>rtip',
        buttons: [{
            extend: 'csv',
            className: 'btn btn-primary',
            exportOptions: {
                columns: ':not(:last-child)'
            },
            customize: function (csv) {
                return "\uFEFF" + csv;
            }
        },
            {
                extend: 'excelHtml5',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: ':not(:last-child)'
                },
            },
            {
                extend: 'pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'print',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            }
        ],
        lengthMenu: [10, 25, 50, 100, -1],
        pageLength: 10
    });
    $('.dt-search').addClass('d-flex align-items-center');
    $('.dt-search label').addClass('d-flex align-items-center');
    $('.dt-search input').addClass('form-control ml-2');


</script>
@endsection
