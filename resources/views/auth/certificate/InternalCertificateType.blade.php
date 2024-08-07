@extends('auth.main')

@section('head')
    <style>
        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - 4rem);
        }
    </style>
@endsection

@section('contents')
    <div class="pagetitle">
        <h1>Internal Certificates Type</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Internal Certificates Type</li>
            </ol>
        </nav>
    </div>
    <div class="card">
        <div class="card-body p-3 ">
            <div class="row">
                <table class="table table-hover table-striped" id="certificateTable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Company</th>
                            <th scope="col">Certificate Name</th>
                            <th class="text-center" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($certificates as $item)
                            <tr class="">
                                <th class="text-center" scope="row">{{ $item->certificate_internal_id }}</th>
                                <td>
                                    {{ $item->certificate_body_name }} @if ($item->certificate_body_Acronym)
                                        ({{ ' ' . $item->certificate_body_Acronym }})
                                    @endif
                                </td>
                                <td>
                                    {{ $item->certificate_internal_name }}
                                    @if ($item->certificate_internal_acronym)
                                        ({{ ' ' . $item->certificate_internal_acronym }})
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button data-disciplinary="1" class="btn p-1 text-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    |
                                    <button data-disciplinary="1" class="btn p-1 text-danger" onclick="deleteCertificate(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row justify-content-center">
                <div class="col-auto d-flex gap-2">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
                        Add Company
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCertificateModal">
                        Add Certificates
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addCompanyModal" tabindex="-1" aria-labelledby="addCompanyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyModalLabel">Add Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        @csrf
                        <div class="mb-3">
                            <label for="companyName" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="companyName" placeholder="Enter company name">
                        </div>
                        <div class="mb-3">
                            <label for="companyAcronym" class="form-label">Company Acronym</label>
                            <input type="text" class="form-control" id="companyAcronym"
                                placeholder="Enter company acronym">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addCertificateModal" tabindex="-1" aria-labelledby="addCertificateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCertificateModalLabel">Add Certificate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        @csrf
                        <!-- Company Select -->
                        <div class="mb-3">
                            <label for="companySelect" class="form-label">Select Company</label>
                            <select id="companySelect" class="form-select">
                                <!-- Example options, replace these with your actual companies -->
                                <option value="">Choose a company</option>
                                <option value="1">Company A</option>
                                <option value="2">Company B</option>
                                <option value="3">Company C</option>
                            </select>
                        </div>
                        <!-- Certificate Name -->
                        <div class="mb-3">
                            <label for="certificateName" class="form-label">Certificate Name</label>
                            <input type="text" class="form-control" id="certificateName" placeholder="Enter certificate name">
                        </div>
                        <!-- Certificate Acronym -->
                        <div class="mb-3">
                            <label for="certificateAcronym" class="form-label">Certificate Acronym</label>
                            <input type="text" class="form-control" id="certificateAcronym" placeholder="Enter certificate acronym">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#certificateTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50, 75, 100],
                // "language": {
                //     "search": "Filter:",
                //     "lengthMenu": "Show _MENU_ entries"
                // }
            });
        });
        function deleteCertificate(e) {
            
        }
    </script>
@endsection
