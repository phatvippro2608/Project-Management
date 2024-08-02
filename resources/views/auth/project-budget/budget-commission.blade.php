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
    <a href="{{ route('budget.export.csv', $project->project_id) }}" class="btn btn-success">
        <i class="bi bi-file-earmark-arrow-down pe-2"></i>Export CSV
    </a>
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
                            <button type="button" class="btn btn-info btn-sm w-auto edit-btn"
                                    data-id="{{ $data->commission_id }}"
                                    data-name="{{ $data->description }}" data-amount="{{ $data->amount }}" data-bs-toggle="modal" data-bs-target="#editCommissionModal">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button type="button"
                                    class="btn btn-danger btn-sm delete-btn"
                                    data-id="{{ $data->commission_id }}">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                    <tr><td colspan="4"></td></tr>
                @endif
            @endforeach
        @endforeach
    </tbody>
</table>

<!-- Edit Commission Modal -->
<div class="modal fade" id="editCommissionModal" tabindex="-1" aria-labelledby="editCommissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCommissionModalLabel">Edit Commission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCommissionForm">
                    @csrf
                    @method('PUT') <!-- Specify the PUT method -->
                    <input type="hidden" id="editCommissionId" name="commission_id">
                    <div class="mb-3">
                        <label for="editCommissionDescription" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editCommissionDescription" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCommissionAmount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="editCommissionAmount" name="amount" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('editCommissionForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);
        const commissionId = formData.get('commission_id');
        const projectId = "{{ $id }}";
        const url = "{{ route('budget.updateCommission', ['project_id' => $id, 'commission_id' => '__COMMISSION_ID__']) }}"
            .replace('__COMMISSION_ID__', commissionId);

        fetch(url, {
            method: 'POST',  // POST is used with X-HTTP-Method-Override
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-HTTP-Method-Override': 'PUT', // Override POST to PUT
                'Accept': 'application/json', // Expect JSON response
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text); // Throw error with response text
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update the UI with the new values
                const commissionRow = document.getElementById(commissionId).closest('tr');
                commissionRow.querySelector('td:nth-child(2)').textContent = formData.get('description');
                commissionRow.querySelector('td:nth-child(3)').textContent = new Intl.NumberFormat().format(formData.get('amount')) + ' VND';

                // Close the modal
                const modal = document.getElementById('editCommissionModal');
                const modalInstance = bootstrap.Modal.getInstance(modal);
                modalInstance.hide();
                toastr.success('Update Successful!');
            } else if (data.errors) {
                console.log(data.errors);
                toastr.error('Validation errors occurred. Please check the form fields.');
            } else {
                toastr.error('Error updating commission');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred while updating the commission.');
        });
    });

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commissionId = this.getAttribute('data-id');
            const description = this.getAttribute('data-name');
            const amount = this.getAttribute('data-amount');
            
            // Update modal fields
            document.getElementById('editCommissionId').value = commissionId;
            document.getElementById('editCommissionDescription').value = description;
            document.getElementById('editCommissionAmount').value = amount;
        });
    });

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commissionId = this.getAttribute('data-id');
            const projectId = "{{ $id }}";
            const url = "{{ route('budget.deleteCommission', ['project_id' => $id, 'cost_commission_id' => '__COMMISSION_ID__']) }}"
                .replace('__COMMISSION_ID__', commissionId);
            
            if (confirm('Are you sure you want to delete this commission?')) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success('Deleted successfully!');
                        document.getElementById(commissionId).closest('tr').remove();
                    } else {
                        toastr.error('Delete Failed: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Delete Failed: ' + error.message);
                });
            }
        });
    });
</script>
@endsection
