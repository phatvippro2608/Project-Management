@extends('auth.main-edu')

@section('head')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
@endsection

@section('contents')
    <div class="container mt-4">
        <div class="pagetitle">
            <h1 class="mb-4">Create Quiz</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../lms">Home</a></li>
                    <li class="breadcrumb-item"><a href="./">Quiz</a></li>
                    <li class="breadcrumb-item active">Create Quiz</li>
                </ol>
            </nav>
        </div>
        <div class="row mb-4">
            <div class="col-md-12">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addQuestionModal">Add question</button>
                <button class="btn btn-secondary" data-toggle="modal" data-target="#uploadExcelModal">Import
                    question</button>
                <button class="btn btn-success" id="export_excel">Export question</button>
            </div>
        </div>
        <div class="card p-2 rounded-4 border">
            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="courseSelect">Chọn khóa học</label>
                    <select class="form-control" id="courseSelect">

                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table id="questionsTable" class="display">
                        <thead>
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
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Thêm câu hỏi -->
    <div class="modal fade" id="addQuestionModal" tabindex="-1" role="dialog" aria-labelledby="addQuestionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addQuestionModalLabel">Thêm câu hỏi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addQuestionForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="course_name">Tên khóa học</label>
                            <select class="form-control" id="course_name" name="course_name" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="question">Câu hỏi</label>
                            <textarea class="form-control" id="question" name="question" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="question_img">Hình ảnh</label>
                            <input type="file" class="form-control" id="question_img" name="question_img"
                                accept="image/*">
                        </div>
                        <div class="form-group">
                            <label for="answer_a">Đáp án A</label>
                            <input type="text" class="form-control" id="answer_a" name="answer_a" required>
                        </div>
                        <div class="form-group">
                            <label for="answer_b">Đáp án B</label>
                            <input type="text" class="form-control" id="answer_b" name="answer_b" required>
                        </div>
                        <div class="form-group">
                            <label for="answer_c">Đáp án C</label>
                            <input type="text" class="form-control" id="answer_c" name="answer_c" required>
                        </div>
                        <div class="form-group">
                            <label for="answer_d">Đáp án D</label>
                            <input type="text" class="form-control" id="answer_d" name="answer_d" required>
                        </div>
                        <div class="form-group">
                            <label for="correct_answer">Đáp án đúng</label>
                            <select class="form-control" id="correct_answer" name="correct_answer" required>
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

    <!-- Modal Thêm bằng Excel -->
    <div class="modal fade" id="uploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadExcelModalLabel">Tải lên file Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="uploadExcelForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="excelFile">Chọn file Excel</label>
                            <input type="file" class="form-control" id="excelFile" name="excelFile"
                                accept=".xls,.xlsx" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tải lên</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/js/datatables.js') }}"></script>
    <script src="{{ asset('assets/js/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/js/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ asset('assets/js/filepond-plugin-image-overlay.min.js') }}"></script>
    <script type="text/javascript" src="https://unpkg.com/vis-timeline@latest/standalone/umd/vis-timeline-graph2d.min.js">
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var table = $('#questionsTable').DataTable();
    </script>
@endsection
