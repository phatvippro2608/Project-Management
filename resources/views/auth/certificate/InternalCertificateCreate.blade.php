@extends('auth.main')

@section('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"
        integrity="sha512-Z8CqofpIcnJN80feS2uccz+pXWgZzeKxDsDNMD/dJ6997/LSRY+W4NmEt9acwR+Gt9OHN0kkI1CTianCwoqcjQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
            <div class="btn btn-primary mx-2 btn-add">
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
                        <th class="text-center" scope="col">Certificate</th>
                        <th class="text-center" scope="col">status</th>
                        <th scope="col">Created</th>
                        <th class="text-center" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $index => $item)
                        <tr>
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

                            </td>
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
                            <td class="text-center" scope="col">
                                <i class="bi bi-person-fill-add"></i>
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
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#certificateTable').DataTable();

        });
    </script>
@endsection
