@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Employees</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{action('App\Http\Controllers\EmployeesController@getView')}}">Employees</a></li>
                <li class="breadcrumb-item active">Import</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header py-0">
                    <div class="card-title my-3 p-0">Import Employee</div>
                </div>
                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col d-flex align-items-center p-0 m-0">
                            <div class="btn btn-success mx-2" id="uploadBtn">
                                <div class="d-flex align-items-center at2">
                                    <i class="bi bi-file-earmark-arrow-up-fill pe-2"></i>
                                    Import
                                </div>
                            </div>
                            <input type="file" hidden="hidden" id="fileInput">
                            <div class="d-flex align-items-center m-0"><span>Lưu ý các trường bắt buộc : </span><span class="text-warning"> &nbsp;Email, Điện thoại, Tên thành viên &nbsp;</span><span> Mật khẩu mặc định là 123456</span></div>
                        </div>
                    </div>
                    <table id="employeesTable" class="table table-hover table-borderless">
                        <thead class="table-light tableHeader">
                        <!-- Headers will be added dynamically -->
                        </thead>
                        <tbody class="tableData">
                        <!-- Data will be added dynamically -->
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="btn btn-success btn-save-excel">
                        <i class="bi bi-floppy-fill pe-2"></i>
                        Save
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade md1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Import employee status</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="modal-body bg-white p-0">
                    <ul class="nav nav-tabs d-flex" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100 active"
                                    id="success-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#success-justified"
                                    type="button"
                                    role="tab"
                                    aria-controls=""
                                    aria-selected="true"
                            >
                                Successful
                            </button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link d-flex align-items-center justify-content-center w-100"
                                    id="profile-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#error-justified"
                                    type="button" role="tab"
                                    aria-controls=""
                                    aria-selected="false"
                                    tabindex="-1"
                            >
                                Error
                                <span class="badge text-bg-danger ms-2 have-error"></span>
                                <span class="badge text-bg-success ms-2 no-error"></span>
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="success-justified" role="tabpanel" aria-labelledby="success-tab">
                            <div class="table-responsive p-2">
                                <table id="successfulTable" class="table table-borderless table-hover">
                                    <thead class="table-light">
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    </thead>
                                    <tbody class="tableData"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="error-justified" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="table-responsive p-2">
                                <table id="errorTable" class="table table-borderless table-hover">
                                    <thead class="table-light">
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Error</th>
                                    </thead>
                                    <tbody class="tableData"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let _load_excel = '{{ action('App\Http\Controllers\EmployeesController@loadExcel') }}';
        let _import = '{{ action('App\Http\Controllers\EmployeesController@import') }}';

        $('.at2').click(function (event) {
            event.preventDefault();
        });
        let dataExcel = [];

        $(document).ready(function () {
            let table;

            $('#uploadBtn').on('click', function (e) {
                e.preventDefault();
                $('#fileInput').click();
            });

            $('#fileInput').on('change', function () {
                let fileInput = this.files[0];

                if (fileInput === undefined) {
                    toastr.error("Vui lòng chọn file", "Thao tác thất bại");
                    return;
                }

                var formData = new FormData();
                formData.append('file-excel', fileInput);
                $.ajax({
                    url: _load_excel,
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        dataExcel = response;
                        // Clear existing table data
                        $('#employeesTable .tableHeader').empty();
                        $('#employeesTable .tableData').empty();

                        // Add table headers
                        const headers = ['First Name', 'Last Name', 'English Name', 'Email', 'Phone Number'];
                        let headerRow = '<tr>';
                        headers.forEach(header => {
                            headerRow += '<th>' + header + '</th>';
                        });
                        headerRow += '</tr>';
                        $('#employeesTable .tableHeader').append(headerRow);

                        // Add table data
                        response.slice(1).forEach(row => {
                            let dataRow = '<tr>';
                            dataRow += '<td>' + row.first_name + '</td>';
                            dataRow += '<td>' + row.last_name + '</td>';
                            dataRow += '<td>' + row.en_name + '</td>';
                            dataRow += '<td>' + row.email + '</td>';
                            dataRow += '<td>' + row.phone_number + '</td>';
                            dataRow += '</tr>';
                            $('#employeesTable .tableData').append(dataRow);
                        });

                        // Destroy existing DataTable instance (if any)
                        if ($.fn.DataTable.isDataTable('#employeesTable')) {
                            $('#employeesTable').DataTable().destroy();
                        }

                        // Initialize DataTable
                        table = $('#employeesTable').DataTable({
                            language: { search: "" },
                            initComplete: function (settings, json) {
                                $('.dt-search').addClass('input-group');
                                $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                    <i class="bi bi-search"></i>
                                </button>`);
                            },
                            responsive: true
                        });

                    }
                });
            });
            $('.btn-save-excel').click(function () {
                if (dataExcel.length === 0) {
                    toastr.error("No data to save", "Operation failed");
                    return;
                }

                // Create FormData object
                let formData = new FormData();
                formData.append('dataExcel', JSON.stringify(dataExcel)); // Add dataExcel as a JSON string

                $.ajax({
                    url: _import,
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('.md1').modal('show');
                        console.log(response.successfulRows);
                        console.log(response.errorRows);
                        if (response.successfulRows.length > 0) {
                            response.successfulRows.forEach(row => {
                                let dataRow = '<tr>';
                                dataRow += '<td>' + row.first_name + '</td>';
                                dataRow += '<td>' + row.last_name + '</td>';
                                dataRow += '<td>' + row.email + '</td>';
                                dataRow += '</tr>';
                                $('#successfulTable .tableData').append(dataRow);
                            });
                        }
                        if (response.errorRows.length > 0) {
                            $('.have-error').text(response.errorRows.length);
                            $('.no-error').addClass('d-none');
                            response.errorRows.forEach(row => {
                                let dataRow = '<tr>';
                                dataRow += '<td>' + row.data.first_name + '</td>';
                                dataRow += '<td>' + row.data.last_name + '</td>';
                                dataRow += '<td>' + row.data.email + '</td>';
                                dataRow += '<td>' + row.error + '</td>';
                                dataRow += '</tr>';
                                $('#errorTable .tableData').append(dataRow);
                            });
                        }else{
                            $('.no-error').text('0');
                            $('.have-error').addClass('d-none');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error(textStatus, errorThrown);
                        toastr.error("An error occurred while importing data", "Error");
                    }
                });
            });

        });
    </script>
@endsection
