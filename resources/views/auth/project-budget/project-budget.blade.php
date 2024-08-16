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
        <div class="d-flex justify-content-between align-items-center">
            <h3>Budget Estimates of Project</h3>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Select Location
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('project.details', ['project_id' => $id]) }}">All</a></li>
                    @foreach($locations as $location)
                        <li><a class="dropdown-item" href="{{ route('project.details', ['project_id' => $id, 'location' => $location->project_location_id]) }}">{{ $location->project_location_name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <h5>Project Budget Summary</h5>
        <div class="card rounded-4 p-2 mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">FINANCIAL STATUS</h5>
                    <div role="group" aria-label="Button group">
                    @if(!empty($keyword))
                        <a href="{{ route('project.progress', ['project_id' => $id, 'location_id' => $keyword]) }}"
                            class="btn btn-sm btn-primary me-2">Progress</a>
                    @endif
                    <a href="{{ !empty($keyword) ? route('budget', ['project_id' => $id, 'location' => $keyword]) : route('budget', ['project_id' => $id]) }}" class="btn btn-sm btn-primary me-2">List Of Expenses</a>
                    <a href="{{ !empty($keyword) ? route('prjMaterials', ['project_id' => $id, 'location' => $keyword]) : route('prjMaterials', ['project_id' => $id]) }}" class="btn btn-sm btn-primary me-2">Materials</a>

                    </div>
                </div>
                <table class="table">
                    <tbody>
                        <tr>
                            <td scope="row" id="amount">{{!empty($keyword) ? "AMOUNT" : "AMOUNT OF CONTRACT"}}</td>
                            <td id="valAmount">
                                {{ number_format(!empty($keyword) ? $dataLoca->location_amount : $contract->amount, 0, ',', '.') }} VND
                            </td>
                        </tr>
                        <tr>
                            <td scope="row">SUBTOTAL (Budget)</td>
                            <td id="valSubtotal">{{ number_format($total, 0, ',', '.') }} VND</td>
                        </tr>
                        <tr>
                            <td scope="row">RISK (CONTINGENCY)</td>
                            <td id="valRisk">{{ number_format($contingency_price->project_price_contingency, 0, ',', '.') }} VND</td>
                        </tr>
                        @if(empty($keyword))
                            <tr>
                                <td scope="row">MATERIALS COST</td>
                                <td id="valMaterialCost">{{ number_format($materialCost, 0, ',', '.') }} VND</td>
                            </tr>
                        @endif
                        
                        <tr class="table-primary">
                            <th scope="row">FUNDS REMAINING</th>
                            <td id="remain"></td>
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
                                    <th>Contract</th>
                                    <td id="Contract">{{ $contract->contract_name }}</td>
                                </tr>
                                <tr>
                                    <th>Main Contractor Company</th>
                                    <td id="project_contractor">{{ $customer->company_name }}</td>
                                </tr>
                                <tr>
                                    <th>Contact Name</th>
                                    <td id="project_contact_name">{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>Contact Website</th>
                                    <td id="project_contact_website"><a href="{{ $customer->website }}" target="_blank">{{ $customer->website }}</a></td>
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
                            <input type="text" class="form-control" id="edit_project_name" name="project_name" value="{{ $data->project_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_project_description" class="form-label">Project Description</label>
                            <textarea class="form-control" id="edit_project_description" name="project_description" required>{{ $data->project_description }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function parseCurrency(value) {
            return parseInt(value.replace(/\./g, '').replace(' VND', ''), 10);
        }

        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(',', '').replace(' ', '');
            const n = !isFinite(+number) ? 0 : +number,
                  prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                  sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                  dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                  toFixedFix = function(n, prec) {
                      const k = Math.pow(10, prec);
                      return '' + Math.round(n * k) / k;
                  };
            const s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        let amount = parseCurrency(document.getElementById('valAmount').textContent);
        let risk = parseCurrency(document.getElementById('valRisk').textContent);
        let subtotal = parseCurrency(document.getElementById('valSubtotal').textContent);
        @if(empty($keyword))
            let fundsRemaining = amount - subtotal - risk - parseCurrency(document.getElementById('valMaterialCost').textContent);
        @else
            let fundsRemaining = amount - subtotal - risk;
        @endif
        document.getElementById('remain').textContent = `${number_format(fundsRemaining, 0, ',', '.')} VND`;

        $('#editProjectForm').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                type: 'PUT',
                url: '{{ route('project.update', ['project_id' => $data->project_id]) }}',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        toastr.success('Project information updated successfully!');
                        $('#editProjectModal').modal('hide');
                        $('#project_name').text(response.data.project_name);
                        $('#project_description').text(response.data.project_description);
                    } else {
                        toastr.error('Failed to update project information.');
                    }
                },
                error: function(response) {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        });
    });
    </script>
@endsection