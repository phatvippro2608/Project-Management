@extends('auth.main')

@section('head')
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

    <div class="card">
        <div class="card-header py-0">
            <div class="card-title my-3 p-0">
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
                                <button type="button" class="btn text-primary btn-sm edit-btn" data-toggle="modal"
                                    data-target="#exampleModal" data-id="{{ $item->employee_signature_id }}">
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

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
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
                var employeeId = $(this).data('id');

                $.ajax({
                    url: '/employee/' + employeeId,
                    method: 'GET',
                    success: function(response) {
                        $('#employeeDetails').html(`
                        <p><strong>Employee Code:</strong> ${response.employee_code}</p>
                        <p><strong>Full Name:</strong> ${response.full_name}</p>
                        <p><strong>EN Name:</strong> ${response.en_name}</p>
                        <p><strong>Signature:</strong> <img src="${response.employee_signature_img}" style="height: 50px; object-fit: contain;"></p>
                    `);

                        // Hiển thị modal
                        $('#editModal').modal('show');
                    },
                    error: function() {
                        $('#employeeDetails').html(
                            '<p class="text-danger">Failed to load employee details.</p>');
                    }
                });
            });
        });
    </script>
@endsection
