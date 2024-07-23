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

        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addMaterialModal">Add</button>
        <button class="btn btn-primary mb-4">Import</button>
        <button class="btn btn-primary mb-4">Export</button>
        <button class="btn btn-primary mb-4">Filter</button>
        <button class="btn btn-primary mb-4">Sort</button>
        <button class="btn btn-primary mb-4">Search</button>

        <!-- Table to display materials -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">Đang triển khai</h5>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">Sale</th>
                            <th scope="col">StartDate</th>
                            <th scope="col">EndDate</th>
                            <th scope="col">Status</th>
                            <th scope="col">Budget</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-success">Nghiệm thu - Defect</h5>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">Sale</th>
                            <th scope="col">StartDate</th>
                            <th scope="col">EndDate</th>
                            <th scope="col">Status</th>
                            <th scope="col">Budget</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-info">Khảo sát - Thiết kế (3)</h5>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">Sale</th>
                            <th scope="col">StartDate</th>
                            <th scope="col">EndDate</th>
                            <th scope="col">Status</th>
                            <th scope="col">Budget</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-warning">Support - Hổ trợ (3)</h5>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">Sale</th>
                            <th scope="col">StartDate</th>
                            <th scope="col">EndDate</th>
                            <th scope="col">Status</th>
                            <th scope="col">Budget</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-danger">Close (4)</h5>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">Sale</th>
                            <th scope="col">StartDate</th>
                            <th scope="col">EndDate</th>
                            <th scope="col">Status</th>
                            <th scope="col">Budget</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
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
