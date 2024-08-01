@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Earn Leave</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Earn Leave</li>
            </ol>
        </nav>
    </div>
    <div class="rounded-4">
        <div class="card">
            <div class="card-header py-0">
                <div class="card-title my-3 p-0">Earn Balance</div>
            </div>
            <div class="card-body">
                <div class="row gx-3 my-3">
                    <div class="col-md-6 m-0">
                        <button class="btn btn-primary" id="addApplicationBtn" data-bs-toggle="modal" data-bs-target="#addApplicationModal">
                            <i class="bi bi-plus-lg me-2"></i> Assign Earned Leave
                        </button>
                    </div>
                </div>
                <table id="applicationTable" class="table table-borderless table-hover">
                    <thead class="table-light">
                    <tr>
                        <th class="text-start">Employee Pin</th>
                        <th>Employee Name</th>
                        <th>Total Hour</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($earn_leave as $item)
                        <tr>
                            <td class="text-start">{{$item->employee_code}}</td>
                            <td>{{$item->employee_name}}</td>
                            <td>{{$item->totalhour}} hour</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Application Modal -->
    <div class="modal fade" id="addApplicationModal" tabindex="-1" aria-labelledby="addApplicationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addApplicationModalLabel">Add New Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addApplicationForm">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Employee Name</label>
                            {{--                            <input type="text" class="form-control" id="employee_name" name="employee_name" required>--}}
                            <select class="form-select" aria-label="Default" name="employee_id">
                                <option value="">No select</option>
{{--                                @foreach($employee_name as $item)--}}
{{--                                    <option value="{{$item->id_employee}}">{{$item->employee_code}}--}}
{{--                                        - {{$item->first_name}} {{$item->last_name}}</option>--}}
{{--                                @endforeach--}}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">PIN</label>
                            <input type="text" class="form-control" id="pin" name="pin" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Leave Type</label>
                            {{--                            <input type="text" class="form-control" id="leave_type" name="leave_type" required>--}}
                            <select class="form-select" aria-label="Default" name="leave_type">
                                <option value="">No select</option>
{{--                                @foreach($leave_type as $item)--}}
{{--                                    <option value="{{$item->id}}">{{$item->leave_type}}</option>--}}
{{--                                @endforeach--}}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Apply Date</label>
                            <input type="date" class="form-control" id="apply_date" name="apply_date" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Duration</label>
                            <input type="text" class="form-control" id="duration" name="duration" required>
                        </div>
                        <div class="mb-3">
                            <label for="days" class="form-label">Leaves Status</label>
                            <input type="number" class="form-control" id="leave_status" name="leave_status" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="editApplicationModal" tabindex="-1" aria-labelledby="editApplicationModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editApplicationModalLabel">Edit Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editApplicationForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Employee Name</label>
                            {{--                            <input type="text" class="form-control" id="employee_name" name="employee_name" required>--}}
                            <select class="form-select" aria-label="Default" name="employee_id" id="edit_employee_id">
                                <option value="">No select</option>
{{--                                @foreach($employee_name as $item)--}}
{{--                                    <option value="{{$item->id_employee}}">{{$item->employee_code}}--}}
{{--                                        - {{$item->first_name}} {{$item->last_name}}</option>--}}
{{--                                @endforeach--}}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">PIN</label>
                            <input type="text" class="form-control" id="edit_pin" name="pin" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Leave Type</label>
                            {{--                            <input type="text" class="form-control" id="leave_type" name="leave_type" required>--}}
                            <select class="form-select" aria-label="Default" name="leave_type" id="edit_leave_type">
                                <option value="">No select</option>
{{--                                @foreach($leave_type as $item)--}}
{{--                                    <option value="{{$item->id}}">{{$item->leave_type}}</option>--}}
{{--                                @endforeach--}}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Apply Date</label>
                            <input type="date" class="form-control" id="edit_apply_date" name="apply_date" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="edit_start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="edit_end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Duration</label>
                            <input type="text" class="form-control" id="edit_duration" name="duration" required>
                        </div>
                        <div class="mb-3">
                            <label for="days" class="form-label">Leaves Status</label>
                            <input type="number" class="form-control" id="edit_leave_status" name="leave_status" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Edit Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#applicationTable').DataTable({
                language: { search: "" },
                initComplete: function (settings, json) {
                    $('.dt-search').addClass('input-group');
                    $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
                },
                responsive: true
            })
        });
    </script>
@endsection

@section('head')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.flash.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.print.min.js">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
