@extends('auth.main');
@section('contents')
    <style>
        .custom-img {
            width: 100%;
            /* Điều chỉnh kích thước chiều rộng */
            height: auto;
            /* Giữ tỷ lệ khung hình */
        }
    </style>
    <div class="pagetitle">
        <h1>Proposal Applicaiton</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Proposal Applicaiton</li>
            </ol>
        </nav>
    </div>
    <div class="row gx-3 my-3">
        <div class="col-md-6 m-0">
            <div class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#addProposalApplicationModal">
                <div class="d-flex align-items-center at1">
                    <i class="bi bi-file-earmark-plus pe-2"></i>
                    Add Proposal Applicaiton
                </div>
            </div>
            <div class="btn btn-success mx-2 btn-export">
                <a href="" class="d-flex align-items-center text-white">
                    <i class="bi bi-file-earmark-arrow-down pe-2"></i>
                    Export
                </a>
            </div>
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
                            <select class="form-select" aria-label="Default" name="employee_id" id="employee_id"
                                @if ($data['permission'] == 0) disabled @endif>
                                @if ($data['permission'] == 0)
                                    <option value="{{ $data['list_proposal'][0]->employee_id }}">
                                        {{ $data['list_proposal'][0]->first_name }}
                                        {{ $data['list_proposal'][0]->last_name }}
                                    </option>
                                @elseif($data['permission'] == 3)
                                    @foreach ($data['employee_of_depart'] as $item)
                                        <option value="{{ $item->employee_id }}">{{ $item->first_name }}
                                            {{ $item->last_name }}</option>
                                    @endforeach
                                @elseif($data['permission'] == 4)
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
                            <label for="floatingTextarea2">Description</label>
                            <textarea class="form-control" placeholder="Leave a Description here" id="description" name="description"
                                style="height: 100px"></textarea>
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

    <div class="card shadow-sm p-3 mb-5 bg-white rounded-4">
        <h3 class="text-left mb-4">Proposal Application</h3>
        <table id="departmentsTable" class="table table-hover table-borderless">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Employee Name</th>
                    <th>Proposal Name Type</th>
                    <th>Description</th>
                    <th>Progress</th>
                    @if ($data['permission'] == 3)
                        <th class="text-center">Direct Department</th>
                    @endif
                    @if ($data['permission'] == 4)
                        <th class="text-center">Direct Manager</th>
                    @endif
                </tr>
            </thead>
            <tbody id="departmentsTableBody">
                @php($stt = 0)
                @foreach ($data['list_proposal'] as $item)
                    <tr>
                        <td>{{ $stt++ }}</td>
                        <td>{{ $item->last_name . ' ' . $item->first_name }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->proposal_description }}</td>
                        <td>
                            <div class="progress">
                                @if ($item->progress == 0)
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 33%;"
                                        aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">
                                        Chưa xét
                                    </div>
                                @elseif($item->progress == 1)
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 66%;"
                                        aria-valuenow="66" aria-valuemin="0" aria-valuemax="100">
                                        Đã duyệt cấp 1
                                    </div>
                                @elseif($item->progress == 2)
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"
                                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                        Đã duyệt xong
                                    </div>
                                @endif
                            </div>
                        </td>
                        @if ($data['permission'] == 3)
                            <td class="text-center">
                                <button
                                    class="text-secondary btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                    data-id="">
                                    <i class="bi bi-check-circle"></i>
                                    Chưa duyệt
                                </button>
                            </td>
                        @endif
                        @if ($data['permission'] == 4)
                            <td class="text-center">
                                <button
                                    class="text-secondary btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                    data-id="">
                                    <i class="bi bi-check-circle "></i>
                                    Chưa duyệt

                                </button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script>
        var table = $('#departmentsTable').DataTable({
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
                li.className = 'mb-3 d-flex justify-content-between align-items-center text-truncate';
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
                        toastr.error(response.message, "Error");
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 400) {
                        var response = xhr.responseJSON;
                        toastr.error(response.message, "Error");
                    } else {
                        toastr.error("An error occurred", "Error");
                    }
                }
            });
        });
    </script>
@endsection
