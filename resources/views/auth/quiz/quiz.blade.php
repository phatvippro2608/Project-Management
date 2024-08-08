@extends('auth.main-edu')

@section('head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
@endsection

@section('contents')
    <div class="container mt-4">
        <div class="pagetitle">
            <h1 class="mb-4">Quiz</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="./lms">Home</a></li>
                    <li class="breadcrumb-item active">Quiz</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Thông tin nhân viên
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('path_to_default_image') }}" alt="Ảnh thí sinh" class="img-fluid mb-3">
                        <p><strong>Employee Code:</strong> 21022019</p>
                        <p><strong>Full name:</strong> Huỳnh Bảo Thắng</p>
                        <p><strong>Department:</strong> 1KMT21A</p>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body text-center">
                        <a href="{{ route('create-quiz.index') }}" class="btn btn-success">Tạo đề thi</a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        Lịch thi ngày Thứ Năm, 08/08/2024
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger text-center">
                            Hiện tại không có bài thi.
                        </div>
                        <div class="text-center">
                            <button class="btn btn-info" onclick="location.reload();">Tải lại trang</button>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-success text-white">
                        Kết quả thi
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger text-center">
                            Không có dữ liệu.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
