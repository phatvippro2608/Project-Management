@extends('auth.prm')
@section('head')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
@section('contents')
    <div class="pagetitle">
        <h1>Project Budget</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ action('App\Http\Controllers\DashboardController@getViewDashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Project List</li>
                <li class="breadcrumb-item active">Details and Costs </li>
                <li class="breadcrumb-item active">List of Materials</li>

            </ol>
        </nav>
    </div>
    <div class="d-flex ms-auto">
        @if (!empty($locationName))
            <button type="button" id="add-material-btn" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal"
                data-bs-target="#addMaterialModal">
                <i class="bi bi-plus-lg me-2"></i>Add New Material
            </button>
        @endif
        <button type="button" class="btn btn-primary btn-sm me-2" id="importButton">Import</button>
        <!-- Hidden Import Form -->
        <form id="importForm"
            action="{{ !empty($location) ? route('prjMaterials.import', ['project_id' => $id, 'location' => $location]) : route('prjMaterials.import', ['project_id' => $id]) }}"
            method="POST" enctype="multipart/form-data" style="display: none;">
            @csrf
            <input type="file" name="file" id="fileInput" required>
            <button type="button" id="submitButton">Submit</button>
        </form>

        <a href="{{ !empty($indexLocation) ? route('prjMaterials.export', ['project_id' => $id, 'location' => $indexLocation]) : route('prjMaterials.export', ['project_id' => $id]) }}"
            class="btn btn-primary btn-sm">Export</a>
    </div>

    <div class="card p-2 border rounded-4">
        <div class="card-header py-0">
            <div class="card-title my-3 p-0">
                Materials of {{ $projectName->project_name }}
                @if (!empty($locationName))
                    {{ $locationName->project_location_name }}
                @else
                    All location
                @endif

            </div>
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
                        <th data-sort="date">Date<i class="bi bi-sort"></i></th>
                        <th data-sort="perdiempay">Action<i class="bi bi-sort"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < count($materialData); $i++)
                        <tr>
                            <td class="text-center" data="{{ $materialData[$i]->project_material_id }}"
                                value="{{ $materialData[$i]->project_material_id }}">{{ 1 + $i }}
                            </td>
                            <td>{{ Str::limit($materialData[$i]->project_material_name, 15, '...') }}</td>
                            <td>{{ Str::limit($materialData[$i]->project_material_code, 15, '...') }}</td>
                            <td class="text-center">{{ $materialData[$i]->project_material_quantity }}</td>
                            <td>{{ number_format($materialData[$i]->project_material_unit_price, 0, ',', '.') }} VND</td>
                            <td>{{ $materialData[$i]->project_material_labor_price }}</td>
                            <td>{{ number_format($subtotal[$i], 0, ',', '.') }} VND</td>
                            <td>{{ $materialData[$i]->project_material_vat }}%</td>
                            <td>{{ $materialData[$i]->date_create }}</td>

                            <td>
                                <button
                                    class="btn p-1 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn"
                                    data-id="{{ $materialData[$i]->project_material_id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button
                                    class="btn p-1 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn"
                                    data-id="{{ $materialData[$i]->project_material_id }}">
                                    <i class="bi bi-trash3"></i>
                                </button>
                                <button class="btn p-1 btn-primary border-0 bg-transparent text-info shadow-none detail-btn"
                                    data-id="{{ $materialData[$i]->project_material_id }}">
                                    <i class="bi bi-info-circle"></i>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-2 border rounded-4">
            <div class="card-header py-0">
                <div class="card-title my-3 p-0">Materials Project Statistics</div>
            </div>
            <div class="card-body">
                <table id="total" class="table table-hover table-borderless">
                    <tbody>
                        <tr>
                            <td><strong>Sub Total</strong></td>
                            <td id="subTotal"><strong>{{ number_format($total, 0, ',', '.') }} VND</strong></td>
                        </tr>
                        <tr>
                            <td>VAT of GOODS</td>
                            <td id="vatOfGoods">{{ number_format($vat, 0, ',', '.') }} VND</td>
                        </tr>
                        <tr>
                            <td><strong>GRAND TOTAL</strong></td>
                            <td id="grandTotal"><strong>{{ number_format($grandtotal, 0, ',', '.') }} VND</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel" aria-hidden="true">
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
                                <input type="number" step="0.01" class="form-control" id="vat" max="999"
                                    name="vat">
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

    <!-- Edit Material Modal -->
    <div class="modal fade" id="editMaterialModal" tabindex="-1" aria-labelledby="editMaterialModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMaterialModalLabel">Edit Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editMaterialForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editMaterialId" name="editMaterialId">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="editMaterialName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="editMaterialName" name="material_name"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label for="editMaterialCode" class="form-label">P/n</label>
                                <input type="text" class="form-control" id="editMaterialCode" name="material_code"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label for="editDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="editDescription" name="description"></textarea>
                            </div>
                        </div>
                        <!-- Add other fields similarly -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="editBrand" class="form-label">Brand</label>
                                <input type="text" class="form-control" id="editBrand" name="brand">
                            </div>
                            <div class="col-md-4">
                                <label for="editOrigin" class="form-label">Origin</label>
                                <input type="text" class="form-control" id="editOrigin" name="origin">
                            </div>
                            <div class="col-md-4">
                                <label for="editUnit" class="form-label">Unit</label>
                                <input type="text" class="form-control" id="editUnit" name="unit">
                            </div>
                        </div>
                        <!-- Continue with other fields -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="editQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="editQuantity" name="quantity">
                            </div>
                            <div class="col-md-4">
                                <label for="editUnitPrice" class="form-label">Unit Price</label>
                                <input type="number" step="0.01" class="form-control" id="editUnitPrice"
                                    name="unit_price">
                            </div>
                            <div class="col-md-4">
                                <label for="editLaborPrice" class="form-label">Labor Price</label>
                                <input type="number" step="0.01" class="form-control" id="editLaborPrice"
                                    name="labor_price">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editVat" class="form-label">VAT</label>
                                <input type="number" step="0.01" class="form-control" id="editVat"
                                    name="vat">
                            </div>
                            <div class="col-md-6">
                                <label for="editDeliveryTime" class="form-label">Delivery Time</label>
                                <input type="text" class="form-control" id="editDeliveryTime" name="delivery_time">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editWarrantyTime" class="form-label">Warranty Time</label>
                                <input type="text" class="form-control" id="editWarrantyTime" name="warranty_time">
                            </div>
                            <div class="col-md-6">
                                <label for="editRemarks" class="form-label">Remarks</label>
                                <textarea class="form-control" id="editRemarks" name="remarks"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">Save Changes</button>
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

    <script>
        function number_format(number, decimals = 0, dec_point = ',', thousands_sep = '.') {
            if (number === undefined || isNaN(number)) {
                number = 0; // or any default value
            }
            let parts = Number(number).toFixed(decimals).split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);
            return parts.join(dec_point);

            return Number(value).toFixed(2); // Format with 2 decimal places
        }
        new DataTable('#materialsProject');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        document.getElementById('importButton').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });

        document.getElementById('fileInput').addEventListener('change', function() {
            document.getElementById('submitButton').click();
        });

        $('#submitButton').on('click', function(event) {
            event.preventDefault();

            var fileInput = $('#fileInput')[0];
            var file = fileInput.files[0];

            if (!file) {
                toastr.error('Please select a file before submitting.');
                return;
            }

            var formData = new FormData();
            formData.append('file', file);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            var url = $('#importForm').attr('action');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success) {
                        toastr.success('Import successful!');
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    } else {
                        toastr.error('Import failed: ' + (data.message || 'Unknown error'));
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('Error: ' + errorThrown);
                }
            });
        });

        $(document).ready(function() {
            // Handle the add material form submission
            $('#addMaterialForm').submit(function(e) {
                e.preventDefault();
                let url = '';
                @if (!empty($indexLocation))
                    url =
                        '{{ route('prjMaterials.add', ['project_id' => $id, 'indexLocation' => $indexLocation]) }}';
                @else
                    url = '{{ route('prjMaterials.add', ['project_id' => $id]) }}';
                @endif

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message, "Successful");

                            let newRow = `<tr data-id="${response.data.project_material_id}"
                    data-name="${response.data.project_material_name}"
                    data-code="${response.data.project_material_code}"
                    data-description="${response.data.description}"
                    data-brand="${response.data.brand}"
                    data-origin="${response.data.origin}"
                    data-unit="${response.data.unit}"
                    data-quantity="${response.data.project_material_quantity}"
                    data-unit-price="${response.data.project_material_unit_price}"
                    data-labor-price="${response.data.project_material_labor_price}"
                    data-vat="${response.data.project_material_vat}"
                    data-delivery-time="${response.data.delivery_time}"
                    data-warranty-time="${response.data.warranty_time}"
                    data-remarks="${response.data.remarks}">
                    <td class="text-center">${response.data.project_material_id}</td>
                    <td>${response.data.project_material_name}</td>
                    <td>${response.data.project_material_code}</td>
                    <td class="text-center">${response.data.project_material_quantity}</td>
                    <td>${number_format(response.data.project_material_unit_price, 0, ',', '.')} VND</td>
                    <td>${response.data.project_material_labor_price}</td>
                    <td>${number_format(response.data.project_material_quantity * response.data.project_material_unit_price, 0, ',', '.')} VND</td>
                    <td>${response.data.project_material_vat}%</td>
                    <td>${response.data.date_create}</td>
                    <td>
                        <button class="btn p-1 btn-primary border-0 bg-transparent text-primary shadow-none edit-btn" data-id="${response.data.project_material_id}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        |
                        <button class="btn p-1 btn-primary border-0 bg-transparent text-danger shadow-none delete-btn" data-id="${response.data.project_material_id}">
                            <i class="bi bi-trash3"></i>
                        </button>
                        |
                        <button class="btn p-1 btn-primary border-0 bg-transparent text-info shadow-none detail-btn" data-id="${response.data.project_material_id}">
                            <i class="bi bi-info-circle"></i>
                        </button>
                    </td>
                    </tr>`;
                            $('#materialsProject tbody').append(newRow);

                            $('#addMaterialModal').modal('hide');
                            $('#addMaterialForm')[0].reset();
                        } else {
                            toastr.error(response.message, "Failed");
                        }
                    },
                    error: function(response) {
                        toastr.error('Something went wrong.', "Failed");
                    }
                });
            });
            $(document).on('click', '.detail-btn', function() {
                let materialId = $(this).data('id');
                var projectId = "{{ $id }}";

                $.ajax({
                    url: `/project/${projectId}/materials/${materialId}/details`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            let material = response.data;

                            // Hiển thị thông tin vật liệu trong modal
                            $('#materialDetails').html(`
                        <p><strong>Name:</strong> ${material.project_material_name}</p>
                        <p><strong>P/N:</strong> ${material.project_material_code || ''}</p>
                        <p><strong>Description:</strong> ${material.project_material_description || ''}</p>
                        <p><strong>Brand:</strong> ${material.project_material_brand || ''}</p>
                        <p><strong>Origin:</strong> ${material.project_material_origin || ''}</p>
                        <p><strong>Unit:</strong> ${material.project_material_unit || ''}</p>
                        <p><strong>Quantity:</strong> ${material.project_material_quantity || ''}</p>
                        <p><strong>Unit Price:</strong> ${material.project_material_unit_price || ''} VND</p>
                        <p><strong>Labor Price:</strong> ${material.project_material_labor_price || ''}</p>
                        <p><strong>VAT:</strong> ${material.project_material_vat}%</p>
                        <p><strong>Delivery Time:</strong> ${material.project_material_delivery_time || ''}</p>
                        <p><strong>Warranty Time:</strong> ${material.project_material_warranty_time || ''}</p>
                        <p><strong>Remarks:</strong> ${material.project_material_remarks || ''}</p>
                    `);

                            $('#detailModal').modal('show');
                        } else {
                            toastr.error(response.message, "Failed");
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong.', "Failed");
                    }
                });
            });
        });
        $(document).on('click', '.edit-btn', function() {
            let materialId = $(this).data('id');
            let url = `/project/{{ $id }}/materials/${materialId}/edit`; // Adjust route as needed
            console.log(url);
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        let material = response.data;
                        // Populate the edit form with material data
                        $('#editMaterialId').val(material.project_material_id);
                        $('#editMaterialName').val(material.project_material_name);
                        $('#editMaterialCode').val(material.project_material_code);
                        $('#editDescription').val(material.project_material_description);
                        $('#editBrand').val(material.project_material_brand);
                        $('#editOrigin').val(material.project_material_origin);
                        $('#editUnit').val(material.project_material_unit);
                        $('#editQuantity').val(material.project_material_quantity);
                        $('#editUnitPrice').val(material.project_material_unit_price);
                        $('#editLaborPrice').val(material.project_material_labor_price);
                        $('#editVat').val(material.project_material_vat);
                        $('#editDeliveryTime').val(material.project_material_delivery_time);
                        $('#editWarrantyTime').val(material.project_material_warranty_time);
                        $('#editRemarks').val(material.project_material_remarks);

                        $('#editMaterialModal').modal('show'); // Show the modal
                    } else {
                        toastr.error(response.message, "Failed");
                    }
                },
                error: function() {
                    toastr.error('Something went wrong.', "Failed");
                }
            });
        });

        $('#editMaterialForm').submit(function(e) {
            e.preventDefault();
            let url =
                `/project/{{ $id }}/materials/${$('#editMaterialId').val()}/update`; // Adjust route as needed
            console.log(url);
            $.ajax({
                url: url,
                method: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, "Successful");

                        setTimeout(function() {
                            location.reload();
                        }, 300);
                        $('#editMaterialModal').modal('hide');
                    } else {
                        toastr.error(response.message, "Failed");
                    }
                },
                error: function() {
                    toastr.error('Something went wrong.', "Failed");
                }
            });
        });
        $(document).on('click', '.delete-btn', function() {
    let materialId = $(this).data('id');
    let projectId = "{{ $id }}"; // Make sure this variable is correctly set in your Blade template
    let url = `/project/${projectId}/materials/${materialId}/delete`;

    // Confirm deletion
    let confirmDelete = confirm("Do you really want to delete this material?");
    
    if (confirmDelete) {
        $.ajax({
            url: url,
            method: 'DELETE',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message, "Successful");
                    setTimeout(function() {
                        location.reload();
                    }, 300);
                } else {
                    toastr.error(response.message, "Failed");
                }
            },
            error: function() {
                toastr.error('Something went wrong.', "Failed");
            }
        });
    } else {
        toastr.info('Deletion cancelled.', "Cancelled");
    }
});

    </script>
@endsection
