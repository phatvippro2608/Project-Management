@extends('auth.quiz.main-quiz')
@section('contents')
    <style>
        textarea {
            border: none;
            outline: none;
        }

        #questionsTable th {
            text-align: left !important;
        }
    </style>
    <div class="pagetitle">
        <h1>Create Quiz</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item"><a href="">Quiz</a></li>
                <li class="breadcrumb-item active">Question bank</li>
            </ol>
        </nav>
    </div>
    <div class="row gx-3 my-3">
        <div class="col-md-6 m-0">
            <div class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                <div class="d-flex align-items-center at1">
                    <i class="bi bi-file-earmark-plus pe-2"></i>
                    Add question
                </div>
            </div>
            <div class="btn btn-success mx-2">
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
            </div>
        </div>
    </div>
    <div class="card shadow-sm p-3 mb-5 bg-white rounded-4">
        <h3 class="text-left mb-4">Question list</h3>
        <div class="row mb-4">
            <div class="col-md-12">
                <label for="courseSelect">Select course</label>
                <select class="form-control" id="courseSelect">
                    <option value="">No select</option>
                    @foreach($courses as $item)
                        <option value="{{$item->course_id}}">{{$item->course_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table id="questionsTable" class="table table-hover table-borderless">
                <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Question</th>
                    <th>Answer A</th>
                    <th>Answer B</th>
                    <th>Answer C</th>
                    <th>Answer D</th>
                    <th>Correct</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Thêm câu hỏi -->
    <div class="modal fade" id="addQuestionModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Question</h4>
                </div>
                <div class="modal-body">
                    <form id="addQuestionForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="course_name" class="form-label">Course name</label>
                            <select class="form-control" id="add_course_name" name="add_course_name" required>
                                @foreach($courses as $item)
                                    <option value="{{$item->course_id}}">{{$item->course_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="question" class="form-label">Question</label>
                            <textarea class="form-control" id="add_question" name="add_question" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="question_img" class="form-label">Image</label>
                            <input type="file" class="form-control" id="add_question_img" name="add_question_img"
                                   accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="answer_a" class="form-label">Answer A</label>
                            <textarea type="text" class="form-control" id="add_answer_a" name="add_answer_a"
                                      required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="answer_b" class="form-label">Answer B</label>
                            <textarea type="text" class="form-control" id="add_answer_b" name="add_answer_b"
                                      required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="answer_c" class="form-label">Answer C</label>
                            <textarea type="text" class="form-control" id="add_answer_c" name="add_answer_c"
                                      required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="answer_d" class="form-label">Answer D</label>
                            <textarea type="text" class="form-control" id="add_answer_d" name="add_answer_d"
                                      required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="correct_answer" class="form-label">Correct</label>
                            <select class="form-control" id="add_correct_answer" name="add_correct_answer" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Add question</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editQuestionModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Question</h4>
                </div>
                <div class="modal-body">
                    <form id="editQuestionForm" enctype="multipart/form-data">
                        @csrf
                        <input type="text" hidden id="question_bank_id" name="question_bank_id">
                        <div class="mb-3">
                            <label for="course_name" class="form-label">Course name</label>
                            <select class="form-control" id="course_name" name="course_id" required>
                                @foreach($courses as $item)
                                    <option value="{{$item->course_id}}">{{$item->course_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="question" class="form-label">Question</label>
                            <textarea class="form-control" id="question" name="question" required></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-4">
                                    <label for="">
                                        <a target="_blank" href="" id="preview_img"></a>
                                    </label>
                                </div>
                                <div class="col-8">
                                    <label for="question_img" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="question_img" name="question_image"
                                           accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="answer_a" class="form-label">Answer A</label>
                            <textarea type="text" class="form-control" id="answer_a" name="question_a"
                                      required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="answer_b" class="form-label">Answer B</label>
                            <textarea type="text" class="form-control" id="answer_b" name="question_b"
                                      required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="answer_c" class="form-label">Answer C</label>
                            <textarea type="text" class="form-control" id="answer_c" name="question_c"
                                      required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="answer_d" class="form-label">Answer D</label>
                            <textarea type="text" class="form-control" id="answer_d" name="question_d"
                                      required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="correct_answer" class="form-label">Correct</label>
                            <select class="form-control" id="correct_answer" name="correct" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade md2">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-bold"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Select file (*.xlsx) or download
                                <a target="_blank" href="">
                                    Example file
                                </a>
                            </label>
                            <input accept=".xlsx" name="file-excel" type="file" class="form-control">
                            <br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary luuTT">Upload</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var table = $('#questionsTable').DataTable();

        $('.btnExcel').click(function () {
            $('.md2 .modal-title').text('Import question');
            $('.md2').modal('show');
            $('.luuTT').click(function () {
                var fileInput = $('input[name="file-excel"]')[0].files[0];

                var formData = new FormData();
                formData.append('file-excel', fileInput);
                $.ajax({
                    url: '',
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        if (result.status === 200) {
                            toastr.success(result.message);
                            setTimeout(function () {
                                location.reload();
                            }, 750);
                        } else {
                            toastr.error(result.message);
                        }
                    },
                    error: function (error) {
                        toastr.error("Thêm thất bại");
                    }
                });
            })
        })

        $('#addQuestionForm').submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: '{{ route('question-bank.add') }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        $('#addQuestionForm').modal('hide');
                        toastr.success(response.message, "Successful");
                        setTimeout(function () {
                            location.reload();
                        }, 500);
                    } else {
                        toastr.error(response.message, "Error");
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 400) {
                        var response = xhr.responseJSON;
                        toastr.error(response.message, "Error");
                    } else {
                        toastr.error("An error occurred", "Error");
                    }
                }
            });
        });

        $(document).ready(function() {
            let questionsTable = $('#questionsTable').DataTable(); // Khởi tạo DataTables

            $('#courseSelect').on("change", function() {
                let course_id = $("#courseSelect option:selected").val();
                let tableBody = $("#questionsTable tbody");

                if (course_id === "") {
                    tableBody.empty();
                    questionsTable.clear().draw(); // Xóa dữ liệu trong DataTables
                    return;
                }

                $.ajax({
                    url: '{{ route('question-bank.list', ':id') }}'.replace(':id', course_id),
                    method: 'GET',
                    success: function(response) {
                        const data = response.question_list;
                        tableBody.empty();  // Clear existing data
                        questionsTable.clear(); // Clear DataTables data

                        data.forEach((question, index) => {
                            let row =
                                `
                            <tr>
                                <td class="text-start">${index + 1}</td>
                                <td> <textarea readonly>${question.question}</textarea></td>
                                <td> <textarea readonly>${question.question_a}</textarea></td>
                                <td> <textarea readonly>${question.question_b}</textarea></td>
                                <td> <textarea readonly>${question.question_c}</textarea></td>
                                <td> <textarea readonly>${question.question_d}</textarea></td>
                                <td>${question.correct}</td>
                                <td>
                                    <button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                        data-id="${question.question_bank_id}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                        data-id="${question.question_bank_id}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                            questionsTable.row.add($(row)).draw(false); // Add row to DataTables and draw
                        });
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message, "Error");
                    }
                });
            });
        });

        $('#questionsTable').on('click', '.delete-btn', function () {
            var question_id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this question?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('question-bank.destroy', ':id') }}'.replace(':id',
                            question_id),
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.success) {
                                toastr.success(response.message, "Deleted successfully");
                                setTimeout(function () {
                                    location.reload();
                                }, 500);
                            } else {
                                toastr.error("Failed to delete the proposal application.",
                                    "Operation Failed");
                            }
                        },
                        error: function (xhr) {
                            toastr.error("An error occurred.", "Operation Failed");
                        }
                    });
                }
            });
        });

        $('#questionsTable').on('click', '.edit-btn', function () {
            var question_id = $(this).data('id');

            $('#editQuestionForm').data('id', question_id);
            var url = "{{ route('question-bank.edit', ':id') }}";
            url = url.replace(':id', question_id);
            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    var data = response.question;
                    $('#question_bank_id').val(data.question_bank_id);
                    $('#course_name').val(data.course_id);
                    $('#question').val(data.question);
                    {{--var imagePath = '{{asset('question_bank_image')}}' + '/' + data.question_image;--}}

                    if (data.question_image) {
                        var imagePath = '{{asset('question_bank_image')}}' + '/' + data.question_image;
                        $('#preview_img').attr('href', imagePath).text('Click to preview image question');
                    } else {
                        $('#preview_img').removeAttr('href').text('No Image Available');
                    }
                    $('#preview_img').attr('href', imagePath)
                    $('#answer_a').val(data.question_a);
                    $('#answer_b').val(data.question_b);
                    $('#answer_c').val(data.question_c);
                    $('#answer_d').val(data.question_d);
                    $('#correct_answer').val(data.correct);
                    $('#editQuestionModal').modal('show');
                },
                error: function (xhr) {
                }
            });
        });


        $('#editQuestionForm').submit(function (e) {
            e.preventDefault();
            var question_id = $(this).data('id'); // Lấy ID từ form
            var url = "{{ route('question-bank.update', ':id') }}";
            url = url.replace(':id', question_id);
            var formData = new FormData(this);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        $('#editQuestionModal').modal('hide');
                        toastr.success(response.response, "Edit successful");
                        setTimeout(function () {
                            location.reload()
                        }, 500);
                    }
                },
                error: function (xhr) {
                    toastr.error("Error");
                }
            });
        });
    </script>
@endsection
