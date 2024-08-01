@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Project Budget</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Project Budget Summary</li>
            </ol>
        </nav>
    </div>

    <section class="sectionBudget">
        <h3>Budget Estimates of Project</h3>
        <h5>Project Budget Summary</h5>
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">FINANCIAL STATUS</h5>
                    <div class="btn-group" role="group" aria-label="Button group">
                        <a href="{{ route('project.progress', ['id' => $id]) }}" class="btn btn-info rounded-pill me-2">Progress</a>
                        <a href="{{ route('budget', ['id' => $id]) }}" class="btn btn-info rounded-pill me-2">LIST OF EXPENSES</a>
                        <a href="{{ route('commission', ['id' => $id]) }}" class="btn btn-info rounded-pill">LIST OF COMMISSION</a>
                    </div>
                </div>
                <hr>
                <!-- Default Table -->
                <table class="table">
                    <tbody>
                        <tr>
                            <td scope="row">AMOUNT OF CONTRACT</td>
                            <td>{{ number_format($data->project_contract_amount, 0, ',', '.') }} VND</td>
                        </tr>
                        <tr>
                            <td scope="row">SUBTOTAL (Budget)</td>
                            <td>{{ number_format($total, 0, ',', '.') }} VND</td>
                        </tr>
                        <tr>
                            <td scope="row">RISK (CONTINGENCY)</td>
                            <td>{{ number_format($data->project_price_contingency, 0, ',', '.') }} VND</td>
                        </tr>
                        <tr>
                            <td scope="row">COMMISSION</td>
                            <td>0 VND</td>
                        </tr>
                        <tr class="table-primary">
                            <th scope="row">FUNDS REMAINING</th>
                            <td>{{ number_format($data->project_contract_amount - $total - $data->project_price_contingency, 0, ',', '.') }} VND</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">PROJECT INFORMATION</h5>
                            <div class="d-flex ms-auto">
                                <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editProjectModal"><i class="bi bi-pencil-square"></i> Edit Information</button>
                            </div>
                        </div>

                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <tbody id="project-info">
                                <tr>
                                    <th>Project Name</th>
                                    <td id="project_name">{{ $data->project_name }}</td>
                                </tr>
                                <tr>
                                    <th>Project Description</th>
                                    <td id="project_description">{{ $data->project_description }}</td>
                                </tr>
                                <tr>
                                    <th>Project Address</th>
                                    <td id="project_address">{{ $data->project_address }}</td>
                                </tr>
                                <tr>
                                    <th>Main Contractor</th>
                                    <td>
                                        @php
                                            $contract = $contracts->firstWhere('contract_id', $data->project_contract_id);
                                        @endphp
                                        {{ $contract ? $contract->contract_name : 'Not Available' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Contact Name</th>
                                    <td id="project_contact_name">{{ $data->project_contact_name }}</td>
                                </tr>
                                <tr>
                                    <th>Contact Website</th>
                                    <td id="project_contact_website">{{ $data->project_contact_website }}</td>
                                </tr>
                                <tr>
                                    <th>Contact Phone</th>
                                    <td id="project_contact_phone">{{ $data->project_contact_phone }}</td>
                                </tr>
                                <tr>
                                    <th>Contact Address</th>
                                    <td id="project_contact_address">{{ $data->project_contact_address }}</td>
                                </tr>
                                <tr>
                                    <th>Start Date</th>
                                    <td id="project_date_start">{{ $data->project_date_start }}</td>
                                </tr>
                                <tr>
                                    <th>End Date</th>
                                    <td id="project_date_end">{{ $data->project_date_end }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Edit Project Modal -->
    <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProjectModalLabel">Edit Project Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProjectForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_project_name" class="form-label">Project Name</label>
                            <input type="text" class="form-control" id="edit_project_name" name="project_name" value="{{ $data->project_name }}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_project_description" class="form-label">Project Description</label>
                            <textarea class="form-control" id="edit_project_description" name="project_description">{{ $data->project_description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_project_address" class="form-label">Project Address</label>
                            <input type="text" class="form-control" id="edit_project_address" name="project_address" value="{{ $data->project_address }}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_project_main_contractor" class="form-label">Main Contractor</label>
                            <select class="form-select" id="edit_project_main_contractor" name="project_contract_id">
                                @foreach($contracts as $contract)
                                    <option value="{{ $contract->contract_id }}" {{ $data->project_contract_id == $contract->contract_id ? 'selected' : '' }}>
                                        {{ $contract->contract_name }}
                                    </option>
                                @endforeach
                        </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_project_contact_name" class="form-label">Contact Name</label>
                            <input type="text" class="form-control" id="edit_project_contact_name" name="project_contact_name" value="{{ $data->project_contact_name }}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_project_contact_website" class="form-label">Contact Website</label>
                            <input type="text" class="form-control" id="edit_project_contact_website" name="project_contact_website" value="{{ $data->project_contact_website }}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_project_contact_phone" class="form-label">Contact Phone</label>
                            <input type="text" class="form-control" id="edit_project_contact_phone" name="project_contact_phone" value="{{ $data->project_contact_phone }}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_project_contact_address" class="form-label">Contact Address</label>
                            <input type="text" class="form-control" id="edit_project_contact_address" name="project_contact_address" value="{{ $data->project_contact_address }}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_project_date_start" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="edit_project_date_start" name="project_date_start" value="{{ $data->project_date_start }}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_project_date_end" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="edit_project_date_end" name="project_date_end" value="{{ $data->project_date_end }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('editProjectForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const id = "{{ $id }}";
    const url = "{{ route('project.update', ['id' => $id]) }}";

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-HTTP-Method-Override': 'PUT',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the UI with the new values
            document.getElementById('project_name').textContent = formData.get('project_name');
            document.getElementById('project_description').textContent = formData.get('project_description');
            document.getElementById('project_address').textContent = formData.get('project_address');
            
            // Update the Main Contractor field
            const contractorId = formData.get('project_contract_id');
            const contractorName = [...document.getElementById('edit_project_main_contractor').options].find(option => option.value === contractorId)?.text;
            document.querySelector('#project-info td:nth-child(2)').textContent = contractorName || 'Not Available';

            document.getElementById('project_contact_name').textContent = formData.get('project_contact_name');
            document.getElementById('project_contact_website').textContent = formData.get('project_contact_website');
            document.getElementById('project_contact_phone').textContent = formData.get('project_contact_phone');
            document.getElementById('project_contact_address').textContent = formData.get('project_contact_address');
            document.getElementById('project_date_start').textContent = formData.get('project_date_start');
            document.getElementById('project_date_end').textContent = formData.get('project_date_end');
            
            // Close the modal
            const modal = document.getElementById('editProjectModal');
            const modalInstance = bootstrap.Modal.getInstance(modal);
            modalInstance.hide();
            toastr.success('Update Successful!');
        } else if (data.errors) {
            // Handle validation errors
            console.log(data.errors);
            alert('Validation errors occurred. Please check the form fields.');
        } else {
            alert('Error updating project');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the project.');
    });
});
    </script>
@endsection
