@extends('auth.main-lms')


@section('head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .question-list {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .question-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }

        .question-item {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .question-item:hover {
            background-color: #0056b3;
        }

        .question-timer {
            font-size: 1.5rem;
            font-weight: bold;
            color: red;
        }

        .custom-hr {
            border: 2px solid #000;
        }
    </style>
@endsection

@section('contents')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div id="question-content" class="mb-4">
                    <h3 id="question-text">Câu hỏi 1: Nội dung câu hỏi sẽ hiển thị ở đây?</h3>
                    <hr class="custom-hr">
                    <div id="answers">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer" id="answerA" value="A">
                            <label class="form-check-label" for="answerA">Đáp án A</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer" id="answerB" value="B">
                            <label class="form-check-label" for="answerB">Đáp án B</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer" id="answerC" value="C">
                            <label class="form-check-label" for="answerC">Đáp án C</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer" id="answerD" value="D">
                            <label class="form-check-label" for="answerD">Đáp án D</label>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button id="submit-answer" class="btn btn-primary">Nộp bài</button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Danh sách câu hỏi
                    </div>
                    <div class="card-body question-list">
                        <div class="question-grid" id="question-list">
                            <div class="question-item" data-question="1">1</div>
                            <div class="question-item" data-question="2">2</div>
                            <div class="question-item" data-question="3">3</div>
                            <div class="question-item" data-question="4">4</div>
                            <div class="question-item" data-question="5">5</div>
                            <div class="question-item" data-question="6">6</div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header bg-warning text-white">
                        Thời gian
                    </div>
                    <div class="card-body text-center">
                        <span id="timer" class="question-timer">60</span> giây
                    </div>
                </div>
            </div>

        </div>
    @endsection

    @section('script')
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                var currentQuestion = 1;
                var totalQuestions = $('#question-list .list-group-item').length;
                var timer = 60;
                var timerInterval;

                function startTimer() {
                    timer = 60;
                    $('#timer').text(timer);
                    clearInterval(timerInterval);
                    timerInterval = setInterval(function() {
                        timer--;
                        $('#timer').text(timer);
                        if (timer <= 0) {
                            clearInterval(timerInterval);
                            alert('Hết thời gian cho câu hỏi này!');
                        }
                    }, 1000);
                }

                $('#question-list .list-group-item').click(function() {
                    var questionNumber = $(this).data('question');
                    currentQuestion = questionNumber;
                    loadQuestion(questionNumber);
                    startTimer();
                });

                $('#submit-answer').click(function() {
                    alert('Câu trả lời đã được nộp!');
                    currentQuestion++;
                    if (currentQuestion > totalQuestions) {
                        alert('Bạn đã hoàn thành bài thi!');
                    } else {
                        loadQuestion(currentQuestion);
                        startTimer();
                    }
                });
                loadQuestion(currentQuestion);
                startTimer();
            });
        </script>
    @endsection
