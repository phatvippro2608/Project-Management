@extends('auth.main')

@section('contents')
    <div class="container-fluid">
        <div class="pagetitle">
            <h1>Holiday</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Holiday</li>
                </ol>
            </nav>
        </div>
        <div class="row gx-3 my-3">
            <div class="col-md-6 m-0">
                <button class="btn btn-primary" id="addHolidayBtn" data-bs-toggle="modal" data-bs-target="#addHolidayModal">
                    <i class="bi bi-plus-lg me-2"></i> Add Holiday
                </button>
                <div class="btn btn-success mx-2 btn-export">
                    <a href="{{route('holidays.export')}}"
                       class="d-flex align-items-center text-white">
                        <i class="bi bi-file-earmark-spreadsheet me-2"></i>Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="card p-2 rounded-4 border">
            <div class="card-header py-0">
                <div class="card-title my-3 p-0">Holiday List</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="holidaysTable" class="table table-hover table-borderless">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days</th>
                                <th>Year</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($holidays as $holiday)
                                <tr>
                                    <td>{{ $holiday->name }}</td>
                                    <td>{{ $holiday->start_date }}</td>
                                    <td>{{ $holiday->end_date }}</td>
                                    <td>{{ $holiday->days }}</td>
                                    <td>{{ $holiday->year }}</td>
                                    <td>
                                        <button
                                            class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                            data-id="{{ $holiday->holiday_id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        |
                                        <button
                                            class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                            data-id="{{ $holiday->holiday_id }}">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Add Holiday Modal -->
        <div class="modal fade" id="addHolidayModal" tabindex="-1" aria-labelledby="addHolidayModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addHolidayModalLabel">Add New Holiday</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addHolidayForm">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
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
                                <label for="days" class="form-label">Days</label>
                                <input type="number" class="form-control" id="days" name="days" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="month_year" class="form-label">Year</label>
                                <input type="month" class="form-control" id="month_year" name="year" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Holiday</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editHolidayModal" tabindex="-1" aria-labelledby="editHolidayModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editHolidayModalLabel">Edit Holiday</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editHolidayForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="editHolidayId" name="id">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="edit_start_date" name="start_date"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="edit_end_date" name="end_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_days" class="form-label">Days</label>
                                <input type="number" class="form-control" id="edit_days" name="days" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="edit_year" class="form-label">Year</label>
                                <input type="month" class="form-control" id="edit_year" name="year" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Edit Holiday</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#holidaysTable').DataTable({
                language: {
                    search: ""
                },
                initComplete: function(settings, json) {
                    $('.dt-search').addClass('input-group');
                    $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
                },
                responsive: true
            });

            table.buttons().container().appendTo('#holidaysTable_wrapper .col-md-6:eq(0)');

            $('#addHolidayForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('holidays.store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#addHolidayModal').modal('hide');
                            toastr.success(response.message, "Successful");

                            $('#holidaysTable').DataTable().row.add([
                                response.holiday.name,
                                response.holiday.start_date,
                                response.holiday.end_date,
                                response.holiday.days,
                                response.holiday.year,
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn" data-id="' +
                                response.holiday.holiday_id + '">' +
                                '<i class="bi bi-pencil-square"></i>' +
                                '</button>' +
                                ' | ' +
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn" data-id="' +
                                response.holiday.holiday_id + '">' +
                                '<i class="bi bi-trash3"></i>' +
                                '</button>'
                            ]).draw();

                            $('#addHolidayForm')[0].reset();
                        }
                    },
                    error: function(xhr) {
                        toastr.error(response.message, "Error");
                    }
                });
            });

            $('#holidaysTable').on('click', '.edit-btn', function() {
                var holidaysID = $(this).data('id');

                if (!holidaysID) {
                    alert('Department ID not found.');
                    return;
                }

                $.ajax({
                    url: '{{ url('leave/holidays') }}/' + holidaysID + '/edit',
                    method: 'GET',
                    success: function(response) {
                        $('#editHolidayId').val(response.holiday.holiday_id);
                        $('#edit_name').val(response.holiday.name);
                        $('#edit_start_date').val(response.holiday.start_date);
                        $('#edit_end_date').val(response.holiday.end_date);
                        $('#edit_days').val(response.holiday.days);
                        $('#edit_year').val(response.holiday.year);
                        $('#editHolidayModal').modal('show');
                    },
                    error: function(xhr) {}
                });
            });

            $('#editHolidayForm').submit(function(e) {
                e.preventDefault();
                var holidaysID = $('#editHolidayId').val();

                $.ajax({
                    url: '{{ url('leave/holidays') }}/' + holidaysID,
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#editHolidayModal').modal('hide');
                            $('#successModal').modal('show');
                            toastr.success(response.response, "Edit successful");
                            var row = table.row($('button[data-id="' + holidaysID + '"]')
                                .parents('tr'));
                            row.data([
                                response.holiday.name,
                                response.holiday.start_date,
                                response.holiday.end_date,
                                response.holiday.days,
                                response.holiday.year,
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn" data-id="' +
                                response.holiday.holiday_id + '">' +
                                '<i class="bi bi-pencil-square"></i>' +
                                '</button>' +
                                ' | ' +
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn" data-id="' +
                                response.holiday.holiday_id + '">' +
                                '<i class="bi bi-trash3"></i>' +
                                '</button>'
                            ]).draw();
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Error");
                    }
                });
            });

            $('#holidaysTable').on('click', '.delete-btn', function() {
                var holidaysID = $(this).data('id');
                var row = $(this).parents('tr');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to delete this holiday?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url = '{{ route('holidays.destroy', ':id') }}'.replace(':id',
                            holidaysID);
                        $.ajax({
                            url: url,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    table.row(row).remove().draw();
                                    toastr.success(response.message,
                                        "Deleted successfully");
                                } else {
                                    toastr.error("Failed to delete holiday.",
                                        "Operation Failed");
                                }
                            },
                            error: function(xhr) {
                                toastr.error("An error occurred.", "Operation Failed");
                            }
                        });
                    }
                });
            });

            function addDateValidation(startDateInput, endDateInput, daysInput, monthYearInput) {
                function calculateDays() {
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);

                    if (startDate && endDate && startDate <= endDate) {
                        const timeDiff = endDate.getTime() - startDate.getTime();
                        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                        daysInput.value = daysDiff;

                        endDateInput.setAttribute('min', startDateInput.value);
                    } else {
                        daysInput.value = '';
                        endDateInput.removeAttribute('min');
                    }

                    const minMonthYear = startDate.toISOString().slice(0, 7);
                    const maxMonthYear = endDate.toISOString().slice(0, 7);

                    monthYearInput.setAttribute('min', minMonthYear);
                    monthYearInput.setAttribute('max', maxMonthYear);

                    if (new Date(monthYearInput.value + '-01') < startDate || new Date(monthYearInput.value +
                            '-01') > endDate) {
                        monthYearInput.value = '';
                    }
                }

                function validateEndDate() {
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);

                    if (endDate < startDate) {
                        endDateInput.value = startDateInput.value;
                    }

                    calculateDays();
                }

                function validateDates() {
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);

                    if (endDate < startDate) {
                        endDateInput.value = startDateInput.value;
                    }

                    calculateDays();
                }

                startDateInput.addEventListener('change', validateDates);
                endDateInput.addEventListener('change', validateDates);

                startDateInput.addEventListener('input', validateDates);
                endDateInput.addEventListener('input', validateDates);
            }


            addDateValidation(
                document.getElementById('start_date'),
                document.getElementById('end_date'),
                document.getElementById('days'),
                document.getElementById('month_year')
            );

            addDateValidation(
                document.getElementById('edit_start_date'),
                document.getElementById('edit_end_date'),
                document.getElementById('edit_days'),
                document.getElementById('edit_year')
            );
        });
    </script>
@endsection
@section('head')
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
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
@section('head')
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
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
