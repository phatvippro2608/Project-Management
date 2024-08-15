@extends('auth.main');
@section('contents')
    <style>
        .table-responsive {
            max-height: 200px;
            overflow-y: auto;
        }

        .modal-body {
            max-height: 500px;
            overflow-y: auto;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 1;
            border-bottom: 2px solid #ccc;
        }
    </style>
    <div class="pagetitle">
        <h1>{{ __('messages.proposal') }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">{{ __('messages.proposal') }}</li>
            </ol>
        </nav>
    </div>

    <div class="row gx-3 my-3">
        <div class="col-md-6 m-0">
            <div class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#addProposalApplicationModal">
                <div class="d-flex align-items-center at1">
                    <i class="bi bi-file-earmark-plus pe-2"></i>
                    {{ __('messages.add') }}
                </div>
            </div>
            @if ($data['permission'] == 9)
                <div class="btn btn-primary mx-2 btn-export">
                    <a href="{{route('proposal-application.export',['permission'  => $data['permission'],
                                                                    'employee_id' => $data['employee_current']->employee_id
                                                                    ])}}"
                       class="d-flex align-items-center text-white">
                        <i class="bi bi-file-earmark-arrow-down pe-2"></i>
                        {{ __('messages.import') }}
                    </a>
                </div>
            @elseif($data['permission'] == 10)
                <div class="btn btn-primary mx-2 btn-export">
                    <a href="{{route('proposal-application.export',['permission'  => $data['permission'],
                                                                    'employee_id' => $data['employee_current']->employee_id])}}" class="d-flex align-items-center text-white">
                        <i class="bi bi-file-earmark-arrow-down pe-2"></i>
                        {{ __('messages.export') }}
                    </a>
                </div>
            @endif

        </div>
    </div>

    <div class="modal fade" id="addProposalApplicationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Proposal Application</h4>
                    @component('auth.component.btnCloseModal')
                    @endcomponent
                </div>
                <div class="modal-body">
                    <form id="addProposalApplicationForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="department_name" class="form-label">Employee name</label>
                            <select class="form-select" aria-label="Default" name="employee_id" id="employee_id">
                                @if ($data['permission'] == 0)
                                    @if (isset($data['employee_current']))
                                        <option value="{{ $data['employee_current']->employee_id }}">
                                            {{ $data['employee_current']->first_name }}
                                            {{ $data['employee_current']->last_name }}
                                        </option>
                                    @else
                                        <option value="">No Employee Found</option>
                                    @endif

                                @elseif($data['permission'] == 9)
                                    @foreach ($data['employee_of_depart'] as $item)
                                        <option value="{{ $item->employee_id }}">{{ $item->first_name }}
                                            {{ $item->last_name }}</option>
                                    @endforeach
                                @elseif($data['permission'] == 10)
                                    @foreach ($employee_name as $item)
                                        <option value="{{ $item->employee_id }}">{{ $item->first_name }}
                                            {{ $item->last_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="department_name" class="form-label">Proposal Type</label>
                            <select class="form-select" aria-label="Default" name="proposal_id" id="proposal_id">
                                @foreach ($proposal_types as $item)
                                    <option value="{{ $item->proposal_type_id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="proposal_description">Description</label>
                            <textarea class="form-control" placeholder="Leave a Description here" id="proposal_description"
                                name="proposal_description" style="height: 100px"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Upload your files</label>
                            <input class="form-control" type="file" id="file" name="files[]" multiple>
                            <ul id="fileList" class="list-unstyled mt-2"></ul>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Proposal Application</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editProposalModal" tabindex="-1" aria-labelledby="editProposalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProposalModalLabel">Edit Proposal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProposalForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editProposalId" name="proposal_application_id">
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee name</label>
                            <input type="text" class="form-control" id="employee_name" readonly>
                            <input type="hidden" id="edit_employee_id" name="employee_id">
                        </div>
                        <div class="mb-3">
                            <label for="proposal_id" class="form-label">Proposal Type</label>
                            <select class="form-select" aria-label="Default" name="proposal_id" id="edit_proposal_id">
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_proposal_description">Description</label>
                            <textarea class="form-control" placeholder="Leave a Description here" id="edit_proposal_description"
                                name="proposal_description" style="height: 100px"></textarea>
                        </div>
                        <label for="proposal_files">Proposal File Uploaded</label>
                        <div class="mb-3 table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Proposal File Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="overflow-y-scroll">
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-3">
                            <label for="edit_files" class="form-label">Add more files</label>
                            <input class="form-control" type="file" id="edit_files" name="files[]" multiple>
                            <ul id="editFileList" class="list-unstyled mt-2"></ul>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm p-3 mb-5 bg-white rounded-4">
        <h3 class="text-left mb-4">{{ __('messages.proposal_list') }}</h3>
        <table id="proposalApplicationsTable" class="table table-hover table-borderless">
            <thead class="table-light">
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">{{ __('messages.employee_name') }}</th>
                    <th class="text-center">{{ __('messages.proposal_type') }}</th>
                    <th class="text-center">{{ __('messages.description') }}</th>
                    <th class='text-center w-25 '>Progress</th>
                    @if ($data['permission'] == 9)
                        <th class="text-center">Direct Department</th>
                    @endif
                    @if ($data['permission'] == 10)
                        <th class="text-center">Direct Manager</th>
                    @endif
                    <th>{{ __('messages.action') }}</th>
                </tr>
            </thead>
            <tbody id="proposalTableBody">
                @php($stt = 0)
                @foreach ($data['list_proposal'] as $item)
                    <tr>
                        <td class="text-center">{{ ++$stt }}</td>
                        <td>{{ $item->last_name . ' ' . $item->first_name }}</td>
                        <td>{{ $item->name }}</td>
                        <td>
                            <textarea readonly class="border border-light w-100" rows="1" name="" id="">{{ $item->proposal_description }}</textarea>
                        </td>
                        <td>
                            <div class="progress">
                                @if ($item->progress == 0)
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 33%;"
                                        aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">
                                        Not approve
                                    </div>
                                @elseif($item->progress == 1)
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 66%;"
                                        aria-valuenow="66" aria-valuemin="0" aria-valuemax="100">
                                        Direct Derpartment approved
                                    </div>
                                @elseif($item->progress == 2)
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"
                                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                        Done
                                    </div>
                                @endif
                            </div>
                        </td>
                        @if ($data['permission'] == 9)
                            <td class="text-center">
                                @if ($item->progress == 0)
                                    <button
                                        class="text-secondary btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none btn_approved"
                                        data-id="{{ $item->proposal_application_id }}"
                                        data-permission="{{$data['permission']}}">
                                        <i class="bi bi-check-circle"></i>
                                        Not approve
                                    </button>
                                @elseif($item->progress == 1)
                                    <button
                                        class="text-warning btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none"
                                        data-id="{{ $item->proposal_application_id }}" disabled>
                                        <i class="bi bi-check-circle-fill"></i>
                                        Direct Derpartment approved
                                    </button>
                                @elseif($item->progress == 2)
                                    <button
                                        class="text-success btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none btn_approved"
                                        data-id="{{ $item->proposal_application_id }}"
                                        data-permission="{{$data['permission']}}" disabled>
                                        <i class="bi bi-check-circle-fill"></i>
                                        Done
                                    </button>
                                @endif
                            </td>
                        @endif

                        @if ($data['permission'] == 10)
                            <td class="text-center">
                                @if ($item->progress == 0)
                                    <button
                                        class="text-secondary btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none btn_approved"
                                        data-id="{{ $item->proposal_application_id }}" disabled>
                                        <i class="bi bi-check-circle"></i>
                                        Direct Department not approve
                                    </button>
                                @elseif($item->progress == 1)
                                    <button
                                        class="text-secondary btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none btn_approved"
                                        data-id="{{ $item->proposal_application_id }}"
                                        data-permission="{{$data['permission']}}">
                                        <i class="bi bi-check-circle"></i>
                                        Not approve
                                    </button>
                                @elseif($item->progress == 2)
                                    <button
                                        class="text-success btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none btn_approved"
                                        data-id="{{ $item->proposal_application_id }}"
                                        data-permission="{{$data['permission']}}" disabled>
                                        <i class="bi bi-check-circle-fill"></i>
                                        Done
                                    </button>
                                @endif
                            </td>
                        @endif
                        <td>
                            <button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                data-id="{{ $item->proposal_application_id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            |
                            <button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                data-id="{{ $item->proposal_application_id }}">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endsection

@section('script')
    <script>
        var table = $('#proposalApplicationsTable').DataTable({
            language: {
                search: ""
            },
            initComplete: function(settings, json) {
                $('.dt-search').addClass('input-group');
                $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
            }
        });

        const fileArray = [];
        const input = document.getElementById('file');

        input.addEventListener('change', function(event) {
            const fileList = document.getElementById('fileList');

            for (let i = 0; i < input.files.length; i++) {
                fileArray.push(input.files[i]);
            }

            updateFileList();
        });

        function updateFileList() {
            const dataTransfer = new DataTransfer();
            fileArray.forEach(file => dataTransfer.items.add(file));
            input.files = dataTransfer.files;

            const fileList = document.getElementById('fileList');
            fileList.innerHTML = '';
            fileArray.forEach((file, index) => {
                const li = document.createElement('li');
                li.className =
                    'mb-3 d-flex justify-content-between align-items-center text-truncate file-list-item';
                let displayName = file.name;
                const extension = displayName.split('.').pop();
                const baseName = displayName.substring(0, displayName.lastIndexOf('.'));

                if (baseName.length > 30) {
                    displayName = baseName.substring(0, 25) + '...' + '.' + extension;
                } else {
                    displayName = baseName + '.' + extension;
                }

                li.textContent = displayName + ' ';

                const removeBtn = document.createElement('button');
                removeBtn.textContent = 'Remove';
                removeBtn.className = 'text-right btn btn-danger btn-sm ms-2';
                removeBtn.onclick = function() {
                    fileArray.splice(index, 1);
                    updateFileList();
                };

                li.appendChild(removeBtn);
                fileList.appendChild(li);
            });
        }

        $('#addProposalApplicationForm').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            for (var pair of formData.entries()) {
                console.log(pair[0] + ": " + pair[1]);
            }

            $.ajax({
                url: '{{ route('proposal-application.add') }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        $('#addProposalApplicationModal').modal('hide');
                        toastr.success(response.message, "Successful");
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    } else {
                        '</td><td><a href="{{ asset('proposal_files') }}' + '/' + data.employee
                            .employee_id + '/' + file.proposal_file_name + '" download>' + file
                            .proposal_file_name + '</a></td></tr>';
                        toastr.error("An error occurred", "Error");
                    }
                }
            });
        });

        const EditfileArray = [];
        const editInput = document.getElementById('edit_files');

        editInput.addEventListener('change', function(event) {
            for (let i = 0; i < editInput.files.length; i++) {
                EditfileArray.push(editInput.files[i]);
            }
            updateEditFileList();
        });

        function updateEditFileList() {
            const fileList = document.getElementById('editFileList');
            fileList.innerHTML = '';

            EditfileArray.forEach((file, index) => {
                const listItem = document.createElement('li');
                listItem.className = 'mb-3 d-flex justify-content-between align-items-center text-truncate';
                listItem.textContent = file.name;
                const removeButton = document.createElement('button');
                removeButton.textContent = 'Remove';
                removeButton.classList.add('btn', 'btn-danger', 'btn-sm', 'ms-2');
                removeButton.onclick = () => {
                    EditfileArray.splice(index, 1);
                    updateEditFileList();
                };
                listItem.appendChild(removeButton);
                fileList.appendChild(listItem);
            });
        }

        $('#proposalApplicationsTable').on('click', '.edit-btn', function() {
            const proposalAppId = $(this).data('id');

            $.ajax({
                url: '{{ route('proposal-application.edit', ':id') }}'.replace(':id', proposalAppId),
                method: 'GET',
                success: function(response) {
                    const data = response.proposal_app;

                    $('#editProposalId').val(data.proposal_application_id);
                    $('#edit_employee_id').val(data.employee.employee_id);
                    $('#employee_name').val(data.employee.first_name + ' ' + data.employee.last_name);

                    const proposalTypeSelect = $('#edit_proposal_id');
                    proposalTypeSelect.empty();
                    response.proposal_types.forEach(function(type) {
                        const option = new Option(type.name, type.proposal_type_id);
                        if (type.proposal_type_id === data.proposal_id) {
                            option.selected = true;
                        }
                        proposalTypeSelect.append(option);
                    });

                    $('#edit_proposal_description').val(data.proposal_description);

                    let fileListHtml = '';
                    if (data.files) {
                        data.files.forEach(function(file, index) {
                            fileListHtml +=
                                `<tr>
                                    <td>${index + 1}</td>
                                    <td><a href="{{ asset('proposal_files') }}/${data.employee.employee_id}/${file.proposal_file_name}" download>${file.proposal_file_name}</a></td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-file" data-id="${file.proposal_file_id}">Remove</button></td>
                                </tr>`;
                        });
                    } else {
                        console.error('No files in response');
                    }
                    $('#editProposalModal table tbody').html(fileListHtml);

                    $('#editProposalModal').modal('show');
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message, "Error");
                }
            });
        });

        $(document).on('click', '.remove-file', function() {
            var fileId = $(this).data('id');
            var row = $(this).closest('tr');

            $.ajax({
                url: '{{ route('proposal-application.removeFile', ':id') }}'.replace(':id', fileId),
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        row.remove();
                        toastr.success(response.message, "File Removed");
                    }
                },
                error: function(xhr) {
                    toastr.error("An error occurred.", "Error");
                }
            });
        });


        $('#editProposalForm').submit(function(e) {
            e.preventDefault();

            var proposalAppId = $('#editProposalId').val();
            var formData = new FormData(this);

            for (var pair of formData.entries()) {
                console.log(pair[0] + ": " + pair[1]);
            }

            $.ajax({
                url: '{{ route('proposal-application.update', ':id') }}'.replace(':id', proposalAppId),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, "Updated successfully");
                        $('#editProposalModal').modal('hide');
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    } else {
                        toastr.error(response.message, "Error");
                    }
                },
                error: function(xhr) {
                    toastr.error("An error occurred.", "Operation Failed");
                }
            });
        });

        $('#proposalApplicationsTable').on('click', '.delete-btn', function() {
            var proposalAppId = $(this).data('id');
            var row = $(this).closest('tr');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this proposal application?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('proposal-application.destroy', ':id') }}'.replace(':id',
                            proposalAppId),
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                table.row(row).remove().draw();
                                toastr.success(response.message, "Deleted successfully");
                            } else {
                                toastr.error("Failed to delete the proposal application.",
                                    "Operation Failed");
                            }
                        },
                        error: function(xhr) {
                            toastr.error("An error occurred.", "Operation Failed");
                        }
                    });
                }
            });
        });

        $('#proposalApplicationsTable').on('click', '.btn_approved', function() {
            var proposalAppId = $(this).data('id');
            var permis = $(this).data('permission');
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to approve this proposal application?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('proposal-application.approve', ['id' => ':id', 'permission' => ':permission']) }}'
                            .replace(':id', proposalAppId)
                            .replace(':permission', permis),
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message, "Approved successfully");
                                setTimeout(function() {
                                    location.reload();
                                }, 250);
                            } else {
                                toastr.error("Failed to approve the proposal application.",
                                    "Operation Failed");
                            }
                        },
                        error: function(xhr) {
                            toastr.error("An error occurred.", "Operation Failed");
                        }
                    });
                }
            });
        });

    </script>
@endsection
