@extends('auth.quiz.main-quiz')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card rounded-bottom-4">
                    <div class="card-header p-0" style="background: var(--clr-1)">
                        <div class="progress" id="progress-bar" style="height: 3px" role="progressbar"
                            aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-danger fade-animation" style="width: 0%;"></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between p-3">
                            <h5 class="fw-bold text-white mb-0">
                                <i class="bi bi-question-circle me-2"></i> Question <span
                                    id="current-question">1</span>/{{ count($questions) }}
                            </h5>
                            <div class="rounded-circle bg-light p-2">
                                <span class="fw-bold text-danger" id="time-left">{{ $exam->time }}s</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-3" id="question-container">
                        @foreach ($questions as $index => $question)
                            <div class="question d-none" data-question-id="{{ $question->question_bank_id }}"
                                data-question-index="{{ $index + 1 }}">
                                <h5 class="fw-bold">Câu {{ $index + 1 }}: {{ $question->question }}</h5>
                                @if ($question->question_image)
                                    <div class="my-2 w-100" style="height: 200px">
                                        <img class="w-100 h-100 object-fit-contain"
                                            src="{{ asset('question_bank_image/' . $question->question_image) }}" alt="">
                                    </div>
                                @endif
                                <div class="form-check my-3">
                                    <input class="form-check-input" type="radio"
                                        name="answer_{{ $question->question_bank_id }}"
                                        id="ansA_{{ $question->question_bank_id }}" value="A">
                                    <label class="form-check-label" for="ansA_{{ $question->question_bank_id }}">
                                        <strong class="me-2">A:</strong>{{ $question->question_a }}
                                    </label>
                                </div>
                                <div class="form-check my-3">
                                    <input class="form-check-input" type="radio"
                                        name="answer_{{ $question->question_bank_id }}"
                                        id="ansB_{{ $question->question_bank_id }}" value="B">
                                    <label class="form-check-label" for="ansB_{{ $question->question_bank_id }}">
                                        <strong class="me-2">B:</strong>{{ $question->question_b }}
                                    </label>
                                </div>
                                <div class="form-check my-3">
                                    <input class="form-check-input" type="radio"
                                        name="answer_{{ $question->question_bank_id }}"
                                        id="ansC_{{ $question->question_bank_id }}" value="C">
                                    <label class="form-check-label" for="ansC_{{ $question->question_bank_id }}">
                                        <strong class="me-2">C:</strong>{{ $question->question_c }}
                                    </label>
                                </div>
                                <div class="form-check my-3">
                                    <input class="form-check-input" type="radio"
                                        name="answer_{{ $question->question_bank_id }}"
                                        id="ansD_{{ $question->question_bank_id }}" value="D">
                                    <label class="form-check-label" for="ansD_{{ $question->question_bank_id }}">
                                        <strong class="me-2">D:</strong>{{ $question->question_d }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        {{-- <button class="btn btn-primary" id="next-button">
                            <i class="bi bi-arrow-right me-2"></i>
                            Next
                        </button> --}}
                        <button class="btn btn-danger" id="submit-button">
                            <i class="bi bi-check2-all me-2"></i>
                            Submit
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card rounded-4 border">
                    <div class="card-header bg-secondary fw-bold text-dark p-4">
                        <h5 class="fw-bold text-white mb-0">
                            <i class="bi bi-list me-2"></i> Question list
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="row gy-2">
                            @foreach ($questions as $index => $question)
                                <div class="col-xl-3 col-sm-3 col-4 text-center">
                                    <button class="btn btn-outline-primary rounded-4 px-4 fw-bold question-btn"
                                        data-question-index="{{ $index + 1 }}">{{ $index + 1 }}</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card rounded-4 border">
                    <div class="card-header bg-info fw-bold text-dark">
                        <h5 class="fw-bold text-white mb-0">
                            <i class="bi bi-info-circle me-2"></i> Info
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-xl-4">
                                <button class="btn btn-outline-primary rounded-4 px-4 fw-bold">Question</button>
                            </div>
                            <div class="col-xl-8">
                                <div class="fw-semibold">Question not completed</div>
                            </div>
                            <div class="col-xl-4">
                                <button class="btn btn-primary rounded-4 px-4 fw-bold">Question</button>
                            </div>
                            <div class="col-xl-8">
                                <div class="fw-semibold">You are here</div>
                            </div>
                            <div class="col-xl-4">
                                <button class="btn btn-success rounded-4 px-4 fw-bold">Question</button>
                            </div>
                            <div class="col-xl-8">
                                <div class="fw-semibold">Question completed</div>
                            </div>
                            <div class="col-xl-4">
                                <button class="btn btn-danger rounded-4 px-4 fw-bold">Question</button>
                            </div>
                            <div class="col-xl-8">
                                <div class="fw-semibold">Time expired or skipped</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questions = document.querySelectorAll('.question');
            const questionButtons = document.querySelectorAll('.question-btn');
            const nextButton = document.getElementById('next-button');
            const submitButton = document.getElementById('submit-button');
            const timeLeft = document.getElementById('time-left');
            const progressBar = document.querySelector('.progress-bar');

            let currentQuestionIndex = 0;
            let timeRemaining = {{ $exam->time }};
            let interval;
            let visitedQuestions = new Set();
            let examSubmitted = false;

            function startTimer() {
                stopTimer();
                interval = setInterval(function() {
                    timeRemaining--;
                    timeLeft.textContent = `${timeRemaining}s`;
                    progressBar.style.width = `${(timeRemaining / {{ $exam->time }}) * 100}%`;

                    if (timeRemaining <= 0) {
                        clearInterval(interval);
                        moveToNextQuestion(true);
                    }
                }, 1000);
            }

            function stopTimer() {
                clearInterval(interval);
            }

            async function saveAnswer(questionIndex, timeout) {
                const questionId = questions[questionIndex].dataset.questionId;
                const selectedAnswer = document.querySelector(`input[name="answer_${questionId}"]:checked`);
                const answer = selectedAnswer ? selectedAnswer.value : null;
                const answered = !!selectedAnswer;

                const response = await fetch('{{ route('test-quiz.saveAnswer') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        exam_id: '{{ $exam->exam_id }}',
                        question_id: questionId,
                        selected_answer: answer,
                        employee_id: '{{ $employee_id }}'
                    })
                });

                const text = await response.text();
                return {
                    answered,
                    timeout
                };
            }

            function findNextUnvisitedQuestion(startIndex) {
                for (let i = startIndex; i < questions.length; i++) {
                    if (!visitedQuestions.has(i)) {
                        return i;
                    }
                }
                for (let i = 0; i < startIndex; i++) {
                    if (!visitedQuestions.has(i)) {
                        return i;
                    }
                }
                return -1;
            }

            function moveToQuestion(index) {
                if (index < 0 || index >= questions.length) return;

                saveAnswer(currentQuestionIndex, false).then(() => {
                    const currentQuestion = questions[currentQuestionIndex];
                    const nextQuestion = questions[index];

                    const questionId = questions[currentQuestionIndex].dataset.questionId;
                    const selectedAnswer = document.querySelector(
                        `input[name="answer_${questionId}"]:checked`);
                    const answered = !!selectedAnswer;

                    const currentButton = questionButtons[currentQuestionIndex];
                    currentButton.classList.remove('btn-primary', 'btn-outline-primary', 'btn-danger',
                        'btn-success', 'btn-info');

                    if (!answered) {
                        currentButton.classList.add('btn-danger');
                    } else {
                        currentButton.classList.add('btn-success');
                    }

                    currentQuestion.classList.add('d-none');
                    nextQuestion.classList.remove('d-none');

                    questionButtons[index].classList.remove('btn-outline-primary', 'btn-info');
                    questionButtons[index].classList.add('btn-primary');

                    visitedQuestions.add(currentQuestionIndex);

                    currentQuestionIndex = index;
                    document.getElementById('current-question').textContent = index + 1;

                    timeRemaining = {{ $exam->time }};
                    startTimer();
                });
            }

            function moveToNextQuestion(timeout = false) {
                stopTimer();
                saveAnswer(currentQuestionIndex, timeout).then((data) => {
                    const currentButton = questionButtons[currentQuestionIndex];

                    currentButton.classList.remove('btn-primary', 'btn-success', 'btn-danger', 'btn-info',
                        'btn-outline-primary');

                    if (timeout && !data.answered) {
                        currentButton.classList.add('btn-danger');
                    } else if (!data.answered) {
                        currentButton.classList.add('btn-danger');
                    } else {
                        currentButton.classList.add('btn-success');
                    }

                    visitedQuestions.add(currentQuestionIndex);

                    let nextQuestionIndex = findNextUnvisitedQuestion(currentQuestionIndex + 1);

                    if (nextQuestionIndex !== -1) {
                        moveToQuestion(nextQuestionIndex);
                    } else {
                        submitQuiz(
                        true); // Automatically submit quiz if all questions are visited or last question times out
                    }
                });
            }

            function submitQuiz(autoSubmit = false) {
                if (autoSubmit) {
                    examSubmitted = true;
                    saveAnswer(currentQuestionIndex, false).then(() => {
                        let promises = [];
                        for (let i = 0; i < questions.length; i++) {
                            if (!visitedQuestions.has(i)) {
                                promises.push(saveAnswer(i, false));
                            }
                        }
                        Promise.all(promises).then(() => {
                            calculateAndSaveScore().then(() => {
                                window.location.href = '{{ route('quiz.index') }}';
                            });
                        });
                    });
                } else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to submit the quiz?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, submit it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            examSubmitted = true;
                            saveAnswer(currentQuestionIndex, false).then(() => {
                                let promises = [];
                                for (let i = 0; i < questions.length; i++) {
                                    if (!visitedQuestions.has(i)) {
                                        promises.push(saveAnswer(i, false));
                                    }
                                }
                                Promise.all(promises).then(() => {
                                    calculateAndSaveScore().then(() => {
                                        window.location.href =
                                            '{{ route('quiz.index') }}';
                                    });
                                });
                            });
                        }
                    });
                }
            }

            async function calculateAndSaveScore() {
                const response = await fetch('{{ route('test-quiz.calculateScore') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        exam_id: '{{ $exam->exam_id }}',
                        employee_id: '{{ $employee_id }}'
                    })
                });

                const data = await response.json();
                const score = data.score;

                Swal.fire(
                    'Submitted!',
                    'Your quiz has been submitted.',
                    'success'
                );
            }

            window.addEventListener('beforeunload', function(e) {
                if (!examSubmitted) {
                    fetch('{{ route('test-quiz.markExamAsZero') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            exam_id: '{{ $exam->exam_id }}',
                            employee_id: '{{ $employee_id }}'
                        })
                    });
                }
            });

            submitButton.addEventListener('click', function() {
                submitQuiz();
            });

            questionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.dataset.questionIndex) - 1;
                    if (index === currentQuestionIndex) return;
                    if (visitedQuestions.has(index)) return;
                    visitedQuestions.add(currentQuestionIndex);
                    moveToQuestion(index);
                });
            });

            moveToQuestion(0);
        });
    </script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questions = document.querySelectorAll('.question');
            const questionButtons = document.querySelectorAll('.question-btn');
            const nextButton = document.getElementById('next-button');
            const submitButton = document.getElementById('submit-button');
            const timeLeft = document.getElementById('time-left');
            const progressBar = document.querySelector('.progress-bar');

            let currentQuestionIndex = 0;
            let interval;
            let visitedQuestions = new Set();
            let examSubmitted = false;
            let timeRemainingPerQuestion = {};

            // Initialize time remaining for each question
            questions.forEach((question, index) => {
                timeRemainingPerQuestion[index] = {{ $exam->time }};
            });

            function startTimer() {
                stopTimer();
                interval = setInterval(function() {
                    timeRemainingPerQuestion[currentQuestionIndex]--;
                    timeLeft.textContent = `${timeRemainingPerQuestion[currentQuestionIndex]}s`;
                    progressBar.style.width =
                        `${(timeRemainingPerQuestion[currentQuestionIndex] / {{ $exam->time }}) * 100}%`;

                    if (timeRemainingPerQuestion[currentQuestionIndex] <= 0) {
                        clearInterval(interval);
                        moveToNextQuestion(true);
                    }
                }, 1000);
            }

            function stopTimer() {
                clearInterval(interval);
            }

            async function saveAnswer(questionIndex, timeout) {
                const questionId = questions[questionIndex].dataset.questionId;
                const selectedAnswer = document.querySelector(`input[name="answer_${questionId}"]:checked`);
                const answer = selectedAnswer ? selectedAnswer.value : null;
                const answered = !!selectedAnswer;

                const response = await fetch('{{ route('test-quiz.saveAnswer') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        exam_id: '{{ $exam->exam_id }}',
                        question_id: questionId,
                        selected_answer: answer,
                        employee_id: '{{ $employee_id }}'
                    })
                });

                const text = await response.text();
                return {
                    answered,
                    timeout
                };
            }

            function findNextUnvisitedQuestion(startIndex) {
                for (let i = startIndex; i < questions.length; i++) {
                    if (timeRemainingPerQuestion[i] > 0) {
                        return i;
                    }
                }
                for (let i = 0; i < startIndex; i++) {
                    if (timeRemainingPerQuestion[i] > 0) {
                        return i;
                    }
                }
                return -1;
            }

            function moveToQuestion(index) {
                if (index < 0 || index >= questions.length) return;
                if (timeRemainingPerQuestion[index] <= 0) return; // Prevent moving to questions with no time left

                saveAnswer(currentQuestionIndex, false).then(() => {
                    const currentQuestion = questions[currentQuestionIndex];
                    const nextQuestion = questions[index];

                    const questionId = questions[currentQuestionIndex].dataset.questionId;
                    const selectedAnswer = document.querySelector(
                        `input[name="answer_${questionId}"]:checked`);
                    const answered = !!selectedAnswer;

                    const currentButton = questionButtons[currentQuestionIndex];
                    currentButton.classList.remove('btn-primary', 'btn-outline-primary', 'btn-danger',
                        'btn-success', 'btn-info');

                    if (!answered) {
                        currentButton.classList.add('btn-danger');
                    } else {
                        currentButton.classList.add('btn-success');
                    }

                    currentQuestion.classList.add('d-none');
                    nextQuestion.classList.remove('d-none');

                    questionButtons[index].classList.remove('btn-outline-primary', 'btn-info');
                    questionButtons[index].classList.add('btn-primary');

                    visitedQuestions.add(currentQuestionIndex);

                    currentQuestionIndex = index;
                    document.getElementById('current-question').textContent = index + 1;

                    startTimer();
                });
            }

            function moveToNextQuestion(timeout = false) {
                stopTimer();
                saveAnswer(currentQuestionIndex, timeout).then((data) => {
                    const currentButton = questionButtons[currentQuestionIndex];

                    currentButton.classList.remove('btn-primary', 'btn-success', 'btn-danger', 'btn-info',
                        'btn-outline-primary');

                    if (timeout && !data.answered) {
                        currentButton.classList.add('btn-danger');
                    } else if (!data.answered) {
                        currentButton.classList.add('btn-danger');
                    } else {
                        currentButton.classList.add('btn-success');
                    }

                    visitedQuestions.add(currentQuestionIndex);

                    let nextQuestionIndex = findNextUnvisitedQuestion(currentQuestionIndex + 1);

                    if (nextQuestionIndex !== -1) {
                        moveToQuestion(nextQuestionIndex);
                    } else {
                        submitQuiz(
                        true); // Automatically submit quiz if all questions are visited or last question times out
                    }
                });
            }

            function submitQuiz(autoSubmit = false) {
                if (autoSubmit) {
                    examSubmitted = true;
                    saveAnswer(currentQuestionIndex, false).then(() => {
                        let promises = [];
                        for (let i = 0; i < questions.length; i++) {
                            if (!visitedQuestions.has(i)) {
                                promises.push(saveAnswer(i, false));
                            }
                        }
                        Promise.all(promises).then(() => {
                            calculateAndSaveScore().then(() => {
                                window.location.href = '{{ route('quiz.index') }}';
                            });
                        });
                    });
                } else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to submit the quiz?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, submit it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            examSubmitted = true;
                            saveAnswer(currentQuestionIndex, false).then(() => {
                                let promises = [];
                                for (let i = 0; i < questions.length; i++) {
                                    if (!visitedQuestions.has(i)) {
                                        promises.push(saveAnswer(i, false));
                                    }
                                }
                                Promise.all(promises).then(() => {
                                    calculateAndSaveScore().then(() => {
                                        window.location.href =
                                            '{{ route('quiz.index') }}';
                                    });
                                });
                            });
                        }
                    });
                }
            }

            async function calculateAndSaveScore() {
                const response = await fetch('{{ route('test-quiz.calculateScore') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        exam_id: '{{ $exam->exam_id }}',
                        employee_id: '{{ $employee_id }}'
                    })
                });

                const data = await response.json();
                const score = data.score;

                Swal.fire(
                    'Submitted!',
                    'Your quiz has been submitted.',
                    'success'
                );
            }

            window.addEventListener('beforeunload', function(e) {
                if (!examSubmitted) {
                    fetch('{{ route('test-quiz.markExamAsZero') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            exam_id: '{{ $exam->exam_id }}',
                            employee_id: '{{ $employee_id }}'
                        })
                    });
                }
            });

            submitButton.addEventListener('click', function() {
                submitQuiz();
            });

            questionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.dataset.questionIndex) - 1;
                    if (index === currentQuestionIndex) return;
                    if (timeRemainingPerQuestion[index] <= 0)
                return; // Prevent moving to questions with no time left
                    visitedQuestions.add(currentQuestionIndex);
                    moveToQuestion(index);
                });
            });

            moveToQuestion(0);
        });
    </script>
@endsection
