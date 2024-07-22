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
@endsection
