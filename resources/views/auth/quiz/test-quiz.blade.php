@extends('auth.quiz.main-quiz')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card rounded-bottom-4">
                    <div class="card-header p-0" style="background: var(--clr-1)">
                        <div class="progress" style="height: 3px" role="progressbar" aria-label="Basic example"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-danger fade-animation" style="width: 30%;"></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between p-3">
                            <h5 class="fw-bold text-white mb-0">
                                <i class="bi bi-question-circle me-2"></i> Question 1/30
                            </h5>
                            <div class="rounded-circle bg-light p-2">
                                <span class="fw-bold text-danger">60s</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-3">
                        <h5 class="fw-bold">Câu 1: Câu hỏi sẽ ở đây</h5>
                        <div class="my-2 w-100" style="height: 200px">
                            <img class="w-100 h-100 object-fit-contain" src="{{asset('assets/img/news-1.jpg')}}" alt="">
                        </div>
                        <div class="form-check my-3">
                            <input class="form-check-input" type="radio" name="answer" id="ansA">
                            <label class="form-check-label" for="ansA">
                                <strong class="me-2">A:</strong>Nội dung câu trả lời
                            </label>
                        </div>
                        <div class="form-check my-3">
                            <input class="form-check-input" type="radio" name="answer" id="ansB" >
                            <label class="form-check-label" for="ansB">
                                <strong class="me-2">B:</strong>Nội dung câu trả lời
                            </label>
                        </div>
                        <div class="form-check my-3">
                            <input class="form-check-input" type="radio" name="answer" id="ansC">
                            <label class="form-check-label" for="ansC">
                                <strong class="me-2">C:</strong>Nội dung câu trả lời
                            </label>
                        </div>
                        <div class="form-check my-3">
                            <input class="form-check-input" type="radio" name="answer" id="ansD">
                            <label class="form-check-label" for="ansD">
                                <strong class="me-2">D:</strong>Nội dung câu trả lời
                            </label>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <div class="btn btn-primary">
                            <i class="bi bi-arrow-right me-2"></i>
                            Next
                        </div>
                        <div class="btn btn-danger">
                            <i class="bi bi-check2-all me-2"></i>
                            Submit
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card rounded-4 border">
                    <div class="card-body p-3">
                        <div class="row gy-2">
                            <div class="col-xl-3 col-sm-3 col-4 text-center">
                                <button class="btn btn-success rounded-4 px-4 fw-bold">01</button>
                            </div>
                            <div class="col-xl-3 col-sm-3 col-4 text-center">
                                <button class="btn btn-outline-primary rounded-4 px-4 fw-bold">12</button>
                            </div>
                            <div class="col-xl-3 col-sm-3 col-4 text-center">
                                <button class="btn btn-outline-primary rounded-4 px-4 fw-bold">13</button>
                            </div>
                            <div class="col-xl-3 col-sm-3 col-4 text-center">
                                <button class="btn btn-primary rounded-4 px-4 fw-bold">15</button>
                            </div>
                            <div class="col-xl-3 col-sm-3 col-4 text-center">
                                <button class="btn btn-outline-primary rounded-4 px-4 fw-bold">16</button>
                            </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

    </script>
@endsection
