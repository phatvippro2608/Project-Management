@extends('auth.main')

@section('contents')
    <div class="pagetitle">
        <h1>Employees</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{action('App\Http\Controllers\EmployeesController@getView')}}">Employees</a></li>
                <li class="breadcrumb-item active">Import</li>

            </ol>
        </nav>
    </div>
    <section class="section import">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Import Employee
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col d-flex align-items-center">
                                <div class="btn btn-success mx-2" id="uploadBtn">
                                    <div class="d-flex align-items-center at2">
                                        <i class="bi bi-file-earmark-arrow-up-fill pe-2"></i>
                                        Import
                                    </div>
                                </div>
                                <input type="file" hidden="hidden" id="fileInput">
                                <div class="d-flex align-items-center m-0"><span>Lưu ý các trường bắt buộc : </span><span class="text-warning"> &nbsp;Email, Điện thoại, Tên thành viên &nbsp;</span><span> Mật khẩu mặc định là 123456</span></div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mt-3">
                                            <button class="btn btn-primary">123</button>
                                            <button class="btn btn-primary">123</button>
                                            <button class="btn btn-primary">123</button>
                                            <button class="btn btn-primary">123</button>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">First</th>
                                                    <th scope="col">Last</th>
                                                    <th scope="col">Handle</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <th scope="row">1</th>
                                                    <td>Mark</td>
                                                    <td>Otto</td>
                                                    <td>@mdo</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">2</th>
                                                    <td>Jacob</td>
                                                    <td>Thornton</td>
                                                    <td>@fat</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">3</th>
                                                    <td colspan="2">Larry the Bird</td>
                                                    <td>@twitter</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        let _post_import = '{{action('App\Http\Controllers\EmployeesController@import')}}';
        $('.at2').click(function (event) {
            event.preventDefault();

        })
        $(document).ready(function() {
            $('#uploadBtn').on('click', function (e) {
                e.preventDefault(); // Prevent the default action
                $('#fileInput').click(); // Trigger the file input click
            });

            $('#fileInput').on('change', function () {

                let fileInput = this.files[0];

                if (fileInput === undefined) {
                    toastr.error("Vui lòng chọn file", "Thao tác thất bại");
                    // setTimeout(function () {
                    //     window.location.reload();
                    // }, 500);
                }

                var formData = new FormData();
                formData.append('file-excel', fileInput);
                $.ajax({
                    url: _post_import, type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        result = JSON.parse(result);
                        if (result.status === 200) {
                            toastr.success(result.message, "Thao tác thành công");
                            // setTimeout(function () {
                            //     window.location.reload();
                            // }, 500);
                        } else {
                            toastr.error(result.message, "Thao tác thất bại");
                        }
                    }
                });
            });
        })

    </script>
@endsection
