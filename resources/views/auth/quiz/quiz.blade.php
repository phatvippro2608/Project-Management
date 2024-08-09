@extends('auth.quiz.main-quiz')
@section('contents')
    <div class="pagetitle">
        <h1>Quiz</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">LMS</li>
                <li class="breadcrumb-item active">Quiz</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                     Information
                </div>
                @php
                    $imageUrl = asset('assets/img/avt.png');
                    if($data->photo != null){
                        $imagePath = public_path($data->photo);
                        if(file_exists($imagePath)) {
                            $imageUrl = asset($data->photo);
                        }
                    }
                @endphp
                <div class="card-body text-center mt-3">
                    <img src="{{ $imageUrl }}" alt="Ảnh thí sinh" class="img-fluid mb-3 rounded-circle" style="height: 200px">
                    <p><strong>Employee Code: </strong> {{$data->employee_code}}</p>
                    <p><strong>Full name: </strong>{{$data->last_name.' '.$data->first_name}}</p>
                    <p><strong>Department: </strong>{{$data->department_name}}</p>
                </div>
            </div>

            @if($data->permission == 11)
                <div class="card mt-4">
                    <div class="card-body text-center p-3 d-flex justify-content-around">
                        <a href="{{ route('create-quiz.index') }}" class="btn btn-success">Create exam</a>
                        <a href="{{ route('question-bank.index') }}" class="btn btn-success">Question bank</a>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    Exam schedule on Thursday, August 8, 2024
                </div>
                <div class="card-body p-3">
                    <div class="alert alert-danger text-center">
                        There are currently no exams.
                    </div>
                    <div class="text-center">
                        <button class="btn btn-info" onclick="location.reload();">Reload</button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-success text-white">
                    Result
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-danger text-center">
                        No data available.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection

