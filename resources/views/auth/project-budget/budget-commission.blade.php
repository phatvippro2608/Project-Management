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
</div>
<table id="projectsTable" class="table table-bordered">
    <thead>
        <tr class="table-dark text-center align-middle">
            <th>ID</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dataGroupCommission as $commissionGroup)
            <tr class="table-warning">
                <td>{{ $commissionGroup->group_id }}</td>
                <td>{{ $commissionGroup->groupcommission_name }}</td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-primary btn-sm view-details"
                        data-group-id="{{ $commissionGroup->group_id }}">Details</button>
                    <button type="button" class="btn btn-primary btn-sm"
                        data-group-id="{{ $commissionGroup->group_id }}">Rename</button>
                    <button type="button" class="btn btn-danger btn-sm"
                        data-group-id="{{ $commissionGroup->group_id }}">X</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.view-details').click(function() {
            var $this = $(this);
            var groupId = $this.data('group-id');
            var projectId = "{{ $id }}"; // Ensure the project ID is available
            var url = '{{ route('commission.details', ['id' => '__PROJECT_ID__']) }}'
                .replace('__PROJECT_ID__', projectId);

            // Check if the details row already exists
            var nextRow = $this.closest('tr').next('tr.details-row[data-group-id="' + groupId + '"]');
            if (nextRow.length) {
                // Toggle the visibility of the existing details row
                nextRow.toggle();
            } else {
                // Perform AJAX request
                $.ajax({
                    url: url, // URL should match the route
                    type: 'POST',
                    data: { group_id: groupId },
                    success: function(response) {
                        if (response.success) {
                            var detailsTable = '<tr class="details-row" data-group-id="' + groupId + '"><td colspan="3">';
                            detailsTable += '<table class="table">';
                            detailsTable += '<thead><tr><th>ID</th><th>Description</th><th>Amount</th></tr></thead>';
                            detailsTable += '<tbody>';
                            $.each(response.data, function(index, detail) {
                                detailsTable += '<tr>';
                                detailsTable += '<td>' + detail.commission_id + '</td>';
                                detailsTable += '<td>' + detail.description + '</td>';
                                detailsTable += '<td>' + detail.amount + '</td>';
                                detailsTable += '</tr>';
                            });
                            detailsTable += '</tbody></table>';
                            detailsTable += '</td></tr>';

                            // Append the details row after the clicked row
                            $this.closest('tr').after(detailsTable);
                        } else {
                            alert('No details found.');
                        }
                    },
                    error: function() {
                        alert('Error fetching details.');
                    }
                });
            }
        });
    });
</script>

@endsection
