@extends('auth.main')

@section('contents')
    <section class="section dashboard">
        <div class="row gx-5">
            <div class="col-lg-12">
                <div class="row">
                    <h4 class="fw-bold mb-3"><i class="bi bi-bar-chart-line"> </i>{{ __('messages.overview') }}</h4>

                    <div class="col-xxl-3 col-xl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title"><b>{{ __('messages.department') }}</b></h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-list-task"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>@if($department_c)
                                                {{$department_c}}
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
                                <h5 class="card-title"><b>{{ __('messages.employee') }}</b></h5>
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
                                <h5 class="card-title"><b>{{ __('messages.team') }}</b></h5>
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
                                <h5 class="card-title"><b>{{ __('messages.project') }}</b></h5>
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
                                <h5 class="card-title"><b>{{ __('messages.task') }}</b></h5>
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
                                <h5 class="card-title"><b>{{ __('messages.subtask') }}</b></h5>
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
            <div class="col-xl-8 col-lg-6">
                <h4 class="fw-bold mb-3"><i class="bi bi-clock-history"> </i>{{ __('messages.recent_project') }}</h4>
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
            <div class="col-xl-4 col-lg-6">
                <h4 class="fw-bold mb-3"><i class="bi bi-list-task"> </i>{{ __('messages.todolist') }}</h4>
                <div class="border-0 rounded-4 shadow bg-white" style="min-height: 300px">
                    <div class="p-3">
                        <div class="card bg-white shadow-none">
                            <div class="card-header">
                                <div class="card-title">{{\App\Http\Controllers\AccountController::getNow()}}</div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach($tasks as $item)
                                @if($item->state == 1)
                                    <a href="{{route('root').'/task/'.$item->task_id}}"  class="card card-hover shadow-none m-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div
                                                    class="todo-event-{{$item->task_id}} d-flex align-items-center text-success">
                                                    <h5 class="m-0 ms-2">{{$item->task_name}}</h5>
                                                </div>
                                                <div class="todo-time">
                                                    <input
                                                        type="checkbox"
                                                        class="update-todo rounded-checkbox"
                                                        id="{{$item->task_id}}"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @elseif($item->state == 2)
                                    <a href="{{route('root').'/task/'.$item->task_id}}" class="card card-hover shadow-none m-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div
                                                    class="todo-event-{{$item->task_id}} d-flex align-items-center text-success text-decoration-line-through">
                                                    <h5 class="m-0 ms-2">{{$item->task_name}}</h5>
                                                </div>
                                                <div class="todo-time">
                                                    <input
                                                        type="checkbox"
                                                        class="update-todo rounded-checkbox"
                                                        id="{{$item->task_id}}"
                                                        checked
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $('.update-todo').change(function () {
            var task_id = $(this).attr('id');
            console.log($(this).attr('checked'))
            $(this).prop('checked') ? $('.todo-event-' + task_id).addClass('text-decoration-line-through') : $('.todo-event-' + task_id).removeClass('text-decoration-line-through')
            $.ajax({
                url: `{{action('App\Http\Controllers\DashboardController@UpdateTodo')}}`,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'task_id': task_id,
                },
                success: function (result) {
                }
            });
        })
    </script>
@endsection
