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
                    <div class="card-header">
                        <div class="w-100 d-flex justify-content-between">
                            <div class="ms-2 text-secondary">
                                <div class="search-container">
                                    <form method="GET"
                                          action="{{ action('App\Http\Controllers\AccountController@loginHistory') }}"
                                          class="d-flex">
                                        <input name="keyw" type="date"
                                               value="{{ request()->input('keyw') }}"
                                               class="form-control form-control-md" aria-label="Search invoice"
                                               placeholder="Search ...">
                                        <button type="submit" class="btn btn-link p-0"><i
                                                class="bi bi-search search-button"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="btn btn-danger mx-2 btn-add">
                                <div class="d-flex align-items-center clear-btn">
                                    <i class="bi bi-file-earmark-plus-fill pe-2"></i>
                                    Clear Log
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body m-1">
                        <div class="table-responsive mt-4">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                <tr>
                                    <th class="text-left">Description</th>
                                    <th class="text-center">Date</th>
                                </tr>

                                </thead>
                                <tbody class="account-list">
                                @foreach($history as $item)
                                    <tr class="account-item">
                                        <td class="text-right">
                                            @if($item->status == 0)
                                                User Successfully Logged In (IP: {{$item->ip}})
                                            @else
                                                Failed Login Attempt (IP: {{$item->ip}})
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{$item->created_at}}
                                        </td>
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
@endsection
@section('script')
    <script>
        $('.clear-btn').click(function () {
            if (!confirm("Chọn vào 'YES' để xác nhận xóa thông tin?\nSau khi xóa dữ liệu sẽ không thể phục hồi lại được.")) {
                return;
            }
            $.ajax({
                url: `{{action('App\Http\Controllers\AccountController@clearHistory')}}`,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    result = JSON.parse(result);
                    if (result.status === 200) {
                        toastr.success(result.message, "Thao tác thành công");
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    } else {
                        toastr.error(result.message, "Thao tác thất bại");
                    }
                }
            });
        })
    </script>
@endsection

