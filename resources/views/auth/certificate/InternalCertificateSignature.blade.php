@extends('auth.main')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        #signatureTable tbody td {
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
                    Add Certificate
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
                        <th class="text-center" scope="col">Action</th>
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
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
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
                            <label for="employeeId" class="form-label">Employee ID</label>
                            <input type="text" class="form-control" id="employeeId" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullName" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="enName" class="form-label">EN Name</label>
                            <input type="text" class="form-control" id="enName" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="signature" class="form-label">Signature</label>
                            <div class="d-flex flex-column align-items-center">
                                <img id="signaturePreview" src="" alt="Signature Image" class="img-thumbnail mb-2"
                                    style="max: 100px; object-fit: contain;">
                                <input type="file" id="signatureInput" class="form-control mt-2">
                            </div>
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

            $('.edit-btn').on('click', function() {
                // Lấy dữ liệu từ các thuộc tính data- của nút bấm
                var employeeId = $(this).data('id');
                var employeeCode = $(this).data('employee_code');
                var fullName = $(this).data('full_name');
                var enName = $(this).data('en_name');
                var signature = $(this).data('signature');

                // Cập nhật các trường input trong modal
                $('#employeeId').val(employeeCode);
                $('#fullName').val(fullName);
                $('#enName').val(enName);
                $('#signaturePreview').attr('src', signature);

                // Hiển thị modal
                $('#editModal').modal('show');
            });

            $('#saveBtn').on('click', function() {
                // Xử lý lưu thay đổi nếu cần
                Swal.fire({
                    title: 'Success',
                    text: 'Changes saved successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });

            $('#signatureInput').on('change', function(event) {
                var file = event.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#signaturePreview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
