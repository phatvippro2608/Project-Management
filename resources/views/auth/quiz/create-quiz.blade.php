@extends('auth.main-lms')
@section('contents')
    <div class="pagetitle">
        <h1>Create Quiz</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item"><a href="">Quiz</a></li>
                <li class="breadcrumb-item active">Create Quiz</li>
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
                <label for="courseSelect">Chọn khóa học</label>
                <select class="form-control" id="courseSelect">
                    @foreach($courses as $item)
                        <option value="{{$item->course_id}}">{{$item->course_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <table id="questionsTable" class="table table-hover table-borderless">
            <thead class="table-light">
            <tr>
                <th>STT</th>
                <th>Tên khóa học</th>
                <th>Câu hỏi</th>
                <th>Đáp án A</th>
                <th>Đáp án B</th>
                <th>Đáp án C</th>
                <th>Đáp án D</th>
                <th>Đáp án đúng</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody >
            </tbody>
        </table>
    </div>

    <!-- Modal Thêm câu hỏi -->
    <div class="modal fade" id="addQuestionModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Question</h4>
                </div>
                <div class="modal-body">
                    <form id="addQuestionForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="course_name" class="form-label">Tên khóa học</label>
                            <select class="form-control" id="course_name" name="add_course_name" required>
                                @foreach($courses as $item)
                                    <option value="{{$item->course_id}}">{{$item->course_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="question" class="form-label">Câu hỏi</label>
                            <textarea class="form-control" id="question" name="add_question" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="question_img" class="form-label">Hình ảnh</label>
                            <input type="file" class="form-control" id="question_img" name="add_question_img"
                                   accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="answer_a" class="form-label">Đáp án A</label>
                            <textarea type="text" class="form-control" id="answer_a" name="add_answer_a" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="answer_b" class="form-label">Đáp án B</label>
                            <textarea type="text" class="form-control" id="answer_b" name="add_answer_b" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="answer_c" class="form-label">Đáp án C</label>
                            <textarea type="text" class="form-control" id="answer_c" name="add_answer_c" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="answer_d" class="form-label">Đáp án D</label>
                            <textarea type="text" class="form-control" id="answer_d" name="add_answer_d" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="correct_answer" class="form-label">Đáp án đúng</label>
                            <select class="form-control" id="correct_answer" name="add_correct_answer" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm câu hỏi</button>
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
                            <label for="">Chọn file (*.xlsx) hoặc tải về
                                <a target="_blank" href="">
                                    File mẫu
                                </a>
                            </label>
                            <input accept=".xlsx" name="file-excel" type="file" class="form-control">
                            <br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary luuTT">Tải lên</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var table = $('#questionsTable').DataTable();

        $('.btnExcel').click(function(){
            $('.md2 .modal-title').text('Import question');
            $('.md2').modal('show');
            $('.luuTT').click(function(){
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
                url: '{{ route('create-quiz.add') }}',
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
    </script>
@endsection
