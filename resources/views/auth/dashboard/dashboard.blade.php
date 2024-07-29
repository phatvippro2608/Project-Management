@extends('auth.main')

@section('contents')
    <section class="section dashboard">
        <div class="row gx-5">
            <div class="col-lg-12">
                <div class="row">
                    <h4 class="fw-bold mb-3"><i class="bi bi-bar-chart-line"></i> Tổng quan</h4>
                    <div class="col-xxl-3 col-xl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title"><b>Employee</b></h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$em_c}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-xl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title"><b>Team</b></h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$team_c}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-xl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title"><b>Project</b></h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-briefcase"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$project_c}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-xl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title"><b>Task</b></h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-list-task"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>@if($task_c)
                                                {{$task_c}}
                                            @else
                                                0
                                            @endif</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-xl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title"><b>Subtask</b></h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-list-task"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>@if($sub_c)
                                                {{$sub_c}}
                                            @else
                                                0
                                            @endif</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row gy-3">
            <div class="col-lg-8">
                <h4 class="fw-bold mb-3"><i class="bi bi-clock-history"></i> Recent</h4>
                <div class="border-0 rounded-4 shadow bg-white" style="min-height: 300px">
                    <div class="p-3">
                        <div class="row">
                            @foreach($recent_project as $item)
                                <div class="col-lg-12">
                                    <a href="../project/{{$item->project_id}}" class="card card-hover shadow-none">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-briefcase"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6 class="fw-bolder">{{$item->project_name}}</h6>
                                                    <span
                                                        class="text-success small pt-1 fw-bold">{{$item->created_at}}</span>
                                                    <span class="text-muted small pt-2 ps-1"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <h4 class="fw-bold mb-3"><i class="bi bi-list-task"></i> Todo list</h4>
                <div class="border-0 rounded-4 shadow bg-white" style="min-height: 300px">
                    <div class="p-3">
                        <div class="card bg-white shadow-none">
                            <div class="card-header">
                                <div class="card-title">{{\App\Http\Controllers\AccountController::getNow()}}</div>
                            </div>


                        </div>
                        <div class="row">
                            @foreach($tasks as $item)
                                @if($item->state == 2)
                                    <div class="card card-hover shadow-none m-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div
                                                    class="todo-event d-flex align-items-center text-success">
                                                    <h5 class="m-0 ms-2">{{$item->task_name}}</h5>
                                                </div>
                                                <div class="todo-time">
                                                    <input
                                                        type="checkbox"
                                                        class="rounded-checkbox"
                                                        id="checkbox"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($item->state == 1)
                                    <div class="card card-hover shadow-none m-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div
                                                    class="todo-event d-flex align-items-center text-success text-decoration-line-through">
                                                    <h5 class="m-0 ms-2">{{$item->task_name}}</h5>
                                                </div>
                                                <div class="todo-time">
                                                    <input
                                                        type="checkbox"
                                                        class="rounded-checkbox"
                                                        id="checkbox"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @foreach($subtasks as $sub_item)
                                    @if($item->state == 2 && $sub_item->task_id == $item->task_id)
                                        <div class="card card-hover shadow-none m-0">
                                            <div class="card-body p-3">
                                                <div class="ms-3 d-flex align-items-center justify-content-between">
                                                    <div
                                                        class="todo-event d-flex align-items-center text-success">
                                                        <h5 class="m-0 ms-2">{{$sub_item->sub_task_name}}</h5>
                                                    </div>
                                                    <div class="todo-time">
                                                        <input
                                                            type="checkbox"
                                                            class="rounded-checkbox"
                                                            id="checkbox"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($item->state == 1 && $sub_item->task_id == $item->task_id)
                                        <div class="card card-hover shadow-none m-0">
                                            <div class="card-body p-3">
                                                <div class="ms-3 d-flex align-items-center justify-content-between">
                                                    <div
                                                        class="todo-event d-flex align-items-center text-success text-decoration-line-through">
                                                        <h5 class="m-0 ms-2">{{$sub_item->sub_task_name}}</h5>
                                                    </div>
                                                    <div class="todo-time">
                                                        <input
                                                            type="checkbox"
                                                            class="rounded-checkbox"
                                                            id="checkbox"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
