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
                            <th scope="col">Company Acronym</th>
                            <th scope="col">Certificate Name</th>
                            <th scope="col">Certificate Name Acronym</th>
                            <th class="text-center" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($certificates as $item)
                            <tr class="">
                                <td class="text-center">{{ $item->certificate_type_id }}</td>
                                <td>
                                    {{ $item->certificate_body_name }}
                                </td>
                                <td>
                                    @if ($item->certificate_body_Acronym)
                                        {{ ' ' . $item->certificate_body_Acronym }}
                                    @endif
                                </td>
                                <td>
                                    {{ $item->certificate_type_name }}
                                </td>
                                <td>
                                    @if ($item->certificate_type_acronym)
                                        {{ ' ' . $item->certificate_type_acronym }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button data-disciplinary="1" class="btn p-1 text-primary"
                                        onclick="editCertificate(this)">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    |
                                    <button data-disciplinary="1" class="btn p-1 text-danger"
                                        onclick="deleteCertificate(this)">
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addCertificateModal">
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
    <div class="modal fade" id="addCertificateModal" tabindex="-1" aria-labelledby="addCertificateModalLabel"
        aria-hidden="true">
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
                                @foreach ($companies as $company)
                                    <option data-acronym ="{{ $company->certificate_body_Acronym }}"
                                        class="{{ $company->certificate_body_id }}"
                                        @if ($company->certificate_body_name == 'Other') selected @endif>
                                        {{ $company->certificate_body_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="companyAcronymCertificate" class="form-label">Company Acronym</label>
                            <input type="text" class="form-control" id="companyAcronymCertificate" disabled>
                        </div>
                        <!-- Certificate Name -->
                        <div class="mb-3">
                            <label for="certificateName" class="form-label">Certificate Name</label>
                            <input type="text" class="form-control" id="certificateName"
                                placeholder="Enter certificate name">
                        </div>
                        <!-- Certificate Acronym -->
                        <div class="mb-3">
                            <label for="certificateAcronym" class="form-label">Certificate Acronym</label>
                            <input type="text" class="form-control" id="certificateAcronym"
                                placeholder="Enter certificate acronym">
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
    <div class="modal fade" id="editCertificateModal" tabindex="-1" aria-labelledby="editCertificateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCertificateModalLabel">Edit Certificate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCertificateForm">
                        @csrf
                        <input type="hidden" id="editCertificateId">
                        <div class="mb-3">
                            <label for="editCompanySelect" class="form-label">Select Company</label>
                            <select id="editCompanySelect" class="form-select">
                                @foreach ($companies as $company)
                                    <option data-acronym="{{ $company->certificate_body_Acronym }}"
                                        class="{{ $company->certificate_body_id }}">
                                        {{ $company->certificate_body_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editCompanyAcronym" class="form-label">Company Acronym</label>
                            <input type="text" class="form-control" id="editCompanyAcronym" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="editCertificateName" class="form-label">Certificate Name</label>
                            <input type="text" class="form-control" id="editCertificateName"
                                placeholder="Enter certificate name">
                        </div>
                        <div class="mb-3">
                            <label for="editCertificateAcronym" class="form-label">Certificate Acronym</label>
                            <input type="text" class="form-control" id="editCertificateAcronym"
                                placeholder="Enter certificate acronym">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateCertificateButton">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                "lengthMenu": [5, 10, 25, 50, 75, 100]
            });

            $('#editCompanySelect').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var dataAcronym = selectedOption.data('acronym');
                $('#editCompanyAcronym').val(dataAcronym);
            });

            $('#companySelect').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var dataAcronym = selectedOption.data('acronym');
                console.log(dataAcronym)
                $('#companyAcronymCertificate').val(dataAcronym);
            });

            $('#saveCompanyButton').on('click', function() {
                // Add logic to handle saving new company details
            });

            $('#saveCertificateButton').on('click', function() {
                // Add logic to handle saving new certificate details
            });

            $('#updateCertificateButton').on('click', function() {
                // Add logic to handle updating certificate details
            });
        });

        function editCertificate(button) {
            var row = $(button).closest('tr');
            var id = row.find('td').eq(0).text().trim();
            var companyId = row.find('td').eq(1).text().trim();
            var companyAcronym = row.find('td').eq(2).text().trim();
            var certificateName = row.find('td').eq(3).text().trim();
            var certificateAcronym = row.find('td').eq(4).text().trim();
            $('#editCertificateId').val(id);
            $('#editCompanySelect').val(companyId).change();
            $('#editCompanyAcronym').val(companyAcronym);
            $('#editCertificateName').val(certificateName);
            $('#editCertificateAcronym').val(certificateAcronym);
            $('#editCertificateModal').modal('show');
        }

        function deleteCertificate(button) {
            var row = $(button).closest('tr');
            var id = row.find('td').eq(0).text().trim();

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
                    console.log("Delete item with ID:", id);
                }
            });
        }
    </script>
@endsection
