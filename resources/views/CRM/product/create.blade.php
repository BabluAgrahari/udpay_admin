@extends('CRM.Layout.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row card-header">
                <div class="col-md-10">
                    <h5>Create Product</h5>
                </div>
                <div class="col-md-2 text-right">
                    <a href="{{ url('crm/products') }}" class="btn btn-outline-warning btn-sm">
                        <i class='bx bx-left-arrow-circle'></i>&nbsp;Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div id="message"></div>
                <form id="save" method="post" action="{{ url('crm/products') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="POST">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="product_name" id="product_name" class="form-control"
                                        placeholder="Enter product name" value="" required maxlength="255">
                                    <span class="text-danger error" id="product_name_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">SKU Code <span class="text-danger">*</span></label>
                                    <input type="text" name="sku_code" id="sku_code" class="form-control"
                                        placeholder="Enter SKU code" value="" required maxlength="55">
                                    <span class="text-danger error" id="sku_code_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">HSN Code <span class="text-danger">*</span></label>
                                    <input type="text" name="hsn_code" id="hsn_code" class="form-control"
                                        placeholder="Enter HSN code" value="" required maxlength="55">
                                    <span class="text-danger error" id="hsn_code_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Stock <span class="text-danger">*</span></label>
                                    <input type="number" name="product_stock" id="product_stock" class="form-control"
                                        placeholder="Enter stock quantity" value="0" min="0" required>
                                    <span class="text-danger error" id="product_stock_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Unit <span class="text-danger">*</span></label>
                                    <select name="unit_id" id="unit_id" class="form-select" required>
                                        <option value="">Select Unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error" id="unit_id_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Type</label>
                                    <select name="pro_type" id="pro_type" class="form-select">
                                        <option value="primary1">Primary</option>
                                        <option value="rp">RP</option>
                                    </select>
                                    <span class="text-danger error" id="pro_type_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Section</label>
                                    <select name="pro_section" id="pro_section" class="form-select">
                                        <option value="primary">Primary</option>
                                        <option value="deals">Deals</option>
                                    </select>
                                    <span class="text-danger error" id="pro_section_error"></span>
                                </div>

                                <div class="col-md-6 mb-3" id="bonus_point_container" style="display:none;">
                                    <label class="form-label">Bonus Point (Flat)</label>
                                    <input type="number" step="0.01" name="bonus_point" id="bonus_point" class="form-control" placeholder="Enter bonus point" min="0">
                                    <span class="text-danger error" id="bonus_point_error"></span>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Is Combo Product?</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="is_combo" name="is_combo" value="1">
                                        <label class="form-check-label" for="is_combo">Yes</label>
                                    </div>
                                </div>

                                <div id="product_select_container" class="col-md-12 mb-3" style="display: none;">
                                    <label class="form-label">Select Combo Products</label>
                                    <select name="combo_id" id="combo_id" class="form-select select2" data-placeholder="Select Products" multiple>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error" id="combo_id_error"></span>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Short Description</label>
                                    <textarea name="product_short_description" rows="3" id="product_short_description" class="form-control"
                                        placeholder="Enter short description" maxlength="1000"></textarea>
                                    <span class="text-danger error" id="product_short_description_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="product_category_id" id="product_category_id" class="form-select" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}
                                            </option>
                                            @if ($category->children->count() > 0)
                                                @foreach ($category->children as $child)
                                                    <option value="{{ $child->id }}">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;{{ $child->name }}
                                                    </option>
                                                    @if ($child->children->count() > 0)
                                                        @foreach ($child->children as $grandChild)
                                                            <option value="{{ $grandChild->id }}">
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $grandChild->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="text-danger error" id="product_category_id_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Brand <span class="text-danger">*</span></label>
                                    <select name="brand_id" id="brand_id" class="form-select select2" data-placeholder="Select Brand" required>
                                        <option value="">Select Brand</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error" id="brand_id_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Price <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="product_price" id="product_price" class="form-control" 
                                           placeholder="Enter product price" value="" min="0" required>
                                    <span class="text-danger error" id="product_price_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sale Price <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="product_sale_price" id="product_sale_price" class="form-control" 
                                           placeholder="Enter sale price" value="" min="0" required>
                                    <span class="text-danger error" id="product_sale_price_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">MRP <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="mrp" id="mrp" class="form-control" 
                                           placeholder="Enter MRP" value="" min="0" required>
                                    <span class="text-danger error" id="mrp_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">IGST (%)</label>
                                    <input type="number" step="0.01" name="igst" id="igst" class="form-control" 
                                           placeholder="Enter IGST" value="0" min="0" max="100">
                                    <span class="text-danger error" id="igst_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">UP (Unit Price)</label>
                                    <input type="number" step="0.01" name="up" id="up" class="form-control" 
                                           placeholder="Enter unit price" value="0" min="0">
                                    <span class="text-danger error" id="up_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">SV (Selling Value)</label>
                                    <input type="number" step="0.01" name="sv" id="sv" class="form-control" 
                                           placeholder="Enter selling value" value="0" min="0">
                                    <span class="text-danger error" id="sv_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Minimum Quantity</label>
                                    <input type="number" name="product_min_qty" id="product_min_qty" class="form-control" 
                                           placeholder="Enter minimum quantity" value="1" min="1">
                                    <span class="text-danger error" id="product_min_qty_error"></span>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Offer</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="offer" name="offer" value="1">
                                        <label class="form-check-label" for="offer">Active</label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Offer Date</label>
                                    <input type="date" name="offer_date" id="offer_date" class="form-control">
                                    <span class="text-danger error" id="offer_date_error"></span>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Featured</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1">
                                        <label class="form-check-label" for="is_featured">Featured</label>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">On Slider</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="on_slider" name="on_slider" value="1">
                                        <label class="form-check-label" for="on_slider">Show on Slider</label>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">On Banner</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="on_banner" name="on_banner" value="1">
                                        <label class="form-check-label" for="on_banner">Show on Banner</label>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Mart Status</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="mart_status" name="mart_status" value="1">
                                        <label class="form-check-label" for="mart_status">Active in Mart</label>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="status" name="status" value="1" checked>
                                        <label class="form-check-label" for="status">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Product Description</label>
                            <textarea name="product_description" rows="4" id="product_description" class="form-control"
                                placeholder="Enter product description" maxlength="5000"></textarea>
                            <span class="text-danger error" id="product_description_error"></span>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Product Image <span class="text-danger">*</span></label>
                            <input type="file" name="product_image" id="product_image" class="form-control" 
                                   accept="image/*" required>
                            <small class="text-muted">Main product image (JPEG, PNG, GIF, WebP - Max 2MB)</small>
                            <span class="text-danger error" id="product_image_error"></span>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Additional Product Images</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                            <small class="text-muted">You can select multiple images (JPEG, PNG, GIF, WebP - Max 2MB each)</small>
                            <span class="text-danger error" id="images_error"></span>
                        </div>

                        <!-- Product Variants Section -->
                        <div class="col-md-12 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Product Variants</h6>
                                    <small class="text-muted">Add multiple variants of this product (e.g., different sizes, colors, etc.)</small>
                                </div>
                                <div class="card-body">
                                    <div id="variants-container">
                                        <div class="variant-row row mb-3" data-index="0">
                                            <div class="col-md-3">
                                                <label class="form-label">Variant Name <span class="text-danger">*</span></label>
                                                <input type="text" name="variants[0][varient_name]" class="form-control variant-name" 
                                                       placeholder="e.g., Red, Large, etc.">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">SKU</label>
                                                <input type="text" name="variants[0][sku]" class="form-control" 
                                                       placeholder="Variant SKU">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Stock</label>
                                                <input type="number" name="variants[0][stock]" class="form-control" 
                                                       placeholder="0" min="0" value="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Price</label>
                                                <input type="number" step="0.01" name="variants[0][price]" class="form-control" 
                                                       placeholder="0.00" min="0" value="0">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Status</label>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input" name="variants[0][status]" value="1" checked>
                                                    <label class="form-check-label">Active</label>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-sm remove-variant" style="display: none;">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-success btn-sm" id="add-variant">
                                        <i class='bx bx-plus'></i> Add Variant
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="product_meta_title" id="product_meta_title" class="form-control"
                                placeholder="Enter meta title" maxlength="255">
                            <span class="text-danger error" id="product_meta_title_error"></span>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Meta Keywords</label>
                            <input type="text" name="product_meta_keywords" id="product_meta_keywords" class="form-control"
                                placeholder="Enter meta keywords" maxlength="500">
                            <span class="text-danger error" id="product_meta_keywords_error"></span>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" class="form-control" rows="3"
                                placeholder="Enter meta description" maxlength="1000"></textarea>
                            <span class="text-danger error" id="meta_description_error"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <i class='bx bx-save'></i> Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .variant-row {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .variant-row:hover {
            background-color: #e9ecef;
        }
        .remove-variant {
            margin-top: 25px;
        }
        #add-variant {
            margin-top: 10px;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // Handle combo product checkbox change
            $('#is_combo').change(function() {
                if ($(this).is(':checked')) {
                    $('#product_select_container').show();
                    $('#combo_id').prop('required', true);
                } else {
                    $('#product_select_container').hide();
                    $('#combo_id').prop('required', false);
                }
            });

            // Show/hide bonus_point field based on pro_section
            $('#pro_section').change(function() {
                if ($(this).val() === 'deals') {
                    $('#bonus_point_container').show();
                } else {
                    $('#bonus_point_container').hide();
                    $('#bonus_point').val('');
                }
            });

            // Clear validation errors on input change
            $('input, select, textarea').on('change keyup', function() {
                $(this).removeClass('is-invalid');
                $(`#${$(this).attr('id')}_error`).html('');
            });

            // Auto-calculate sale price based on MRP and discount
            $('#mrp, #product_price').on('input', function() {
                const mrp = parseFloat($('#mrp').val()) || 0;
                const productPrice = parseFloat($('#product_price').val()) || 0;
                
                if (mrp > 0 && productPrice > 0) {
                    $('#product_sale_price').val(productPrice);
                }
            });

            // Variant functionality
            let variantIndex = 1;

            // Add new variant
            $('#add-variant').click(function() {
                const newVariant = `
                    <div class="variant-row row mb-3" data-index="${variantIndex}">
                        <div class="col-md-3">
                            <label class="form-label">Variant Name <span class="text-danger">*</span></label>
                            <input type="text" name="variants[${variantIndex}][varient_name]" class="form-control variant-name" 
                                   placeholder="e.g., Red, Large, etc.">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">SKU</label>
                            <input type="text" name="variants[${variantIndex}][sku]" class="form-control" 
                                   placeholder="Variant SKU">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Stock</label>
                            <input type="number" name="variants[${variantIndex}][stock]" class="form-control" 
                                   placeholder="0" min="0" value="0">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" name="variants[${variantIndex}][price]" class="form-control" 
                                   placeholder="0.00" min="0" value="0">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" name="variants[${variantIndex}][status]" value="1" checked>
                                <label class="form-check-label">Active</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm remove-variant">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                `;
                $('#variants-container').append(newVariant);
                variantIndex++;
                
                // Show remove button for first variant if there are multiple variants
                if ($('.variant-row').length > 1) {
                    $('.remove-variant').show();
                }
            });

            // Remove variant
            $(document).on('click', '.remove-variant', function() {
                $(this).closest('.variant-row').remove();
                
                // Hide remove button for first variant if only one remains
                if ($('.variant-row').length === 1) {
                    $('.remove-variant').hide();
                }
                
                // Reindex variants
                $('.variant-row').each(function(index) {
                    $(this).attr('data-index', index);
                    $(this).find('input, select').each(function() {
                        const name = $(this).attr('name');
                        if (name) {
                            const newName = name.replace(/variants\[\d+\]/, `variants[${index}]`);
                            $(this).attr('name', newName);
                        }
                    });
                });
                variantIndex = $('.variant-row').length;
            });

            // Form submission
            $('#save').submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const url = $(this).attr('action');

                // Clear previous errors
                $('.error').html('');
                $('.is-invalid').removeClass('is-invalid');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#saveBtn').html(
                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Saving...`
                        ).attr('disabled', true);
                    },
                    success: function(res) {
                        $('#saveBtn').html('<i class="bx bx-save"></i> Save').removeAttr('disabled');
                        alertMsg(res.status, res.msg, 3000);

                        if (res.status) {
                            setTimeout(() => {
                                window.location.href = "{{ url('crm/products') }}";
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        $('#saveBtn').html('<i class="bx bx-save"></i> Save').removeAttr('disabled');

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.validation;
                            $.each(errors, (field, messages) => {
                                $(`#${field}_error`).html(messages[0]);
                                $(`#${field}`).addClass('is-invalid');
                            });
                        } else if (xhr.status === 400) {
                            alertMsg(false, xhr.responseJSON.msg, 3000);
                        } else {
                            alertMsg(false, xhr.responseJSON.msg || 'An error occurred while processing your request.', 3000);
                        }
                    }
                });
            });
        });
    </script>
@endpush
