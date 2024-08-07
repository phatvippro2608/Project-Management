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
    <div class="card">
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
                        <tr data-pdf-url="{{ asset('uploads/1/' . $item->certificate) }}">
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
                                <div class="btn-group action-btns" role="group">
                                    <button data-disciplinary="1" class="btn btn-success btn-sm"
                                        onclick="downloadCertificate(event)">
                                        <i class="bi bi-download"></i>
                                    </button>
                                    <button data-disciplinary="1" class="btn btn-primary btn-sm"
                                        onclick="editCertificate(event)">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button data-disciplinary="1" class="btn btn-danger btn-sm"
                                        onclick="deleteCertificate(event)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
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

    <script>
        $(document).ready(function() {
            var table = $('#certificateTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true
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
        }

        function editCertificate(event) {
            event.preventDefault();
        }

        function deleteCertificate(event) {
            event.preventDefault();
        }
    </script>
@endsection
