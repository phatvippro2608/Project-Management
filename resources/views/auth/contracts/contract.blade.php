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
    <div class="btn btn-primary my-3 btn-add-contract">
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
                                    <a class=" edit" style="cursor: pointer">
                                        <i class="bi bi-pencil-square ic-update ic-btn at2"
                                           data="{{(\App\Http\Controllers\AccountController::toAttrJson($contract))}}"></i>
                                    </a>
                                    <a class=" delete" style="cursor: pointer">
                                        <i class="bi bi-trash ic-delete ic-btn at3" aria-hidden="true"
                                           data="{{$contract->contract_id}}"></i>
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
    <div class="modal modal-lg fade md1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-bold"></h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="">
                                Contract Name
                            </label>
                            <input type="text" class="form-control name21">
                        </div>

                        <div class="col-md-6" style="margin-top: 1rem">
                            <label for="">
                                Contract Start Date
                            </label>
                            <input type="date" class="form-control name22">
                        </div>
                        <div class="col-md-6" style="margin-top: 1rem">
                            <label for="">
                                Contract End Date
                            </label>
                            <input type="date" class="form-control name23">
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="">
                                Contract Details
                            </label>
                            <textarea class="form-control name24"></textarea>
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="">
                                Contract File
                            </label>
                            <form class="border rounded-4 p-2 text-center" action="{{route('attachment-store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file"
                                       id="imageAttachment"
                                       name="imageAttachment[]"
                                       multiple="multiple"
                                       accept="image/png, image/jpeg, image/gif">
                                <button class="btn btn-primary mx-auto d-none filepond-upload1 btn-upload-image" type="submit"><i class="bi bi-upload me-2"></i>Upload</button>
                                <div class="border rounded-4 p-3 mb-2 ">
                                    <div class="row gy-3 content_image">

                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-upload btn-add-contract">Create</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var table = $('#contractTable').DataTable({
            language: {search: ""},
            initComplete: function (settings, json) {
                $('.dt-search').addClass('input-group');
                $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
            },
            responsive: true,
        });

        $('.btn-add-contract').click(function () {
            $('.md1 .modal-title').text('Add New Contract');
            $('.md1').modal('show');
        })
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType
        );
        const imageAttachmentPond = FilePond.create(
            document.querySelector('#imageAttachment'),
            {
                allowPdfPreview: true,
                pdfPreviewHeight: 320,
                pdfComponentExtraParams: 'toolbar=0&view=fit&page=1',
                server: {
                    process: {
                        url: '{{route('attachment-upload')}}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        ondata: (formData) => {
                            formData.append('_token', csrfToken);
                            formData.append('project_location_id', document.querySelector('.location_select').value);
                            formData.append('date', document.querySelector('.date_select').value);
                            formData.append('type', 'image');

                            return formData;
                        },
                    },
                    revert: {
                        url: '{{route('attachment-delete')}}',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    }
                },
            }
        );
    </script>
@endsection
