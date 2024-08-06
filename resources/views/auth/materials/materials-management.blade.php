@extends('auth.main')

@section('contents')
    <style>
        #materialsTable td,
        #materialsTable th {
            text-align: center;
            vertical-align: middle;
        }

        #materialDetails {
            word-wrap: break-word;
            word-break: break-all;
            white-space: normal;
        }
    </style>

    <div class="container-fluid">
        <div class="pagetitle">
            <h1>Material Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Material Management</li>
                </ol>
            </nav>
        </div>
        {{-- @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif --}}
        <!-- Button to open the modal to add new material -->
        <button type="button" id="add-material-btn" class="btn btn-primary mb-4" data-bs-toggle="modal"
            data-bs-target="#addMaterialModal">
            <i class="bi bi-plus-lg me-2"></i>Add New Material
        </button>

        <!-- Table to display materials -->
        <div class="card p-2 border rounded-4">
            <div class="card-header py-0">
                <div class="card-title my-3 p-0">Material Management Table</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="materialsTable" class="table table-borderless table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">P/N</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Labor Price</th>
                                <th scope="col">Total Price</th>
                                <th scope="col">VAT</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($materials as $material)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ Str::limit($material->material_name, 10, '...') }}</td>
                                    <td>{{ Str::limit($material->material_code, 10, '...') }}</td>
                                    <td>{{ $material->quantity }}</td>
                                    <td>{{ number_format($material->unit_price, 2, ',', '.') }}</td>
                                    <td>{{ number_format($material->labor_price, 2, ',', '.') }}</td>
                                    <td>{{ number_format($material->total_price, 2, ',', '.') }}</td>
                                    <td>{{ number_format($material->vat, 2, ',', '.') }}%</td>
                                    <td>
                                        <button
                                            class="btn p-1 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                            data-id="{{ $material->material_id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        |
                                        <button
                                            class="btn p-1 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                            data-id="{{ $material->material_id }}">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                        |
                                        <button
                                            class="btn p-1 btn-primary border-0 bg-transparent text-info shadow-none detail-btn"
                                            data-id="{{ $material->material_id }}">
                                            <i class="bi bi-info-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Material Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div id="materialDetails"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card border rounded-4 p-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless mb-0">
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
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border rounded-4 p-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <tbody>
                                <tr>
                                    <td><strong>Sub Total</strong></td>
                                    <td id="subTotal"><strong>{{ number_format($sub_total, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <td>VAT of GOODS</td>
                                    <td id="vatOfGoods">{{ number_format($vat_of_goods, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>GRAND TOTAL</strong></td>
                                    <td id="grandTotal"><strong>{{ number_format($grand_total, 0, ',', '.') }}</strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMaterialModalLabel">Add New Material</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addMaterialForm" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="material_name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="material_name" name="material_name"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label for="material_code" class="form-label">P/n</label>
                                    <input type="text" class="form-control" id="material_code" name="material_code"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="brand" class="form-label">Brand</label>
                                    <input type="text" class="form-control" id="brand" name="brand">
                                </div>
                                <div class="col-md-4">
                                    <label for="origin" class="form-label">Origin</label>
                                    <input type="text" class="form-control" id="origin" name="origin">
                                </div>
                                <div class="col-md-4">
                                    <label for="unit" class="form-label">Unit</label>
                                    <input type="text" class="form-control" id="unit" name="unit">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity">
                                </div>
                                <div class="col-md-4">
                                    <label for="unit_price" class="form-label">Unit Price</label>
                                    <input type="number" step="0.01" class="form-control" id="unit_price"
                                        name="unit_price">
                                </div>
                                <div class="col-md-4">
                                    <label for="labor_price" class="form-label">Labor Price</label>
                                    <input type="number" step="0.01" class="form-control" id="labor_price"
                                        name="labor_price">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="vat" class="form-label">VAT</label>
                                    <input type="number" step="0.01" class="form-control" id="vat"
                                        max="999" name="vat">
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
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary w-100">Add Material</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-secondary w-100"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#materialsTable').DataTable({
                language: { search: "" },
                initComplete: function (settings, json) {
                    $('.dt-search').addClass('input-group');
                    $('.dt-search').prepend(`<button class="input-group-text bg-secondary-subtle border-secondary-subtle rounded-start-4">
                                <i class="bi bi-search"></i>
                            </button>`)
                },
                responsive: true
            });

            $('#add-material-btn').click(function() {
                $('#addMaterialModal').show();
            });

            $('#addMaterialForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('materials.store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message, "Successful");

                            const formatter = new Intl.NumberFormat('de-DE', {
                                useGrouping: true
                            })

                            const formattedUnitPrice = formatter.format(response.material
                                .unit_price);
                            const formattedLaborPrice = formatter.format(response.material
                                .labor_price);
                            const formattedTotalPrice = formatter.format(response.material
                                .total_price);
                            const formattedVAT = formatter.format(response.material.vat);

                            const materialName = response.material.material_name.length > 10 ?
                                response.material.material_name.substring(0, 10) + '...' :
                                response.material.material_name;

                            const materialCode = response.material.material_code.length > 10 ?
                                response.material.material_code.substring(0, 10) + '...' :
                                response.material.material_code;

                            table.row.add([
                                '<strong>' + (table.rows().count() + 1) + '</strong>',
                                materialName,
                                materialCode,
                                response.material.quantity,
                                formattedUnitPrice,
                                formattedLaborPrice,
                                formattedTotalPrice,
                                formattedVAT + '%',
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn" data-id="' +
                                response.material.material_id + '">' +
                                '<i class="bi bi-pencil-square"></i>' +
                                '</button>' +
                                ' | ' +
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn" data-id="' +
                                response.material.material_id + '">' +
                                '<i class="bi bi-trash3"></i>' +
                                '</button>' +
                                ' | ' +
                                '<button class="btn p-0 btn-primary border-0 bg-transparent text-info shadow-none detail-btn" data-id="' +
                                response.material.material_id + '">' +
                                '<i class="bi bi-info-circle"></i>' +
                                '</button>'
                            ]).draw();

                            // $('#addMaterialModal').hide();
                            $('#addMaterialForm')[0].reset();
                            updateTotals(response.sub_total, response.vat_of_goods, response
                                .grand_total);
                            windows.location.reload();
                        }
                    },
                    error: function(xhr) {
                        toastr.error(response.message, "Error");
                    }
                });
            });

            $('#materialsTable').on('click', '.delete-btn', function() {
                var materialId = $(this).data('id');
                var row = $(this).closest('tr');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url('materials') }}/' + materialId,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Deleted!',
                                        response.message,
                                        'success'
                                    );
                                    table.row(row).remove().draw();
                                    updateTotals(response.sub_total, response
                                        .vat_of_goods, response.grand_total);
                                } else {
                                    Swal.fire(
                                        'Failed!',
                                        'Failed to delete the material.',
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $('#materialsTable').on('click', '.edit-btn', function() {
                var materialId = $(this).data('id');
                window.location.href = '{{ url('materials') }}/' + materialId + '/edit';
            });

            function updateTotals(subTotal, vatOfGoods, grandTotal) {
                subtotal = isNaN(subTotal) ? 0 : subTotal;
                vatOfGoods = isNaN(vatOfGoods) ? 0 : vatOfGoods;
                grandTotal = isNaN(grandTotal) ? 0 : grandTotal;

                const formatter = new Intl.NumberFormat('de-DE', {
                    useGrouping: true
                });

                $('#subTotal').html('<strong>' + formatter.format(subTotal) + '</strong>');;
                $('#vatOfGoods').text(formatter.format(vatOfGoods));
                $('#grandTotal').html('<strong>' + formatter.format(grandTotal) + '</strong>');
            }

            $('#materialsTable').on('click', '.detail-btn', function() {
                var materialId = $(this).data('id');
                fetch(`/materials/${materialId}`)
                    .then(response => response.json())
                    .then(data => {
                        const formatter = new Intl.NumberFormat('de-DE', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                            useGrouping: true
                        });

                        var materialDetails = `
                        <p><strong>Name:</strong> ${data.material_name}</p>
                        <p><strong>P/N:</strong> ${data.material_code}</p>
                        <p><strong>Description:</strong> ${data.description}</p>
                        <p><strong>Brand:</strong> ${data.brand}</p>
                        <p><strong>Origin:</strong> ${data.origin}</p>
                        <p><strong>Unit:</strong> ${data.unit}</p>
                        <p><strong>Quantity:</strong> ${data.quantity}</p>
                        <p><strong>Unit Price:</strong> ${formatter.format(data.unit_price)}</p>
                        <p><strong>Labor Price:</strong> ${formatter.format(data.labor_price)}</p>
                        <p><strong>Total Price:</strong> ${formatter.format(data.total_price)}</p>
                        <p><strong>VAT:</strong> ${formatter.format(data.vat)}%</p>
                        <p><strong>Delivery Time:</strong> ${data.delivery_time}</p>
                        <p><strong>Warranty Time:</strong> ${data.warranty_time}</p>
                        <p><strong>Remarks:</strong> ${data.remarks}</p>
                    `;
                        document.getElementById('materialDetails').innerHTML = materialDetails;
                        var detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
                        detailModal.show();
                    });
            });

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

            $('#materialsTable').on('click', '.delete-btn', function() {
                var row = $(this).closest('tr');
                row.remove();
                reindexRows();
            });

            function reindexRows() {
                $('#materialsTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
        });
    </script>
@endsection
