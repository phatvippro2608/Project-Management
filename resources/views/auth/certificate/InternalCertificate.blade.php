@extends('auth.main')

@section('head')
    <style>
        .table__img {
            border-radius: 50%;
            width: 35px;
            height: 35px;
        }

        .action-btns .btn {
            margin: 0 2px;
        }

        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - 4rem);
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
            width: 100%;
            height: max-content;
            overflow: auto;
        }

        #pdf-viewer {
            width: 100%;
            height: 100%;
        }

        .modal-content {
            height: 80vh;
        }

        .modal-body {
            overflow-y: auto;
        }

        .separator {
            display: inline-block;
            width: 1px;
            height: 24px;
            background-color: #ddd;
            margin: 0 8px;
        }
    </style>
@endsection

@section('contents')
    <div class="pagetitle">
        <h1>Internal Certificates</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Internal Certificates</li>
            </ol>
        </nav>
    </div>
    <div class="card rounded-4 p-2 border">
        <div class="card-body p-3">
            <table class="table table-hover table-striped" id="certificateTable">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th scope="col">Employee Code</th>
                        <th scope="col">Photo</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">English Name</th>
                        {{-- <th scope="col">Certificate Body</th> --}}
                        <th scope="col">Certificate</th>
                        <th class="text-center" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $item)
                        <tr id="row-{{ $item->certificate_id }}" data-id="{{ $item->certificate_id }}"
                            data-pdf-url="{{ asset('uploads/1/' . $item->certificate) }}">
                            <td class="text-center">{{ $item->certificate_id }}</td>
                            <td>{{ $item->employee_code }}</td>
                            <td>
                                <img src="{{ $item->photo ? asset($item->photo) : asset('assets/img/avt.png') }}"
                                    class="table__img" alt="">
                            </td>
                            <td>{{ $item->last_name . ' ' . $item->first_name }}</td>
                            <td>{{ $item->en_name }}</td>
                            {{-- <td>{{ $item->certificate_body_name }}</td> --}}
                            <td>{{ $item->certificate_type_name }}</td>
                            <td class="text-center">
                                <button data-disciplinary="1" class="btn p-1 text-primary"
                                    onclick="downloadCertificate(event)">
                                    <i class="bi bi-download"></i>
                                </button>
                                |
                                <button data-disciplinary="1" class="btn p-1 text-danger"
                                    onclick="deleteCertificate(event)">
                                    <i class="bi bi-trash"></i>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            var table = $('#certificateTable').DataTable({
                language: {
                    search: ""
                },
                initComplete: function(settings, json) {
                    $('.dt-search').addClass('input-group');
                    $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
                },
                responsive: true
            });

            $('#certificateTable tbody').on('click', 'tr', function() {
                if (!$(event.target).closest('button').length) {
                    var pdfUrl = $(this).data('pdf-url');
                    if (pdfUrl) {
                        showPdfInModal(pdfUrl);
                    }
                }
            });
        });

        function showPdfInModal(pdfUrl) {
            var pdfViewer = document.getElementById('pdf-viewer');
            var loadingTask = pdfjsLib.getDocument(pdfUrl);

            loadingTask.promise.then(function(pdf) {
                pdf.getPage(1).then(function(page) {
                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    var canvas = document.getElementById('pdf-viewer');
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    page.render(renderContext);
                });
            }, function(reason) {
                console.error(reason);
            });

            $('#certificateModal').modal('show');
        }

        function downloadCertificate(event) {
            event.preventDefault();
            var pdfUrl = $(event.target).closest('tr').data('pdf-url');

            if (pdfUrl) {
                // Tạo một liên kết tạm thời
                var link = document.createElement('a');
                link.href = pdfUrl;
                link.download = pdfUrl.split('/').pop(); // Đặt tên file từ URL
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }

        function deleteCertificate(button) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var row = $(button.target).closest('tr');
                    var id = row.data('id');
                    var data = {
                        id: id
                    };
                    $.ajax({
                        url: '/certificate',
                        type: 'DELETE',
                        contentType: 'application/json',
                        data: JSON.stringify(data),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'The certificate has been deleted.',
                                'success'
                            );
                            row.remove();
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the certificate.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
@endsection
