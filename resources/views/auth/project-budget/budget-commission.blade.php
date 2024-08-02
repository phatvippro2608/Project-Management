@extends('auth.main')

@section('contents')
<div class="pagetitle">
    <h1>Project Budget</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Project Budget Summary</li>
            <li class="breadcrumb-item active">List of Commission</li>
        </ol>
    </nav>
</div>
<div class="d-flex justify-content-between align-items-center">
    <h3>List of Commission Project: <b>{{ $project->project_name }}</b></h3>
    <div role="group" aria-label="Button group">
        <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal"
                    data-bs-target="#addNewCostModal">Add New</button>
        <a href="{{ route('budget.export.csv', $project->project_id) }}" class="btn btn-sm btn-success">
            <i class="bi bi-file-earmark-arrow-down pe-2"></i>Export CSV
        </a>
    </div>
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
        @php $i = 1; @endphp
        @foreach ($dataGroupCommission as $commissionGroup)
            <tr class="table-warning">
                <th colspan="4">Group: {{ $commissionGroup->groupcommission_name }}</th>
            </tr>
            @foreach ($dataCommission as $data)
                @if ($data->project_id == $id && $data->groupcommission_id == $commissionGroup->group_id)
                    <tr>
                        <td class="text-center align-middle" id="{{ $data->commission_id }}">{{ $i++ }}</td>
                        <td>{{ $data->description }}</td>
                        <td>{{ number_format($data->amount, 0, ',', '.') }} VND</td>
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-info btn-sm w-auto edit-btn"
                                    data-id="{{ $data->commission_id }}"
                                    data-name="{{ $data->description }}"
                                    data-amount="{{ $data->amount }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCommissionModal">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button type="button"
                                    class="btn btn-danger btn-sm delete-btn"
                                    data-id="{{ $data->commission_id }}">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                @endif
            @endforeach
        @endforeach
    </tbody>
</table>

<!-- Edit Commission Modal -->
<div class="modal fade" id="editCommissionModal" tabindex="-1" role="dialog" aria-labelledby="editCommissionModalLabel"
        aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCommissionModalLabel">Edit Commission</h5>
            </div>
            <form id="editCommissionForm" method="POST" action="{{ route('budget.updateCommission', ['project_id' => $id, 'commission_id' => '__COMMISSION_ID__']) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editCommissionId" name="commission_id">
                    
                    <div class="mb-3">
                        <label for="editCommissionDescription" class="form-label">Description</label>
                        <input type="text" class="form-control" id="editCommissionDescription" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCommissionAmount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="editCommissionAmount" name="amount" required>
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

<!-- Add New Cost Modal -->
<div class="modal fade" id="addNewCostModal" tabindex="-1" role="dialog" aria-labelledby="addNewCostModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewCostModalLabel">Add New Cost</h5>
            </div>
            <form id="addNewCommissionForm" method="POST" action="#">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="selectGroup" class="form-label">Select Group</label>
                        <select class="form-select" id="selectGroup" name="group_id" required>
                            @foreach($dataGroupCommission as $group)
                                <option value="{{ $group->group_id }}">{{ $group->groupcommission_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="newCostDescription" class="form-label">Description</label>
                        <input type="text" class="form-control" id="newCostDescription" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="newCostAmount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="newCostAmount" name="amount" required>
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
document.getElementById('editCommissionForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const commissionId = formData.get('commission_id');
    const url = form.action.replace('__COMMISSION_ID__', commissionId);

    fetch(url, {
        method: 'POST', // POST with X-HTTP-Method-Override header for PUT
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-HTTP-Method-Override': 'PUT',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => { throw new Error(data.message || 'An error occurred.'); });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update the UI with the new values
            const commissionRow = document.getElementById(commissionId).closest('tr');
            commissionRow.querySelector('td:nth-child(2)').textContent = formData.get('description');
            commissionRow.querySelector('td:nth-child(3)').textContent = new Intl.NumberFormat().format(formData.get('amount')) + ' VND';

            // Update the button attributes with new values
            const editButton = commissionRow.querySelector('.edit-btn');
            editButton.setAttribute('data-name', formData.get('description'));
            editButton.setAttribute('data-amount', formData.get('amount'));

            // Close the modal
            const modal = document.getElementById('editCommissionModal');
            const modalInstance = bootstrap.Modal.getInstance(modal);
            modalInstance.hide();
            toastr.success('Update Successful!');

            // Reset the modal fields
            form.reset();
        } else {
            toastr.error(data.message || 'Error updating commission');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error(error.message || 'An error occurred while updating the commission.');
    });
});

document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function() {
        const commissionId = this.getAttribute('data-id');
        const description = this.getAttribute('data-name');
        const amount = this.getAttribute('data-amount');

        // Reset the modal fields
        const form = document.getElementById('editCommissionForm');
        form.reset();

        // Update modal fields
        document.getElementById('editCommissionId').value = commissionId;
        document.getElementById('editCommissionDescription').value = description;
        document.getElementById('editCommissionAmount').value = amount;
    });
});

document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function() {
        const commissionId = this.getAttribute('data-id');
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

document.getElementById('addNewCommissionForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const groupId = formData.get('group_id');
    const url = `{{ route('budget.AddNewComission', ['project_id' => $id, 'group_id' => '__GROUP_ID__']) }}`.replace('__GROUP_ID__', groupId);

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Create a new table row


            // Close the modal
            const modal = document.getElementById('addNewCostModal');
            const modalInstance = bootstrap.Modal.getInstance(modal);
            modalInstance.hide();

            // Reset the form
            form.reset();
            toastr.success('Added successfully!');
            setTimeout(function() {
                location.reload();
            }, 500);
        } else {
            toastr.error(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An error occurred while adding the commission.');
    });
});

document.getElementById('addNewCostModal').addEventListener('show.bs.modal', function () {
    const selectGroup = document.getElementById('selectGroup');
    const form = document.getElementById('addNewCommissionForm');
    const projectId = "{{ $id }}"; // Ensure this is correctly set in your Blade template

    // Set the base URL for the route
    const baseUrl = "{{ route('budget.AddNewComission', ['project_id' => '__PROJECT_ID__', 'group_id' => '__GROUP_ID__']) }}";

    // Update the form action URL when the group selection changes
    selectGroup.addEventListener('change', function () {
        const groupId = selectGroup.value;
        form.action = baseUrl.replace('__GROUP_ID__', groupId).replace('__PROJECT_ID__', projectId);
    });

    // Set the initial action URL when the modal is first opened
    const initialGroupId = selectGroup.value;
    form.action = baseUrl.replace('__GROUP_ID__', initialGroupId).replace('__PROJECT_ID__', projectId);
});
</script>
@endsection
