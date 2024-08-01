@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Proposal Types</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Proposal Types</li>
            </ol>
        </nav>
    </div>

    <section class="section employees">
        <div class="card">
            <div class="card-header py-0">
                <div class="card-title my-3 p-0">Proposal Type List</div>
            </div>
            <div class="card-body">
                <div class="row gx-3 my-3">
                    <div class="col-md-6 m-0 d-flex">
                        <button id="addProposalTypeBtn" class="btn btn-primary d-flex align-items-center">
                            <i class="bi bi-file-earmark-plus pe-2"></i>
                            Add
                        </button>
                        <div class="btn btn-success mx-2 btn-export">
                            <a href="{{ route('proposal-types.export') }}" class="d-flex align-items-center text-white">
                                <i class="bi bi-file-earmark-arrow-down pe-2"></i>
                                Export
                            </a>
                        </div>
                    </div>
                    <div class="input-group ms-sm-auto w-25" id="customSearchBox">
                        <button id="searchData" class="input-group-text bg-transparent border-secondary rounded-start-4">
                            <i class="bi bi-search"></i>
                        </button>
                        <input type="text" id="customSearchInput"
                            class="form-control border-start-0 border-secondary rounded-end-4">
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="proposalTypesTable"
                        class="table card-table table-vcenter text-nowrap datatable table-hover table-borderless">
                        <thead>
                            <tr>
                                <th class="text-center">Proposal Name</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($model as $item)
                                <tr>
                                    <td class="text-center">{{ $item->name }}</td>
                                    <td class="text-center">
                                        <button
                                            class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                            data-id="{{ $item->id }}"><i class="bi bi-pencil-square"></i></button>
                                        |
                                        <button
                                            class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                            data-id="{{ $item->id }}"><i class="bi bi-trash3"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="proposalTypeModal" tabindex="-1" aria-labelledby="proposalTypeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="proposalTypeModalLabel">Add Proposal Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="proposalTypeForm">
                                @csrf
                                <input type="hidden" id="proposalTypeId">
                                <div class="mb-3">
                                    <label for="proposalTypeName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="proposalTypeName" name="name"
                                        required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#proposalTypesTable').DataTable({
                'dom': '<"d-flex justify-content-between align-items-center"l>rt<"d-flex justify-content-between align-items-center"ip>',
                'columnDefs': [{
                    className: "text-center",
                    "targets": "_all"
                }]
            });


            $('#customSearchInput').on('keyup', function() {
                table.search(this.value).draw();
            });


            $('#addProposalTypeBtn').on('click', function() {
                $('#proposalTypeForm')[0].reset();
                $('#proposalTypeId').val('');
                $('#proposalTypeModalLabel').text('Add Proposal Type');
                $('#proposalTypeModal').modal('show');
            });

            $('#proposalTypesTable').on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                var url = "{{ route('proposal-types.show', ':id') }}";
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        $('#proposalTypeId').val(response.proposal_type.id);
                        $('#proposalTypeName').val(response.proposal_type.name);
                        $('#proposalTypeModalLabel').text('Edit Proposal Type');
                        $('#proposalTypeModal').modal('show');
                    },
                    error: function(xhr) {
                        toastr.error("An error occurred while fetching data.", "Error");
                    }
                });
            });

            $('#proposalTypesTable').on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                var row = $(this).closest('tr');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to delete this proposal type?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('proposal-types.destroy', ':id') }}'.replace(
                                ':id', id),
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    table.row(row).remove().draw(false);
                                    Swal.fire(
                                        'Deleted!',
                                        response.message,
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Failed!',
                                        'Failed to delete the proposal type.',
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $('#proposalTypeForm').on('submit', function(e) {
                e.preventDefault();
                var id = $('#proposalTypeId').val();
                var method = id ? 'PUT' : 'POST';
                var url = id ? '{{ route('proposal-types.update', ':id') }}'.replace(':id', id) :
                    '{{ route('proposal-types.add') }}';
                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#proposalTypeModal').modal('hide');
                            toastr.success(response.message, "Successful");

                            if (method === 'POST') {
                                table.row.add([
                                    response.proposal_type.name,
                                    '<button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn" data-id="' +
                                    response.proposal_type.id + '">' +
                                    '<i class="bi bi-pencil-square"></i>' +
                                    '</button>' +
                                    ' | ' +
                                    '<button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn" data-id="' +
                                    response.proposal_type.id + '">' +
                                    '<i class="bi bi-trash3"></i>' +
                                    '</button>'
                                ]).draw(false);
                            } else {
                                var row = table.row($('button[data-id="' + id + '"]').parents(
                                    'tr'));
                                row.data([
                                    response.proposal_type.name,
                                    '<button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn" data-id="' +
                                    response.proposal_type.id + '">' +
                                    '<i class="bi bi-pencil-square"></i>' +
                                    '</button>' +
                                    ' | ' +
                                    '<button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn" data-id="' +
                                    response.proposal_type.id + '">' +
                                    '<i class="bi bi-trash3"></i>' +
                                    '</button>'
                                ]).draw(false);
                            }
                            $('#proposalTypeForm')[0].reset();
                        }
                    },
                    error: function(xhr) {
                        toastr.error("An error occurred.", "Error");
                    }
                });
            });
        });
    </script>
@endsection
@section('head')
@endsection
