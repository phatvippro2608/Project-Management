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
                        <th scope="col">Certificate Body</th>
                        <th scope="col">Certificate</th>
                        <th class="text-center" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $item)
                        <tr>
                            <td class="text-center">{{ $item->certificate_id }}</td>
                            <td>{{ $item->employee_code }}</td>
                            <td>
                                <img src="{{ $item->photo ? asset($item->photo) : asset('assets/img/avt.png') }}"
                                    class="table__img" alt="">
                            </td>
                            <td>{{ $item->last_name . ' ' . $item->first_name }}</td>
                            <td>{{ $item->en_name }}</td>
                            <td>{{ $item->certificate_body_name }}</td>
                            <td>{{ $item->certificate_type_name }}</td>
                            <td class="text-center">
                                <div class="btn-group action-btns" role="group">
                                    <button data-disciplinary="1" class="btn btn-success btn-sm"
                                        onclick="downloadCertificate(this)">
                                        <i class="bi bi-download"></i>
                                    </button>
                                    <button data-disciplinary="1" class="btn btn-primary btn-sm"
                                        onclick="editCertificate(this)">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button data-disciplinary="1" class="btn btn-danger btn-sm"
                                        onclick="deleteCertificate(this)">
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

    <script>
        $(document).ready(function() {
            $('#certificateTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true
            });
        });

        function downloadCertificate(button) {
            // Xử lý tải chứng chỉ ở đây
        }

        function editCertificate(button) {
            // Xử lý chỉnh sửa chứng chỉ ở đây
        }

        function deleteCertificate(button) {
            // Xử lý xóa chứng chỉ ở đây
        }
    </script>
@endsection
