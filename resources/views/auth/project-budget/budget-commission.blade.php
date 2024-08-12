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
                <th colspan="3">Group: <span class="group-name">{{ $commissionGroup->groupcommission_name }}</span></th>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-info btn-sm w-auto editName-btn"
                            data-id="{{ $commissionGroup->group_id }}"
                            data-name="{{ $commissionGroup->groupcommission_name }}"
                            data-bs-toggle="modal"
                            data-bs-target="#editNameGroup"
                            onclick="showEditModal('{{ $commissionGroup->group_id }}', '{{ $commissionGroup->groupcommission_name }}')">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm delete-group-btn"
                            data-id="{{ $commissionGroup->group_id }}"
                            data-name="{{ $commissionGroup->groupcommission_name }}">
                        <i class="bi bi-trash3"></i>
                    </button>
                </td>
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
                                    data-bs-target="#editCommissionModal"
                                    onclick="showCommission('{{ $data->commission_id }}', '{{ $data->description }}', '{{ $data->amount }}')">
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

<!-- Edit Name Group Commission Modal -->
<div class="modal fade" id="editNameGroup" tabindex="-1" role="dialog" aria-labelledby="editNameGroup" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editNameGroup">Edit Name</h5>
            </div>
            <form id="editNameGroupCommissionForm" method="POST" action="{{ route('budget.editNameGroup', ['project_id' => $id, 'group_id' => '__GROUP_ID__']) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="groupId" name="groupId">
                    <div class="mb-3">
                        <label for="editCommissionName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="groupName" name="groupName" required>
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

<!-- Edit Commission Modal -->
<div class="modal fade" id="editCommissionModal" tabindex="-1" role="dialog" aria-labelledby="editCommissionModalLabel" aria-hidden="true">
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
            <form id="addNewCommissionForm" method="POST" action="{{ route('budget.addNewCommission', ['project_id' => $id]) }}">
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
                    <button type="button" data-bs-dismiss="modal" class="btn btn-link p-0" id="openAddGroupModal">or Add New Group Commission</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add New Group Modal -->
<div class="modal fade" id="addNewGroupModal" tabindex="-1" role="dialog" aria-labelledby="addNewGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewGroupModalLabel">Add New Group Commission</h5>
            </div>
            <form id="addNewGroupForm" method="POST" action="#">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="newGroupName" class="form-label">Group Name</label>
                        <input type="text" class="form-control" id="newGroupName" name="groupcommission_name" required>
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

@endsection

@section('script')
<script>
    $('#addNewCommissionForm').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        var formData = form.serialize();
        var url = form.attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success('Add new commission successfull'); // Hiển thị thông báo thành công
                    $('#addNewCostModal').modal('hide'); // Đóng modal
                    // Thực hiện các hành động khác nếu cần thiết
                } else {
                    toastr.error('Failed to add commission: ' + (response.message || 'Unknown error')); // Hiển thị thông báo lỗi
                }
            },
            error: function(xhr) {
                toastr.error('Request failed: ' + xhr.status + ' ' + xhr.statusText); // Hiển thị thông báo lỗi
            }
        });
    });

    function showEditModal(groupId, groupName) {
        document.getElementById('groupId').value = groupId;
        document.getElementById('groupName').value = groupName;

        var form = document.getElementById('editNameGroupCommissionForm');
        form.action = form.action.replace('__GROUP_ID__', groupId);
    }

    document.getElementById('editCommissionForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var form = this;
        var commissionId = document.getElementById('editCommissionId').value;
        var formData = new FormData(form);

        var url = form.action.replace('__COMMISSION_ID__', commissionId);

        fetch(url, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
        $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.includes('<html>')) {
                        toastr.success('Commission update successfully!');
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

    function showCommission(commissionId, description, amount) {
        document.getElementById('editCommissionId').value = commissionId;
        document.getElementById('editCommissionDescription').value = description;
        document.getElementById('editCommissionAmount').value = amount;

        var form = document.getElementById('editCommissionForm');
        form.action = form.action.replace('__COMMISSION_ID__', commissionId);
    }

</script>
@endsection
