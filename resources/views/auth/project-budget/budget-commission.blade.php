@extends('auth.main')
@section('contents')
<div class="pagetitle">
    <h1>Project Budget</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Project Budget Summary</li>
            <li class="breadcrumb-item active">LIST OF COMMISSION</li>
        </ol>
    </nav>
</div>
<div class="d-flex justify-content-between align-items-center">
    <h3>List Of Commission Project: <b>{{ $project->project_name }}</b></h3>
    <a href="{{ route('budget.export.csv', $project->project_id) }}" class="btn btn-success"><i class="bi bi-file-earmark-arrow-down pe-2"></i>Export CSV</a>
</div>
<table id="projectsTable" class="table table-bordered">
    <thead>
        <tr class="table-dark text-center align-middle">
            <th>ID</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i=1;
        @endphp
        @foreach ($dataGroupCommission as $commissionGroup)
            <tr class="table-warning">
                <th colspan="4">{{ $commissionGroup->groupcommission_name }}</th>
            </tr>
            @foreach ($dataCommission as $data)
                @if ($data->project_id == $id && $data->groupcommission_id == $commissionGroup->group_id)
                    <tr>
                        <td class="text-center align-middle" id="{{ $data->commission_id }}">{{$i++}}</td>
                        <td>{{ $data->description }}</td>
                        <td>{{ number_format($data->amount, 0, ',', '.') }} VND</td>
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-info btn-sm w-auto rename-btn"
                                    data-id="{{ $data->commission_id }}"
                                    data-name="{{ $data->commission_id }}" data-bs-toggle="modal"><i class="bi bi-pencil-square"></i></button>
                            <button type="button"
                                    class="btn btn-danger btn-sm delete-btn"
                                    data-id="{{ $data->commission_id }}"><i class="bi bi-trash3"></i></button>
                        </td>
                    </tr>
                    <tr><td colspan="4"></td></tr>
                @endif
            @endforeach
        @endforeach
    </tbody>
</table>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('.delete-btn').on('click', function() {
            var costCommissionId = $(this).data('id');
            var projectId = '{{ $id }}';
            var url = '{{ route('budget.deleteCommission', ['project_id' => '__PROJECT_ID__', 'cost_commission_id' => '__COST_COMMISSION_ID']) }}'
                .replace('__PROJECT_ID__', projectId)
                .replace('__COST_COMMISSION_ID', costCommissionId);
                
        console.log(url);
    
            if (confirm('Are you sure you want to delete this cost commission item?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Delete successfully!');
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
    });
</script>
@endsection
