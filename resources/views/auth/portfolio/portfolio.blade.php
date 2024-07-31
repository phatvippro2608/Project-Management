@extends('auth.main')

@section('head')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        /* Tắt chọn văn bản cho một phần cụ thể */
        .no-select {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        /* Đổi màu nền khi hover trên hàng */
        .table-hover tr:hover {
            background-color: #f5f5f5;
            cursor: pointer; /* Thay đổi con trỏ để chỉ định hàng có thể nhấp được */
        }
    </style>
@endsection

@section('contents')
    <div class="pagetitle">
        <h1>Portfolio</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Portfolio</li>
            </ol>
        </nav>
    </div>
    <div class="content col-11 m-auto">
        <table id="table" class="table table-hover table-striped">
            <thead>
                <tr>
                    <th data-field="employee_code">Employee Code</th>
                    <th data-field="photo">Photo</th>
                    <th data-field="full_name">Full Name</th>
                    <th data-field="en_name">English Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sql as $item)
                    <tr data-href="{{ route('portfolio.id', ['id' => $item->employee_code]) }}">
                        <td>{{ $item->employee_code }}</td>
                        <td>
                            <img src="{{ $item->photoExists ? asset($item->photo) : asset('assets/img/avt.png') }}"
                                style="width: 50px; height: 50px; border-radius: 50%" alt="">
                        </td>
                        <td>{{ $item->last_name . ' ' . $item->first_name }}</td>
                        <td>{{ $item->en_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                "paging": true,
                "searching": true,
                "info": true,
                "autoWidth": true
            });

            // Sự kiện click trên hàng
            $('#table').on('click', 'tr', function() {
                var url = $(this).data('href');
                if (url) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endsection
