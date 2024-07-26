@extends('auth.main')

@section('contents')
    <div class="card">
        <div class="card-body">
{{--            <div class="row" style="margin-top: 10px">--}}
{{--                <div class="col-md-3">--}}
{{--                    <select class="form-select w-100 c1" aria-label="Default">--}}
{{--                        <option value="email">Email</option>--}}
{{--                        <option value="sdt">Số điện thoại</option>--}}
{{--                        <option value="hoten">Họ tên</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--                <div class="col-md-3">--}}
{{--                    <select class="form-select w-100 c2" aria-label="Default">--}}
{{--                        <option value="sdt">Số điện thoại</option>--}}
{{--                        <option value="email">Email</option>--}}
{{--                        <option value="hoten">Họ tên</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--                <div class="col-md-3">--}}
{{--                    <select class="form-select w-100 c3" aria-label="Default">--}}
{{--                        <option value="hoten">Họ tên</option>--}}
{{--                        <option value="email">Email</option>--}}
{{--                        <option value="sdt">Số điện thoại</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}
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
        $('.btn-import').click(function (){
            var fileInput = $('input[name="file-excel"]')[0].files[0];
            var formData = new FormData();
            formData.append('file-excel', fileInput);
            $.ajax({
                url: `{{action('App\Http\Controllers\AccountController@import')}}`,
                type: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
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

