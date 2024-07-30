@extends('auth.main')

@section('head')
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
@endsection

@section('contents')

<style type="text/css">

</style>
<div class="pagetitle">
    <h1>Attendance</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{action('App\Http\Controllers\DashboardController@getViewDashboard')}}">Home</a></li>
            <li class="breadcrumb-item active">Attendance</li>
        </ol>
    </nav>
</div>
<div class="section employees">
    <div class="card">
        <div class="card-header">
            <a href="{{ action('App\Http\Controllers\AttendanceController@addAttendanceView') }}" class="btn btn-info text-white"><i class="bi bi-plus"></i> Add Attendance</a>
            <a href="{{ action('App\Http\Controllers\AttendanceController@addAttendanceView') }}" class="btn btn-info text-white"><i class="bi bi-file-earmark-fill"></i> Attendance Report</a>
        </div>
        <div class="card-body">
            <table id="attendanceTable" class="display">
                <thead>
                    <tr>
                        <th scope="col">Employee Name</th>
                        <th scope="col">ID</th>
                        <th scope="col">Date</th>
                        <th scope="col">Sign In</th>
                        <th scope="col">Sign Out</th>
                        <th scope="col">Working</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Abc</th>
                        <td>1</td>
                        <td>12-12-2020</td>
                        <td>9:00 AM</td>
                        <td>6:00 PM</td>
                        <td>9 Hours</td>
                        <td>
                            <a href="#" class="btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    var table = $('#attendanceTable').DataTable({
        responsive: true,
        dom: '<"d-flex justify-content-between align-items-center mt-2 mb-2"<"mr-auto"l><"d-flex justify-content-center mt-2 mb-2"B><"ml-auto mt-2 mb-2"f>>rtip',
        buttons: [{
                extend: 'csv',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column (Actions)
                }
            },
            {
                extend: 'excelHtml5',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column (Actions)
                },
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];

                    // Example: Set the width of the first column
                    $('col', sheet).eq(0).attr('width', 30);

                    // Example: Set the font style of the first row
                    $('row:first c', sheet).attr('s', '42');

                    // Example: Add a custom header
                    var header = '<row r="1"><c t="inlineStr" r="A1"><is><t>Custom Header</t></is></c></row>';
                    sheet.childNodes[0].childNodes[1].innerHTML = header + sheet.childNodes[0].childNodes[1].innerHTML;
                }
            },
            {
                extend: 'pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'print',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            }
        ],
        lengthMenu: [10, 25, 50, 100],
        pageLength: 10
    });
    $('.dt-search').addClass('d-flex align-items-center');
    $('.dt-search label').addClass('d-flex align-items-center');
    $('.dt-search input').addClass('form-control ml-2');
</script>
@endsection