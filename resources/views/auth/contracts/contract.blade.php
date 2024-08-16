@extends('auth.main')
@section('head')
    <style>
        label {
            font-weight: bolder;
            margin-left: 5px;
            margin-top: 20px;
        }


        tr {
            border-bottom: 1px solid #E8E8E8;
        }

        .bg-hover:hover {
            background: #E2E3E5 !important;
        }

        .dropdown-toggle::after {
            display: none !important;
        }
    </style>
@endsection
@section('contents')
    <div class="pagetitle">
        <h1>Contract list</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Contract List</li>
            </ol>
        </nav>
    </div>
    <div class="btn btn-primary my-3 add-contract">
        <div class="d-flex align-items-center">
            <i class="bi bi-file-earmark-plus pe-2"></i>
            Add contract
        </div>
    </div>
    <div class="card border rounded-4 p-2">
        <div class="card-body">
            <table id="contractTable" class="table table-borderless table-hover">
                <thead class="table-light">
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Contract detail</th>
                    <th class="text-center">Contact date</th>
                    <th class="text-center">Contact end date</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @php $i = 1 @endphp
                @foreach($contracts as $contract)
                    <tr>
                        <td class="text-center">{{$i}}</td>
                        @php $i++ @endphp
                        <td style="width: 33%">
                            <div class="fw-bold">{{Str::limit($contract->contract_name,60)}}</div>
                            <div>{{Str::limit($contract->contract_details,120)}}</div>
                        </td>
                        <td class="text-center">{{\App\Http\Controllers\AccountController::format($contract->contract_date)}}</td>
                        <td class="text-center">{{\App\Http\Controllers\AccountController::format($contract->contract_end_date)}}</td>

                        <td class="text-end">{{number_format($contract->amount)}} VND</td>
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="d-flex align-items-center">
                                    <a class=" btn-update edit" style="cursor: pointer"
                                       data="{{(\App\Http\Controllers\AccountController::toAttrJson($contract))}}"
                                       files="{{(\App\Http\Controllers\AccountController::toAttrJson(\App\Http\Controllers\ContractController::getFile($contract->contract_id)))}}"
                                    >
                                        <i class="bi bi-pencil-square ic-update ic-btn "
                                        ></i>
                                    </a>
                                    <a class="btn-delete delete" style="cursor: pointer" data="{{$contract->contract_id}}">
                                        <i class="bi bi-trash ic-delete ic-btn at3" aria-hidden="true"
                                           ></i>
                                    </a>
                                </div>

                            </div>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal modal-xl fade md1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form class="form-contract"
                  enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h4 class="modal-title text-bold"></h4>
                        <i class="bi bi-x-lg fs-4" style="cursor:pointer" data-bs-dismiss="modal" aria-label="Close"></i>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-md-12" style="margin-top: 1rem;">
                                        <label for="" style="margin-top: 0px;">
                                            Contract Name
                                        </label>
                                        <input type="text" class="form-control name">
                                    </div>

                                    <div class="col-md-6" style="margin-top: 1rem">
                                        <label for="">
                                            Contract Start Date
                                        </label>
                                        <input type="date" class="form-control start">
                                    </div>
                                    <div class="col-md-6" style="margin-top: 1rem">
                                        <label for="">
                                            Contract End Date
                                        </label>
                                        <input type="date" class="form-control end">
                                    </div>
                                    <div class="col-md-12" style="margin-top: 1rem">
                                        <label for="" class="form-label">Select Customer</label>
                                        <div class="input-group">

                                            <select class="form-select customer"
                                                    name="customer"
                                                    aria-label="Example select with button addon">
                                                <option selected>Choose...</option>
                                                @foreach($customers as $item)
                                                    <option
                                                        value="{{$item->customer_id}}">{{$item->first_name.' '.$item->last_name}}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-outline-secondary add-customer" type="button"><i
                                                    class="bi bi-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="margin-top: 1rem">
                                        <label for="">
                                            Contract Details
                                        </label>
                                        <textarea class="form-control details" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-md-12" style="margin-top: 1rem">
                                        <label for="" style="margin-top: 0px;">
                                            Contract File
                                        </label>

                                        <input type="file"
                                               id="contractAttachment"
                                               name="contractAttachment[]"
                                               multiple="multiple">
                                        <button class="btn btn-primary mx-auto d-none filepond-upload1 btn-upload-image"
                                                type="submit"><i class="bi bi-upload me-2"></i>Upload
                                        </button>
                                        <div class="files-of-contract" style="max-height: 120px">

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-upload btn-add-contract btn-update-contract">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('script')
    <script>
        var table = $('#contractTable').DataTable({
            language: {search: ""},
            lengthMenu: [
                [10, 30, 50, 100, -1],
                [10, 30, 50, 100, "All"]
            ],
            pageLength: {{env('ITEM_PER_PAGE')}},
            initComplete: function (settings, json) {
                $('.dt-search').addClass('input-group');
                $('.dt-search').prepend(`
                            <button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
            },
            responsive: true,
        });

        $('.add-contract').off('click').click(function () {
            $('.md1 .modal-title').text('Add New Contract');
            $('.md1 .btn-add-contract').text('Create');
            $('.md1').modal('show');


            $('.btn-add-contract').off('click').on('click', function (e) {
                e.preventDefault();

                // Thu thập dữ liệu từ form
                var formData = new FormData($('.form-contract')[0]);

                formData.append('contract_name', $('.name').val());
                formData.append('contract_date', $('.start').val());
                formData.append('contract_end_date', $('.end').val());
                formData.append('contract_details', $('.details').val());
                formData.append('customer_id', $('.customer').val());

                $.ajax({
                    url:  `{{action('App\Http\Controllers\ContractController@addContract')}}`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        result = JSON.parse(response);
                        if (result.status === 200) {
                            toastr.success(result.message, "Successfully");
                            setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        }else if(result.status === 422){
                            const errorRes = JSON.parse(result.message);
                            let strMes = "";
                            if (errorRes.contract_name) strMes += `<div style="color: red; text-align: left;">-${errorRes.contract_name}</div>`;
                            if (errorRes.contract_date) strMes += `<div style="color: red; text-align: left;">-${errorRes.contract_date}</div>`;
                            if (errorRes.contract_end_date) strMes += `<div style="color: red; text-align: left;">-${errorRes.contract_end_date}</div>`;
                            if (errorRes.contract_details) strMes += `<div style="color: red; text-align: left;">-${errorRes.contract_details}</div>`;
                            if (errorRes.customer_id) strMes += `<div style="color: red; text-align: left;">-${errorRes.customer_id}</div>`;
                            Swal.fire({
                                html: strMes,
                                icon: 'error',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Back and continue edit!'
                            }).then((result) => {
                                if (result.isConfirmed) {

                                }
                            });
                        }
                        else {
                            toastr.error(result.message, "Failed Action");
                        }
                    },
                    error: function (response) {
                        // console.error(response);
                    }
                });
            });
        })

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType
        );

        const contractAttachmentPond = FilePond.create(
            document.querySelector('#contractAttachment'),
            {
                allowPdfPreview: false,
                pdfPreviewHeight: 320,
                pdfComponentExtraParams: 'toolbar=0&view=fit&page=1',
                server: {
                    process: {
                        url: '{{action('App\Http\Controllers\ContractController@uploadContractFile')}}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        ondata: (formData) => {
                            formData.append('_token', csrfToken);
                            return formData;
                        },
                    },
                    revert: {
                        url: '{{route('attachment-delete', ['project_id'=>-1])}}',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    }
                },
            }
        );

    </script>



    <script>
        const icons = {
            pdf: "bi bi-file-earmark-pdf fs-1",
            doc: "bi bi-file-earmark-word fs-1",
            docx: "bi bi-file-earmark-word fs-1",
            xls: "bi bi-file-earmark-excel fs-1",
            xlsx: "bi bi-file-earmark-excel fs-1",
            ppt: "bi bi-file-earmark-ppt fs-1",
            pptx: "bi bi-file-earmark-ppt fs-1",
            txt: "bi bi-file-earmark-text fs-1",
            sql: "bi bi-file-earmark-code fs-1",
            zip: "bi bi-file-earmark-zip fs-1",
            rar: "bi bi-file-earmark-zip fs-1",
            default: "bi bi-file-earmark fs-1"
        };


        $('.md1').on('hidden.bs.modal', function () {
            $(document).on('hidden.bs.modal', '.md1', function () {
                $(this).find('.modal-title').text('');
                $(this).find('input, textarea').val('');
                $(this).find('.files-of-contract').empty();
            });
        });

        function shortenFileName(fileName, maxLength = 40) {
            if (fileName.length <= maxLength) {
                return fileName;
            }

            const halfLength = Math.floor((maxLength - 3) / 2);
            const firstPart = fileName.slice(0, halfLength);
            const lastPart = fileName.slice(-halfLength);

            return `${firstPart}...${lastPart}`;
        }

        $(document).on('click', '.btn-update', function () {
            $('.md1 .modal-title').text('Update Contract');
            $('.md1 .btn-update-contract').text('Update');
            var data = JSON.parse($(this).attr('data'));
            var files = JSON.parse($(this).attr('files'));

            // const files = $(this).attr('files');
            $('.name').val(data.contract_name);
            $('.start').val(data.contract_date);
            $('.customer').val(data.customer_id);
            $('.end').val(data.contract_end_date);
            $('.details').val(data.contract_details);

            console.log("Den day")
            if (files) {
                // console.log(files);
                files.forEach(file => {
                    const extension = file.file_path.split('.').pop().toLowerCase();
                    console.log(extension)
                    const iconClass = icons[extension] || icons.default;

                    const fileElement = `
                        <div class="border rounded-3 p-1 mb-2 card-file" data="${file.contract_file_id}">
                            <div class="row gy-3 content_image ms-2">
                                <div class="file_ d-flex align-items-center">
                                    <div class="thumbnail d-flex align-items-center">
                                        <i class="${iconClass}"></i>
                                    </div>
                                    <div class="file-info ms-2 w-100">
                                        <div class="file-title fw-bold">${shortenFileName(file.file_name)}</div>
                                        <div class="d-flex align-items-center">

                                            <div class="file-date">${file.created_at}</div>
                                        </div>
                                    </div>
                                    <div class="actions">
                                        <a class="delete-file" style="cursor: pointer" data="${file.contract_file_id}"><i class="bi bi-trash fs-4 text-danger"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    const container = document.querySelector('.files-of-contract');
                    container.innerHTML += fileElement;
                    // <div class="file-size">30 KB</div> -
                });

            }

            $('.md1').modal('show');


            $('.btn-update-contract').off('click').on('click', function (e) {
                e.preventDefault();

                // Thu thập dữ liệu từ form
                var formData = new FormData($('.form-contract')[0]);

                formData.append('contract_name', $('.name').val());
                formData.append('contract_date', $('.start').val());
                formData.append('contract_end_date', $('.end').val());
                formData.append('contract_details', $('.details').val());
                formData.append('customer_id', $('.customer').val());
                formData.append('contract_id', data.contract_id)

                for (var pair of formData.entries()) {
                    console.log(pair[0] + ', ' + pair[1]);
                }

                $.ajax({
                    url: `{{action('App\Http\Controllers\ContractController@updateContract')}}`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        result = JSON.parse(response);
                        if (result.status === 200) {
                            toastr.success(result.message, "Successfully");
                            setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        }else if(result.status === 422){
                            const errorRes = JSON.parse(result.message);
                            let strMes = "";
                            if (errorRes.contract_name) strMes += `<div style="color: red; text-align: left;">-${errorRes.contract_name}</div>`;
                            if (errorRes.contract_date) strMes += `<div style="color: red; text-align: left;">-${errorRes.contract_date}</div>`;
                            if (errorRes.contract_end_date) strMes += `<div style="color: red; text-align: left;">-${errorRes.contract_end_date}</div>`;
                            if (errorRes.contract_details) strMes += `<div style="color: red; text-align: left;">-${errorRes.contract_details}</div>`;
                            if (errorRes.customer_id) strMes += `<div style="color: red; text-align: left;">-${errorRes.customer_id}</div>`;
                            Swal.fire({
                                html: strMes,
                                icon: 'error',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Back and continue edit!'
                            }).then((result) => {
                                if (result.isConfirmed) {

                                }
                            });
                        } else {
                            toastr.error(result.message, "Failed Action");
                        }
                    },
                    error: function (response) {
                        console.error(response);
                    }
                });
            });
        })

        $(document).on('click', '.delete-file', function () {
            if (!confirm("Chọn vào 'YES' để xác nhận xóa thông tin?\nSau khi xóa dữ liệu sẽ không thể phục hồi lại được.")) {
                return;
            }
            var id = $(this).attr('data');
            $.ajax({
                url: `{{action('App\Http\Controllers\ContractController@deleteFileContract')}}`,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'contract_file_id': id,
                },
                success: function (result) {
                    result = JSON.parse(result);
                    if (result.status === 200) {
                        toastr.success(result.message, "Successfully");
                        $(document).find(`.card-file[data="${id}"]`).addClass("d-none");
                    } else {
                        toastr.success(result.message, "Failed");
                    }
                }
            });
        })





        $(document).on('click', '.btn-delete', function () {
            if (!confirm("Chọn vào 'YES' để xác nhận xóa thông tin?\nSau khi xóa dữ liệu sẽ không thể phục hồi lại được.")) {
                return;
            }
            var id = $(this).attr('data');
            $.ajax({
                url: `{{action('App\Http\Controllers\ContractController@deleteContract')}}`,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'contract_id': id,
                },
                success: function (result) {
                    result = JSON.parse(result);
                    if (result.status === 200) {
                        toastr.success(result.message, "Successfully");
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        toastr.success(result.message, "Failed");
                    }
                }
            });
        })


        $('.add-customer').click(function () {
            let timerInterval;
            Swal.fire({
                html: 'You will redirect to the customer page to add a new customer in <b>4</b> seconds.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Redirect Now',
                didOpen: () => {
                    const content = Swal.getHtmlContainer();
                    const b = content.querySelector('b');
                    let countdown = 3;
                    timerInterval = setInterval(() => {
                        b.textContent = countdown;
                        countdown--;
                        if (countdown < 0) {
                            clearInterval(timerInterval);
                            Swal.close();
                            const contractName = document.querySelector('.name').value;
                            const contractStartDate = document.querySelector('.start').value;
                            const contractEndDate = document.querySelector('.end').value;
                            const customer = document.querySelector('.customer').value;
                            const contractDetails = document.querySelector('.details').value;
                            // const encodedContractName = encodeURIComponent(contractName);
                            // const encodedContractStartDate = encodeURIComponent(contractStartDate);
                            // const encodedContractEndDate = encodeURIComponent(contractEndDate);
                            // const encodedCustomer = encodeURIComponent(customer);
                            // const encodedContractDetails = encodeURIComponent(contractDetails);

                            let redirectUrl = `{{ action('App\Http\Controllers\CustomerController@getView', ['redirect' => action('App\Http\Controllers\ContractController@getView', ['re'=>'re','contractName' => 'CONTRACT_NAME', 'contractStartDate' => 'CONTRACT_START_DATE', 'contractEndDate' => 'CONTRACT_END_DATE', 'customer' => 'CUSTOMER', 'contractDetails' => 'CONTRACT_DETAILS'])]) }}`
                                .replace('CONTRACT_NAME', contractName)
                                .replace('CONTRACT_START_DATE', contractStartDate)
                                .replace('CONTRACT_END_DATE', contractEndDate)
                                .replace('CUSTOMER', customer)
                                .replace('CONTRACT_DETAILS', contractDetails);
                            window.location.href = redirectUrl;
                        }
                    }, 1000);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Handle the confirmation button click if needed
                    window.location.href = `{{action('App\Http\Controllers\ContractController@getView')}}`;
                }
            });
        })
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('re') && urlParams.get('re')==='re') {
            $('.name').val(urlParams.get('contractName') || '');
            $('.start').val(urlParams.get('contractStartDate') || '');
            $('.end').val(urlParams.get('contractEndDate') || '');
            $('.customer').val(urlParams.get('customerId') || '');
            $('.details').val(urlParams.get('contractDetails') || '');
            const url = new URL(window.location.href);
            url.search = '';
            window.history.replaceState({}, '', url);
            $('.md1 .modal-title').text('Add New Contract');

            $('.md1').modal('show');

            $('.btn-add-contract').off('click').on('click', function (e) {
                e.preventDefault();

                // Thu thập dữ liệu từ form
                var formData = new FormData($('.form-contract')[0]);

                formData.append('contract_name', $('.name').val());
                formData.append('contract_date', $('.start').val());
                formData.append('contract_end_date', $('.end').val());
                formData.append('contract_details', $('.details').val());
                formData.append('customer_id', $('.customer').val());

                $.ajax({
                    url:  `{{action('App\Http\Controllers\ContractController@addContract')}}`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        result = JSON.parse(response);
                        if (result.status === 200) {
                            toastr.success(result.message, "Successfully");
                            setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        }else if(result.status === 422){
                            const errorRes = JSON.parse(result.message);
                            let strMes = "";
                            if (errorRes.contract_name) strMes += `<div style="color: red; text-align: left;">-${errorRes.contract_name}</div>`;
                            if (errorRes.contract_date) strMes += `<div style="color: red; text-align: left;">-${errorRes.contract_date}</div>`;
                            if (errorRes.contract_end_date) strMes += `<div style="color: red; text-align: left;">-${errorRes.contract_end_date}</div>`;
                            if (errorRes.contract_details) strMes += `<div style="color: red; text-align: left;">-${errorRes.contract_details}</div>`;
                            if (errorRes.customer_id) strMes += `<div style="color: red; text-align: left;">-${errorRes.customer_id}</div>`;
                            Swal.fire({
                                html: strMes,
                                icon: 'error',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Back and continue edit!'
                            }).then((result) => {
                                if (result.isConfirmed) {

                                }
                            });
                        }
                        else {
                            toastr.error(result.message, "Failed Action");
                        }
                    },
                    error: function (response) {
                        // console.error(response);
                    }
                });
            });
        }
    </script>
@endsection
