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
    <h1>{{ __('messages.recognition') }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{action('App\Http\Controllers\DashboardController@getViewDashboard')}}">Home</a></li>
            <li class="breadcrumb-item active">{{ __('messages.recognition') }}</li>
        </ol>
    </nav>
</div>

<div class="mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecognitionModal">
        <i class="bi bi-plus-lg"></i>{{ __('messages.add') }}
    </button>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecognitionTypeModal">
        <i class="bi bi-plus-lg"></i>
        Add recognition type
    </button>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importRecognitionModal">
        <i class="bi bi-file-earmark-arrow-up"></i>
        {{ __('messages.import') }}
    </button>
    <button class="btn btn-secondary" id="toggleHiddenRows" onclick="toggleShow()">
        <i class="bi bi-eye-slash me-2"></i>
        Show/Hide Hidden Rows
    </button>
</div>

<div class="section recognition">
    <div class="card border rounded-4 p-2">
        <div class="card-header py-0">
            <div class="card-title my-3 p-0">{{ __('messages.recognition_list') }}</div>
        </div>
        <div class="card-body">
            <table id="recognitionTable" class="table table-hover table-borderless display">
                <thead class="table-light">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Employee Code</th>
                    <th scope="col">Employee Name</th>
                    <th scope="col">Recognition</th>
                    <th scope="col">Recognition Date</th>
                    <th class="text-center" scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($recognitions as $recognition)
                    <tr class="{{ $recognition->recognition_hidden ? 'hidden-row' : '' }}">
                        <th scope="col">{{ $recognition->recognition_id }}</th>
                        <th scope="col">{{ $recognition->employee_code }}</th>
                        <th scope="col">{{ $recognition->last_name }} {{ $recognition->first_name }}</th>
                        <th scope="col">{{ $recognition->recognition_type_name }}</th>
                        <th scope="col">{{ $recognition->recognition_date }}</th>
                        <td class="text-center">
                            <button data-recognition="{{ $recognition->recognition_id }}" class="btn text-primary fw-bold" onclick="editRecognitionModal(this)"><i class="bi bi-pencil-square"></i></button>
                        </td>
                    </tr>
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
                            <label>Recognition Date</label>
                            <input type="date" name="recognition_date" id="recognition_date" class="form-control">
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="recognition_description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="2" class="form-control"></textarea>
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

