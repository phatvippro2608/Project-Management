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
                    if ($data->photo != null) {
                        $imagePath = public_path($data->photo);
                        if (file_exists($imagePath)) {
                            $imageUrl = asset($data->photo);
                        }
                    }
                @endphp
                <div class="card-body text-center mt-3">
                    <img src="{{ $imageUrl }}" alt="Ảnh thí sinh" class="img-fluid mb-3 rounded-circle"
                        style="height: 200px">
                    <p><strong>Employee Code: </strong> {{ $data->employee_code }}</p>
                    <p><strong>Full name: </strong>{{ $data->last_name . ' ' . $data->first_name }}</p>
                    <p><strong>Department: </strong>{{ $data->department_name }}</p>
                </div>
            </div>

            @if ($data->permission == 11)
                <div class="card mt-4">
                    <div class="card-body text-center p-3 d-flex justify-content-around">
                        <a href="{{ route('exams.index') }}" class="btn btn-success">Create exam</a>
                        <a href="{{ route('question-bank.index') }}" class="btn btn-success">Question bank</a>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                @php
                    use Carbon\Carbon;
                    $currentDate = Carbon::now();
                    $formattedDate = $currentDate->format('l, F j, Y');
                @endphp

                <div class="card-header bg-primary text-white">
                    Exam schedule on {{ $formattedDate }}
                </div>
                <div class="card-body p-3">
                    <div id="examContainer">
                        @if (isset($completed_courses) && count($completed_courses) > 0)
                            @foreach ($completed_courses as $course)
                                @php
                                    $exam_result = $exam_results->firstWhere('course_name', $course->course_name);
                                @endphp
                                @if (!$exam_result)
                                    <div class="alert alert-success text-center">
                                        <p>You have a test available for the course: {{ $course->course_name }}</p>
                                        <a href="/exam-link" class="btn btn-primary">Start Exam</a>
                                    </div>
                                @endif
                            @endforeach
                            <div class="d-flex justify-content-end mt-4">
                                {{ $completed_courses->links('pagination::bootstrap-5') }}
                            </div>
                        @else
                            <div class="alert alert-danger text-center">
                                There are currently no exams.
                            </div>
                        @endif
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
                    @if (isset($exam_results) && count($exam_results) > 0)
                        @foreach ($exam_results as $result)
                            <div class="alert alert-info text-left">
                                Course: {{ $result->course_name }} <br>
                                Score: <span
                                    style="color: red; font-weight: normal;"><b>{{ $result->score }}</b></span><br>
                                Test Date: {{ $result->exam_date }}
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-end mt-4">
                            {{ $exam_results->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="alert alert-danger text-center">
                            No data available.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
