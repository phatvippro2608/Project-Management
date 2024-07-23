@extends('auth.main')

@section('contents')
    <div class="container mt-5">
        <h1 class="mb-4">Project List</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <!-- Button to open the modal to add new material -->
        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
            Add New Material
        </button>

        <!-- Table to display materials -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Material Management Table</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Code</th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Brand</th>
                            <th scope="col">Origin</th>
                            <th scope="col">Unit</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Labor Price</th>
                            <th scope="col">Total Price</th>
                            <th scope="col">VAT</th>
                            <th scope="col">Delivery Time</th>
                            <th scope="col">Warranty Time</th>
                            <th scope="col">Remarks</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($materials as $material)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $material->material_code }}</td>
                                <td>{{ $material->material_name }}</td>
                                <td>{{ $material->description }}</td>
                                <td>{{ $material->brand }}</td>
                                <td>{{ $material->origin }}</td>
                                <td>{{ $material->unit }}</td>
                                <td>{{ $material->quantity }}</td>
                                <td>{{ $material->unit_price }}</td>
                                <td>{{ $material->labor_price }}</td>
                                <td>{{ $material->total_price }}</td>
                                <td>{{ $material->vat }}</td>
                                <td>{{ $material->delivery_time }}</td>
                                <td>{{ $material->warranty_time }}</td>
                                <td>{{ $material->remarks }}</td>
                                <td>
                                    <a href="{{ route('materials.edit', $material->material_id) }}" class="btn btn-warning btn-sm mb-2 pe-4">Edit</a>
                                    <form action="{{ route('materials.destroy', $material->material_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm mb-2">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <table class="table">
                    <tbody>
                    <tr>
                        <td>Payment term</td>
                        <td>- First payment: Deposit 30% of the Contract value included VAT within 7 working days</td>
                    </tr>
                    <tr>
                        <td>Warranty term</td>
                        <td>- All Goods of the Contract shall be warranted As</td>
                    </tr>
                    <tr>
                        <td>Delivery & Installation</td>
                        <td>For all the site at Quang Nam </br>
                            Following the - Standard for cabling</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <table class="table">
                    <tbody>
                    <tr>
                        <td>Sub Total</td>
                        <td>{{ number_format($sub_total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>VAT of GOODS</td>
                        <td>{{ number_format($vat_of_goods, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>GRAND TOTAL</strong></td>
                        <td><strong>{{ number_format($grand_total, 0, ',', '.') }}</strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMaterialModalLabel">Add New Material</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('materials.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="material_code" class="form-label">Code</label>
                                    <input type="text" class="form-control" id="material_code" name="material_code" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="material_name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="material_name" name="material_name" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="brand" class="form-label">Brand</label>
                                    <input type="text" class="form-control" id="brand" name="brand">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="origin" class="form-label">Origin</label>
                                    <input type="text" class="form-control" id="origin" name="origin">
                                </div>
                                <div class="col-md-6">
                                    <label for="unit" class="form-label">Unit</label>
                                    <input type="text" class="form-control" id="unit" name="unit">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity">
                                </div>
                                <div class="col-md-6">
                                    <label for="unit_price" class="form-label">Unit Price</label>
                                    <input type="number" step="0.01" class="form-control" id="unit_price" name="unit_price">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="labor_price" class="form-label">Labor Price</label>
                                    <input type="number" step="0.01" class="form-control" id="labor_price" name="labor_price">
                                </div>
                                <div class="col-md-6">
                                    <label for="total_price" class="form-label">Total Price</label>
                                    <input type="number" step="0.01" class="form-control" id="total_price" name="total_price">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="vat" class="form-label">VAT</label>
                                    <input type="number" step="0.01" class="form-control" id="vat" name="vat">
                                </div>
                                <div class="col-md-6">
                                    <label for="delivery_time" class="form-label">Delivery Time</label>
                                    <input type="text" class="form-control" id="delivery_time" name="delivery_time">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="warranty_time" class="form-label">Warranty Time</label>
                                    <input type="text" class="form-control" id="warranty_time" name="warranty_time">
                                </div>
                                <div class="col-md-6">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Material</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Custom JavaScript can be added here
    </script>

    <style>
        .custom-td {
            display: block;
            margin-top: 5px;
        }
    </style>
@endsection
