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
                                        placeholder="Enter product name" value="" required>
                                    <span class="text-danger error" id="product_name_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">SKU <span class="text-danger">*</span></label>
                                    <input type="text" name="sku" id="sku" class="form-control"
                                        placeholder="Enter SKU" value="" required>
                                    <span class="text-danger error" id="sku_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">HSN Code <span class="text-danger">*</span></label>
                                    <input type="text" name="hsn_code" id="hsn_code" class="form-control"
                                        placeholder="Enter HSN code" value="" required>
                                    <span class="text-danger error" id="hsn_code_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Stock</label>
                                    <input type="number" name="stock" id="stock" class="form-control"
                                        placeholder="Enter stock quantity" value="" required>
                                    <span class="text-danger error" id="stock_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Unit</label>
                                    <select name="unit_id" id="unit_id" class="form-select">
                                        <option value="">Select Unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->_id }}">{{ $unit->unit }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error" id="unit_id_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Type</label>
                                    <select name="product_type" id="product_type" class="form-select">
                                        <option value="">Select Section</option>
                                        <option value="primary1">Primary</option>
                                        <option value="rp">RP</option>
                                    </select>
                                    <span class="text-danger error" id="product_type_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Section</label>
                                    <select name="product_section" id="product_section" class="form-select">
                                        <option value="">Select Section</option>
                                        <option value="primary">Primary</option>
                                        <option value="deals">Deals</option>
                                    </select>
                                    <span class="text-danger error" id="product_section_error"></span>
                                </div>

                                <div class="col-md-6 mb-3" id="bonus_point_container" style="display:none;">
                                    <label class="form-label">Bonus Point (Flat)</label>
                                    <input type="number" step="0.01" name="bonus_point" id="bonus_point" class="form-control" placeholder="Enter bonus point">
                                    <span class="text-danger error" id="bonus_point_error"></span>
                                </div>
                                

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Is Combo Product?</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="is_combo" name="is_combo"
                                            value="1">
                                        <label class="form-check-label" for="is_combo">Yes</label>
                                    </div>
                                </div>

                                <div id="product_select_container" class="col-md-12 mb-3" style="display: none;">
                                    <label class="form-label">Select Combo Products</label>
                                    <select name="product_ids[]" id="product_ids" class="form-select select2"
                                        data-placeholder="Select Products" multiple>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->_id }}">{{ $product->product_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error" id="product_ids_error"></span>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Short Description <span class="text-danger">*</span></label>
                                    <textarea type="number" name="short_description" rows="3" id="short_description" class="form-control"
                                        placeholder="Enter Short Description"></textarea>
                                    <span class="text-danger error" id="short_description_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id" class="form-select" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->_id }}">
                                                {{ $category->name }}
                                            </option>
                                            @if ($category->children->count() > 0)
                                                @foreach ($category->children as $child)
                                                    <option value="{{ $child->_id }}">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;{{ $child->name }}
                                                    </option>
                                                    @if ($child->children->count() > 0)
                                                        @foreach ($child->children as $grandChild)
                                                            <option value="{{ $grandChild->_id }}">
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $grandChild->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="text-danger error" id="category_id_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Brand <span class="text-danger">*</span></label>
                                    <select name="brand_id" id="brand_id" class="form-select select2"
                                        data-placeholder="Select Brand" required>
                                        <option value="">Select Brand</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->_id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error" id="brand_id_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">MRP <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="mrp" id="mrp"
                                        class="form-control" placeholder="Enter MRP" value="" required>
                                    <span class="text-danger error" id="mrp_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sale Price <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="sale_price" id="sale_price"
                                        class="form-control" placeholder="Enter sale price" value="" required>
                                    <span class="text-danger error" id="sale_price_error"></span>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label class="form-label">GST (%) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="gst" id="gst"
                                        class="form-control" placeholder="Enter GST" value="" required>
                                    <span class="text-danger error" id="gst_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">UP (Unit Price)</label>
                                    <input type="number" step="0.01" name="up" id="up"
                                        class="form-control" placeholder="Enter unit price">
                                    <span class="text-danger error" id="up_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">SV (Selling Value)</label>
                                    <input type="number" step="0.01" name="sv" id="sv"
                                        class="form-control" placeholder="Enter selling value">
                                    <span class="text-danger error" id="sv_error"></span>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Offer</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="offer" name="offer"
                                            value="1">
                                        <label class="form-check-label" for="offer">Active</label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Offer Date</label>
                                    <input type="date" name="offer_date" id="offer_date" class="form-control">
                                    <span class="text-danger error" id="offer_date_error"></span>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Mart Status</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="mart_status"
                                            name="mart_status" value="1">
                                        <label class="form-check-label" for="mart_status">Active in Mart</label>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="status" name="status"
                                            value="1">
                                        <label class="form-check-label" for="status">Active</label>
                                    </div>
                                </div>

                                

                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Product Variants</label>
                                <button type="button" class="btn btn-primary btn-sm" id="addVariant">
                                    <i class='bx bx-plus'></i> Add Variant
                                </button>
                            </div>
                            <div id="variantsContainer">
                                <!-- Variants will be added here dynamically -->
                            </div>
                        </div>


                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea type="number" name="description" rows="3" id="description" class="form-control"
                                placeholder="Enter Description"></textarea>
                            <span class="text-danger error" id="description_error"></span>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Product Images</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple
                                accept="image/*">
                            <small class="text-muted">You can select multiple images</small>
                            <span class="text-danger error" id="images_error"></span>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Product Thumbnail</label>
                            <input type="file" name="thumbnail" id="thumbnail" class="form-control"
                                accept="image/*">
                            <small class="text-muted">Main product image</small>
                            <span class="text-danger error" id="thumbnail_error"></span>
                        </div>



                        <div class="col-md-12 mb-3">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control"
                                placeholder="Enter meta title">
                            <span class="text-danger error" id="meta_title_error"></span>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Meta Keywords</label>
                            <input type="text" name="meta_keyword" id="meta_keyword" class="form-control"
                                placeholder="Enter meta keywords">
                            <span class="text-danger error" id="meta_keyword_error"></span>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" class="form-control" rows="3"
                                placeholder="Enter meta description"></textarea>
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


