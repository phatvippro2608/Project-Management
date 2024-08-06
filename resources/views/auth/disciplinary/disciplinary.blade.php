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
    <style>
        #disciplinaryTable th {
            text-align: center !important;
        }

        #disciplinaryTable td:nth-child(3),
        #disciplinaryTable td:nth-child(4) {
            text-align: left !important;
        }
    </style>

    <div class="pagetitle">
        <h1>Disciplinary</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{action('App\Http\Controllers\DashboardController@getViewDashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Disciplinary</li>
            </ol>
        </nav>
    </div>

    <div class="section disciplinary">
        <div class="card border rounded-4 p-2">
            <div class="card-header">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDisciplinaryModal">
                    <i class="bi bi-plus-lg"></i>
                    Add
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDisciplinaryTypeModal">
                    <i class="bi bi-plus-lg"></i>
                    Add disciplinary type
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importDisciplinaryModal">
                    <i class="bi bi-file-earmark-arrow-up"></i>
                    Import disciplinary
                </button>
                <button class="btn btn-secondary" id="toggleHiddenRows" onclick="toggleShow()">
                    <i class="bi bi-eye-slash me-2"></i>
                    Show/Hide Hidden Rows
                </button>
            </div>
            <div class="card-body">
                <table id="disciplinaryTable" class="table table-hover table-borderless display">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Employee Code</th>
                        <th scope="col">Employee Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Date</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($disciplinaries as $disciplinary)
                        <tr class="{{ $disciplinary->disciplinary_hidden ? 'hidden-row' : '' }}">
                            <th scope="col">{{ $disciplinary->disciplinary_id }}</th>
                            <th scope="col">{{ $disciplinary->employee_code }}</th>
                            <th scope="col" style="text-align: left !important;">{{ $disciplinary->last_name }} {{ $disciplinary->first_name }}</th>
                            <th scope="col" style="text-align: left !important;">{{ $disciplinary->disciplinary_type_name }}</th>
                            <th scope="col">{{ $disciplinary->disciplinary_date }}</th>

                            <td align="center">
                                <button data-disciplinary="{{ $disciplinary->disciplinary_id }}" class="btn btn-primary text-white" onclick="editDisciplinary(this)"><i class="bi bi-pencil-square"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade md1" id="addDisciplinaryModal" tabindex="-1" aria-labelledby="addDisciplinaryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProjectModalLabel">Add new disciplinary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Form trong view -->
                <div class="modal-body">
                    <form id="disciplinaryForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label for="select_add_employee_id" class="form-label">Employee Name</label>
                                <select class="form-select name1" name="employee_id" id="select_add_employee_id" required>
                                    <option value="-1">No select</option>
                                    @foreach($employees as $employee)
                                        <option value="{{$employee->employee_id}}">{{$employee->employee_code}}
                                            - {{$employee->first_name}} {{$employee->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12" style="margin-top: 1rem">
                                <label for="select_add_disciplinary_type_id">Disciplinary Types</label>
                                <select class="form-select name1" name="disciplinary_type_id" id="select_add_disciplinary_type_id" required>
                                    <option value="-1">No select</option>
                                    @foreach($disciplinary_types as $type)
                                        <option value='{{$type->disciplinary_type_id}}'>{{$type->disciplinary_type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12" style="margin-top: 1rem">
                                <label for="input_add_disciplinary_date">Disciplinary Date</label>
                                <input type="date" name="disciplinary_date" id="input_add_disciplinary_date" class="form-control">
                            </div>
                            <div class="col-md-12" style="margin-top: 1rem">
                                <label for="textarea_add_description" class="form-label">Description</label>
                                <textarea name="description" id="textarea_add_description" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="btnDisciplinarySubmit">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade md1" id="addDisciplinaryTypeModal" tabindex="-1" aria-labelledby="addDisciplinaryTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDisciplinaryModalTypeLabel">Add new disciplinary type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Form trong view -->
                <div class="modal-body">
                    <form id="disciplinaryTypeForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label for="disciplinary_type_" class="form-label">Disciplinary type</label>
                                <input type="text" name="disciplinary_type_name" id="disciplinary_type_name" class="form-control" >
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="btnDisciplinaryTypeSubmit">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade md1" id="importDisciplinaryModal" tabindex="-1" aria-labelledby="importDisciplinaryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProjectModalTypeLabel">Import Disciplinary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Form trong view -->
                <div class="modal-body">
                    <form id="importDisciplinaryForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label for="disciplinary_types">Disciplinary Types</label>
                                <select class="form-select name1" name="disciplinary_type_id" id="disciplinary_type_id" required>
                                    @foreach($disciplinary_types as $type)
                                        <option value='{{$type->disciplinary_type_id}}'>{{$type->disciplinary_type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12" style="margin-top: 1rem">
                                <label>Disciplinary Date</label>
                                <input type="date" name="disciplinary_date" id="disciplinary_date" class="form-control">
                            </div>
                            <div class="col-md-12" style="margin-top: 1rem">
                                <label for="disciplinary_types">Disciplinary Types</label>
                                <label for="disciplinary_types">Upload file excel</label>
                                <input accept=".xlsx" name="file-excel" id="file-excel" type="file" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="btnimportDisciplinarySubmit">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDisciplinaryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="editDisciplinaryForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Disciplinary</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                {{--                                <label class="form-label">Disciplinary ID</label>--}}
                                <input type="text" name="disciplinary_id" id="edit_disciplinary_id" class="form-control" hidden>
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
                                <label for="edit_disciplinary_type_id" class="form-label">Disciplinary Type</label>
                                <select class="form-select" name="disciplinary_type_id" id="edit_disciplinary_type_id" required>
                                    @foreach($disciplinary_types as $type)
                                        <option value="{{$type->disciplinary_type_id}}">{{$type->disciplinary_type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12" style="margin-top: 1rem">
                                <label for="edit_disciplinary_date" class="form-label">Disciplinary Date</label>
                                <input type="date" name="disciplinary_date" id="edit_disciplinary_date" class="form-control">
                            </div>
                            <div class="col-md-12" style="margin-top: 1rem">
                                <label for="edit_disciplinary_hidden">Hidden</label>
                                <input type="checkbox" name="disciplinary_hidden" id="edit_disciplinary_hidden">
                            </div>
                            <div class="col-md-12" style="margin-top: 1rem">
                                <label for="edit_description" class="form-label">Description</label>
                                <textarea name="description" id="edit_description" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="btnEditDisciplinarySubmit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        var table = $('#disciplinaryTable').DataTable({
            responsive: true,
            language: { search: "" },
            initComplete: function (settings, json) {
                $('.dt-search').addClass('input-group');
                $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
            },
            dom: '<"d-flex justify-content-between align-items-center mt-2 mb-2"<"mr-auto"l><"d-flex justify-content-center mt-2 mb-2"B><"ml-auto mt-2 mb-2"f>>rtip',
            buttons: [
            {
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
            }],
            lengthMenu: [10, 25, 50, 100, -1],
            pageLength: 10
        });

        document.getElementById('btnDisciplinarySubmit').addEventListener('click', function (event) {
            const form = document.getElementById('disciplinaryForm');
            const select_add_employee_id = document.getElementById('select_add_employee_id').value;
            const select_add_disciplinary_type_id = document.getElementById('select_add_disciplinary_type_id').value;
            const input_add_disciplinary_date = document.getElementById('input_add_disciplinary_date').value;
            const textarea_add_description = document.getElementById('textarea_add_description').value;
            let formData = new FormData(form);

            // Hàm kiểm tra và thông báo lỗi
            function showError(message) {
                event.preventDefault(); // Ngăn chặn việc gửi form
                toastr.error(message, "Lỗi nhập liệu");
            }

            // Kiểm tra nếu người dùng không chọn employee
            if (!select_add_employee_id || select_add_employee_id == -1) {
                showError('Vui lòng chọn nhân viên.');
                return;
            }

            // Kiểm tra nếu người dùng không chọn loại kỷ luật
            if (!select_add_disciplinary_type_id || select_add_disciplinary_type_id == -1) {
                showError('Vui lòng chọn loại kỷ luật.');
                return;
            }

            // Kiểm tra chưa nhập date
            if (!input_add_disciplinary_date) {
                showError('Vui lòng chọn ngày.');
                return;
            }

            // Kiểm tra chưa nhập mô tả
            if (!textarea_add_description) {
                showError('Vui lòng nhập mô tả.');
                return;
            }

            fetch('{{ route('disciplinary.add') }}', {
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

        document.getElementById('btnDisciplinaryTypeSubmit').addEventListener('click', function (event) {
            let form = document.getElementById('disciplinaryTypeForm');
            let formData = new FormData(form);

            fetch('{{ route('disciplinary.addType') }}', {
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

        document.getElementById('btnimportDisciplinarySubmit').addEventListener('click', function (event) {
            let form = document.getElementById('importDisciplinaryForm');
            let formData = new FormData(form);
            let disciplinary_date = form.querySelector('#disciplinary_date').value;
            const fileInput = form.querySelector('#file-excel');

            // Kiểm tra nếu người dùng không nhập ngày bắt đầu hoặc ngày kết thúc
            if (!disciplinary_date) {
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

            fetch('{{ route('disciplinary.import') }}', {
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

        function editDisciplinary(button) {
            var disciplinary_id = button.getAttribute('data-disciplinary');
            // Dùng Ajax để lấy dữ liệu từ máy chủ và điền vào form chỉnh sửa
            fetch(`/disciplinary/${disciplinary_id}`)
                .then(response => response.json())
                .then(data => {
                    if (Array.isArray(data) && data.length > 0) {
                        let disciplinary = data[0];
                        document.getElementById('edit_disciplinary_id').value = disciplinary.disciplinary_id || '';
                        document.getElementById('edit_employee_id').value = disciplinary.employee_id || '';
                        document.getElementById('edit_disciplinary_type_id').value = disciplinary.disciplinary_type_id || '';
                        document.getElementById('edit_disciplinary_date').value = disciplinary.disciplinary_date ? new Date(disciplinary.disciplinary_date).toISOString().split('T')[0] : '';
                        document.getElementById('edit_disciplinary_hidden').checked = disciplinary.disciplinary_hidden == 1;
                        document.getElementById('edit_description').value = disciplinary.description || '';

                        var editModal = new bootstrap.Modal(document.getElementById('editDisciplinaryModal'));
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

        document.getElementById('btnEditDisciplinarySubmit').addEventListener('click', function (event) {
            let form = document.getElementById('editDisciplinaryForm');
            let formData = new FormData(form);

            // Thêm giá trị 0 nếu checkbox không được chọn
            if (!formData.has('disciplinary_hidden')) {
                formData.append('disciplinary_hidden', 0);
            } else {
                formData.set('disciplinary_hidden', 1);
            }

            fetch('{{ route('disciplinary.update') }}', {
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
            var tableRows = document.querySelectorAll('#disciplinaryTable tbody tr');
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
