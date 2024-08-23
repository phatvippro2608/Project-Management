@section('title', $title)

@extends('auth.hrm')

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
<div class="row">
    <div class="col-lg-4 col-md-12">
        <div class="card border rounded-4 p-2">
            <div class="card-header py-0">
                <div class="card-title my-3 p-0">Add Disciplinary</div>
            </div>
            <div class="card-body">
                <form id="disciplinaryTypeForm">
                    <label for="input_add_disciplinary_type" class="mt-3">Disciplinary name</label>
                    <input type="text" name="disciplinary_type_name" id="input_add_disciplinary_type" class="form-control">
                </form>
            </div>
            <div class="card-footer">
                <input type="submit" value="Save" class="btn btn-primary" id="btnDisciplinaryTypeSubmit">
{{--                <input type="submit" value="Cancel" class="btn btn-danger">--}}
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-12">
        <div class="card border rounded-4 p-2">
            <div class="card-header py-0">
                <div class="card-title my-3 p-0">Add Disciplinary</div>
            </div>
            <div class="card-body">
                <table id="disciplinaryTypeTable" class="table-hover table-borderless display">
                    <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-center">ID</th>
                        <th scope="col" class="text-center">Employee Code</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($disciplinary_types as $disciplinary_type)
                        <tr class="{{ $disciplinary_type->disciplinary_type_hidden ? 'hidden-row' : '' }}">
                            <th scope="col" class="text-center">{{ $disciplinary_type->disciplinary_type_id }}</th>
                            <th scope="col">{{ $disciplinary_type->disciplinary_type_name }}</th>
                            <td class="text-center">
                                <button data="{{ $disciplinary_type->disciplinary_type_id }}" class="btn p-1 text-primary" onclick="btnEdit(this)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                |
                                <button data="{{ $disciplinary_type->disciplinary_type_id }}"
                                        class="btn p-1 text-danger"
                                        onclick="btnDel(this)" >
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade md1" id="editDisciplinaryTypeModal" tabindex="-1" aria-labelledby="editDisciplinaryTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDisciplinaryTypeModalLabel">Edit Disciplinary Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form trong view -->
            <div class="modal-body">
                <form id="recognitionTypeForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" name="disciplinary_type_id" id="edit_disciplinary_type_id" class="form-control" hidden>
                        </div>
                        <div class="col-md-12">
                            <label for="edit_disciplinary_type_name" class="form-label">Disciplinary type name</label>
                            <input type="text" name="recognition_type_name" id="edit_disciplinary_type_name" class="form-control">
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="edit_disciplinary_type_hidden">Hidden</label>
                            <input type="checkbox" name="disciplinary_type_hidden" id="edit_disciplinary_type_hidden">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="btnUpd">Submit</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        var table = $('#disciplinaryTypeTable').DataTable({
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

        function btnEdit(button) {
            var id = button.getAttribute('data');

            fetch(`/disciplinary/type/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        document.getElementById('edit_disciplinary_type_id').value = data.disciplinary_type_id || '';
                        document.getElementById('edit_disciplinary_type_name').value = data.disciplinary_type_name || '';
                        document.getElementById('edit_disciplinary_type_hidden').checked = data.disciplinary_type_hidden == 1;

                        var editModal = new bootstrap.Modal(document.getElementById('editDisciplinaryTypeModal'));
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

        document.getElementById('btnUpd').addEventListener('click', function() {
            // Lấy giá trị từ form
            var disciplinary_type_id = document.getElementById('edit_disciplinary_type_id').value;
            var disciplinary_type_name = document.getElementById('edit_disciplinary_type_name').value;
            var disciplinary_type_hidden = document.getElementById('edit_disciplinary_type_hidden').checked ? 1 : 0;

            // Tạo object dữ liệu để gửi đi
            var data = {
                disciplinary_type_id: disciplinary_type_id,
                disciplinary_type_name: disciplinary_type_name,
                disciplinary_type_hidden: disciplinary_type_hidden,
            };

            // Gửi yêu cầu cập nhật qua fetch API
            fetch(`/disciplinary/type/${disciplinary_type_id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify(data)
            })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Có lỗi xảy ra khi cập nhật dữ liệu.');
                    }
                })
                .then(responseData => {
                    toastr.success('Cập nhật loại công nhận thành công!', "Thành công");
                    // Ẩn modal sau khi cập nhật thành công
                    var editModal = bootstrap.Modal.getInstance(document.getElementById('editDisciplinaryTypeModal'));
                    editModal.hide();

                    // Tải lại bảng dữ liệu sau khi cập nhật thành công
                    // loadRecognitionTypeTable();
                    location.reload()
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Có lỗi xảy ra trong quá trình cập nhật.', "Thao tác thất bại");
                });
        });

        function btnDel(button) {
            // Lấy giá trị từ thuộc tính data của nút
            var id = button.getAttribute('data');

            // Hiển thị hộp thoại xác nhận trước khi xóa
            var confirmDelete = window.confirm("Bạn có chắc chắn muốn xóa loại công nhận này không?");

            if (confirmDelete) {
                // Lấy CSRF token từ thẻ meta
                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Nếu người dùng chọn "OK", gửi yêu cầu xóa qua Fetch API
                fetch(`/disciplinary/type/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            throw new Error('Có lỗi xảy ra khi xóa dữ liệu.');
                        }
                    })
                    .then(responseData => {
                        toastr.success('Xóa loại công nhận thành công!', "Thành công");
                        // Tải lại trang sau khi xóa thành công
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('Có lỗi xảy ra trong quá trình xóa.', "Thao tác thất bại");
                    });
            } else {
                // Nếu người dùng chọn "Cancel", không làm gì cả
                toastr.info('Hành động xóa đã bị hủy.', "Thông báo");
            }
        }
    </script>
@endsection
