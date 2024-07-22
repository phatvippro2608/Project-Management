@extends('auth.main')
@section('contents')
    <div class="pagetitle">
        <h1>Account</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Account</li>
            </ol>
        </nav>
    </div>

    <section class="section employees">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body m-1 mt-3">
                        <div class="d-inline-flex align-items-center">
                            <div class="btn btn-primary mx-2 btn-add">
                                <div class="d-flex align-items-center">
                                <span class="material-symbols-outlined">
                                    add
                                </span>
                                    Add Account
                                </div>
                            </div>
                            <div class="ms-auto text-secondary">
                                <div class="search-container">
                                    <input type="text" class="form-control form-control-md" aria-label="Search invoice" placeholder="Search ...">
                                    <i class="bi bi-search search-button"></i>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mt-4">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                <tr>
                                    <th style="width: 112px"></th>
                                    <th class="text-center">Code</th>
                                    <th class="text-center">Full Name</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Password</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Last Active</th>
                                    <th class="text-center">Created At</th>
                                </tr>

                                </thead>
                                <tbody class="account-list">
                                <tr class="account-item">
                                    <td class="text-right">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="action-buttons ">
                                                <a class=" edit">
                                                    <i class="bi bi-pencil-square ic-update ic-btn"></i>
                                                </a>
                                                <a class=" delete">
                                                    <i class="bi bi-trash ic-delete ic-btn" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                            <img src="http://ventech.local/assets/img/profile-img.jpg" alt="" class="account-photo rounded-circle p-0 m-0">
                                        </div>

                                    </td>
                                    <td class="text-center ">
                                        5673838
                                    </td>
                                    <td class="text-center">
                                        Nguyễn Văn A
                                    </td>
                                    <td class="text-center">
                                        nguyenvana
                                    </td>
                                    <td class="text-center">
                                        password
                                    </td>
                                    <td class="text-center">
                                        <i class="bi bi-circle-fill account-status"></i>Active
                                    </td>
                                    <td class="text-center">
                                        Active 16 hours ago
                                    </td>
                                    <td class="text-center">
                                        16 years ago
                                    </td>
                                </tr>
                                <tr class="account-item">
                                    <td class="text-right">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="action-buttons ">
                                                <a class=" edit">
                                                    <i class="bi bi-pencil-square ic-update ic-btn"></i>
                                                </a>
                                                <a class=" delete">
                                                    <i class="bi bi-trash ic-delete ic-btn" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                            <img src="http://ventech.local/assets/img/profile-img.jpg" alt="" class="account-photo rounded-circle p-0 m-0">
                                        </div>

                                    </td>
                                    <td class="text-center ">
                                        5673838
                                    </td>
                                    <td class="text-center">
                                        Nguyễn Văn A
                                    </td>
                                    <td class="text-center">
                                        nguyenvana
                                    </td>
                                    <td class="text-center">
                                        password
                                    </td>
                                    <td class="text-center">
                                        <i class="bi bi-circle-fill account-status"></i>Active
                                    </td>
                                    <td class="text-center">
                                        Active 16 hours ago
                                    </td>
                                    <td class="text-center">
                                        16 years ago
                                    </td>
                                </tr>
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
                            <label for="">
                                Employee
                            </label>
                            <select  class="form-select" aria-label="Default select example">
                                <option value="-1">No select</option>
                                <option value="123456">123456 - Nguyễn Văn A</option>
                                <option value="123452">123456 - Nguyễn Văn B</option>
                            </select>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="">
                                Username
                            </label>
                            <input  type="text" class="form-control name2">
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="">Password</label>
                            <label for="auto_pwd">Auto</label>
                            <input class="auto_pwd" type="checkbox" name="auto_pwd" checked>
                            <input class="form-control name5" type="text" placeholder="">
                        </div>
                        <div class="col-md-12" style="margin-top: 1rem">
                            <label for="">
                                Status
                            </label>
                            <select  class="form-select" aria-label="Default select example">
                                <option value="1">Active</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-upload">Create</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.btn-add').click(function () {
            $('.md1 .modal-title').text('Add Account');
            $('.md1').modal('show');
        });
    </script>
@endsection
