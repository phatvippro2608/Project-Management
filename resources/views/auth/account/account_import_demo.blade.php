@extends('auth.main')

@section('contents')
    <div class="card">
        <div class="card-body">
            <div class="row" style="margin-top: 10px">
                <div class="col-md-3">
                    <select class="form-select w-100 c1" aria-label="Default">
                        <option value="email">Email</option>
                        <option value="sdt">Số điện thoại</option>
                        <option value="hoten">Họ tên</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select w-100 c2" aria-label="Default">
                        <option value="sdt">Số điện thoại</option>
                        <option value="email">Email</option>
                        <option value="hoten">Họ tên</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select w-100 c3" aria-label="Default">
                        <option value="hoten">Họ tên</option>
                        <option value="email">Email</option>
                        <option value="sdt">Số điện thoại</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3 ">
                <div class="col-md-3">
                    <input accept=".xlsx" name="file-excel" type="file" class="form-control">
                    <br>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary btn-import">Import</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.name3').prop('disabled', $('.auto_pwd').is(':checked'));
        $('.auto_pwd').change(function () {
            $('.name3').prop('disabled', $('.auto_pwd').is(':checked'));
        })

        $('.btn-add').click(function () {
            $('.md1 .modal-title').text('Add Account');
            $('.md1 .passName').text('Password');
            $('.name5').val(0);
            $('.md1').modal('show');

            $('.at2').click(function () {
                $.ajax({
                    url: `{{action('App\Http\Controllers\AccountController@add')}}`,
                    type: "PUT",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id_employee': $('.name1').val(),
                        'username': $('.name2').val(),
                        'password': $('.name3').val(),
                        'status': $('.name4').val(),
                        'permission': $('.name5').val(),
                        'auto_pwd': $('.auto_pwd').is(':checked')
                    },
                    success: function (result) {
                        result = JSON.parse(result);
                        if (result.status === 200) {
                            // toastr.success(result.message, "Thao tác thành công");
                            alert(result.message);
                            setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        } else {
                            // toastr.error(result.message, "Thao tác thất bại");
                            alert(result.message);
                        }
                    }
                });
            });
        });

        $(document).on('click', '.ic-update', function () {
            $('.md1 .modal-title').text('Update Account');
            $('.md1 .passName').text('New Password');
            var data = JSON.parse($(this).attr('data'));
            $('.name1').val(data.id_employee);
            $('.name2').val(data.username);
            $('.name4').val(data.status);
            $('.name5').val(data.permission);
            $('.md1').modal('show');

            $('.at2').click(function () {
                $.ajax({
                    url: `{{action('App\Http\Controllers\AccountController@update')}}`,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id_account': data.id_account,
                        'id_employee': $('.name1').val(),
                        'username': $('.name2').val(),
                        'password': $('.name3').val(),
                        'status': $('.name4').val(),
                        'permission': $('.name5').val(),
                        'auto_pwd': $('.auto_pwd').is(':checked')
                    },
                    success: function (result) {
                        result = JSON.parse(result);
                        if (result.status === 200) {
                            // toastr.success(result.message, "Thao tác thành công");
                            alert(result.message);
                            setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        } else {
                            // toastr.error(result.message, "Thao tác thất bại");
                            alert(result.message);
                        }
                    }
                });
            });
        });


        $('.ic-delete').click(function () {
            if (!confirm("Chọn vào 'YES' để xác nhận xóa thông tin?\nSau khi xóa dữ liệu sẽ không thể phục hồi lại được.")) {
                return;
            }
            var id = $(this).attr('data');
            $.ajax({
                url: `{{action('App\Http\Controllers\AccountController@delete')}}`,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id_account': id,
                },
                success: function (result) {
                    result = JSON.parse(result);
                    if (result.status === 200) {
                        alert(result.message);
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        alert(result.message);
                    }
                }
            });
        })

        $('.btn-import').click(function (){
            var fileInput = $('input[name="file-excel"]')[0].files[0];
            var formData = new FormData();
            formData.append('file-excel', fileInput);
            $.ajax({
                url: _postImport, type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    result = JSON.parse(result);
                    if (result.status === 200) {
                        alert('Import thành công')
                    } else {
                        toastr.error(result.message, "Import thất bại");
                    }
                }
            })
        })
    </script>
@endsection

