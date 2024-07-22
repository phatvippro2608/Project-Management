@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Employees</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Employees</li>
            </ol>
        </nav>
    </div>

    <section class="section employees">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Employee List
                    </div>
                    <div class="card-body m-1">
                        <div class="btn btn-primary mx-2">
                            <div class="d-flex align-items-center at1">
                                <span class="material-symbols-outlined">
                                    add
                                </span>
                                Add
                            </div>
                        </div>
                        <div class="btn btn-success mx-2 at2">
                            <div class="d-flex align-items-center">
                                <span class="material-symbols-outlined">
                                    upload_2
                                </span>
                                Import
                            </div>
                        </div>
                        <div class="btn btn-success mx-2">
                            <div class="d-flex align-items-center">
                                <span class="material-symbols-outlined">
                                    download_2
                                </span>
                                Export
                            </div>
                        </div>
                        <div class="card-body border-bottom py-3">
                            <div class="d-flex">
                                <div class="text-secondary">
                                    Show
                                    <div class="mx-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm" value="8" size="3" aria-label="Invoices count">
                                    </div>
                                    entries
                                </div>
                                <div class="ms-auto text-secondary">
                                    Search:
                                    <div class="ms-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>Employee Code</th>
                                        <th>Photo</th>
                                        <th>Full Name</th>
                                        <th>English Name</th>
                                        <th>Gender</th>
                                        <th>Phone</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $item)
                                    <tr>
                                        <td>{{$item->employee_code}}</td>
                                        <td>{{$item->photo}}</td>
                                        <td>{{$item->first_name + $item->last_name}}</td>
                                        <td>{{$item->en_name}}</td>
                                        <td>{{$item->gender}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade md1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-bold"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-add">Upload</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade md2">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-bold"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Choose file (*.xlsx) or download
                            </label>
                            <a href="">Example</a>
                            <input accept=".xlsx" name="file-excel" type="file" class="form-control">
                            <br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-upload">Upload</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.at1').click(function () {
            $('.md1 .modal-title').text('Add Employee');
            $('.md1').modal('show');
        });
        $('.at2').click(function () {
            $('.md2 .modal-title').text('Add Employee');
            $('.md2').modal('show');
        });
    </script>
@endsection
