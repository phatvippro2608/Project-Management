@extends('auth.main')

@section('head')
@endsection

@section('contents')
    <div class="pagetitle">
        <h1>Internal Certificates</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Internal Certificates</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-body p-3">

                <table class="table table-hover table-striped" id="certificateTable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Employee Code</th>
                            <th scope="col">Photo</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Certificate Body</th>
                            <th scope="col">Certificate</th>
                            <th class="text-center" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center">
                                <button data-disciplinary="1" class="btn p-1 text-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                |
                                <button data-disciplinary="1" class="btn p-1 text-danger" onclick="deleteCertificate(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
