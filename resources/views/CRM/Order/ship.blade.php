@extends('CRM.Layout.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row card-header">
                <div class="col-md-10">
                    <h5>Ship Order</h5>
                </div>
                <div class="col-md-2 text-right">
                    <a href="{{ url('crm/order') }}" class="btn btn-outline-warning btn-sm">
                        <i class='bx bx-left-arrow-circle'></i>&nbsp;Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div id="message"></div>
                <form id="save" method="post" action="{{ url('crm/orders/ship') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="POST">

                    <!-- Order Details Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class='bx bx-package'></i> Order Details</h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Order No <span class="text-danger">*</span></label>
                            <input type="text" name="order_no" id="order_no" class="form-control" disabled readonly
                                placeholder="Order Number" value="{{ $order->order_number ?? '' }}">
                            <span class="text-danger error" id="order_no_error"></span>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Payment Mode <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_mode" readonly
                                        id="payment_mode_cod" value="cod"
                                        {{ isset($order->payment_method) && $order->payment_method == 'cod' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="payment_mode_cod">COD</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_mode" readonly
                                        id="payment_mode_prepaid" value="prepaid"
                                        {{ isset($order->payment_method) && $order->payment_method == 'prepaid' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="payment_mode_prepaid">Prepaid</label>
                                </div>
                            </div>
                            <span class="text-danger error" id="payment_mode_error"></span>
                        </div>

                        <div class="col-md-2 mb-3" id="value_field"
                            style="display:{{ isset($order->payment_method) && $order->payment_method == 'cod' ? 'block' : 'none' }};">
                            <label class="form-label">Value <span class="text-danger">*</span></label>
                            <input type="number" name="value" id="value" class="form-control"
                                placeholder="Enter Value" value="{{ $order->cod_value ?? '' }}">
                            <span class="text-danger error" id="value_error"></span>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Weight (GM) <span class="text-danger">*</span></label>
                            <input type="number" name="weight" id="weight" class="form-control"
                                placeholder="Weight in grams" value="{{ $order->weight ?? '' }}">
                            <span class="text-danger error" id="weight_error"></span>
                        </div>
                    </div>

                    <!-- Address Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class='bx bx-map'></i> Address Details</h6>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="same_address" name="same_address">
                                <label class="form-check-label" for="same_address">
                                    Make Pickup Address same as Return and RTO Address
                                </label>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pickup Address <span class="text-danger">*</span></label>
                            <select name="pickup_address_id" id="pickup_address_id" class="form-select" required>
                                <option value="">Select Pickup Address</option>
                                @foreach ($pickup_addresses as $pickup_address)
                                    <option value="{{ $pickup_address->_id }}">{{ $pickup_address->name }}
                                        ({{ $pickup_address->location }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-danger error" id="pickup_address_id_error"></span>
                        </div>

                        <div class="col-md-4 mb-3" id="return_address_section">
                            <label class="form-label">Return Address <span class="text-danger">*</span></label>
                            <select name="return_address_id" id="return_address_id" class="form-select" required>
                                <option value="">Select Return Address</option>
                                @foreach ($return_addresses as $return_address)
                                    <option value="{{ $return_address->_id }}">{{ $return_address->name }}
                                        ({{ $return_address->location }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-danger error" id="return_address_id_error"></span>
                        </div>

                        <div class="col-md-4 mb-3" id="rto_address_section">
                            <label class="form-label">RTO Address <span class="text-danger">*</span></label>
                            <select name="rto_address_id" id="rto_address_id" class="form-select" required>
                                <option value="">Select RTO Address</option>
                                @foreach ($rto_addresses as $rto_address)
                                    <option value="{{ $rto_address->_id }}">{{ $rto_address->name }}
                                        ({{ $rto_address->location }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-danger error" id="rto_address_id_error"></span>
                        </div>
                    </div>

                    <!-- Address Details Display -->
                    <div class="row mb-4" id="address_details_section">

                        <div class="col-md-4" id="pickup_address_details_card">
                            <div class="card border-primary">
                                <div class="card-header pt-2 pb-0">
                                    <h6 class="mb-0">Pickup Address Details</h6>
                                </div>
                                <hr>
                                <div class="card-body pt-0" id="pickup_address_details">
                                    <p class="text-muted">Select pickup address to view details</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4" id="return_address_details_card">
                            <div class="card border-success">
                                <div class="card-header pt-2 pb-0">
                                    <h6 class="mb-0">Return Address Details</h6>
                                </div>
                                <hr>
                                <div class="card-body pt-0" id="return_address_details">
                                    <p class="text-muted">Select return address to view details</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4" id="rto_address_details_card">
                            <div class="card border-warning">
                                <div class="card-header pt-2 pb-0">
                                    <h6 class="mb-0">RTO Address Details</h6>
                                </div>
                                <hr>
                                <div class="card-body pt-0" id="rto_address_details">
                                    <p class="text-muted">Select RTO address to view details</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class='bx bx-user'></i> Delivery Address</h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" readonly disabled
                                value="{{ $order->delivery_address['name'] ?? ($order->customer_name ?? '') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" readonly disabled
                                value="{{ $order->delivery_address['email'] ?? ($order->customer_email ?? '') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" readonly disabled
                                value="{{ $order->delivery_address['phone'] ?? ($order->customer_phone ?? '') }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" rows="2" readonly disabled>{{ $order->delivery_address['address'] ?? ($order->customer_address ?? '') }}</textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" readonly disabled
                                value="{{ $order->delivery_address['city'] ?? '' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">State</label>
                            <input type="text" class="form-control" readonly disabled
                                value="{{ $order->delivery_address['state'] ?? '' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pincode</label>
                            <input type="text" class="form-control" readonly disabled
                                value="{{ $order->delivery_address['pincode'] ?? '' }}">
                        </div>
                    </div>

                    <!-- Products and Dimensions Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class='bx bx-box'></i> Products & Dimensions</h6>
                        </div>

                        <!-- Products Column -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Products</h6>
                                </div>
                                <div class="card-body">
                                    <div id="products_rows">
                                       
                                        @if (!empty($order->products))
                                            @foreach ($order->products as $i => $product)
                                                <div class="row mb-2">
                                                    <div class="col-md-4"><input type="text"
                                                            name="products[{{ $i }}][name]"
                                                            class="form-control-sm form-control" placeholder="Name"
                                                            value="{{ $product['name'] ?? ($product['product_name'] ?? '') }}" />
                                                    </div>

                                                    <div class="col-md-4"><input type="number"
                                                            name="products[{{ $i }}][price]"
                                                            class="form-control-sm form-control" placeholder="Price"
                                                            value="{{ $product['price'] ?? '' }}" /></div>
                                                    <div class="col-md-4"><input type="number"
                                                            name="products[{{ $i }}][quantity]"
                                                            class="form-control-sm form-control" placeholder="Quantity"
                                                            value="{{ $product['quantity'] ?? '' }}" /></div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row mb-2">
                                                <div class="col-md-4"><input type="text" name="products[0][name]"
                                                        class="form-control-sm form-control" placeholder="Name" /></div>

                                                <div class="col-md-4"><input type="number" name="products[0][price]"
                                                        class="form-control-sm form-control" placeholder="Price" /></div>
                                                <div class="col-md-4"><input type="number" name="products[0][quantity]"
                                                        class="form-control-sm form-control" placeholder="Quantity" />
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="text-danger error" id="products_error"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Dimensions Column -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Dimensions (in cm)</h6>
                                    <button type="button" class="btn btn-success btn-sm" id="add_dimension_row">
                                        <i class='bx bx-plus'></i> Add
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div id="dimensions_rows">
                                        @php $dimensions = is_array($order->dimensions ?? null) ? $order->dimensions : (json_decode($order->dimensions, true) ?? []); @endphp
                                        @if (!empty($dimensions))
                                            @foreach ($dimensions as $i => $dimension)
                                                <div class="row mb-2 dimension-row">
                                                    <div class="col-md-3"><input type="number"
                                                            name="dimensions[{{ $i }}][length]"
                                                            class="form-control-sm form-control" placeholder="Length"
                                                            value="{{ $dimension['length'] ?? '' }}" /></div>
                                                    <div class="col-md-3"><input type="number"
                                                            name="dimensions[{{ $i }}][breadth]"
                                                            class="form-control-sm form-control" placeholder="Breadth"
                                                            value="{{ $dimension['breadth'] ?? '' }}" /></div>
                                                    <div class="col-md-3"><input type="number"
                                                            name="dimensions[{{ $i }}][height]"
                                                            class="form-control-sm form-control" placeholder="Height"
                                                            value="{{ $dimension['height'] ?? '' }}" /></div>

                                                    <div class="col-md-1 d-flex align-items-center">
                                                        @if ($i > 0)
                                                            <a href="javascript:void(0)" type="button"
                                                                class="text-danger remove_dimension_row"><i
                                                                    class='bx bx-minus'></i></a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row mb-2 dimension-row">
                                                <div class="col-md-3"><input type="number" name="dimensions[0][length]"
                                                        class="form-control-sm form-control" placeholder="Length" /></div>
                                                <div class="col-md-3"><input type="number" name="dimensions[0][breadth]"
                                                        class="form-control-sm form-control" placeholder="Breadth" />
                                                </div>
                                                <div class="col-md-3"><input type="number" name="dimensions[0][height]"
                                                        class="form-control-sm form-control" placeholder="Height" /></div>

                                                <div class="col-md-1 d-flex align-items-center"></div>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="text-danger error" id="dimensions_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <i class='bx bx-save'></i> Save Shipment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



    @push('script')
        <script>
            $(document).ready(function() {
                // Payment mode radio button handler
                $('input[name="payment_mode"]').on('change', function() {
                    if ($(this).val() === 'cod') {
                        $('#value_field').show();
                    } else {
                        $('#value_field').hide();
                        $('#value').val('');
                    }
                });

                // Same address checkbox handler
                $('#same_address').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#return_address_section, #rto_address_section').hide();
                        $('#return_address_details_card, #rto_address_details_card').hide();
                        $('#return_address_id, #rto_address_id').val('');
                    } else {
                        $('#return_address_section, #rto_address_section').show();
                        $('#return_address_details_card, #rto_address_details_card').show();
                    }
                });

                // Address selection handlers
                $('#pickup_address_id').on('change', function() {
                    const addressId = $(this).val();
                    if (addressId) {
                        showAddressDetails('pickup', addressId);
                        if ($('#same_address').is(':checked')) {
                            $('#return_address_id, #rto_address_id').val(addressId);
                            showAddressDetails('return', addressId);
                            showAddressDetails('rto', addressId);
                        }
                    } else {
                        $('#pickup_address_details').html(
                            '<p class="text-muted">Select pickup address to view details</p>');
                    }
                });

                $('#return_address_id').on('change', function() {
                    const addressId = $(this).val();
                    if (addressId) {
                        showAddressDetails('return', addressId);
                    } else {
                        $('#return_address_details').html(
                            '<p class="text-muted">Select return address to view details</p>');
                    }
                });

                $('#rto_address_id').on('change', function() {
                    const addressId = $(this).val();
                    if (addressId) {
                        showAddressDetails('rto', addressId);
                    } else {
                        $('#rto_address_details').html(
                            '<p class="text-muted">Select RTO address to view details</p>');
                    }
                });

                // Clear validation errors on input change
                $('input, select, textarea').on('change keyup', function() {
                    $(this).removeClass('is-invalid');
                    $(`#${$(this).attr('id')}_error`).html('');
                });

                // AJAX form submit
                $('form#save').submit(function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const url = $(this).attr('action');
                    $('.error').html('');

                    // Set return and RTO address same as pickup if checkbox is checked
                    if ($('#same_address').is(':checked')) {
                        formData.set('return_address_id', $('#pickup_address_id').val());
                        formData.set('rto_address_id', $('#pickup_address_id').val());
                    }

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
                            $('#saveBtn').html('Save Shipment').removeAttr('disabled');
                            alertMsg(res.status, res.msg, 3000);
                            if (res.status) {
                                setTimeout(() => {
                                    window.location.href = "{{ url('crm/order') }}";
                                }, 1000);
                            }
                        },
                        error: function(xhr) {
                            $('#saveBtn').html('Save Shipment').removeAttr('disabled');
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

                // Dynamic dimensions (+ and - buttons)
                let dimensionIndex = $('#dimensions_rows .dimension-row').length;
                $('#add_dimension_row').click(function() {
                    let row = `<div class="row mb-2 dimension-row">
                    <div class="col-md-3"><input type="number" name="dimensions[${dimensionIndex}][length]" class="form-control-sm form-control" placeholder="Length" /></div>
                    <div class="col-md-3"><input type="number" name="dimensions[${dimensionIndex}][breadth]" class="form-control-sm form-control" placeholder="Breadth" /></div>
                    <div class="col-md-3"><input type="number" name="dimensions[${dimensionIndex}][height]" class="form-control-sm form-control" placeholder="Height" /></div>
                    <div class="col-md-1 d-flex align-items-center"><a href="javascript:void(0)" type="button" class="text-danger remove_dimension_row"><i class='bx bx-minus'></i></a></div>
                </div>`;
                    $('#dimensions_rows').append(row);
                    dimensionIndex++;
                });

                $(document).on('click', '.remove_dimension_row', function() {
                    if ($('#dimensions_rows .dimension-row').length > 1) {
                        $(this).closest('.dimension-row').remove();
                    }
                });
            });

            // Show address details
            function showAddressDetails(type, addressId) {
                const addresses = @json($pickup_addresses->concat($return_addresses)->concat($rto_addresses));
                const address = addresses.find(addr => addr._id === addressId);

                if (address) {
                    const details = `
                        <div class="mb-2">
                            <strong>${address.name}</strong><br>
                            <small class="text-muted">${address.email}</small><br>
                            <small class="text-muted">${address.phone}</small>
                        </div>
                        <div class="mb-2">
                            <strong>Location:</strong><br>
                            <small>${address.location}</small>
                        </div>
                        <div>
                            <strong>Address:</strong><br>
                            <small>${address.address}</small><br>
                            <small>${address.city}, ${address.state} - ${address.pincode}</small>
                        </div>
                    `;
                    $(`#${type}_address_details`).html(details);
                }
            }
        </script>
    @endpush
@endsection
