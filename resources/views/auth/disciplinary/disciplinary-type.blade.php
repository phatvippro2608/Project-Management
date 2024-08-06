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
                        <th scope="col">ID</th>
                        <th scope="col">Employee Code</th>
                        <th class="text-center" scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($disciplinary_types as $disciplinary_type)
                        <tr class="{{ $disciplinary_type->disciplinary_type_hidden ? 'hidden-row' : '' }}">
                            <th scope="col">{{ $disciplinary_type->disciplinary_type_id }}</th>
                            <th scope="col">{{ $disciplinary_type->disciplinary_type_name }}</th>
                            <td class="text-center">
                                <button data-disciplinary="{{ $disciplinary_type->disciplinary_type_id }}" class="btn p-1 text-primary" onclick="editDisciplinary(this)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                |
                                <button data-disciplinary="{{ $disciplinary_type->disciplinary_type_id }}" class="btn p-1 text-danger" onclick="deleteAttendanceByID(this)" >
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
    </script>
@endsection