@push('script')
    <script>
        $(document).ready(function() {
            // Handle combo product checkbox change
            $('#is_combo').change(function() {
                if ($(this).is(':checked')) {
                    $('#product_select_container').show();
                    $('#product_ids').prop('required', true);
                } else {
                    $('#product_select_container').hide();
                    $('#product_ids').prop('required', false);
                }
            });

            // Add variant row
            $('#addVariant').click(function() {
                const variantHtml = `
                    <div class="variant-row border rounded p-2 mb-2">
                        <div class="row">

                            <div class="col-md-2">
                                <label class="form-label">SKU</label>
                                <input type="text" name="variants[sku][]" class="form-control form-control-sm" required placeholder="Enter SKU">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Stock</label>
                                <input type="number" name="variants[stock][]" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Unit</label>
                                <select name="variants[unit_id][]" class="form-select form-select-sm" readonly>
                                    <option value="">Select Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->_id }}" @selected($unit->_id == '685401d1ef51f3e7d804ff74')>{{ $unit->unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Attributes</label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="text" name="variants[attributes][color][]" class="form-control form-control-sm" placeholder="Color">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="variants[attributes][size][]" class="form-control form-control-sm" placeholder="Size">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 mt-4">
                                <label class="form-label">&nbsp;</label>
                                <a href="javascript:void(0);" class="text-danger remove-variant">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </div>
                        </div>
                    </div>
                `;
                $('#variantsContainer').append(variantHtml);
            });

            // Remove variant row
            $(document).on('click', '.remove-variant', function() {
                $(this).closest('.variant-row').remove();
            });

            // Clear validation errors on input change
            $('input, select, textarea').on('change keyup', function() {
                $(this).removeClass('is-invalid');
                $(`#${$(this).attr('id')}_error`).html('');
            });

            // Show/hide bonus_point field based on product_section
            $('#product_section').change(function() {
                if ($(this).val() === 'deals') {
                    $('#bonus_point_container').show();
                } else {
                    $('#bonus_point_container').hide();
                    $('#bonus_point').val('');
                }
            });

            $('#save').submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const url = $(this).attr('action');

                $('.error').html('');

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
                        $('#saveBtn').html('Save').removeAttr('disabled');
                        alertMsg(res.status, res.msg, 3000);

                        if (res.status) {
                            setTimeout(() => {
                                window.location.href = "{{ url('crm/products') }}";
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        $('#saveBtn').html('Save').removeAttr('disabled');

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.validation;
                            $.each(errors, (field, messages) => {
                                $(`#${field}_error`).html(messages[0]);
                            });
                        } else if (xhr.status === 400) {
                            alertMsg(false, xhr.responseJSON.msg, 3000);
                        } else {
                            alertMsg(false, xhr.responseJSON.msg ||
                                'An error occurred while processing your request.', 3000);
                        }
                    }
                });
            });
        });
    </script>
@endpush
