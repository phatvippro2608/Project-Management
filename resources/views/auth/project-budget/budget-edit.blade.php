@extends('auth.main')
@section('head')
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
}
</style>
@endsection
@section('contents')
<div class="container mt-5">
    <h1 class="mb-4">Edit Budget Project: {{$contingency_price->project_name}}</h1>
    <form action="" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Select Group Of Cost</label>
            <div class="col-sm-10">
                <select class="form-select" id="costGroupSelect" aria-label="Default select example">
                    <option selected disabled>Choose Group</option>
                    @foreach($dataCostGroup as $group)
                        <option value="{{$group->project_cost_group_id}}">{{$group->project_cost_group_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Select Cost</label>
            <div class="col-sm-10">
                <select class="form-select" id="costIdSelect" aria-label="Default select example" disabled>
                    <option selected disabled>Choose Cost</option>
                    @foreach($dataCost as $cost)
                        <option data-group-id="{{$cost->project_cost_group_id}}" value="{{$cost->project_cost_id}}">{{$cost->project_cost_description}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="budget-data-container">
        </div>
        
    </form>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#costGroupSelect').on('change', function() {
            var groupId = $(this).val();
            $('#costIdSelect').prop('disabled', false).val('').find('option').each(function() {
                $(this).toggle($(this).data('group-id') == groupId);
            });
        });

        $('#costIdSelect').on('change', function() {
            var costId = $(this).val();
            var projectId = '{{ $contingency_price->project_id }}';
            var costGroupId = $('#costGroupSelect').val(); // Get selected cost group ID

            if (costId && projectId && costGroupId) {
                $.ajax({
                    url: '{{ route('budget.data', ['id' => '__ID__', 'costGroupId' => '__COST_GROUP_ID__', 'costId' => '__COST_ID__']) }}'
                        .replace('__ID__', projectId)
                        .replace('__COST_GROUP_ID__', costGroupId)
                        .replace('__COST_ID__', costId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var container = $('#budget-data-container');
                        container.empty();

                        if (response.length > 0) {
                            var formGroups = '';
                            $.each(response, function(index, item) {
                                formGroups += '<div class="row mb-3">' +
                                    '<div class="col-md-6">' +
                                        '<label for="description_' + index + '" class="form-label">Description</label>' +
                                        '<input type="text" class="form-control" name="description[]" id="description_' + index + '" value="' + (item.project_cost_description ?? '') + '">' +
                                        '<span class="text-danger error-description" id="error-description-' + index + '"></span>' +
                                    '</div>' +
                                    '<div class="col-md-6">' +
                                        '<label for="labor_qty_' + index + '" class="form-label">Labor Qty</label>' +
                                        '<input type="number" class="form-control" name="labor_qty[]" id="labor_qty_' + index + '" value="' + (item.project_cost_labor_qty ?? '') + '">' +
                                        '<span class="text-danger error-labor_qty" id="error-labor_qty-' + index + '"></span>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="row mb-3">' +
                                    '<div class="col-md-6">' +
                                        '<label for="labor_unit_' + index + '" class="form-label">Labor Unit</label>' +
                                        '<input type="text" class="form-control" name="labor_unit[]" id="labor_unit_' + index + '" value="' + (item.project_cost_labor_unit ?? '') + '">' +
                                        '<span class="text-danger error-labor_unit" id="error-labor_unit-' + index + '"></span>' +
                                    '</div>' +
                                    '<div class="col-md-6">' +
                                        '<label for="budget_qty_' + index + '" class="form-label">Budget Qty</label>' +
                                        '<input type="number" class="form-control" name="budget_qty[]" id="budget_qty_' + index + '" value="' + (item.project_cost_budget_qty ?? '') + '">' +
                                        '<span class="text-danger error-budget_qty" id="error-budget_qty-' + index + '"></span>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="row mb-3">' +
                                    '<div class="col-md-6">' +
                                        '<label for="budget_unit_' + index + '" class="form-label">Budget Unit</label>' +
                                        '<input type="text" class="form-control" name="budget_unit[]" id="budget_unit_' + index + '" value="' + (item.project_budget_unit ?? '') + '">' +
                                        '<span class="text-danger error-budget_unit" id="error-budget_unit-' + index + '"></span>' +
                                    '</div>' +
                                    '<div class="col-md-6">' +
                                        '<label for="labor_cost_' + index + '" class="form-label">Labor Cost</label>' +
                                        '<input type="number" class="form-control" name="labor_cost[]" id="labor_cost_' + index + '" value="' + (item.project_cost_labor_cost ?? '') + '">' +
                                        '<span class="text-danger error-labor_cost" id="error-labor_cost-' + index + '"></span>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="row mb-3">' +
                                    '<div class="col-md-6">' +
                                        '<label for="misc_cost_' + index + '" class="form-label">Misc Cost</label>' +
                                        '<input type="number" class="form-control" name="misc_cost[]" id="misc_cost_' + index + '" value="' + (item.project_cost_misc_cost ?? '') + '">' +
                                        '<span class="text-danger error-misc_cost" id="error-misc_cost-' + index + '"></span>' +
                                    '</div>' +
                                    '<div class="col-md-6">' +
                                        '<label for="ot_budget_' + index + '" class="form-label">OT Budget</label>' +
                                        '<input type="number" class="form-control" name="ot_budget[]" id="ot_budget_' + index + '" value="' + (item.project_cost_ot_budget ?? '') + '">' +
                                        '<span class="text-danger error-ot_budget" id="error-ot_budget-' + index + '"></span>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="row mb-3">' +
                                    '<div class="col-md-6">' +
                                        '<label for="perdiem_pay_' + index + '" class="form-label">Per Diem Pay</label>' +
                                        '<input type="number" class="form-control" name="perdiem_pay[]" id="perdiem_pay_' + index + '" value="' + (item.project_cost_perdiempay ?? '') + '">' +
                                        '<span class="text-danger error-perdiem_pay" id="error-perdiem_pay-' + index + '"></span>' +
                                    '</div>' +
                                    '<div class="col-md-6">' +
                                        '<label for="remarks_' + index + '" class="form-label">Remarks</label>' +
                                        '<input type="text" class="form-control" name="remarks[]" id="remarks_' + index + '" value="' + (item.project_cost_remaks ?? '') + '">' +
                                        '<span class="text-danger error-remarks" id="error-remarks-' + index + '"></span>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="row mb-3">' +
                                    '<div class="col-md-12">' +
                                        '<button type="button" class="btn btn-primary save-btn" data-index="' + index + '">Save</button>' +
                                    '</div>' +
                                '</div>';
                            });
                            container.html(formGroups);
                        } else {
                            container.html('<p>No budget data found for the selected ID.</p>');
                        }
                    },
                    error: function(xhr) {
                        alert('Failed to fetch data');
                    }
                });
            }
        });

        $(document).on('click', '.save-btn', function() {
            var button = $(this);
            var index = button.data('index');
            var row = button.closest('.row').siblings();
            var projectId = '{{ $contingency_price->project_id }}';
            var costGroupId = $('#costGroupSelect').val();
            var costId = $('#costIdSelect').val();

            var data = {
                description: row.find('#description_' + index).val(),
                labor_qty: row.find('#labor_qty_' + index).val(),
                labor_unit: row.find('#labor_unit_' + index).val(),
                budget_qty: row.find('#budget_qty_' + index).val(),
                budget_unit: row.find('#budget_unit_' + index).val(),
                labor_cost: row.find('#labor_cost_' + index).val(),
                misc_cost: row.find('#misc_cost_' + index).val(),
                ot_budget: row.find('#ot_budget_' + index).val(),
                perdiem_pay: row.find('#perdiem_pay_' + index).val(),
                remarks: row.find('#remarks_' + index).val()
            };

            $.ajax({
                url: '{{ route('budget.update', ['id' => '__ID__', 'costGroupId' => '__COST_GROUP_ID__', 'costId' => '__COST_ID__']) }}'
                    .replace('__ID__', projectId)
                    .replace('__COST_GROUP_ID__', costGroupId)
                    .replace('__COST_ID__', costId),
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function(response) {
                    if (response.success) {
                        toastr.success('Update successful!');
                        setTimeout(function() {
                            window.location.href = '{{ route('budget', ['id' => '__ID__']) }}'
                                .replace('__ID__', projectId);
                        }, 1000);
                    } else {
                        toastr.error('Update failed. Please try again.');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            var errorField = 'error-' + key + '-' + index;
                            $('#' + errorField).text(value[0]);
                        });
                    }
                }
            });
        });
    });
</script>
@endsection
