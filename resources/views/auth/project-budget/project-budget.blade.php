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
                <a href="#" class="btn btn-info rounded-pill">LIST OF COMMISSION</a>
            </div>
        </div>
                <hr>
                <!-- Default Table -->
                <table class="table">
                    <tbody>
                        <tr>
                            <td scope="row">AMOUNT OF CONTRACT</td>
                            <td>x VND</td>
                        </tr>
                        <tr>
                            <td scope="row">SUBTOTAL (Budget)</td>
                            <td>x VND</td>
                        </tr>
                        <tr>
                            <td scope="row">RISK (CONTINGENCY)</td>
                            <td>x VND</td>
                        </tr>
                        <tr>
                            <td scope="row">COMMISSION</td>
                            <td>x VND</td>
                        </tr>
                        <tr class="table-primary">
                            <th scope="row">FUNDS REMAINING</th>
                            <td>x VND</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">PROJECT INFORMATION</h5>
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                        @foreach ($data as $row)
                                <tr>
                                    <th>Project Name</th>
                                    <td>{{ $row->project_name }}</td>
                                </tr>
                                <tr>
                                  <th>Project Description</th>
                                  <td>{{ $row->project_description }}</td>
                                </tr>
                                <tr>
                                  <th>Project Adress</th>
                                  <td>{{ $row->project_address }}</td>
                                </tr>
                                <tr>
                                  <th>Main Contractor</th>
                                  <td>{{ $row->project_main_contractor }}</td>

                                </tr>
                                <tr>
                                  <th>Contact Name</th>
                                  <td>{{ $row->project_contact_name }}</td>
                                </tr>
                                <tr>
                                  <th>Contact Website</th>
                                  <td>{{ $row->project_contact_website }}</td>

                                </tr>
                                <tr>
                                  <th>Contact Phone</th>
                                  <td>{{ $row->project_contact_phone }}</td>
                                </tr>
                                <tr>
                                  <th>Contact Adress</th>
                                  <td>{{ $row->project_contact_address }}</td>
                                </tr>
                                <tr>
                                  <th>Start Date</th>
                                  <td>{{ $row->project_date_start }}</td>
                                </tr>
                                <tr>
                                  <th>End Date</th>
                                  <td>{{ $row->project_date_end }}</td>
                                </tr>
                                            </table>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
