@extends('auth.main')
@section('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.51.0/apexcharts.min.js"></script>
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
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Project Budget Summary</li>
                <li class="breadcrumb-item active">LIST OF EXPENSES</li>
            </ol>
        </nav>
    </div>

    <section class="sectionBudget">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Budget Estimates of Project: <b>{{ $contingency_price->project_name }}</b></h3>
            <div class="d-flex ms-auto">
                <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal"
                    data-bs-target="#chooseGroupModal">Add New Cost</button>
                <a href="{{ route('budget.edit', $id) }}" class="btn btn-primary btn-sm me-2">Edit</a>
                <a href="{{ route('budget.cost-export-csv', $id) }}" class="btn btn-primary btn-sm">Export CSV</a>
                </div>
        </div>

        <h5>LIST OF EXPENSES</h5>
        <table class="table table-bordered">
            @php
                $sumOfTotal = 0;
            @endphp
            <tbody>
                <tr>
                    <td scope="row">SUBTOTAL</td>
                    <td id="sumOfTotal">0 VND</td>
                </tr>
                <tr>
                    <td scope="row">RISK (CONTINGENCY)</td>
                    <td>{{ number_format($contingency_price->project_price_contingency, 0, ',', '.') }} VND</td>
                </tr>
                <tr class="table-primary">
                    <td scope="row"><b>TOTAL</b></td>
                    <td id="allTotal"></td>
                </tr>
            </tbody>
        </table>

        <h5><b>Pie Chart Of Cost</b></h5>
        <div>
            <div class="col-4"></div>
            <div class="col-8">
                <div id="pieChart" class="container-fluid"></div>
            </div>
        </div>
        
        <select class="form-select" id="selectGroup">
        @foreach($dataCostGroup as $group)
            <option value="{{$group->project_cost_group_id}}">{{$group->project_cost_group_id}} - {{$group->project_cost_group_name}}</option>
        @endforeach

        </select>
        
    </section>

    <!-- Choose Group Modal -->
    <div class="modal fade" id="chooseGroupModal" tabindex="-1" role="dialog" aria-labelledby="chooseGroupModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chooseGroupModalLabel">Add New Cost</h5>
                </div>
                <form id="chooseGroupForm" method="POST" action="{{ route('budget.addNewCost', ['id' => $id]) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="existingGroup">Select Existing Cost Group</label>
                            <select class="form-control" id="existingGroup" name="existingGroup">
                                <option value="">-- Select a Cost Group --</option>
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
                                data-bs-target="#addNewGroupModal">Or Add New Group</button>
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
                    <h5 class="modal-title" id="addNewGroupModalLabel">Add New Cost Group</h5>
                </div>
                <form id="addNewGroupForm" method="POST" action="{{ route('budget.createCostGroup', ['id' => $id]) }}">
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var sumOfTotal = {{ $sumOfTotal }};
            var allTotal = {{ $sumOfTotal + $contingency_price->project_price_contingency }};
            document.getElementById('sumOfTotal').innerHTML = new Intl.NumberFormat().format(sumOfTotal) + ' VND';
            document.getElementById('allTotal').innerHTML = '<span style="font-weight:bold;">' + new Intl
                .NumberFormat().format(allTotal) + ' VND</span>';

            new ApexCharts(document.querySelector("#pieChart"), {
                series: [0,0,0,0,0,0
                ],
                chart: {
                    height: 350,
                    type: 'pie',
                    toolbar: {
                        show: true
                    }
                },
                labels: ['Cost Of Labor', 'Cost Of Travel', 'Cost Of Renting', 'Cost Of Other',
                    'Cost Of BackOffice', 'Cost Of Commissision'
                ]
            }).render();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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

            // Delete button click handler
            $('.delete-btn').on('click', function() {
                var costId = $(this).data('id');
                var projectId = '{{ $id }}';
                var url =
                    '{{ route('budget.delete', ['project_id' => '__PROJECT_ID__', 'cost_id' => '__COST_ID__']) }}'
                    .replace('__PROJECT_ID__', projectId)
                    .replace('__COST_ID__', costId);
                console.log(costId);
                if (confirm('Are you sure you want to delete this cost item?')) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Deleted successfully!');
                                setTimeout(function() {
                                    location.reload();
                                }, 500);
                            } else {
                                toastr.error('Delete Failed: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Delete Failed: ' + xhr.status + ' ' + xhr.statusText);
                        }
                    });
                }
            });

            // Rename button click handler
            $('.rename-btn').on('click', function() {
                var costGroupId = $(this).data('id');
                var costGroupName = $(this).data('name');
                $('#costGroupId').val(costGroupId);
                $('#costGroupName').val(costGroupName);
            });
        });
        //delete button cost group
        $('.delete-group-btn').on('click', function() {
            var costGroupId = $(this).data('id');
            var costGroupName = $(this).data('name');
            var projectId = '{{ $id }}';

            if (confirm('Are you sure you want to delete the cost group "' + costGroupName + '"? It also removes the costs within this group!!!')) {
                var url =
                    '{{ route('budget.deleteCostGroup', ['project_id' => '__PROJECT_ID__', 'cost_group_id' => '__GROUP_ID__']) }}'
                    .replace('__PROJECT_ID__', projectId)
                    .replace('__GROUP_ID__', costGroupId);
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Cost group deleted successfully!');
                            setTimeout(function() {
                                location.reload();
                            }, 500);
                        } else {
                            toastr.error('Delete Failed: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Delete Failed: ' + xhr.status + ' ' + xhr.statusText);
                    }
                });
            }
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
                    '{{ route('budget.getCostGroupDetails', ['id' => $id, 'group_id' => '__GROUP_ID__']) }}'
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
    </script>
@endsection
