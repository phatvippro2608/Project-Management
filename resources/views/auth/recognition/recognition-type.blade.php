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
    /*#recognitionTypeTable td:nth-child(3),*/
    /*#recognitionTypeTable td:nth-child(4) {*/
    /*    text-align: left !important;*/
    /*}*/

    /*#recognitionTypeTable th {*/
    /*    text-align: center !important;*/
    /*}*/
</style>
<div class="pagetitle">
    <h1>Recognition Types</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{action('App\Http\Controllers\DashboardController@getViewDashboard')}}">Home</a></li>
            <li class="breadcrumb-item active">Recognition Types</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-4 col-md-12">
        <div class="card border rounded-4 p-2">
            <div class="card-header py-0">
                <div class="card-title my-3 p-0">Add Recognition</div>
            </div>
            <div class="card-body">
                <form id="recognitionTypeForm">
                    <label for="input_add_recognition_type" class="mt-3">Recognition name</label>
                    <input type="text" name="recognition_type_name" id="input_add_recognition_type" class="form-control">
                </form>
            </div>
            <div class="card-footer">
                <input type="submit" value="Save" class="btn btn-primary" id="btnRecognitionTypeSubmit">
{{--                <input type="submit" value="Cancel" class="btn btn-danger">--}}
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-12">
        <div class="card border rounded-4 p-2">
            <div class="card-header py-0">
                <div class="card-title my-3 p-0">Recognition Types</div>
                <button class="btn btn-secondary mb-2" id="toggleHiddenRows" onclick="toggleShow()">
                    <i class="bi bi-eye-slash me-2"></i>
                    Show/Hide Hidden Rows
                </button>
            </div>
            <div class="card-body">
                <table id="recognitionTypeTable" class="table-hover table-borderless display">
                    <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-center">ID</th>
                        <th scope="col" class="text-center">Employee Code</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($recognition_types as $recognition_type)
                        <tr class="{{ $recognition_type->recognition_type_hidden ? 'hidden-row' : '' }}">
                            <th scope="col" class="text-center">{{ $recognition_type->recognition_type_id }}</th>
                            <th scope="col">{{ $recognition_type->recognition_type_name }}</th>
                            <td class="text-center">
                                <button data-recognitiontype="{{ $recognition_type->recognition_type_id }}" class="btn p-1 text-primary" onclick="editRecognitionTypeModal(this)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
{{--                                <button data-recognition="{{ $recognition_type->recognition_type_id }}" class="btn p-1 text-danger" onclick="deleteRecognitionType(this)" >--}}
{{--                                    <i class="bi bi-trash"></i>--}}
{{--                                </button>--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade md1" id="addRecognitionTypeModal" tabindex="-1" aria-labelledby="addRecognitionTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProjectModalTypeLabel">Edit recognition type</h5>
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

<div class="modal fade md1" id="editRecognitionTypeModal" tabindex="-1" aria-labelledby="addRecognitionTypeModalLabel" aria-hidden="true">
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
                            <input type="text" name="recognition_type_id" id="edit_recognition_type_id" class="form-control" hidden>
                        </div>
                        <div class="col-md-12">
                            <label for="edit_recognition_type_name" class="form-label">Recognition type</label>
                            <input type="text" name="recognition_type_name" id="edit_recognition_type_name" class="form-control" >
                        </div>
{{--                        <div class="col-md-12" style="margin-top: 1rem">--}}
{{--                            <label for="edit_recognitiontype_hidden">Hidden</label>--}}
{{--                            <input type="checkbox" name="recognition_type_hidden" id="edit_recognitiontype_hidden">--}}
{{--                        </div>--}}
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
@endsection

@section('script')
    <script>
        var table = $('#recognitionTypeTable').DataTable({
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

        function editRecognitionTypeModal(button) {
            var recognitiontype_id = button.getAttribute('data-recognitiontype');
            // Dùng Ajax để lấy dữ liệu từ máy chủ và điền vào form chỉnh sửa
            fetch(`/recognition/type/${recognitiontype_id}`)
                .then(response => response.json())
                .then(data => {
                    if (Array.isArray(data) && data.length > 0) {
                        let recognition_type = data[0];
                        document.getElementById('edit_recognition_type_id').value = recognition_type.recognition_type_id || '';
                        document.getElementById('edit_recognition_type_name').value = recognition_type.recognition_type_name || '';
                        // document.getElementById('edit_recognition_type_hidden').checked = recognition_type.recognition_type_hidden == 1;

                        var editModal = new bootstrap.Modal(document.getElementById('editRecognitionTypeModal'));
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

        document.getElementById('toggleHiddenRows').addEventListener('click', function() {
            var tableRows = document.querySelectorAll('#recognitionTypeTable tbody tr');
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
