@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">

            <div class="col-lg-12">
                <div class="row">
                    <div class="col-xxl-2 col-md-6">
                        <div class="card info-card sales-card ">
                            <div class="card-body">
                                <h5 class="card-title"><b>Tổng thành viên</b></h5>
                                <div class="d-flex align-items-center">
                                    <div class="ps-3">
                                        <h6>100</h6>
                                    </div>
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ms-3">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-2 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title"><b>Team</b></h5>
                                <div class="d-flex align-items-center">
                                    <div class="ps-3">
                                        <h6>100</h6>
                                    </div>
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ms-3">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xxl-2 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title"><b>Kế hoạch</b></h5>
                                <div class="d-flex align-items-center">
                                    <div class="ps-3">
                                        <h6>100</h6>
                                    </div>
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ms-3">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xxl-2 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title"><b>Công việc</b></h5>
                                <div class="d-flex align-items-center">
                                    <div class="ps-3">
                                        <h6>100</h6>
                                    </div>
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ms-3">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>


        </div>
    </section>
@endsection