<div class="modal fade md1" id="addRecognitionTypeModal" tabindex="-1" aria-labelledby="addRecognitionTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProjectModalTypeLabel">Add new recognition type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form trong view -->
            <div class="modal-body">
                <form id="recognitionTypeForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label for="recognition_type_" class="form-label">Recognition type</label>
                            <input type="text" name="recognition_type_name" id="recognition_type_name" class="form-control" >
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="btnRecognitionTypeSubmit">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade md1" id="importRecognitionModal" tabindex="-1" aria-labelledby="importRecognitionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProjectModalTypeLabel">Import Recognition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form trong view -->
            <div class="modal-body">
                <form id="importRecognitionForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label for="recognition_types">Recognition Types</label>
                            <select class="form-select name1" name="recognition_type_id" id="recognition_type_id" required>
                                @foreach($recognition_types as $type)
                                    <option value='{{$type->recognition_type_id}}'>{{$type->recognition_type_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label>Recognition Date</label>
                            <input type="date" name="recognition_date" id="recognition_date" class="form-control">
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="recognition_types">Recognition Types</label>
                            <label for="recognition_types">Upload file excel</label>
                            <input accept=".xlsx" name="file-excel" id="file-excel" type="file" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="btnimportRecognitionSubmit">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editRecognitionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="editRecognitionForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Recognition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            {{--                                <label class="form-label">Disciplinary ID</label>--}}
                            <input type="text" name="recognition_id" id="edit_recognition_id" class="form-control" hidden>
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="edit_employee_id" class="form-label">Employee Name</label>
                            <select class="form-select" name="employee_id" id="edit_employee_id" required>
                                <option value="-1">No select</option>
                                @foreach($employees as $employee)
                                    <option value="{{$employee->employee_id}}">{{$employee->employee_code}}
                                        - {{$employee->first_name}} {{$employee->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="edit_recognition_type_id" class="form-label">Recognition Type</label>
                            <select class="form-select" name="recognition_type_id" id="edit_recognition_type_id" required>
                                @foreach($recognition_types as $type)
                                    <option value="{{$type->recognition_type_id}}">{{$type->recognition_type_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="edit_recognition_date" class="form-label">Recognition Date</label>
                            <input type="date" name="recognition_date" id="edit_recognition_date" class="form-control">
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="edit_recognition_hidden">Hidden</label>
                            <input type="checkbox" name="recognition_hidden" id="edit_recognition_hidden">
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea name="description" id="edit_description" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="btnEditRecognitionSubmit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@section('script')
<script>
    var table = $('#recognitionTable').DataTable({
        language: { search: "" },
        initComplete: function (settings, json) {
            $('.dt-search').addClass('input-group');
            $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
        },
        responsive: true,
        dom: '<"d-flex justify-content-between align-items-center mt-2 mb-2"<"mr-auto"l><"d-flex justify-content-center mt-2 mb-2"B><"ml-auto mt-2 mb-2"f>>rtip',
        buttons: [{
            extend: 'csv',
            text: '<i class="bi bi-filetype-csv me-2"></i>CSV',
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
            text: '<i class="bi bi-file-earmark-spreadsheet me-2"></i>Excel',
            className: 'btn btn-success',
            exportOptions: {
                columns: ':not(:last-child)'
            },
        },
        {
            extend: 'pdf',
            text: '<i class="bi bi-filetype-pdf me-2"></i>PDF',
            className: 'btn btn-danger',
            exportOptions: {
                columns: ':not(:last-child)'
            }
        },
        {
            extend: 'print',
            text: '<i class="bi bi-printer me-2"></i>Print',
            className: 'btn btn-secondary',
            exportOptions: {
                columns: ':not(:last-child)'
            }
        }
        ],
        lengthMenu: [10, 25, 50, 100, -1],
        pageLength: 10
    });

    function editRecognitionModal(button) {
        var recognition_id = button.getAttribute('data-recognition');
        // Dùng Ajax để lấy dữ liệu từ máy chủ và điền vào form chỉnh sửa
        fetch(`/recognition/${recognition_id}`)
            .then(response => response.json())
            .then(data => {
                if (Array.isArray(data) && data.length > 0) {
                    let recognition = data[0];
                    document.getElementById('edit_recognition_id').value = recognition.recognition_id || '';
                    document.getElementById('edit_employee_id').value = recognition.employee_id || '';
                    document.getElementById('edit_recognition_type_id').value = recognition.recognition_type_id || '';
                    document.getElementById('edit_recognition_date').value = recognition.recognition_date ? new Date(recognition.recognition_date).toISOString().split('T')[0] : '';
                    document.getElementById('edit_recognition_hidden').checked = recognition.recognition_hidden == 1;
                    document.getElementById('edit_description').value = recognition.description || '';

                    var editModal = new bootstrap.Modal(document.getElementById('editRecognitionModal'));
                    editModal.show();
                } else {
                    toastr.error('Dữ liệu không hợp lệ.', "Thao tác thất bại");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Có lỗi xảy ra khi tải dữ liệu.', "Thao tác thất bại");
            });
    }

    document.getElementById('btnRecognitionSubmit').addEventListener('click', function (event) {
        let form = document.getElementById('recognitionForm');
        let formData = new FormData(form);

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
                console.log(data);
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

    document.getElementById('btnRecognitionTypeSubmit').addEventListener('click', function (event) {
        let form = document.getElementById('recognitionTypeForm');
        let formData = new FormData(form);

        fetch('{{ route('recognition.addType') }}', {
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
                    // console.log(data);
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

    document.getElementById('btnimportRecognitionSubmit').addEventListener('click', function (event) {
        let form = document.getElementById('importRecognitionForm');
        let recognition_date = form.querySelector('#recognition_date').value;
        const fileInput = form.querySelector('#file-excel');

        // Kiểm tra nếu người dùng không nhập ngày bắt đầu hoặc ngày kết thúc
        if (!recognition_date) {
            event.preventDefault(); // Ngăn chặn việc gửi form
            toastr.error('Vui lòng vui lòng chọn ngày.', "Lỗi nhập liệu");
            return;
        }

        // Kiểm tra nếu file input không tồn tại hoặc không có file nào được chọn
        if (!fileInput || !fileInput.files.length) {
            event.preventDefault(); // Ngăn chặn việc gửi form
            toastr.error('Vui lòng chọn tệp.', "Lỗi nhập liệu");
            return;
        }

        let formData = new FormData(form);

        fetch('{{ route('recognition.import') }}', {
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
                    console.log(data);
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

    document.getElementById('btnEditRecognitionSubmit').addEventListener('click', function (event) {
        let form = document.getElementById('editRecognitionForm');
        let formData = new FormData(form);

        // Thêm giá trị 0 nếu checkbox không được chọn
        if (!formData.has('recognition_hidden')) {
            formData.append('recognition_hidden', 0);
        } else {
            formData.set('recognition_hidden', 1);
        }

        fetch('{{ route('recognition.update') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.status === 200) {
                    toastr.success(data.message, "Lưu thành công");
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                } else {
                    console.log(data);
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

    document.getElementById('toggleHiddenRows').addEventListener('click', function() {
        var tableRows = document.querySelectorAll('#recognitionTable tbody tr');
        tableRows.forEach(function(row) {
            if (row.classList.contains('hidden-row')) {
                row.classList.toggle('d-none');
            }
        });
    });
    function toggleShow(){
        const toggleHiddenRows = document.querySelector('#toggleHiddenRows i')
        if(toggleHiddenRows.classList.contains('bi-eye-slash')){
            toggleHiddenRows.classList.remove('bi-eye-slash')
            toggleHiddenRows.classList.add('bi-eye')
        }else{
            toggleHiddenRows.classList.remove('bi-eye')
            toggleHiddenRows.classList.add('bi-eye-slash')
        }
    }
</script>
@endsection
