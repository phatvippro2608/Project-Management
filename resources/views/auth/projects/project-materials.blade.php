@extends('auth.main')
@section('head')
@endsection
@section('contents')
<div class="card p-2 border rounded-4">
            <div class="card-header py-0">
                <div class="card-title my-3 p-0">Materials on </div>
            </div>
            <div class="card-body">
                <table id="materialsProject" class="table table-hover table-borderless">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" data-sort="id_source">#<i class="bi bi-sort"></i></th>
                            <th data-sort="name">Name<i class="bi bi-sort"></i></th>
                            <th data-sort="description" style="width:15%">P/N<i class="bi bi-sort"></i></th>
                            <th data-sort="laborqty">Quantity<i class="bi bi-sort"></i></th>
                            <th data-sort="budgetqty">Unit Price<i class="bi bi-sort"></i></th>
                            <th data-sort="laborcost">Labor Price<i class="bi bi-sort"></i></th>
                            <th data-sort="misccost">Total Price<i class="bi bi-sort"></i></th>
                            <th data-sort="otbudget">VAT<i class="bi bi-sort"></i></th>
                            <th data-sort="perdiempay">Action<i class="bi bi-sort"></i></th>
                            <th data-sort="date">Date<i class="bi bi-sort"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materialData as $data)
                        <tr>
                            <td>{{$data->project_material_id}}</td>
                            <td>{{$data->project_material_name}}</td>
                            <td>{{$data->project_material_code}}</td>
                            <td>{{$data->project_material_quantity}}</td>
                            <td>{{$data->project_material_unit_price}}</td>
                            <td>{{$data->project_material_labor_price}}</td>
                            <td>{{$data->project_material_name}}</td>
                            <td>{{$data->project_material_name}}</td>
                            <td>{{$data->project_material_name}}</td>
                            <td>{{$data->project_material_name}}</td>
                            <td>{{$data->project_material_name}}</td>
                        </tr>
                        @endforeach
                    </tbody>
<script>
    new DataTable('#materialsProject');

</script>
@endsection