@extends('auth.quiz.main-quiz')
@section('contents')
    <style>
        #quizTable th,
        td {
            text-align: center !important;
        }
    </style>
    <div class="pagetitle">
        <h1 class="mb-4">Create Exam</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../../">Home</a></li>
                <li class="breadcrumb-item"><a href="../quiz">Quiz</a></li>
                <li class="breadcrumb-item active">Create Exam</li>
            </ol>
        </nav>
    </div>
    <div class="row gx-3 my-3">
        <div class="col-md-6 m-0">
            <div class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#quizModal">
                <div class="d-flex align-items-center at1">
                    <i class="bi bi-file-earmark-plus pe-2"></i>
                    Add exam
                </div>
            </div>
            {{-- <div class="btn btn-success mx-2">
                <div class="d-flex align-items-center at2 text-white btnExcel">
                    <i class="bi bi-file-earmark-arrow-up pe-2 "></i>
                    Import
                </div>
            </div>
            <div class="btn btn-success mx-2">
                <a href="" class="d-flex align-items-center at2 text-white">
                    <i class="bi bi-file-earmark-arrow-down pe-2"></i>
                    Export
                </a>
            </div> --}}
        </div>
    </div>

    <div class="card shadow-sm p-3 mb-5 bg-white rounded-4">
        <h3 class="text-left mb-4">Question list</h3>
        <div class="table-responsive">
            <table id="quizTable" class="display">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Exam Name</th>
                        <th>Course</th>
                        <th>Exam Date</th>
                        <th>Time per question (seconds)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($exams as $index => $exam)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $exam->exam_name }}</td>
                            <td>{{ $exam->course->course_name }}</td>
                            <td>{{ $exam->exam_date }}</td>
                            <td>{{ $exam->time }}</td>
                            <td>
                                <button
                                    class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                    data-id="{{ $exam->exam_id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button
                                    class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                    data-id="{{ $exam->exam_id }}">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="quizModal" tabindex="-1" role="dialog" aria-labelledby="quizModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="quizModalLabel">Create exam</h5>
                    </div>
                    <div class="modal-body">
                        <form id="quizForm">
                            @csrf
                            <input type="hidden" id="exam_id" name="exam_id">
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Course</label>
                                <select class="form-select" id="course_id" name="course_id" required>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exam_name" class="form-label">Exam name</label>
                                <input type="text" class="form-control" id="exam_name" name="exam_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="exam_date" class="form-label">Exam date</label>
                                <input type="datetime-local" class="form-control" id="exam_date" name="exam_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="duration" class="form-label">Time per question (seconds)</label>
                                <input type="number" class="form-control" id="duration" name="time" min="5"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="total_questions" class="form-label">Number of questions</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="total_questions" name="total_questions"
                                        min="1" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text d-flex align-items-center gap-1">
                                            <input class="form-check-input" type="checkbox" id="random_questions"
                                                name="random_questions">
                                            <div>
                                                <label class="form-check-label" for="random_questions"
                                                    style="margin-top: 5px">Random</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <small id="question_count_info" class="form-text text-muted"></small>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var table = $('#quizTable').DataTable();
            const courseSelect = document.getElementById('course_id');
            const totalQuestionsInput = document.getElementById('total_questions');
            const randomCheckbox = document.getElementById('random_questions');
            const questionCountInfo = document.getElementById('question_count_info');
            let allQuestions = [];
            let currentExamQuestions = [];

            courseSelect.addEventListener('change', fetchQuestions);
            randomCheckbox.addEventListener('change', updateQuestionSelection);
            totalQuestionsInput.addEventListener('input', updateQuestionSelection);

            function fetchQuestions() {
                const courseId = courseSelect.value;
                if (!courseId) return;

                fetch(`{{ route('question-bank.list', ':id') }}`.replace(':id', courseId))
                    .then(response => response.json())
                    .then(data => {
                        allQuestions = data.question_list;
                        questionCountInfo.textContent = `Total number of questions: ${allQuestions.length}`;
                        updateQuestionSelection();
                    })
                    .catch(error => console.error('Error fetching questions:', error));
            }

            function updateQuestionSelection() {
                const totalQuestions = parseInt(totalQuestionsInput.value, 10);
                if (isNaN(totalQuestions) || totalQuestions < 1) return;

                if (totalQuestions > allQuestions.length) {
                    alert(`The number of questions must not exceed ${allQuestions.length}`);
                    totalQuestionsInput.value = allQuestions.length;
                    return;
                }

                let selectedQuestions = [];
                if (randomCheckbox.checked) {
                    selectedQuestions = getRandomQuestions(totalQuestions);
                } else {
                    selectedQuestions = allQuestions.slice(0, totalQuestions);
                }

                const quizForm = document.getElementById('quizForm');
                const existingInputs = quizForm.querySelectorAll('input[name="questions[]"]');
                existingInputs.forEach(input => quizForm.removeChild(input));

                selectedQuestions.forEach(question => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'questions[]';
                    hiddenInput.value = question.question_bank_id;
                    quizForm.appendChild(hiddenInput);
                });

                // questionCountInfo.textContent = `Selected number of questions: ${selectedQuestions.length}`;
            }

            function getRandomQuestions(count) {
                const shuffled = [...allQuestions].sort(() => 0.5 - Math.random());
                return shuffled.slice(0, count);
            }

            $('#quizForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var url = $('#exam_id').val() ? '{{ route('exams.update', ':id') }}'.replace(':id', $(
                    '#exam_id').val()) : '{{ route('exams.store') }}';

                $.ajax({
                    url: url,
                    method: $('#exam_id').val() ? 'PUT' : 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success('The exam has been successfully saved!', 'Success');
                            location.reload();
                        } else {
                            toastr.error('An error occurred, please try again', 'Error');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error, please try again', 'Error ');
                    }
                });
            });

            $('#quizTable').on('click', '.edit-btn', function() {
                var exam_id = $(this).data('id');
                $.ajax({
                    url: '{{ route('exams.edit', ':id') }}'.replace(':id', exam_id),
                    method: 'GET',
                    success: function(response) {
                        var exam = response.exam;
                        allQuestions = response.questions;

                        $('#exam_id').val(exam.exam_id);
                        $('#course_id').val(exam.course_id).change();

                        setTimeout(() => {
                            $('#exam_name').val(exam.exam_name);
                            $('#exam_date').val(exam.exam_date);
                            $('#duration').val(exam.time);
                            $('#total_questions').val(exam.questions.length);
                            updateQuestionSelectionWithExisting(exam.questions);
                            $('#quizModalLabel').text('Edit exam');
                            $('#quizModal').modal('show');
                        }, 200);
                    },
                    error: function(xhr) {
                        alert('Error, please try again');
                    }
                });
            });

            $('#quizTable').on('click', '.delete-btn', function() {
                var exam_id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to undo this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('exams.destroy', ':id') }}'.replace(':id',
                                exam_id),
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your exam has been deleted.',
                                    'success'
                                ).then((result) => {
                                    if (result.isConfirmed) {
                                        setTimeout(function() {
                                            location.reload();
                                        }, 300);
                                    }
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Please try again.',
                                    'error'
                                )
                            }
                        });
                    }
                });
            });

            function updateQuestionSelectionWithExisting(questions) {
                console.log(questions);
                const quizForm = document.getElementById('quizForm');
                const existingInputs = quizForm.querySelectorAll('input[name="questions[]"]');
                existingInputs.forEach(input => quizForm.removeChild(input));

                questions.forEach(question => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'questions[]';
                    hiddenInput.value = question.question_bank_id;
                    quizForm.appendChild(hiddenInput);
                });

                totalQuestionsInput.value = questions.length;
                questionCountInfo.textContent = `Selected number of questions: ${questions.length}`;
            }

            // Gọi hàm fetchQuestions ngay lập tức khi tải trang để cập nhật danh sách câu hỏi ban đầu
            fetchQuestions();
        });
    </script>
@endsection
