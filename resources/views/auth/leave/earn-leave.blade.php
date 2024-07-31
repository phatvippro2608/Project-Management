@extends('auth.main')

@section('contents')
    <style>
        .folded-corner {
            position: relative;
            background-color: white;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .folded-corner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 20px;
            height: 20px;
            background-color: #1472e5;
            clip-path: polygon(0 0, 100% 0, 0 100%);
        }
    </style>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center bg-white p-3 mb-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-megaphone text-primary me-2"></i>
                <h3 class="text-primary m-0">Earn Leave</h3>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a
                            href="{{ action('App\Http\Controllers\DashboardController@getViewDashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">earn leave</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
        </div>
        <div class="folded-corner bg-white p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Earn Balance</h5>
            </div>
            <hr>
            <div style="height: 100vh;">
                <table id="applicationTable" class="table table-bordered mt-3 mb-3">
                    <thead>
                        <tr>

                            <th class="text-start">Employee Pin</th>
                            <th>Employee Name</th>
                            <th>Total Hour</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($earn_leave as $item)
                            <tr>
                                <td class="text-start">{{ $item->employee_code }}</td>
                                <td>{{ $item->employee_name }}</td>
                                <td>{{ $item->totalhour }} hour</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            var table = $('#applicationTable').DataTable()

        });
    </script>
@endsection

@section('head')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.flash.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.print.min.js">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
