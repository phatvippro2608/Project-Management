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
    <div class="row gx-3 my-3">
        <div class="col-md-6 m-0">
                @if(\App\Http\Controllers\AccountController::permission())
                    <div class="btn btn-danger btn-add">
                        <div class="d-flex align-items-center clear-btn">
                            <i class="bi bi-x-lg pe-2"></i>
                            Clear Log
                        </div>
                    </div>
                @endif
        </div>
    </div>
    <section class="section employees">
        <div class="card border rounded-4 p-2">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="logTable" class="table table-borderless table-hover datatable">
                        <thead class="table-light">
                        <tr>
                            <th class="text-left">Description</th>
                            <th class="text-center">Date</th>
                        </tr>

                        </thead>
                        <tbody class="account-list">
                        @foreach($history as $item)
                            <tr class="account-item">
                                <td class="text-right">
                                    @if($item->status == 1)
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
    </section>
@endsection
@section('script')
    <script>
        var table = $('#logTable').DataTable({
            language: { search: "" },
            lengthMenu: [
                [10, 30, 50, 100, -1],
                [10, 30, 50, 100, "All"]
            ],
            pageLength: {{env('ITEM_PER_PAGE')}},
            initComplete: function (settings, json) {
                $('.dt-search').addClass('input-group');
                $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
            },
            responsive: true
        });

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

