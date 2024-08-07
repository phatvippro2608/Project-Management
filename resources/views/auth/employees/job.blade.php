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
        <h1>Job Info</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{action('App\Http\Controllers\JobInfoController@getView')}}">Job Info</a></li>
            </ol>
        </nav>
    </div>
    <div class="row mb-3">
        <div class="col-lg-4 col-md-12">
            <select class="form-select form-select-lg rounded-4 selectedJob" aria-label="Default select example">
                <option selected value="0">Open this select menu</option>
                <option value="job_countries">Country</option>
                <option value="job_categories">Categories</option>
                <option value="job_levels">Level</option>
                <option value="job_locations">Location</option>
                <option value="job_positions">Position</option>
                <option value="job_teams">Team</option>
                <option value="job_titles">Title</option>
                <option value="job_type_contracts">Contract</option>
            </select>
        </div>

    </div>
    <div class="row contain d-flex d-none">
        <div class="col-lg-4 col-md-12">
            <div class="card border rounded-4 p-2">
                <div class="card-header py-0">
                    <div class="card-title my-3 p-0 title-add"></div>
                </div>
                <div class="card-body">
                    <form id="certificateTypeForm" >
                        <label for="label-add" class="mt-3 label-add"></label>
                        <input type="text" name="certificate_type_name" id="label-add" class="form-control input-add">
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
                    <div class="card-title my-3 p-0 title-list"></div>
                </div>
                <div class="card-body">
                    <table id="table-job" class="table-hover table-borderless display">
                        <thead class="table-light">
                        <tr class="table-job-header">

                        </tr>
                        </thead>
                        <tbody class="table-job-body">

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
                    <h5 class="modal-title title-update"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label class="label-update" for="">
                            </label>
                            <input type="text" class="form-control input-update">
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

        let _add = '{{action('App\Http\Controllers\JobInfoController@add')}}';
        let _update = '{{action('App\Http\Controllers\JobInfoController@update')}}';
        let _delete = '{{action('App\Http\Controllers\JobInfoController@delete')}}';
        let _getJob = '{{action('App\Http\Controllers\JobInfoController@getJob')}}';
        let selected_job = "";
        let selected_job_text = ""
        $( ".selectedJob" ).on( "change", function() {
            selected_job = $(this).find("option:selected").val();
            if(selected_job.localeCompare("0") === 0 ){
                $('.contain').addClass('d-none')
            }else{
                selected_job_text = $(this).find("option:selected").text();
                $('.title-add').text("Add " + $(this).find("option:selected").text())
                $('.title-list').text($(this).find("option:selected").text() + " List")
                $('.label-add').text($(this).find("option:selected").text() + " Name")
                $('.contain').removeClass('d-none')
                let formData = new FormData();
                formData.append('job',selected_job);
                $.ajax({
                    url: _getJob,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.status === 200) {
                            updateTable(response.data); // Function to update table
                        }
                    }
                });
            }
        } );

        function updateTable(data) {
            let headers = Object.keys(data[0]); // Assuming data is an array of objects
            let headerRow = $('.table-job-header');
            let body = $('.table-job-body');

            if ($.fn.dataTable.isDataTable('#table-job')) {
                $('#table-job').DataTable().clear().destroy();
            }

            headerRow.empty();
            body.empty();

            headers.forEach(function(header) {
                headerRow.append('<th class="text-center">' + formatString(header) + '</th>');
            });
            headerRow.append('<th class="text-center">Action</th>');

            data.forEach(function(row) {
                let bodyRow = '<tr>';
                headers.forEach(function(header) {
                    bodyRow += '<td class="text-center">' + row[header] + '</td>';
                });
                bodyRow += '<td class="text-center">' +
                    '<button data-name="' + row[headers[1]] + '" data-id="' + row[headers[0]] + '" class="btn p-1 text-primary at1">' +
                    '<i class="bi bi-pencil-square"></i>' +
                    '</button>' +
                    ' | ' +
                    '<button data-id="' + row[headers[0]] + '" class="btn p-1 text-danger at2">' +
                    '<i class="bi bi-trash"></i>' +
                    '</button>' +
                    '</td>';
                bodyRow += '</tr>';
                body.append(bodyRow);
            });

            initTable();
        }

        function formatString(str) {
            return str.split('_')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        }
        function initTable(){
            var table = $('#table-job').DataTable({
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
        };
        $('.btn-add').click(function () {
            let job_name = $('.input-add').val();
            let job = selected_job;
            let formData = new FormData();
            formData.append('job', job);
            formData.append('job_name', job_name);
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


        $(document).on('click', '.at1', function() {
            $('.md1').modal('show');
            let job_name = $(this).attr('data-name');
            $('.md1 .input-update').val(job_name);
            $('.btn-update').data('id', $(this).attr('data-id'));

            $('.title-update').text("Update " + selected_job_text)
            $('.label-update').text(selected_job_text + " Name")

        });


        $('.btn-update').click(function () {
            let job = selected_job;
            let job_name = $('.md1 .input-update').val();
            let id_job_name = $(this).data('id');
            let formData = new FormData();
            formData.append('job',job);
            formData.append('job_name', job_name);
            formData.append('id_job_name', id_job_name);
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
                    console.log(response)
                    console.log(response.status)
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

        $(document).on('click', '.at2', function() {
            if(confirm('Are you sure DELETE this '+selected_job_text+' ?')){
                let job = selected_job;
                let job_id = $(this).data('id');

                let formData = new FormData();
                formData.append('job', job);
                formData.append('job_id', job_id);
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
                        console.log(response)
                        console.log(response.status)
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
