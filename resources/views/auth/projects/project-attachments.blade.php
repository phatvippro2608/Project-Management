@extends('auth.main');
@section('contents')
    <div class="pagetitle">
        <h1>Project</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ action('App\Http\Controllers\ProjectController@getView') }}">Project List</a></li>
                <li class="breadcrumb-item active">Project Attachments</li>
            </ol>
        </nav>
    </div>
    <div class="card p-2 mb-4 rounded-4 border">
        <div class="card-body p-2">
            <table>
                <tr>
                    <th>Mã hợp đồng:</th>
                    <td>{{$contract->contract_id}}</td>
                </tr>
                <tr>
                    <th>Tên công ty:</th>
                    <td>{{$company}}</td>
                </tr>
                <tr>
                    <th>Tên dự án:</th>
                    <th>{{$contract->contract_name}}</th>
                </tr>
            </table>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-center">
        <div class="row w-75">
            <div class="col-5">
                <select class="form-select form-select-lg w-100 location_select">
                    @foreach($locations as $index => $location)
                        <option value="{{ $location->project_location_id }}" {{ $index == 0 ? 'selected' : '' }}>
                            {{ $location->project_location_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-5">
                <select class="form-select form-select-lg w-100 date_select">

                </select>
            </div>
            <div class="col-2">
                <a href="" class="btn btn-info btn-lg"> < </a>
                <a href="" class="btn btn-info btn-lg"> > </a>
            </div>
        </div>
    </div>
    <div class="card p-2 rounded-4 border mt-4">
        <div class="card-header">
            <div class="card-title p-0 m-0">
                File
            </div>
        </div>
        <div class="card-body p-2">
            <table id="filesTable" class="table table-hover table-borderless">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">File Name</th>
                        <th class="text-center">Date Add</th>
{{--                        <th>Action</th>--}}
                    </tr>
                </thead>
                <tbody class="filesTableBody">

                </tbody>
            </table>
            <div class="col-lg">
                <form class="border rounded-4 p-2 text-center" action="{{route('attachment-store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file"
                           id="fileAttachment"
                           name="fileAttachment[]"
                           multiple="multiple"
                          >
                    <button class="btn btn-primary mx-auto d-none filepond-upload" type="submit"><i class="bi bi-upload me-2"></i>Upload</button>
                </form>
            </div>
        </div>
    </div>
    <div class="card p-2 rounded-4 border mt-4">
        <div class="card-header">
            <div class="card-title p-0 m-0">
                Image
            </div>
        </div>
        <div class="card-body p-2">
            <div class="border rounded-4 p-3 mb-2 ">
                <div class="row gy-3 content_image">

                </div>
            </div>
            <div class="col-lg">
                <form class="border rounded-4 p-2 text-center" action="{{route('attachment-store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file"
                           id="imageAttachment"
                           name="imageAttachment[]"
                           multiple="multiple"
                           accept="image/png, image/jpeg, image/gif">
                    <button class="btn btn-primary mx-auto d-none filepond-upload" type="submit"><i class="bi bi-upload me-2"></i>Upload</button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal modal-preview-image" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                </div>
                <div class="modal-body">
                    <img src="" alt="" class="w-100 h-100 img_preview12" >
                </div>
                <div class="modal-footer">
                    <a href="" target="_blank" class="btn btn-primary btn-lg btn-download" download><i class="bi bi-download"></i></a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).on('click', '.btn-preview-image img', function () {
            var src = $(this).attr('src');
            console.log(src)
            $('.img_preview12').attr('src', src);
            $('.btn-download').attr('href', src);
            $('.modal-preview-image').modal('show');
        });
        $(document).ready(function() {
            // Function to update the date dropdown based on the selected location
            function updateDates(locationId) {
                var baseUrl = '{{ action('App\Http\Controllers\ProjectController@getDateAttachments', ['project_id' => ':project_id', 'location_id' => ':location_id']) }}';
                var url = baseUrl.replace(':project_id', {{$project_id}}).replace(':location_id', locationId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        result = JSON.parse(result);
                        var $select = $('.date_select');
                        $select.empty();
                        var fisrtOption = $('<option></option>')
                            .val('0')
                            .text('Please choose date');
                        $select.append(fisrtOption);
                        $.each(result, function(index) {
                            var $option = $('<option></option>')
                                .val(result[index])
                                .text(result[index]);

                            $select.append($option);
                        });

                    }
                });
            }

            function updateAttachments(selectedDate) {
                var locationId = $('.location_select').val();
                var baseUrl = '{{ action('App\Http\Controllers\ProjectController@getFileAttachments', ['project_id' => ':project_id', 'location_id' => ':location_id', 'date' => ':date']) }}';
                var url = baseUrl.replace(':project_id', {{$project_id}}).replace(':location_id', locationId).replace(':date', selectedDate);

                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        let files = JSON.parse(result.files);
                        let images = JSON.parse(result.images);
                        console.log(images)
                        var body = $('.filesTableBody');
                        body.empty();

                        $.each(files, function(index, file) {
                            var row = $('<tr></tr>');

                            var fileNameCol = $('<td class="text-center"></td>').text(index+1);
                            var filePathCol = $('<td class="text-center"></td>').html('<a href="{{ asset('attachments/') }}/'+ $('.location_select').val() + '/'  + file.file_name + '" target="_blank">'+file.file_name+'</a>');
                            var createdAtCol = $('<td class="text-center"></td>').text(file.created_at);

                            row.append(fileNameCol);
                            row.append(filePathCol);
                            row.append(createdAtCol);

                            body.append(row);
                        });

                        let bodyImage = $('.content_image');
                        bodyImage.empty();

                        $.each(images, function(index, image) {
                            let div = $('<div></div>').addClass('col-1 text-center btn btn-preview-image');

                            let img = $('<img/>')
                                .attr('src', '{{asset('assets/img')}}'+ '/' + image.image_name)
                                .attr('alt', 'Image preview')
                                .attr('width', '75')
                                .attr('height', '75')
                                .addClass('border rounded-4');

                            let label = $('<label></label>').text(image.image_name);

                            div.append(img);
                            div.append(label);

                            bodyImage.append(div);
                        });
                    }
                });
            }

            // Event handler for location change
            $('.location_select').change(function() {
                var locationId = $(this).val();
                updateDates(locationId);
            });

            // Event handler for date selection
            $('.date_select').change(function() {
                var selectedDate = $(this).val();
                updateAttachments(selectedDate);
            });

            // Initial load
            var initialLocationId = $('.location_select').val();
            if (initialLocationId) {
                updateDates(initialLocationId);
            }
            setInterval(1000);
            var initialAttachments = $('.date_select').val();
            if (initialAttachments) {
                updateAttachments(initialAttachments);
            }
        });


        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType
        );

        const filePondConfig = {
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
                    ondata: (formData, pond) => {
                        formData.append('_token', csrfToken);
                        formData.append('project_location_id', $('.location_select').val());
                        formData.append('date', $('.date_select').val());
                        if (pond.element.id === 'fileAttachment') {
                            formData.append('type', 'file');
                        } else if (pond.element.id === 'imageAttachment') {
                            formData.append('type', 'image');
                        }
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
        };
        const fileAttachmentPond = FilePond.create(
            document.querySelector('#fileAttachment'),
            filePondConfig
        );
        const imageAttachmentPond = FilePond.create(
            document.querySelector('#imageAttachment'),
            {
                ...filePondConfig,
            }
        );

        fileAttachmentPond.addEventListener('FilePond:processfile', e => {
            const filepondUpload = document.querySelector('.filepond-upload');
            if (e.returnValue) {
                filepondUpload.classList.remove('d-none');
            }
        });

        fileAttachmentPond.addEventListener('FilePond:removefile', e => {
            const filepondUpload = document.querySelector('.filepond-upload');
            if (e.returnValue) {
                filepondUpload.classList.add('d-none');
            }
        });
    </script>
    <script src="{{asset('assets/js/upload.js')}}"></script>
@endsection
