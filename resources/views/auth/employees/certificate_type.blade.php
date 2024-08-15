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
        #certificateTable th {
            text-align: center !important;
        }

        #certificateTable td:nth-child(3),
        #certificateTable td:nth-child(4) {
            text-align: left !important;
        }
    </style>
    <div class="pagetitle">
        <h1>Certificate Types</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{action('App\Http\Controllers\CertificateTypeController@getView')}}">Certificate Types</a></li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-12">
            <div class="card border rounded-4 p-2">
                <div class="card-header py-0">
                    <div class="card-title my-3 p-0">Add Certificate Type</div>
                </div>
                <div class="card-body">
                    <form id="certificateTypeForm" >
                        <label for="certificate_type_name" class="mt-3">Certificate Type Name</label>
                        <input type="text" name="certificate_type_name" id="certificate_type_name" class="form-control certificate_type_name">
                    </form>
                </div>
                <div class="card-footer">
                    <input type="submit" value="Add" class="btn btn-primary btn-add" id="btn-add">
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12">
            <div class="card border rounded-4 p-2">
                <div class="card-header py-0">
                    <div class="card-title my-3 p-0">Certificate Type List</div>
                </div>
                <div class="card-body">
                    <table id="certificateTypeTable" class="table-hover table-borderless display">
                        <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-center">No</th>
                            <th scope="col">Certificate Type Name</th>
                            <th class="text-center" scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($certificate_types as $index => $certificate_type)
                                    <tr>
                                        <td class="text-center">{{$index+1}}</td>
                                        <td>{{$certificate_type->certificate_type_name}}</td>
                                        <td class="text-center">
                                            <button data-certificate="{{ $certificate_type->certificate_type_name }}" data-id="{{ $certificate_type->certificate_type_id }}" class="btn p-1 text-primary at1">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            |
                                            <button data-id="{{ $certificate_type->certificate_type_id }}" class="btn p-1 text-danger at2">
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
    <div class="modal fade md1" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Certificate Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="">
                                Certificate Type Name
                            </label>
                            <input type="text" class="form-control certificate_type_name">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-update">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var table = $('#certificateTypeTable').DataTable({
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

        let _add = '{{action('App\Http\Controllers\CertificateTypeController@add')}}';
        let _update = '{{action('App\Http\Controllers\CertificateTypeController@update')}}';
        let _delete = '{{action('App\Http\Controllers\CertificateTypeController@delete')}}';

        $('.btn-add').click(function () {
            let certificate_type_name = $('.certificate_type_name').val();
            let formData = new FormData();
            formData.append('certificate_type_name', certificate_type_name);
            $.ajax({
                url: _add,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.status === 200) {
                        toastr.success(response.message, "Thao tác thành công");
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        toastr.error(response.message, "Thao tác thất bại");
                    }
                }
            });
        })


        $(document).on('click', '.at1', function () {
            $('.md1').modal('show');
            let certificate_type_name = $(this).attr('data-certificate');
            $('.md1 .certificate_type_name').val(certificate_type_name);
            $('.btn-update').data('id', $(this).attr('data-id'));
        });

        $(document).on('click', '.btn-update', function () {
            let certificate_type_name = $('.md1 .certificate_type_name').val();
            let certificate_type_id = $(this).data('id');
            let formData = new FormData();
            formData.append('certificate_type_id', certificate_type_id);
            formData.append('certificate_type_name', certificate_type_name);

            $.ajax({
                url: _update,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response);
                    console.log(response.status);
                    if (response.status === 200) {
                        toastr.success(response.message, response.message);
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        toastr.error(response.message, response.message);
                    }
                }
            });
        });

        $(document).on('click', '.at2', function () {
            if (confirm('Are you sure DELETE this certificate type?')) {
                let certificate_type_id = $(this).data('id');
                let formData = new FormData();
                formData.append('certificate_type_id', certificate_type_id);
                $.ajax({
                    url: _delete,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        console.log(response);
                        console.log(response.status);
                        if (response.status === 200) {
                            toastr.success(response.message, response.message);
                            setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        } else {
                            toastr.error(response.message, response.message);
                        }
                    }
                });
            }
        });


    </script>
@endsection
