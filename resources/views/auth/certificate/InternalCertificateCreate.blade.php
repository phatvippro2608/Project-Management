@extends('auth.main')

@section('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/certificateFonts.css') }}">

    <style>
        .pr-30 {
            padding-right: 30px !important;
        }

        .edit-btn {
            border: none;
            background: transparent;
            cursor: pointer;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .edit-btn i {
            font-size: 1.25rem;
            color: var(--bs-secondary);
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .edit-btn:hover i {
            color: var(--bs-primary);
            transform: rotate(90deg);
        }

        .btn-close-custom {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 1.5rem;
        }

        .btn-close-custom:hover {
            color: #ff6b6b;
        }

        .pdf-container {
            height: 100%;
        }

        #pdf-viewer {
            width: 100%;
            height: 100%;
        }

        #employeeResults {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            z-index: 1000;
            padding: 0;
            margin: 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #employeeResults li {
            cursor: pointer;
            padding: 8px;
        }

        #employeeResults li:hover {
            background-color: #f1f1f1;
        }
    </style>
@endsection

@section('contents')
    <div class="pagetitle">
        <h1>Internal Certificates Create</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Certificates</a></li>
                <li class="breadcrumb-item active">Internal Certificates Create</li>
            </ol>
        </nav>
    </div>

    <div class="row gx-3 my-3">
        <div class="col-md-6 m-0">
            <div class="btn btn-primary mx-2" id="viewAddCertificate">
                <div class="d-flex align-items-center">
                    <i class="bi bi-file-earmark-plus-fill pe-2"></i>
                    Create Certificate
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header py-0">
            <div class="card-title m-3 p-0">
                List of Certificate
            </div>
        </div>
        <div class="card-body">
            <table id="certificateTable" class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Photo</th>
                        <th class="text-center" scope="col">Employee ID</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">EN Name</th>
                        <th scope="col">Title</th>
                        <th class="text-center" scope="col">status</th>
                        <th scope="col">Created</th>
                        <th class="text-center" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $index => $item)
                        <tr data-id="{{ $item->certificate_create_id }}">
                            <td class="pr-30 text-center">{{ $index + 1 }}</td>
                            <td class="pr-30 text-center">
                                <img style="width: 50px; height: 50px; object-fit: cover;" class="rounded-circle"
                                    src="{{ empty($item->photo) ? asset('uploads/1/1219654976041648230.gif') : asset($item->photo) }}"
                                    alt="{{ $item->first_name }}'s photo">
                            </td>
                            <td class="pr-30 text-center">{{ $item->employee_code }}</td>
                            <td>{{ $item->last_name . ' ' . $item->first_name }}</td>
                            <td>{{ $item->en_name }}</td>
                            <td>{{ $item->certificate_create_title }}</td>
                            <td class="pr-30 text-center">
                                @if ($item->certificate_create_status == '0')
                                    Waitting
                                @elseif ($item->certificate_create_status == '1')
                                    Approve
                                @else
                                    Refuse
                                @endif
                            </td>
                            <td>
                                @php
                                    $updatedAt = \Carbon\Carbon::parse($item->created_at);
                                    $now = \Carbon\Carbon::now();
                                    $difference = $updatedAt->diff($now);
                                @endphp
                                @if ($difference->y > 0)
                                    {{ $difference->y }} years ago
                                @elseif ($difference->m > 0)
                                    {{ $difference->m }} months ago
                                @elseif ($difference->d > 0)
                                    {{ $difference->d }} days ago
                                @elseif ($difference->h > 0)
                                    {{ $difference->h }} hours ago
                                @elseif ($difference->i > 0)
                                    {{ $difference->i }} minutes ago
                                @else
                                    now
                                @endif
                            </td>
                            <td class="pr-30 text-center">
                                <button class="btn btn-outline-primary btn-sm addUserBtn">
                                    <i class="bi bi-person-fill-add"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="certificateModal" tabindex="-1" aria-labelledby="certificateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title" id="certificateModalLabel">Certificate Details</h5>
                    <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="pdf-container">
                        <canvas id="pdf-viewer"></canvas>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnAprove" class="btn btn-success" data-bs-dismiss="modal">Aprove</button>
                    <button type="button" id="btnRefuse" class="btn btn-danger" data-bs-dismiss="modal">Refuse</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCertificateModal" tabindex="-1" aria-labelledby="addCertificateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title" id="addCertificateModalLabel">Create New Certificate</h5>
                    <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addCertificateForm">
                        <div class="mb-3">
                            <label for="certificateTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="certificateTitle" name="certificateTitle"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="certificateDes" class="form-label">Description</label>
                            <input type="text" class="form-control" id="certificateDes" name="certificateDescription"
                                required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="certificateLSig" class="form-label">Director Signature</label>
                                    <select class="form-control" id="certificateLSig" name="certificateLSig" required>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 position-relative">
                                    <label for="certificateRSig" class="form-label">Employee Signature</label>
                                    <input type="text" class="form-control" id="certificateRSig"
                                        name="certificateRSig" required>
                                    <ul id="employeeResults" class="list-group position-absolute w-100 d-none"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <canvas id="certificateCanvas" style="width: 100%;"></canvas>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveCertificate">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('#viewAddCertificate').click(function() {
                $('#certificateTitle').val('');
                $('#certificateDes').val('');

                $.ajax({
                    url: '{{ route('certificate.create.leftSignature') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        var html =
                            `<option value="" disabled Selected>Select Left Signature</option>`;
                        if (Array.isArray(response.director)) {
                            response.director.forEach(function(element) {
                                html +=
                                    `<option data-img="${element.employee_signature_img}" 
                                    data-name="${element.last_name} ${element.first_name}"
                                    value="${element.employee_id}">${element.employee_code} - ${element.last_name} ${element.first_name}`;
                                if (element.en_name) {
                                    html += ` - ${element.en_name}`;
                                }
                                html += `</option>`;
                            });
                        }
                        $('#certificateLSig').html(html);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX request failed:', error);
                    }
                });

                $('#addCertificateModal').modal('show');
            });

            var canvas = document.getElementById('certificateCanvas');
            var context = canvas.getContext('2d');
            var img = new Image();
            var signatureImgLeft = new Image();
            var signatureImgRight = new Image();
            var signatureLoadedLeft = false;
            var signatureLoadedRight = false;
            var signatureCanvasLeft = document.createElement('canvas');
            var signatureCanvasRight = document.createElement('canvas');
            var signatureHeight = canvas.height * 30 / 8;
            var signaturePositionLeft = {
                x: (canvas.width * 78 / 64),
                y: signatureHeight,
                width: 150,
                height: 75
            };
            var signaturePositionRight = {
                x: (canvas.width * 88 / 32),
                y: signatureHeight,
                width: 150,
                height: 75
            };
            var namedescription = '';
            var description = '';
            var signatureLeftSrc = '';
            var leftName = '';
            var signatureRightSrc = '';
            var rightName = '';

            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;
                context.drawImage(img, 0, 0, canvas.width, canvas.height);
                document.fonts.ready.then(function() {
                    drawCertificate();
                });
            };

            img.src = '{{ asset('assets/img/certificate.png') }}';

            function drawCertificate() {
                context.clearRect(0, 0, canvas.width, canvas.height);
                context.drawImage(img, 0, 0, canvas.width, canvas.height);
                context.fillStyle = "black";
                context.textAlign = "center";

                if (namedescription) {
                    context.font = "bold 34px 'Lora', serif";
                    context.fillText(namedescription.toUpperCase(), canvas.width / 2, canvas.height * 23 / 64);
                }

                if (description) {
                    var lineHeight = 16;
                    var maxWidth = canvas.width * 18 / 32;
                    context.font = "13px 'Lora', serif";
                    wrapText(context, description, canvas.width / 2, canvas.height * 10 / 16, maxWidth, lineHeight);
                }

                if (signatureLoadedLeft) {
                    context.drawImage(signatureCanvasLeft, signaturePositionLeft.x, signaturePositionLeft.y,
                        signaturePositionLeft.width, signaturePositionLeft.height);
                }

                if (leftName) {
                    var height = canvas.height * 14 / 16;
                    var leftWidth = canvas.width * 21 / 64;
                    context.font = "bold 11px 'Lora', serif";
                    context.fillText(leftName, leftWidth, height);
                }

                if (signatureLoadedRight) {
                    context.drawImage(signatureCanvasRight, signaturePositionRight.x, signaturePositionRight.y,
                        signaturePositionRight.width, signaturePositionRight.height);
                }

                if (rightName) {
                    var height = canvas.height * 14 / 16;
                    var rightWidth = canvas.width * 42 / 64;
                    context.font = "bold 11px 'Lora', serif";
                    context.fillText(rightName, rightWidth, height);
                }
            }

            $('#certificateTitle').on('input', function() {
                namedescription = `of ${$(this).val()}`;
                drawCertificate();
            });

            $('#certificateDes').on('input', function() {
                description = $(this).val();
                drawCertificate();
            });

            var maxChars = 400;

            function wrapText(context, text, x, y, maxWidth, lineHeight) {
                if (text.length > maxChars) {
                    text = text.substring(0, maxChars);
                }
                var words = text.split(' ');
                var line = '';
                var lines = [];

                for (var n = 0; n < words.length; n++) {
                    var testLine = line + words[n] + ' ';
                    var metrics = context.measureText(testLine);
                    var testWidth = metrics.width;

                    if (testWidth > maxWidth && n > 0) {
                        lines.push(line);
                        line = words[n] + ' ';
                    } else {
                        line = testLine;
                    }
                }
                lines.push(line);

                for (var i = 0; i < lines.length; i++) {
                    context.fillText(lines[i], x, y + (i * lineHeight));
                }
            };

            $('#certificateLSig').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var signatureLeftSrc = selectedOption.data('img');
                if (signatureLeftSrc) {
                    signatureImgLeft.src = `{{ asset('${signatureLeftSrc}') }}`;
                }

                leftName = selectedOption.data('name');
                drawCertificate();
            });

            signatureImgLeft.onload = function() {
                signatureCanvasLeft.width = signatureImgLeft.width;
                signatureCanvasLeft.height = signatureImgLeft.height;
                var tempContext = signatureCanvasLeft.getContext('2d');
                tempContext.drawImage(signatureImgLeft, 0, 0);
                signatureLoadedLeft = true;
                drawCertificate();
            };

            $('#certificateRSig').on('input', function() {
                var employeeValue = $(this).val();

                if (employeeValue.length > 2) {
                    $.ajax({
                        url: '{{ route('certificate.signature.search.HasSig') }}',
                        type: 'POST',
                        data: JSON.stringify({
                            employeeValue: employeeValue
                        }),
                        contentType: 'application/json',
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            var html = '';
                            if (response.employees.length > 0) {
                                html = '';
                                $.each(response.employees, function(index, employee) {
                                    html +=
                                        `<li class="list-group-item list-group-item-action" 
                                        data-img="${employee.employee_signature_img}" 
                                        data-name="${employee.last_name} ${employee.first_name}">`;
                                    html +=
                                        `${employee.employee_code} - ${employee.last_name} ${employee.first_name}`;
                                    if (employee.en_name) {
                                        html += ` - ${employee.en_name}`
                                    }
                                    html += '</li>';
                                });
                                $('#employeeResults').html(html).removeClass('d-none');
                            } else {
                                $('#employeeResults').html(
                                        '<li class="list-group-item">No employees found.</li>')
                                    .removeClass('d-none');
                            }
                        }
                    });
                } else {
                    $('#employeeResults').addClass('d-none');
                }
            });

            $(document).on('click', '#employeeResults li', function() {
                var selectedText = $(this).text();
                signatureRightSrc = $(this).data('img');
                $('#certificateRSig').val(selectedText);
                $('#employeeResults').addClass('d-none');
                console.log(signatureRightSrc)
                if (signatureRightSrc) {
                    signatureImgRight.src = `{{ asset('${signatureRightSrc}') }}`;
                }
                rightName = $(this).data('name');
                drawCertificate();

            });

            signatureImgRight.onload = function() {
                signatureCanvasRight.width = signatureImgRight.width;
                signatureCanvasRight.height = signatureImgRight.height;
                var tempContext = signatureCanvasRight.getContext('2d');
                tempContext.drawImage(signatureImgRight, 0, 0);
                signatureLoadedRight = true;
                drawCertificate();
            };
            // lưu ảnh
            $('#saveCertificate').on('click', function() {

            })

            $('#certificateTable').DataTable();

            //chưa viết
            $(document).on('click', '.addUserBtn', function(event) {

            });


            $('#certificateTable tbody').on('click', 'tr', function(event) {
                if (!$(event.target).closest('.addUserBtn').length) {
                    var idCertificate = $(this).data('id');
                    $.ajax({
                        url: '{{ route('certificate.create.load') }}',
                        type: 'POST',
                        data: {
                            id: idCertificate
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.imgCertificate) {
                                var imgUrl = response.imgCertificate.certificate_create_img;
                                var loadingTask = pdfjsLib.getDocument(imgUrl);
                                loadingTask.promise.then(function(pdf) {
                                    pdf.getPage(1).then(function(page) {
                                        var scale = 1;
                                        var viewport = page.getViewport({
                                            scale: scale
                                        });
                                        var canvas = document.getElementById(
                                            'pdf-viewer');
                                        var context = canvas.getContext('2d');
                                        canvas.height = viewport.height;
                                        canvas.width = viewport.width;
                                        var renderContext = {
                                            canvasContext: context,
                                            viewport: viewport
                                        };
                                        var renderTask = page.render(
                                            renderContext);
                                    });
                                }, function(reason) {
                                    console.error(reason);
                                });
                            }

                            $('#certificateModal').modal('show');
                            $('#certificateModal #btnAprove').data('id', idCertificate);
                            $('#certificateModal #btnRefuse').data('id', idCertificate);
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX request failed:', error);
                        }
                    });
                }
            });

            $('#certificateModal #btnAprove').click(function() {
                var idCertificate = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        loadStatusCertificate(idCertificate, 1);
                    }
                });
            });

            $('#certificateModal #btnRefuse').click(function() {
                var idCertificate = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, refuse it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        loadStatusCertificate(idCertificate, 2);
                    }
                });
            });

            function loadStatusCertificate(idCertificate, status) {
                $.ajax({
                    url: '{{ route('certificate.create.status') }}',
                    type: 'POST',
                    data: {
                        id: idCertificate,
                        status: status
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire(
                            status === 1 ? 'Approved!' : 'Refused!',
                            status === 1 ? 'The certificate has been approved.' :
                            'The certificate has been refused.',
                            'success'
                        ).then(() => {
                            location.reload(); // Tải lại trang
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX request failed:', error);
                    }
                });
            }
        });
    </script>
@endsection
