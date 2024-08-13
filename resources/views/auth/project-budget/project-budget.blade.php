@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Project Budget</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Project Budget Summary</li>
            </ol>
        </nav>
    </div>

    <section class="sectionBudget">
        <h3>Budget Estimates of Project</h3>
        <h5>Project Budget Summary</h5>
        <div class="card rounded-4 p-2 mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">FINANCIAL STATUS</h5>
                    <div role="group" aria-label="Button group">
                        <a href="{{ route('project.progress', ['id' => $id]) }}" class="btn btn-sm btn-primary me-2">Progress</a>
                        <a href="{{ route('budget', ['id' => $id]) }}" class="btn btn-sm btn-primary me-2">List Of Expenses</a>
                        <a href="{{ route('commission', ['id' => $id]) }}" class="btn btn-sm btn-primary">List Of Commission</a>
                    </div>
                </div>
                <!-- Default Table -->
                <table class="table">
                    <tbody>
                        <tr>
                            <td scope="row">AMOUNT OF CONTRACT</td>
                            <td>{{ number_format($contract->amount, 0, ',', '.') }} VND</td>
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
                            <td>{{ number_format($contract->amount - $total - $data->project_price_contingency, 0, ',', '.') }} VND</td>
                        </tr>
                    </tbody>
                </table>
                <div class="card shadow-none">
                    <div class="card-body px-0">
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
                                    <th>Contract</th>
                                    <td id="Contract">{{$contract->contract_name}}</td>
                                </tr>
                                <tr>
                                    <th>Main Contractor Company</th>
                                    <td id="project_contractor">
                                        {{ $customer->company_name}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Contact Name</th>
                                    <td id="project_contact_name">{{ $customer->first_name}} {{$customer->last_name}} </td>
                                </tr>
                                <tr>
                                    <th>Contact Website</th>
                                    <td id="project_contact_website"><a href="{{ $customer->website }}">{{ $customer->website }}</a></td>
                                </tr>
                                <tr>
                                    <th>Contact Phone</th>
                                    <td id="project_contact_phone">{{ $customer->phone_number }}</td>
                                </tr>
                                <tr>
                                    <th>Contact Address</th>
                                    <td id="project_contact_address">{{ $customer->address }}</td>
                                </tr>
                                <tr>
                                    <th>Leader Of Project</th>
                                    <td id="project_lead">{{ $contactEmployee->first_name }} {{ $contactEmployee->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>Start Date</th>
                                    <td id="project_date_start">{{ \Carbon\Carbon::parse($data->project_date_start)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>End Date</th>
                                    <td id="project_date_end">{{ \Carbon\Carbon::parse($data->project_date_end)->format('d M Y') }}</td>
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
    // Assuming contracts data is passed as JSON

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
