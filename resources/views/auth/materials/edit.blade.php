@extends('auth.main')
@section('contents')
    <div class="container mt-5">
        <h1 class="mb-4">Edit Material</h1>
        <form action="{{ route('materials.update', $material->material_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="material_code" class="form-label">P/N</label>
                    <input type="text" class="form-control" id="material_code" name="material_code"
                        value="{{ $material->material_code }}" required>
                </div>
                <div class="col-md-6">
                    <label for="material_name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="material_name" name="material_name"
                        value="{{ $material->material_name }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description">{{ $material->description }}</textarea>
                </div>
                <div class="col-md-6">
                    <label for="brand" class="form-label">Brand</label>
                    <input type="text" class="form-control" id="brand" name="brand" value="{{ $material->brand }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="origin" class="form-label">Origin</label>
                    <input type="text" class="form-control" id="origin" name="origin"
                        value="{{ $material->origin }}">
                </div>
                <div class="col-md-6">
                    <label for="unit" class="form-label">Unit</label>
                    <input type="text" class="form-control" id="unit" name="unit" value="{{ $material->unit }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity"
                        value="{{ $material->quantity }}">
                </div>
                <div class="col-md-6">
                    <label for="unit_price" class="form-label">Unit Price</label>
                    <input type="number" step="0.01" class="form-control" id="unit_price" name="unit_price"
                        value="{{ $material->unit_price }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="labor_price" class="form-label">Labor Price</label>
                    <input type="number" step="0.01" class="form-control" id="labor_price" name="labor_price"
                        value="{{ $material->labor_price }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="vat" class="form-label">VAT</label>
                    <input type="number" step="0.01" class="form-control" id="vat" name="vat"
                        value="{{ $material->vat }}">
                </div>
                <div class="col-md-6">
                    <label for="delivery_time" class="form-label">Delivery Time</label>
                    <input type="text" class="form-control" id="delivery_time" name="delivery_time"
                        value="{{ $material->delivery_time }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="warranty_time" class="form-label">Warranty Time</label>
                    <input type="text" class="form-control" id="warranty_time" name="warranty_time"
                        value="{{ $material->warranty_time }}">
                </div>
                <div class="col-md-6">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea class="form-control" id="remarks" name="remarks">{{ $material->remarks }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save changes</button>
            <a href="{{ route('materials.index') }}" class="btn btn-danger">Cancel</a>
        </form>
    </div>
@endsection

@section('script')
    <script>
        document.getElementById('vat').addEventListener('input', function() {
            let vatValue = this.value;
            if (vatValue.length > 3) {
                this.value = vatValue.slice(0, 3);
            }
        });

        document.getElementById('quantity').addEventListener('input', function() {
            let quantityValue = this.value;
            if (quantityValue.length > 3) {
                this.value = quantityValue.slice(0, 3);
            }
        });

        document.getElementById('unit_price').addEventListener('input', function() {
            let unit_price = this.value;
            if (unit_price.length > 9) {
                this.value = unit_price.slice(0, 9);
            }
        });

        document.getElementById('labor_price').addEventListener('input', function() {
            let labor_price = this.value;
            if (labor_price.length > 9) {
                this.value = labor_price.slice(0, 9);
            }
        });
    </script>
@endsection
