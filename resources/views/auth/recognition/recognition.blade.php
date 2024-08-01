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

<div class="modal fade md1" id="addRecognitionModal" tabindex="-1" aria-labelledby="addRecognitionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProjectModalLabel">Add new recognition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form trong view -->
            <div class="modal-body">
                <form id="recognitionForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label for="employee_id" class="form-label">Employee Name</label>
                            <select class="form-select name1" name="employee_id" id="employee_id" required>
                                <option value="-1">No select</option>
                                @foreach($employees as $employee)
                                    <option value="{{$employee->employee_id}}">{{$employee->employee_code}}
                                        - {{$employee->first_name}} {{$employee->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="recognition_types">Recognition Types</label>
                            <select class="form-select name1" name="recognition_type_id" id="recognition_type_id" required>
                            @foreach($recognition_types as $type)
                                <option value='{{$type->recognition_type_id}}'>{{$type->recognition_type_name}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="recognition_description" class="form-label">Description</label>
                            <textarea name="recognition_description" id="recognition_description" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="btnRecognitionSubmit">Submit</button>
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

    document.getElementById('btnRecognitionSubmit').addEventListener('click', function (event) {
        let form = document.getElementById('recognitionForm');
        let formData = new FormData(form);

        console.log(formData);

        fetch('{{ route('recognition.add') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 200) {
                    toastr.success(data.message, "Lưu thành công");
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                } else {
                    let errorMessage = data.message;
                    if (data.error) {
                        errorMessage += ': ' + data.error;
                        console.error('Error:', data.error);  // Log lỗi cụ thể ra console
                    }
                    toastr.error(errorMessage, "Thao tác thất bại");
                }
            })
            .catch(error => {
                console.error('Error:', error);  // Log lỗi mạng hoặc lỗi khác ra console
                toastr.error('Có lỗi xảy ra. Vui lòng thử lại sau.', "Thao tác thất bại");
            });
    });
</script>
@endsection
