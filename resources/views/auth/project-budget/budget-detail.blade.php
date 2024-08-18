@extends('auth.main')
@section('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.51.0/apexcharts.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@endsection

@section('contents')
    <div class="pagetitle">
        <h1>Project Budget</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ action('App\Http\Controllers\DashboardController@getViewDashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Project List</li>
                <li class="breadcrumb-item active">Details and Costs</li>
                <li class="breadcrumb-item active">List of Expenses</li>
            </ol>
        </nav>
    </div>

    <section class="sectionBudget">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Budget Estimates of Project: <b>{{ $contingency_price->project_name }}</b></h3>
            <div class="d-flex ms-auto">
                <!-- Import Form -->
                <button type="button" class="btn btn-primary btn-sm me-2" id="importButton">Import</button>
                <!-- Hidden Import Form -->
                <form id="importForm"
                    action="{{ !empty($location) ? route('budget.import', ['project_id' => $id, 'location' => $location]) : route('budget.import', ['project_id' => $id]) }}"
                    method="POST" enctype="multipart/form-data" style="display: none;">
                    @csrf
                    <input type="file" name="file" id="fileInput" required>
                    <button type="button" id="submitButton">Submit</button>
                </form>
                <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal"
                    data-bs-target="#chooseGroupModal">Add New Cost</button>
                <a href="{{ route('budget.cost-export-csv', $id) }}" class="btn btn-primary btn-sm">Export CSV</a>
            </div>
        </div>

        <h5>LIST OF EXPENSES</h5>
        <div class="col-md-12">
            <div class="card p-2 border rounded-4">
                <div class="card-header py-0">
                    <div class="card-title my-3 p-0">Total of Expenses</div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th scope="row">SUBTOTAL</th>
                                <td>{{ number_format($total, 0, ',', '.') }} VND</td>
                            </tr>
                            <tr>
                                <th scope="row">RISK (CONTINGENCY)</th>
                                <td>{{ number_format($contingency_price->project_price_contingency, 0, ',', '.') }} VND</td>
                            </tr>
                            <tr class="table-primary">
                                <th scope="row"><b>TOTAL</b></th>
                                <td>{{ number_format($total - $contingency_price->project_price_contingency, 0, ',', '.') }}
                                    VND</td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        <h5><b>Pie Chart Of Expenses</b></h5>
        <div class="row">
            <div class="col-md-6">
                <div id="pieChart" class="container-fluid"></div>
            </div>
            <div class="col-md-6">
                <div class="card p-2 border rounded-4">
                    <div class="card-header py-0">
                        <div class="card-title my-3 p-0">Expense Statistics</div>
                    </div>
                    <div class="card-body">
                        <table id="total" class="table table-hover table-borderless">
                            <thead class="table-light">
                                <tr>
                                    <th>Group Name</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($chart as $key => $value)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ number_format($value, 0, ',', '.') }} VND</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <!-- Filter by Name -->
            <div class="col-md-3">
                <select id="nameFilter" class="form-select">
                    <option value="">Filter by name</option>
                    @foreach ($dataCostGroup as $data)
                        <option value="{{ $data->project_cost_group_name }}">{{ $data->project_cost_group_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Start Date Filter -->
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text btn btn-primary text-white" id="basic-addon1">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                    <input type="text" class="form-control" id="start_date" placeholder="Start Date" readonly>
                </div>
            </div>

            <!-- End Date Filter -->
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text btn btn-primary text-white" id="basic-addon2">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                    <input type="text" class="form-control" id="end_date" placeholder="End Date" readonly>
                </div>
            </div>

            <!-- Filter and Reset Buttons -->
            <div class="col-md-3 d-flex align-items-center">
                <button id="filter" class="btn btn-primary me-2">Filter</button>
                <button id="reset" class="btn btn-warning">Reset</button>
            </div>
        </div>


        <div class="card p-2 border rounded-4">
            <div class="card-header py-0">
                <div class="card-title my-3 p-0">Expenses Table</div>
            </div>
            <div class="card-body">
                <table id="costTable" class="table table-hover table-borderless">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" data-sort="id_source">#<i class="bi bi-sort"></i></th>
                            <th data-sort="name">GROUP NAME<i class="bi bi-sort"></i></th>
                            <th data-sort="description" style="width:15%">DESCRIPTION<i class="bi bi-sort"></i></th>
                            <th data-sort="laborqty">LABOR QTY<i class="bi bi-sort"></i></th>
                            <th data-sort="budgetqty">BUDGET QTY <i class="bi bi-sort"></i></th>
                            <th data-sort="laborcost">LABOR COST <i class="bi bi-sort"></i></th>
                            <th data-sort="misccost">MISC. COST<i class="bi bi-sort"></i></th>
                            <th data-sort="otbudget">OT BUDGET<i class="bi bi-sort"></i></th>
                            <th data-sort="perdiempay">PER DIEM PAY<i class="bi bi-sort"></i></th>
                            <th data-sort="date">DATE<i class="bi bi-sort"></i></th>
                            <th data-sort="subtotal">SUBTOTAL<i class="bi bi-sort"></i></th>
                        </tr>
                    </thead>
                    @php
                        $i = 1;
                        $subtotal1 = 0;
                    @endphp
                    <tbody>

                        @foreach ($dataCost as $data)
                            <tr style="cursor: pointer"
                                data="{{ \App\Http\Controllers\AccountController::toAttrJson(\App\Http\Controllers\ProjectBudgetController::getCostDetails($data->project_cost_id)) }}">
                                @php
                                    $subtotal = 0;
                                @endphp
                                <td class="text-center" id="{{ $data->project_cost_id }}">{{ $i++ }}</td>
                                <td id="name" name="name">{{ $data->project_cost_group_name }}</td>
                                <td>{{ $data->project_cost_description }}</td>
                                <td>{{ $data->project_cost_labor_qty }}</td>
                                <td>{{ $data->project_cost_budget_qty }}</td>
                                <td>{{ $data->project_cost_labor_cost }}</td>
                                <td>{{ $data->project_cost_misc_cost }}</td>
                                <td>{{ $data->project_cost_ot_budget }}</td>
                                <td>{{ $data->project_cost_perdiempay }}</td>
                                @php
                                    $subtotal =
                                        $data->project_cost_labor_qty *
                                        $data->project_cost_budget_qty *
                                        ($data->project_cost_labor_cost +
                                            $data->project_cost_misc_cost +
                                            $data->project_cost_ot_budget +
                                            $data->project_cost_perdiempay);
                                    $subtotal1 += $subtotal;
                                @endphp
                                <td>{{ \Carbon\Carbon::parse($data->create_date)->format('d M Y') }}</td>
                                <td style="text-align:right">{{ number_format($subtotal, 0, ',', '.') }} VND</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </section>

    <!-- Choose Group Modal -->
    <div class="modal fade" id="chooseGroupModal" tabindex="-1" role="dialog" aria-labelledby="chooseGroupModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chooseGroupModalLabel">Add New Cost</h5>
                </div>
                <form id="chooseGroupForm" method="POST"
                    action="{{ route('budget.addNewCost', ['project_id' => $id]) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="existingGroup">Select Existing Cost Group Name</label>
                            <select class="form-control" id="existingGroup" name="existingGroup">
                                <option value="">-- Select a Cost Group Name --</option>
                                @foreach ($dataCostGroup as $costGroup)
                                    <option value="{{ $costGroup->project_cost_group_id }}">
                                        {{ $costGroup->project_cost_group_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="costGroupDetails" class="mt-3">
                            <!-- Dynamic content will be loaded here -->
                        </div>
                        <div class="form-group mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#addNewGroupModal">Or Add New Group Name</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add New Group Modal -->
    <div class="modal fade" id="addNewGroupModal" tabindex="-1" role="dialog" aria-labelledby="addNewGroupModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewGroupModalLabel">Add New Cost Group Name</h5>
                </div>
                <form id="addNewGroupForm" method="POST"
                    action="{{ !empty($location) ? route('budget.createCostGroup', ['project_id' => $id, 'location' => $location]) : route('budget.createCostGroup', ['project_id' => $id]) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="newGroupName">Cost Group Name</label>
                            <input type="text" class="form-control" id="newGroupName" name="newGroupName" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Expense Detail Modal -->
    <div class="modal fade" id="detailCostModal" tabindex="-1" aria-labelledby="detailCostModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailCostModalLabel">Detail Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Nội dung chi tiết chi phí sẽ được hiển thị ở đây -->
                    <div id="costDetails">
                        <!-- Nội dung động từ AJAX sẽ được chèn vào đây -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.getElementById('importButton').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });

        document.getElementById('fileInput').addEventListener('change', function() {
            document.getElementById('submitButton').click();
        });

        $('#submitButton').on('click', function(event) {
            event.preventDefault();

            var fileInput = $('#fileInput')[0];
            var file = fileInput.files[0];

            if (!file) {
                toastr.error('Please select a file before submitting.');
                return;
            }

            var formData = new FormData();
            formData.append('file', file);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            var url = $('#importForm').attr('action');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success) {
                        toastr.success('Import successful!');
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    } else {
                        toastr.error('Import failed: ' + (data.message || 'Unknown error'));
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('Error: ' + errorThrown);
                }
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            var seriesData = [];
            var labelsData = [];

            @foreach ($chart as $key => $value)
                seriesData.push({{ $value }});
                labelsData.push('{{ $key }}');
            @endforeach

            new ApexCharts(document.querySelector("#pieChart"), {
                series: seriesData,
                chart: {
                    height: 350,
                    type: 'pie',
                    toolbar: {
                        show: true
                    }
                },
                labels: labelsData,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            }).render();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#selectGroup').on('change', function() {
                var selectedGroupId = $(this).val();
                var url =
                    '{{ route('budget.viewCost', ['project_id' => $id, 'group_id' => '__GROUP_ID__']) }}'
                    .replace('__GROUP_ID__', selectedGroupId);

                if (selectedGroupId) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(response) {
                            if (response.success) {
                                $('#costGroupDetails').html(response.html);
                            } else {
                                $('#costGroupDetails').html(
                                    '<p class="text-danger">Failed to load details.</p>');
                            }
                        },
                        error: function(xhr) {
                            $('#costGroupDetails').html('<p class="text-danger">Error: ' + xhr
                                .status + ' ' + xhr.statusText + '</p>');
                        }
                    });
                } else {
                    $('#costGroupDetails').empty();
                }
            });
            $('#addNewGroupForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#addNewGroupModal').modal('hide');
                            setTimeout(function() {
                                location.reload();
                            }, 500);
                        } else {
                            toastr.error('Failed to add new cost group: ' + (response.message ||
                                'Unknown error'));
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error: ' + xhr.status + ' ' + xhr.statusText);
                    }
                });
            });

            // Rename button click handler
            $('.rename-btn').on('click', function() {
                var costGroupId = $(this).data('id');
                var costGroupName = $(this).data('name');
                $('#costGroupId').val(costGroupId);
                $('#costGroupName').val(costGroupName);
            });
        });

        // Handle adding new cost form submission
        $('#chooseGroupForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.includes('<html>')) {
                        toastr.success('New cost added successfully!');
                        $('#chooseGroupModal').modal('hide');
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    } else {
                        toastr.error('Failed to add new cost: ' + (response.message ||
                            'Unknown error'));
                    }
                },
                error: function(xhr) {
                    toastr.error('Error: ' + xhr.status + ' ' + xhr.statusText);
                }
            });
        });

        //Handle existing group on modal form
        document.addEventListener("DOMContentLoaded", function() {
            $('#existingGroup').on('change', function() {
                var selectedGroupId = $(this).val();
                var url =
                    '{{ route('budget.getCostGroupDetails', ['project_id' => $id, 'group_id' => '__GROUP_ID__']) }}'
                    .replace('__GROUP_ID__', selectedGroupId);

                if (selectedGroupId) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(response) {
                            if (response.success) {
                                $('#costGroupDetails').html(response.html);
                            } else {
                                $('#costGroupDetails').html(
                                    '<p class="text-danger">Failed to load details.</p>');
                            }
                        },
                        error: function(xhr) {
                            $('#costGroupDetails').html('<p class="text-danger">Error: ' + xhr
                                .status + ' ' + xhr.statusText + '</p>');
                        }
                    });
                } else {
                    $('#costGroupDetails').empty();
                }
            });
        });
        $(document).ready(function() {
            // Initialize datepickers
            $("#start_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $("#end_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });

            function filterTable() {
                var nameFilter = $('#nameFilter').val().toLowerCase();
                var startDateFilter = $('#start_date').val();
                var endDateFilter = $('#end_date').val();

                $('#costTable tbody tr').each(function() {
                    var row = $(this);
                    var nameText = row.find('td:eq(1)').text().toLowerCase(); // Name column (second column)
                    var dateText = row.find('td:eq(9)').text(); // Date column (tenth column)

                    // Parse date string into a Date object
                    var dateObj = new Date(dateText);

                    var isNameMatch = !nameFilter || nameText.indexOf(nameFilter) > -1;
                    var isDateMatch = (!startDateFilter && !endDateFilter) ||
                        (startDateFilter && endDateFilter && dateObj >= new Date(startDateFilter) &&
                            dateObj <= new Date(endDateFilter)) ||
                        (startDateFilter && !endDateFilter && dateObj >= new Date(startDateFilter)) ||
                        (!startDateFilter && endDateFilter && dateObj <= new Date(endDateFilter));

                    if (isNameMatch && isDateMatch) {
                        row.show();
                    } else {
                        row.hide();
                    }
                });
            }

            $('#nameFilter, #start_date, #end_date').on('change', filterTable);

            // Reset filters
            $('#reset').on('click', function() {
                $('#nameFilter').val('');
                $('#start_date').val('');
                $('#end_date').val('');
                filterTable();
            });
        });
        // Bắt sự kiện click vào hàng trong bảng
        $(document).ready(function() {
            $('#costTable tbody').on('click', 'tr', function() {
                var data = JSON.parse($(this).attr('data'));
                data = data.original.data
                var html = `
                    <table class="table">
                    <tbody>
                        <tr><th>Group Name</th><td>${data.project_cost_group_name || ''}</td></tr>
                        <tr><th>Description</th><td>${data.project_cost_description || ''}</td></tr>
                        <tr><th>Labor QTY</th><td>${data.project_cost_labor_qty || ''}</td></tr>
                        <tr><th>Labor Unit</th><td>${data.project_cost_labor_unit || ''}</td></tr>
                        <tr><th>Budget QTY</th><td>${data.project_cost_budget_qty || ''}</td></tr>
                        <tr><th>Budget Unit</th><td>${data.project_budget_unit || ''}</td></tr>
                        <tr><th>Labor Cost</th><td>${data.project_cost_labor_cost || ''}</td></tr>
                        <tr><th>Misc. Cost</th><td>${data.project_cost_misc_cost || ''}</td></tr>
                        <tr><th>OT Budget</th><td>${data.project_cost_ot_budget || ''}</td></tr>
                        <tr><th>Per Diem Pay</th><td>${data.project_cost_perdiempay || ''}</td></tr>
                        <tr><th>Remark</th><td>${data.project_cost_remaks || ''}</td></tr>
                    </tbody>
                    </table>
                `;
                $('#costDetails').html(html);
                $('#detailCostModal').modal('show');

            });
        });
        new DataTable('#costTable');
        new DataTable('#total');
    </script>
@endsection
