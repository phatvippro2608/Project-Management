@extends('auth.main')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        #signatureTable tbody td {
            padding-right: 30px;
        }

        .pr-30 {
            padding-right: 30px;
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

        #signaturePreview {
            max-width: 100%;
            max-height: 100px;
            object-fit: contain;
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
        <h1>Internal Certificates Signature</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Internal Certificates Signature</li>
            </ol>
        </nav>
    </div>

    <div class="row gx-3 my-3">
        <div class="col-md-6 m-0">
            <div class="btn btn-primary mx-2 btn-add">
                <div class="d-flex align-items-center">
                    <i class="bi bi-file-earmark-plus-fill pe-2"></i>
                    Add Signature
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header py-0">
            <div class="card-title m-3 p-0">
                List of Signatures
            </div>
        </div>
        <div class="card-body">
            <table id="signatureTable" class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Employee ID</th>
                        <th class="text-center" scope="col">Photo</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">EN Name</th>
                        <th class="text-center" scope="col">Signature</th>
                        <th scope="col">Updated</th>
                        @if ($btnEdit)
                            <th class="text-center" scope="col">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employee as $index => $item)
                        <tr>
                            <td class="text-center" scope="row">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $item->employee_code }}</td>
                            <td class="text-center">
                                <img style="width: 50px; height: 50px; object-fit: cover;" class="rounded-circle"
                                    src="{{ empty($item->photo) ? asset('uploads/1/1219654976041648230.gif') : asset($item->photo) }}"
                                    alt="{{ $item->first_name }}'s photo">
                            </td>
                            <td>{{ $item->last_name . ' ' . $item->first_name }}</td>
                            <td>{{ $item->en_name }}</td>
                            <td class="text-center">
                                <img style="height: 50px; object-fit: contain;"
                                    src="{{ asset($item->employee_signature_img) }}"
                                    alt="{{ $item->first_name }}'s signature">
                            </td>
                            <td>
                                @php
                                    $updatedAt = \Carbon\Carbon::parse($item->updated_at);
                                    $now = \Carbon\Carbon::now();
                                    $difference = $updatedAt->diff($now);
                                @endphp

                                @if ($difference->m < 1)
                                    @if ($difference->d > 0)
                                        {{ $difference->d }} days ago
                                    @elseif ($difference->h > 0)
                                        {{ $difference->h }} hours ago
                                    @elseif ($difference->i > 0)
                                        {{ $difference->i }} minutes ago
                                    @else
                                        now
                                    @endif
                                @else
                                    {{ $updatedAt->format('Y-m-d') }}
                                @endif

                            </td>
                            @if ($btnEdit)
                                <td class="text-center">
                                    <button class="btn text-primary btn-sm edit-btn"
                                        data-id="{{ $item->employee_signature_id }}"
                                        data-employee_code="{{ $item->employee_code }}"
                                        data-full_name="{{ $item->last_name . ' ' . $item->first_name }}"
                                        data-en_name="{{ $item->en_name }}"
                                        data-signature="{{ asset($item->employee_signature_img) }}">
                                        <i class="bi bi-gear-fill"></i>
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No signatures available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    @if ($btnEdit)
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header justify-content-between">
                        <h5 class="modal-title" id="editModalLabel">Edit Employee Signature</h5>
                        <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            @csrf
                            <div class="mb-3">
                                <label for="employeeId" class="form-label">Employee</label>
                                <input type="text" class="form-control" id="employeeId" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="signature" class="form-label">Signature</label>
                                <div class="d-flex flex-column align-items-center">
                                    <img id="signaturePreview" src="" alt="Signature Image"
                                        class="img-thumbnail mb-2" style="max-height: 150px; object-fit: contain;">
                                    <canvas id="signatureCanvas" style="display:none;"></canvas>
                                    <input type="file" id="signatureInput" class="form-control mt-2">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="historyTable" class="form-label">History Edit</label>
                                <table id="historyTable" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Signature Image</th>
                                            <th>Photo</th>
                                            <th>Employee ID</th>
                                            <th>Updated</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Dữ liệu sẽ được chèn vào đây bằng JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveBtn">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="addSignatureModal" tabindex="-1" aria-labelledby="addSignatureModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title" id="addSignatureModalLabel">Add New Signature</h5>
                    <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addSignatureForm">
                        @csrf
                        <div class="mb-3 position-relative">
                            <label for="employee" class="form-label">Employee</label>
                            <input type="text" class="form-control" id="employee" name="employee" required>
                            <ul id="employeeResults" class="list-group position-absolute w-100 d-none"></ul>
                        </div>
                        <div class="mb-3">
                            <label for="signatureImage" class="form-label">Signature Image</label>
                            <div class="d-flex flex-column align-items-center">
                                <img id="signatureAddPreview" src="" alt="Signature Image"
                                    class="img-thumbnail mb-2 d-none" style="max-height: 150px; object-fit: contain;">
                                <canvas id="addSignatureCanvas" style="display:none;"></canvas>
                                <input type="file" id="signatureImage" class="form-control mt-2" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveSignatureBtn">Save Signature</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#signatureTable').DataTable({
                paging: true,
                searching: true,
                info: true,
                lengthChange: true
            });

            function calculateTimeDifference(updatedAt) {
                var updatedDate = new Date(updatedAt);
                var now = new Date();
                var diff = now - updatedDate;

                var diffMinutes = Math.floor(diff / (1000 * 60));
                var diffHours = Math.floor(diff / (1000 * 60 * 60));
                var diffDays = Math.floor(diff / (1000 * 60 * 60 * 24));
                var diffMonths = Math.floor(diff / (1000 * 60 * 60 * 24 * 30));

                if (diffMonths > 0) {
                    return updatedDate.toISOString().split('T')[0];
                } else if (diffDays > 0) {
                    return `${diffDays} days ago`;
                } else if (diffHours > 0) {
                    return `${diffHours} hours ago`;
                } else if (diffMinutes > 0) {
                    return `${diffMinutes} minutes ago`;
                } else {
                    return 'now';
                }
            }

            $('.edit-btn').on('click', function() {
                var signatureID = $(this).data('id');
                var employeeCode = $(this).data('employee_code');
                var signature = $(this).data('signature');
                var employee =
                    `${employeeCode} - ${$(this).data('full_name')} - ${$(this).data('en_name')}`;

                $('#employeeId').val(employee);
                $('#signaturePreview').attr('src', signature);
                $('#signatureInput').val('');

                $('#saveBtn').data('employee', employeeCode);
                $('#saveBtn').data('id', signatureID);

                $.ajax({
                    url: '{{ route('certificate.signature.load') }}',
                    type: 'POST',
                    data: JSON.stringify({
                        employeeCode: employeeCode
                    }),
                    contentType: 'application/json',
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response.history);
                        var historyData = response.history.map(function(signature) {
                            return [
                                `<img src="${signature.employee_signature_img}" style="height: 50px; margin-right: 10px;" alt="">`,
                                `<img src="${signature.creator_photo}" style="height: 30px; margin-right: 10px;" class="rounded-circle"  alt="">`,
                                `${signature.creator_code}`,
                                `${calculateTimeDifference(signature.created_at)}`
                            ];
                        });

                        // Khởi tạo DataTable
                        $('#historyTable').DataTable({
                            data: historyData,
                            destroy: true,
                            columnDefs: [{
                                targets: [0, 2],
                                className: 'text-center pr-30'
                            }],
                            pageLength: 3,
                            lengthChange: false,
                        });
                    }
                });

                $('#editModal').modal('show');
            });

            $('#saveBtn').on('click', function() {
                var employeeId = $(this).data('id');
                var signature = $('#signatureInput')[0].files[0];

                if (!signature) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Please select a signature image.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });

            function sharpen(ctx, width, height, mix) {
                var weights = [0, -1, 0, -1, 5, -1, 0, -1, 0],
                    katet = Math.round(Math.sqrt(weights.length)),
                    half = (katet * 0.5) | 0,
                    dstData = ctx.createImageData(width, height),
                    dstBuff = dstData.data,
                    srcBuff = ctx.getImageData(0, 0, width, height).data,
                    y = height;

                while (y--) {
                    var x = width;
                    while (x--) {
                        var sy = y,
                            sx = x,
                            dstOff = (y * width + x) * 4,
                            r = 0,
                            g = 0,
                            b = 0,
                            a = 0;

                        for (var cy = 0; cy < katet; cy++) {
                            for (var cx = 0; cx < katet; cx++) {
                                var scy = sy + cy - half;
                                var scx = sx + cx - half;

                                if (scy >= 0 && scy < height && scx >= 0 && scx < width) {
                                    var srcOff = (scy * width + scx) * 4;
                                    var wt = weights[cy * katet + cx];
                                    r += srcBuff[srcOff] * wt;
                                    g += srcBuff[srcOff + 1] * wt;
                                    b += srcBuff[srcOff + 2] * wt;
                                    a += srcBuff[srcOff + 3] * wt;
                                }
                            }
                        }

                        dstBuff[dstOff] = r * mix + srcBuff[dstOff] * (1 - mix);
                        dstBuff[dstOff + 1] = g * mix + srcBuff[dstOff + 1] * (1 - mix);
                        dstBuff[dstOff + 2] = b * mix + srcBuff[dstOff + 2] * (1 - mix);
                        dstBuff[dstOff + 3] = srcBuff[dstOff + 3];
                    }
                }

                ctx.putImageData(dstData, 0, 0);
            }

            $('#signatureInput').on('change', function(event) {
                var file = event.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = new Image();
                        img.src = e.target.result;

                        img.onload = function() {
                            var canvas = document.getElementById('signatureCanvas');
                            var ctx = canvas.getContext('2d');

                            canvas.width = img.width;
                            canvas.height = img.height;
                            ctx.drawImage(img, 0, 0);

                            // Kiểm tra xem có điểm nào trong suốt không
                            var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                            var data = imageData.data;
                            var hasTransparentPixel = false;

                            for (var i = 3; i < data.length; i += 4) {
                                if (data[i] < 255) {
                                    hasTransparentPixel = true;
                                    break;
                                }
                            }

                            if (hasTransparentPixel) {
                                // Nếu hình ảnh đã có nền trong suốt, không cần xử lý thêm
                                $('#signaturePreview').attr('src', img.src);
                            } else {
                                // Nếu không có nền trong suốt, xử lý để tạo nền trong suốt
                                ctx.drawImage(img, 0, 0);
                                for (var i = 0; i < data.length; i += 4) {
                                    // Nếu điểm ảnh là màu trắng, thì làm cho nó trong suốt
                                    if (data[i] > 200 && data[i + 1] > 200 && data[i + 2] > 200) {
                                        data[i + 3] = 0;
                                    }
                                }

                                sharpen(ctx, canvas.width, canvas.height, 1.0);
                                ctx.putImageData(imageData, 0, 0);

                                var transparentImage = canvas.toDataURL('image/png');
                                $('#signaturePreview').attr('src', transparentImage);
                            }
                            $('#saveBtn').on('click', function() {
                                var signatureId = $(this).data('id');
                                // var employeeId = $('#employeeId').val();
                                var employeeId = $(this).data('employee');
                                var data = {
                                    signatureId,
                                    employeeId,
                                    signature: $('#signaturePreview').attr('src')
                                };
                                $.ajax({
                                    url: '{{ route('certificate.signature.edit') }}',
                                    type: 'POST',
                                    data: JSON.stringify(data),
                                    contentType: 'application/json',
                                    processData: false,
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            title: 'Success',
                                            text: 'Changes saved successfully!',
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        }).then(function() {
                                            window.location.reload();
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'There was an error saving the changes.',
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                })
                            })
                        };
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('#signatureImage').on('change', function(event) {
                const file = event.target.files[0];
                const reader = new FileReader();

                if (file) {
                    reader.onload = function(e) {
                        $('#signatureAddPreview').attr('src', e.target.result).removeClass('d-none');
                        var img = new Image();
                        img.src = e.target.result;

                        img.onload = function() {
                            var canvas = document.getElementById('addSignatureCanvas');
                            var ctx = canvas.getContext('2d');

                            canvas.width = img.width;
                            canvas.height = img.height;
                            ctx.drawImage(img, 0, 0);

                            // Kiểm tra xem có điểm nào trong suốt không
                            var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                            var data = imageData.data;
                            var hasTransparentPixel = false;

                            for (var i = 3; i < data.length; i += 4) {
                                if (data[i] < 255) {
                                    hasTransparentPixel = true;
                                    break;
                                }
                            }

                            if (!hasTransparentPixel) {
                                // Nếu không có nền trong suốt, xử lý để tạo nền trong suốt
                                for (var i = 0; i < data.length; i += 4) {
                                    // Nếu điểm ảnh là màu trắng, thì làm cho nó trong suốt
                                    if (data[i] > 200 && data[i + 1] > 200 && data[i + 2] > 200) {
                                        data[i + 3] = 0;
                                    }
                                }

                                ctx.putImageData(imageData, 0, 0);
                                var transparentImage = canvas.toDataURL('image/png');
                                $('#signatureAddPreview').attr('src', transparentImage);
                            }
                        };
                    };

                    reader.readAsDataURL(file);
                } else {
                    $('#signatureAddPreview').addClass('d-none');
                }
            });

            $('.btn-add').on('click', function() {
                $('#employee').val('');
                $('#signatureAddPreview').attr('src', '').addClass('d-none');
                $('#signatureImage').val('');
                $('#employeeResult').empty();
                $('#addSignatureModal').modal('show');
            });

            $('#employee').on('input', function() {
                var employeeValue = $(this).val();

                if (employeeValue.length > 2) {
                    $.ajax({
                        url: '{{ route('certificate.signature.search') }}',
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
                                        `<li class="list-group-item list-group-item-action" data-id="${employee.employee_code}">`;
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
                var selectedId = $(this).data('id');
                $('#employee').val(selectedText);
                $('#employee').data('id', selectedId);
                $('#employeeResults').addClass('d-none');
            });

            $('#saveSignatureBtn').on('click', function() {
                var canvas = document.getElementById('addSignatureCanvas');
                var imageData = canvas.toDataURL('image/png');
                var data = {
                    img: imageData,
                    employee: $('#employee').data('id')
                }
                $.ajax({
                    url: '{{ route('certificate.signature.add') }}',
                    type: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                            .attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Signature saved successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while saving the signature.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

        });
    </script>
@endsection
