@extends('auth.main')
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
                    <option selected disabled>Open this select menu</option>
                    @foreach($dataCostGroup as $group)
                        <option value="{{$group->project_cost_group_id}}">{{$group->project_cost_group_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="budget-data-container">
        </div>
        
        <button type="submit" class="btn btn-primary">Save changes</button>
    </form>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
    $('#costGroupSelect').on('change', function() {
        var groupId = $(this).val();
        var projectId = '{{ $contingency_price->project_id }}'; // Đảm bảo biến này được thiết lập đúng trong Blade template

        if (groupId && projectId) {
            $.ajax({
                url: '{{ route('budget.data', ['id' => '__ID__', 'costGroupId' => '__GROUP_ID__']) }}'
                    .replace('__ID__', projectId)
                    .replace('__GROUP_ID__', groupId),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var container = $('#budget-data-container');
                    container.empty();

                    if (response.length > 0) {
                        var table = '<table class="table"><thead><tr><th>Description</th><th>Labor Qty</th><th>Labor Unit</th><th>Budget Qty</th><th>Budget Unit</th><th>Labor Cost</th><th>Misc Cost</th><th>OT Budget</th><th>Per Diem Pay</th><th>Remarks</th><th>Actions</th></tr></thead><tbody>';
                        $.each(response, function(index, item) {
                            table += '<tr>' +
                                '<td><input type="text" name="description[]" value="' + item.project_cost_description + '"></td>' +
                                '<td><input type="number" name="labor_qty[]" value="' + item.project_cost_labor_qty + '"></td>' +
                                '<td><input type="text" name="labor_unit[]" value="' + item.project_cost_labor_unit + '"></td>' +
                                '<td><input type="number" name="budget_qty[]" value="' + item.project_cost_budget_qty + '"></td>' +
                                '<td><input type="text" name="budget_unit[]" value="' + item.project_budget_unit + '"></td>' +
                                '<td><input type="number" name="labor_cost[]" value="' + item.project_cost_labor_cost + '"></td>' +
                                '<td><input type="number" name="misc_cost[]" value="' + item.project_cost_misc_cost + '"></td>' +
                                '<td><input type="number" name="ot_budget[]" value="' + item.project_cost_ot_budget + '"></td>' +
                                '<td><input type="number" name="perdiem_pay[]" value="' + item.project_cost_perdiempay + '"></td>' +
                                '<td><input type="text" name="remarks[]" value="' + item.project_cost_remaks + '"></td>' +
                                '<td><button type="button" class="btn btn-primary save-btn" data-id="' + item.id + '">Save</button></td>' +
                                '</tr>';
                        });
                        table += '</tbody></table>';
                        container.html(table);
                    } else {
                        container.html('<p>Không có dữ liệu cho nhóm đã chọn.</p>');
                    }
                },
                error: function(xhr) {
                    $('#budget-data-container').html('<p>Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText + '</p>');
                }
            });
        } else {
            $('#budget-data-container').empty();
        }
    });

    // Xử lý sự kiện click của nút Save
    $(document).on('click', '.save-btn', function() {
        var button = $(this);
        var row = button.closest('tr');
        var id = button.data('id');
        
        var data = {
            id: id,
            description: row.find('input[name="description[]"]').val(),
            labor_qty: row.find('input[name="labor_qty[]"]').val(),
            labor_unit: row.find('input[name="labor_unit[]"]').val(),
            budget_qty: row.find('input[name="budget_qty[]"]').val(),
            budget_unit: row.find('input[name="budget_unit[]"]').val(),
            labor_cost: row.find('input[name="labor_cost[]"]').val(),
            misc_cost: row.find('input[name="misc_cost[]"]').val(),
            ot_budget: row.find('input[name="ot_budget[]"]').val(),
            perdiem_pay: row.find('input[name="perdiem_pay[]"]').val(),
            remarks: row.find('input[name="remarks[]"]').val()
        };

        
    });
});




</script>
@endsection
