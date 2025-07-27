@extends('CRM.Layout.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row card-header">
                <div class="col-md-10">
                    <h5>Edit Product</h5>
                </div>
                <div class="col-md-2 text-right">
                    <a href="{{ url('crm/products') }}" class="btn btn-outline-warning btn-sm">
                        <i class='bx bx-left-arrow-circle'></i>&nbsp;Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div id="message"></div>
                <form id="save" method="post" action="{{ url('crm/products/' . $product->_id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="product_name" id="product_name" class="form-control"
                                        placeholder="Enter product name" value="{{ $product->product_name }}" required>
                                    <span class="text-danger error" id="product_name_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">SKU <span class="text-danger">*</span></label>
                                    <input type="text" name="sku" id="sku" class="form-control"
                                        placeholder="Enter SKU" value="{{ $product->sku }}" required>
                                    <span class="text-danger error" id="sku_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">HSN Code <span class="text-danger">*</span></label>
                                    <input type="text" name="hsn_code" id="hsn_code" class="form-control"
                                        placeholder="Enter HSN code" value="{{ $product->hsn_code }}" required>
                                    <span class="text-danger error" id="hsn_code_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Stock </label>
                                    <input type="number" name="stock" id="stock" class="form-control"
                                        placeholder="Enter stock quantity"value="{{ $product->stock }}">
                                    <span class="text-danger error" id="stock_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Unit </label>
                                    <select name="unit_id" id="unit_id" class="form-select">
                                        <option value="">Select Unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->_id }}"
                                                {{ $product->unit_id == $unit->_id ? 'selected' : '' }}>
                                                {{ $unit->unit }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error" id="unit_id_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Type</label>
                                    <select name="product_type" id="product_type" class="form-select">
                                        <option value="">Select Section</option>
                                        <option value="primary1"
                                            {{ $product->product_type == 'primary1' ? 'selected' : '' }}>Primary</option>
                                        <option value="rp" {{ $product->product_type == 'rp' ? 'selected' : '' }}>RP
                                        </option>
                                    </select>
                                    <span class="text-danger error" id="product_type_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Section</label>
                                    <select name="product_section" id="product_section" class="form-select">
                                        <option value="">Select Section</option>
                                        <option value="primary"
                                            {{ $product->product_section == 'primary' ? 'selected' : '' }}>Primary</option>
                                        <option value="deals"
                                            {{ $product->product_section == 'deals' ? 'selected' : '' }}>Deals</option>
                                    </select>
                                    <span class="text-danger error" id="product_section_error"></span>
                                </div>

                                <div class="col-md-6 mb-3" id="bonus_point_container" style="display: {{ $product->product_section == 'deals' ? 'block' : 'none' }};">
                                    <label class="form-label">Bonus Point (Flat)</label>
                                    <input type="number" step="0.01" name="bonus_point" id="bonus_point" class="form-control" placeholder="Enter bonus point" value="{{ $product->bonus_point ?? '' }}">
                                    <span class="text-danger error" id="bonus_point_error"></span>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Is Combo Product?</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="is_combo" name="is_combo"
                                            value="1" {{ $product->is_combo ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_combo">Yes</label>
                                    </div>
                                </div>

                                <div id="product_select_container" class="col-md-12 mb-3"
                                    style="display: {{ $product->is_combo ? 'block' : 'none' }};">
                                    <label class="form-label">Select Combo Products</label>
                                    <select name="product_ids[]" id="product_ids" class="form-select select2"
                                        data-placeholder="Select Products" multiple
                                        {{ $product->is_combo ? 'required' : '' }}>
                                        @foreach ($products as $comboProduct)
                                            <option value="{{ $comboProduct->_id }}"
                                                {{ in_array($comboProduct->_id, $product->product_ids ?? []) ? 'selected' : '' }}>
                                                {{ $comboProduct->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error" id="product_ids_error"></span>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Short Description <span class="text-danger">*</span></label>
                                    <textarea name="short_description" id="short_description" class="form-control" rows="3"
                                        placeholder="Enter Short Description">{{ $product->short_description }}</textarea>
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
                                            <option value="{{ $category->_id }}"
                                                {{ $product->category_id == $category->_id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @if ($category->children->count() > 0)
                                                @foreach ($category->children as $child)
                                                    <option value="{{ $child->_id }}"
                                                        {{ $product->category_id == $child->_id ? 'selected' : '' }}>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;{{ $child->name }}
                                                    </option>
                                                    @if ($child->children->count() > 0)
                                                        @foreach ($child->children as $grandChild)
                                                            <option value="{{ $grandChild->_id }}"
                                                                {{ $product->category_id == $grandChild->_id ? 'selected' : '' }}>
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
                                            <option value="{{ $brand->_id }}"
                                                {{ $product->brand_id == $brand->_id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error" id="brand_id_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">MRP <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="mrp" id="mrp"
                                        class="form-control" placeholder="Enter MRP" value="{{ $product->mrp }}"
                                        required>
                                    <span class="text-danger error" id="mrp_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sale Price <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="sale_price" id="sale_price"
                                        class="form-control" placeholder="Enter sale price"
                                        value="{{ $product->sale_price }}" required>
                                    <span class="text-danger error" id="sale_price_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">GST (%) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="cgst" id="cgst"
                                        class="form-control" placeholder="Enter GST" value="{{ $product->cgst }}"
                                        required>
                                    <span class="text-danger error" id="cgst_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">UP (Unit Price)</label>
                                    <input type="number" step="0.01" name="up" id="up"
                                        class="form-control" placeholder="Enter unit price" value="{{ $product->up }}">
                                    <span class="text-danger error" id="up_error"></span>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">SV (Selling Value)</label>
                                    <input type="number" step="0.01" name="sv" id="sv"
                                        class="form-control" placeholder="Enter selling value"
                                        value="{{ $product->sv }}">
                                    <span class="text-danger error" id="sv_error"></span>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Offer</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="offer" name="offer"
                                            value="1" {{ $product->offer ? 'checked' : '' }}>
                                        <label class="form-check-label" for="offer">Active</label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Offer Date</label>
                                    <input type="date" name="offer_date" id="offer_date" class="form-control"
                                        value="{{ $product->offer_date ? $product->offer_date->format('Y-m-d') : '' }}">
                                    <span class="text-danger error" id="offer_date_error"></span>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Mart Status</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="mart_status"
                                            name="mart_status" value="1"
                                            {{ $product->mart_status ? 'checked' : '' }}>
                                        <label class="form-check-label" for="mart_status">Active in Mart</label>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="status" name="status"
                                            value="1" {{ $product->status ? 'checked' : '' }}>
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

                                @if (!empty($product->variants) && count($product->variants) > 0)
                                    @foreach ($product->variants as $variant)
                                        <div class="variant-row border rounded p-2 mb-2">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label class="form-label">SKU</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $variant->sku }}" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Stock</label>
                                                    <input type="number" name="variants[stock][]"
                                                        class="form-control form-control-sm"
                                                        value="{{ $variant->stock ?? '' }}" required>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Unit</label>
                                                    <select name="variants[unit_id][]" class="form-select form-select-sm"
                                                        readonly>
                                                        <option value="">Select Unit</option>
                                                        @foreach ($units as $unit)
                                                            <option value="{{ $unit->_id }}"
                                                                @selected($unit->_id == $variant->unit_id)>{{ $unit->unit }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-5">
                                                    <label class="form-label">Attributes</label>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <input type="text" name="variants[attributes][color][]"
                                                                class="form-control form-control-sm" placeholder="Color"
                                                                value="{{ !empty($variant->attributes['color']) ? $variant->attributes['color'] : '' }}">
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="text" name="variants[attributes][size][]"
                                                                class="form-control form-control-sm" placeholder="Size"
                                                                value="{{ !empty($variant->attributes['size']) ? $variant->attributes['size'] : '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">&nbsp;</label>
                                                    <a href="javascript:void(0);" class="text-danger remove-variant">
                                                        <i class='bx bx-trash'></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter Description">{{ $product->description }}</textarea>
                            <span class="text-danger error" id="description_error"></span>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Current Images</label>
                            <div class="row">
                                @if ($product->images && count($product->images) > 0)
                                    @foreach ($product->images as $image)
                                        <div class="col-md-2 mb-2">
                                            <img src="{{ asset($image) }}" alt="Product Image" class="img-thumbnail">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <p>No images uploaded</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Add New Images</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple
                                accept="image/*">
                            <small class="text-muted">Leave empty to keep existing images</small>
                            <span class="text-danger error" id="images_error"></span>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control"
                                placeholder="Enter meta title" value="{{ $product->meta_title }}">
                            <span class="text-danger error" id="meta_title_error"></span>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Meta Keywords</label>
                            <input type="text" name="meta_keyword" id="meta_keyword" class="form-control"
                                placeholder="Enter meta keywords" value="{{ $product->meta_keyword }}">
                            <span class="text-danger error" id="meta_keyword_error"></span>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" class="form-control" rows="3"
                                placeholder="Enter meta description">{{ $product->meta_description }}</textarea>
                            <span class="text-danger error" id="meta_description_error"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <i class='bx bx-save'></i> Update
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
                const variantCount = $('.variant-row').length;
                const variantHtml = `
            <div class="variant-row border rounded p-2 mb-2">
                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label">SKU</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $product->sku }}-${variantCount + 1}" readonly>
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
                    <div class="col-md-1">
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
                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Updating...`
                            ).attr('disabled', true);
                    },
                    success: function(res) {
                        $('#saveBtn').html('Update').removeAttr('disabled');
                        alertMsg(res.status, res.msg, 3000);

                        if (res.status) {
                            setTimeout(() => {
                                window.location.href = "{{ url('crm/products') }}";
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        $('#saveBtn').html('Update').removeAttr('disabled');

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
