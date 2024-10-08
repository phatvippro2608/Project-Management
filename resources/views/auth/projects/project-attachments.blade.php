@extends('auth.prm')
@section('contents')
    <div class="pagetitle">
        <h1>Project</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ action('App\Http\Controllers\ProjectController@getView') }}">Project
                        List</a></li>
                <li class="breadcrumb-item active">Project Attachments</li>
            </ol>
        </nav>
    </div>
    <div class="card p-2 mb-4 rounded-4 border">
        <div class="card-body p-2">
            <table>
                <tr>
                    <th>Contract Code:</th>
                    <td>{{$contract->contract_id}}</td>
                </tr>
                <tr>
                    <th>Company Name:</th>
                    <td>{{$company}}</td>
                </tr>
                <tr>
                    <th>Project Name:</th>
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
                <button href="" class="btn btn-info btn-lg btn-left"> <</button>
                <button href="" class="btn btn-info btn-lg btn-right"> ></button>
            </div>
        </div>
    </div>
    <div class="card p-2 rounded-4 border mt-4">
        <div class="card-header">
            <div class="card-title p-0 m-0 title-box-file">
                File attachment of day
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
                <form class="border rounded-4 p-2 text-center"
                      action="{{route('attachment-store', ['project_id', $project_id])}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="text-start">
                        <label for="date_file" class="fs-5 m-2">Choose the date of the attachment</label>
                        <input type="date" id="date_file"
                               class="form-control form-control-lg date-of-file-attachment mb-2">
                    </div>
                    <input type="file"
                           id="fileAttachment"
                           name="fileAttachment[]"
                           multiple="multiple"
                    >
                    <button class="btn btn-primary mx-auto d-none filepond-upload" type="submit"><i
                                class="bi bi-upload me-2"></i>Upload
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card p-2 rounded-4 border mt-4">
        <div class="card-header">
            <div class="card-title p-0 m-0 title-box-image">
                Image attachment of day
            </div>
        </div>
        <div class="card-body p-2">
            <div class="border rounded-4 p-3 mb-2 image-box d-none">
                <div class="row gy-3 content_image">

                </div>
            </div>
            <div class="col-lg">
                <form class="border rounded-4 p-2 text-center"
                      action="{{route('attachment-store', ['project_id', $project_id])}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="text-start">
                        <label for="date_image" class="fs-5 m-2">Choose the date of the attachment</label>
                        <input type="date" id="date_image"
                               class="form-control form-control-lg date-of-image-attachment mb-2">
                    </div>
                    <input type="file"
                           id="imageAttachment"
                           name="imageAttachment[]"
                           multiple="multiple"
                           accept="image/png, image/jpeg, image/gif" disabled>
                    <button class="btn btn-primary mx-auto d-none filepond-upload1" type="submit"><i
                                class="bi bi-upload me-2"></i>Upload
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal modal-preview-image" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Image</h5>
                </div>
                <div class="modal-body">
                    <img src="" alt="" class="w-100 h-100 img_preview12">
                </div>
                <div class="modal-footer">
                    <a href="" target="_blank" class="btn btn-primary btn-lg btn-download" download><i
                                class="bi bi-download"></i></a>
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Close</button>
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
        $(document).ready(function () {
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
                    success: function (result) {
                        result = JSON.parse(result);
                        var $select = $('.date_select');
                        $select.empty();
                        var fisrtOption = $('<option></option>')
                            .val('0')
                            .text('Please choose date');
                        $select.append(fisrtOption);
                        $.each(result, function (index) {
                            var $option = $('<option></option>')
                                .val(result[index].date)
                                .text(result[index].date);

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
                    success: function (result) {
                        let files = JSON.parse(result.files);
                        let images = JSON.parse(result.images);
                        var body = $('.filesTableBody');
                        body.empty();

                        $.each(files, function (index, file) {
                            var row = $('<tr></tr>');

                            var fileNameCol = $('<td class="text-center"></td>').text(index + 1);
                            var filePathCol = $('<td class="text-center"></td>').html('<a href="{{ asset('attachments/') }}/' + $('.location_select').val() + '/' + file.file_name + '" target="_blank">' + file.file_name + '</a>');
                            var createdAtCol = $('<td class="text-center"></td>').text(file.created_at);

                            row.append(fileNameCol);
                            row.append(filePathCol);
                            row.append(createdAtCol);

                            body.append(row);
                        });

                        let bodyImage = $('.content_image');
                        bodyImage.empty();
                        if (images.length !== 0) {
                            $.each(images, function (index, image) {
                                let div = $('<div></div>').addClass('col-1 text-center btn btn-preview-image');

                                let img = $('<img/>')
                                    .attr('src', '{{asset('attachments/')}}/' + $('.location_select').val() + '/' + image.image_name)
                                    .attr('alt', 'Image preview')
                                    .attr('width', '100')
                                    .attr('height', '100')
                                    .addClass('border rounded-4');

                                let label = $('<label></label>').text(image.image_name);

                                div.append(img);
                                div.append(label);

                                bodyImage.append(div);
                                $('.image-box').removeClass('d-none');

                            });
                        } else {
                            $('.image-box').addClass('d-none');
                        }

                    }
                });
            }

            // Event handler for location change
            $('.location_select').change(function () {
                var locationId = $(this).val();
                var body = $('.filesTableBody');
                body.empty();
                let bodyImage = $('.content_image');
                bodyImage.empty();
                updateDates(locationId);
            });

            // Event handler for date selection
            $('.date_select').change(function () {
                var selectedDate = $(this).val();
                updateAttachments(selectedDate);
                selectedDate = new Date(selectedDate);

                selectedDate = selectedDate.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
                $('.title-box-image').text('Image attachment of day ' + selectedDate);
                $('.title-box-file').text('File attachment of day ' + selectedDate);
            });
            document.querySelector('.date-of-image-attachment').addEventListener('click', function () {
                this.value = new Date().toISOString().split('T')[0];
                toggleUploadButton1();
            });
            document.querySelector('.date-of-file-attachment').addEventListener('click', function () {
                this.value = new Date().toISOString().split('T')[0];
                toggleUploadButton();
            });
            // Initial load
            var initialLocationId = $('.location_select').val();
            if (initialLocationId) {
                updateDates(initialLocationId);
            }

            var initialAttachments = $('.date_select').val();
            if (initialAttachments) {
                updateAttachments(initialAttachments);
            }
            const dateSelect = $('.date_select');

            $('.btn-left').on('click', function (event) {
                event.preventDefault();

                let currentIndex = dateSelect.prop('selectedIndex');

                let newIndex = currentIndex - 1;

                if (newIndex >= 0) {
                    dateSelect.prop('selectedIndex', newIndex).change();
                }
            });
            $('.btn-right').on('click', function (event) {
                event.preventDefault();

                let currentIndex = dateSelect.prop('selectedIndex');

                let newIndex = currentIndex + 1;

                if (newIndex < dateSelect.children('option').length) {
                    dateSelect.prop('selectedIndex', newIndex).change();
                }
            });
        });


        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType
        );

        function removeVietnameseAccents(str) {
            const accents = 'ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚÛÝàáâãèéêìíòóôõùúûý';
            const accentsOut = 'AAAAEEEIIOOOOUUUYaaaaeeeiiioooouuuuy';
            str = str.split('');
            for (let i = 0; i < str.length; i++) {
                const index = accents.indexOf(str[i]);
                if (index !== -1) {
                    str[i] = accentsOut[index];
                }
            }
            return str.join('');
        }

        const fileAttachmentPond = FilePond.create(
            document.querySelector('#fileAttachment'),
            {
                allowPdfPreview: true,
                pdfPreviewHeight: 320,
                pdfComponentExtraParams: 'toolbar=0&view=fit&page=1',
                server: {
                    process: {
                        url: '{{route('attachment-upload', ['project_id', $project_id])}}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        ondata: (formData) => {
                            if ($('.date-of-file-attachment').val().length != 0) {
                                formData.append('_token', csrfToken);
                                formData.append('project_location_id', document.querySelector('.location_select').value);
                                formData.append('date', document.querySelector('.date-of-file-attachment').value);
                                formData.append('type', 'file');

                                return formData;
                            } else {
                                toastr.error('Please choose date!', 'Error');
                            }

                        },
                    },
                    revert: {
                        url: '{{route('attachment-delete', ['project_id', $project_id])}}',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    }
                },

            }
        );

        const imageAttachmentPond = FilePond.create(
            document.querySelector('#imageAttachment'),
            {
                allowPdfPreview: true,
                pdfPreviewHeight: 320,
                pdfComponentExtraParams: 'toolbar=0&view=fit&page=1',
                server: {
                    process: {
                        url: '{{route('attachment-upload', ['project_id', $project_id])}}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        ondata: (formData) => {
                            if ($('.date-of-image-attachment').val().length != 0) {
                                formData.append('_token', csrfToken);
                                formData.append('project_location_id', document.querySelector('.location_select').value);
                                formData.append('date', document.querySelector('.date-of-image-attachment').value);
                                formData.append('type', 'image');

                                return formData;
                            } else {
                                toastr.error('Please choose date!', 'Error');
                            }
                        },
                    },
                    revert: {
                        url: '{{route('attachment-delete', ['project_id', $project_id])}}',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    }
                },
            }
        );
        const filePond = document.querySelector("#fileAttachment")
        const filepondUpload = document.querySelector('.filepond-upload')
        filePond.addEventListener('FilePond:processfile', e => {
            if (e.returnValue) {
                filepondUpload.classList.remove('d-none')
            }
        });

        filePond.addEventListener('FilePond:removefile', e => {
            if (e.returnValue) {
                filepondUpload.classList.add('d-none')
            }
        })

        const filePond1 = document.querySelector("#imageAttachment")
        const filepondUpload1 = document.querySelector('.filepond-upload1')
        filePond1.addEventListener('FilePond:processfile', e => {
            if (e.returnValue) {
                filepondUpload1.classList.remove('d-none')
            }
        });

        filePond1.addEventListener('FilePond:removefile', e => {
            if (e.returnValue) {
                filepondUpload1.classList.add('d-none')
            }
        })


        function toggleUploadButton() {
            const dateValue = $('.date-of-file-attachment').val();
            const fileInput = $('#fileAttachment');
            const uploadButton = $('.filepond-upload');

            if (dateValue.length === 0) {
                fileInput.prop('disabled', true);
                uploadButton.prop('disabled', true);
                fileAttachmentPond.setOptions({disabled: true});
            } else {
                fileInput.prop('disabled', false);
                uploadButton.prop('disabled', false);
                fileAttachmentPond.setOptions({disabled: false});
            }
        }

        function toggleUploadButton1() {
            const dateValue = $('.date-of-image-attachment').val();
            const fileInput = $('#imageAttachment');
            const uploadButton = $('.filepond-upload1');

            if (dateValue.length === 0) {
                fileInput.prop('disabled', true);
                uploadButton.prop('disabled', true);
                imageAttachmentPond.setOptions({disabled: true});
            } else {
                fileInput.prop('disabled', false);
                uploadButton.prop('disabled', false);
                imageAttachmentPond.setOptions({disabled: false});
            }
        }

        toggleUploadButton();
        toggleUploadButton1();

        $('.date-of-file-attachment').on('change', function () {
            toggleUploadButton();
        });

        $('.date-of-image-attachment').on('change', function () {
            toggleUploadButton1();
        });


    </script>
@endsection
