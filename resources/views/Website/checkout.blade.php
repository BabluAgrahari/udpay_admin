
@extends('Website.Layout.app')

@section('content')
<section class="section-padding">
  <div class="container">
    <div class="row">
        <div class="col-lg-8">
            <!-- Left: Cart Items -->
            <div class="cart-items p-3">
                <h6 class="mb-2">Your order updates & invoice will be sent to</h6>
                <div class="d-flex gap-2 align-items-center">
                    <p class="mb-0 text-black"><i class="fa-regular fa-envelope"></i> {{ Auth::user()->email ?? '' }}</p>
                </div>
            </div>
            <div class="cart-items">
                <div class="edit-form-open">
                    <div class="address-header-title">
                        <h5 class="mb-0">Payment</h5>
                        <button class="add-address editBtn">+ Add New Address</button>
                    </div>
                    <!-- Add/Edit Address Form -->
                    <div class="edit-form-box mb-3" id="addAddressForm" style="display:none;">
                        <h4 class="mb-4 form-title">Add New Address</h4>
                        <form id="addressForm" method="POST" action="{{ url('address') }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label>Name <span class="color-red">*</span></label>
                                    <input type="text" id="user_add_name" name="user_add_name" class="form-control" placeholder="Enter Name" required />
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Mobile No. <span class="color-red">*</span></label>
                                    <input type="text" id="user_add_mobile" name="user_add_mobile" class="form-control" placeholder="Enter Phone" required />
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label>Address (Area & Street) <span class="color-red">*</span></label>
                                    <textarea id="user_add_1" name="user_add_1" class="form-control" placeholder="Enter Addresses" required></textarea>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Landmark</label>
                                    <input type="text" id="land_mark" name="land_mark" class="form-control" placeholder="Enter Landmark" />
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Pincode <span class="color-red">*</span></label>
                                    <input type="text" id="user_zip_code" name="user_zip_code" class="form-control" placeholder="Enter Pincode" required />
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>State <span class="color-red">*</span></label>
                                    <input type="text" id="user_state" name="user_state" class="form-control" placeholder="Enter State" required />
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>City <span class="color-red">*</span></label>
                                    <input type="text" id="user_city" name="user_city" class="form-control" placeholder="Enter City" required />
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between">
                                <div>
                                    <p class="mb-2">Save As</p>
                                    <div class="weight-flower mt-0">
                                        <div class="options">
                                            <label><input type="radio" name="address_type" value="Home" checked> Home</label>
                                            <label><input type="radio" name="address_type" value="Office"> Office</label>
                                            <label><input type="radio" name="address_type" value="Others"> Others</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="thm-btn submit-btn">Save Address</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Saved Addresses -->
                <div class="saved-addresses edit-form-open p-0">
                    <div class="address-list">
                        @if($addresses->isNotEmpty())
                        @foreach($addresses as $address)
                        <div class="address-card checkout-address" data-address="{{ json_encode([
                            'id' => $address->id,
                            'name' => $address->user_add_name,
                            'mobile' => $address->user_add_mobile,
                            'address' => $address->user_add_1,
                            'landmark' => $address->land_mark,
                            'pincode' => $address->user_zip_code,
                            'state' => $address->user_state,
                            'city' => $address->user_city,
                            'type' => $address->address_type
                        ]) }}">
                            <div class="address-header">
                                <h6 class="mb-2">{{ $address->user_add_name }}</h6>
                                <div class="address-actions">
                                    <button class="btn btn-sm btn-outline-primary edit-address" data-address-id="{{ $address->id }}" title="Edit Address">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <div class="default-checkbox-wrapper">
                                        <label class="default-checkbox-label">
                                            <input type="checkbox" class="set-default-checkbox" data-address-id="{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }}>
                                            <span class="default-text">Default</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <p>{{ $address->user_add_1 }}</p>
                            <p>{{ $address->user_city }}, {{ $address->user_state }}, {{ $address->user_zip_code }}</p>
                            <p>{{ $address->user_add_mobile }}</p>
                            <div class="d-flex gap-2 justify-content-between align-items-center">
                                <form method="POST" action="{{ url('checkout') }}">
                                    @csrf
                                    <input type="hidden" name="address_id" value="{{ $address->id }}">
                                    <button type="submit" class="thm-btn btn-small">Deliver Here</button>
                                </form>
                                <label class="select-add">
                                    <input type="radio" name="address_id" value="{{ $address->id }}"> {{ ucfirst($address->address_type) }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <p>No saved addresses. Please add one.</p>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
        <div class="col-lg-4">
            <div class="cart-summary">
                <div class="summary-box">
                    <span><strong class="text-black">Order Summary</strong> ({{ $total_items }} Items)</span>
                    <p>Total MRP <span>₹{{ $total_mrp }}</span></p>
                    <p>Total Discounts <span class="discount">−₹{{ $total_saving }}</span></p>
                    <p>Coupon Discount <span class="discount">-₹0</span></p>
                    <p>Convenience Fee <span>₹0</span></p>
                    <hr />
                    <p class="total text-black">Payable Amount <span>₹{{ $subtotal }}</span></p>
                </div>
            </div>
            <div class="cart-items p-3">
                <h6 class="mb-2">Payment Method</h6>
                <form method="POST" action="{{ url('checkout') }}">
                    @csrf
                    <input type="hidden" name="address_id" value="">
                    <select name="payment_method" class="form-control mb-2">
                        <option value="cod">Cash on Delivery</option>
                        <option value="online">Online Payment</option>
                    </select>
                    <button type="submit" class="thm-btn">Proceed to Pay</button>
                </form>
            </div>
        </div>
    </div>
  </div>
</section>

<style>

.address-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #fff;
    position: relative;
}

.address-card:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
}

.address-card.selected {
    border-color: #007bff;
    background-color: #f8f9ff;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.2);
}

.address-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.address-header h6 {
    margin: 0;
    font-weight: 600;
    color: #333;
}

.address-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.edit-address {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    transition: all 0.3s ease;
}

.edit-address:hover {
    background-color: #0056b3;
    border-color: #0056b3;
    transform: scale(1.1);
}

.edit-address i {
    font-size: 12px;
}

.default-checkbox-wrapper {
    display: flex;
    align-items: center;
}

.default-checkbox-label {
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    font-size: 12px;
    color: #666;
    margin: 0;
}

.set-default-checkbox {
    width: 16px;
    height: 16px;
    margin: 0;
    cursor: pointer;
}

.default-text {
    font-weight: 500;
}

.set-default-checkbox:checked + .default-text {
    color: #28a745;
    font-weight: 600;
}

.address-card p {
    margin: 5px 0;
    color: #666;
    font-size: 14px;
}

.address-card .d-flex {
    margin-top: 10px;
}

.btn-small {
    padding: 5px 12px;
    font-size: 12px;
}

.edit-form-box {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
}


.options {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.options label {
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    font-weight: normal;
}

.options input[type="radio"] {
    margin: 0;
}

@media (max-width: 768px) {
    .address-card .d-flex {
        flex-direction: column;
        gap: 10px;
    }
    
    .options {
        flex-direction: column;
        gap: 10px;
    }
    
    .edit-form-box {
        padding: 15px;
    }
    
    .address-actions {
        flex-direction: column;
        gap: 5px;
    }
}

.processing {
    opacity: 0.6;
    pointer-events: none;
}

#addAddressForm {
    transition: all 0.3s ease;
}

.address-card.selected::before {
    content: "✓";
    position: absolute;
    top: 10px;
    right: 10px;
    background: #007bff;
    color: white;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    
    // Global variables
    let isEditMode = false;
    let currentAddressId = null;
    
    // Toggle address form visibility
    $('.add-address').on('click', function() {
        const $form = $('#addAddressForm');
        const $btn = $(this);
        if ($form.is(':visible')) {
            $form.hide();
            $btn.text('+ Add New Address');
            resetForm();
        } else {
            $form.show();
            $btn.text('Cancel');
        }
    });
    
    // Reset form to add mode
    function resetForm() {
        isEditMode = false;
        currentAddressId = null;
        $('#addressForm')[0].reset();
        $('#addressForm').attr('action', '{{ url("address") }}');
        $('#addressForm input[name="_method"]').remove();
        $('.form-title').text('Add New Address');
        $('.submit-btn').text('Save Address');
    }
    
    // Edit address functionality
    $(document).on('click', '.edit-address', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const addressId = $(this).data('address-id');
        const $addressCard = $(this).closest('.address-card');
        const addressData = JSON.parse($addressCard.attr('data-address'));
        
        $('#user_add_name').val(addressData.name);
        $('#user_add_mobile').val(addressData.mobile);
        $('#user_add_1').val(addressData.address);
        $('#land_mark').val(addressData.landmark || '');
        $('#user_zip_code').val(addressData.pincode);
        $('#user_state').val(addressData.state);
        $('#user_city').val(addressData.city);
        $(`input[name="address_type"][value="${addressData.type}"]`).prop('checked', true);
        
        isEditMode = true;
        currentAddressId = addressId;
        $('#addressForm').attr('action', `{{ url('address') }}/${addressId}`);
        
        if ($('#addressForm input[name="_method"]').length === 0) {
            $('#addressForm').append('<input type="hidden" name="_method" value="PUT">');
        } else {
            $('#addressForm input[name="_method"]').val('PUT');
        }
        $('.form-title').text('Edit Address');
        $('.submit-btn').text('Update Address');
        $('#addAddressForm').show();
        $('.add-address').text('Cancel');
    });
    
    $(document).on('change', '.set-default-checkbox', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const addressId = $(this).data('address-id');
        const $checkbox = $(this);
        const isChecked = $checkbox.is(':checked');
        
        if (isChecked) {
            $.ajax({
                url: `{{ url('address') }}/${addressId}/set-default`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        $('.set-default-checkbox').not($checkbox).prop('checked', false);
                    } else {
                        $checkbox.prop('checked', false);
                    }
                    showSnackbar(response.msg,response.status?'success':'error');
                },
                error: function(xhr) {
                    $checkbox.prop('checked', false);
                    showSnackbar(xhr.responseJSON.msg,'error');
                }
            });
        }
    });
    
    $('#addressForm').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitBtn = $form.find('.submit-btn');
        const originalText = $submitBtn.text();
        
        $submitBtn.prop('disabled', true).text('Processing...');
        $('.error-message').remove();
        $('.form-control').removeClass('is-invalid');
        
        $.ajax({
            url: $form.attr('action'),
            method: $form.attr('method'),
            data: $form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                showCartLoading();
            },
            dataType: 'json',
            success: function(response) {
                hideCartLoading();
                showSnackbar(response.msg, response.status?'success':'error');
                    if(response.status){
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
            },
            error: function(xhr) {
                hideCartLoading();
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        const $field = $(`[name="${field}"]`);
                        $field.addClass('is-invalid');
                        $field.after(`<div class="error-message">${messages[0]}</div>`);
                    });
                } else {
                    showSnackbar(xhr.responseJSON.msg,'error');
                }
            }
        });
    });
    
    $('#user_add_mobile').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        $(this).val(value);
    });
    
    $('#user_zip_code').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 6) {
            value = value.substring(0, 6);
        }
        $(this).val(value);
    });
    
    
    $(document).on('click', '.address-card', function() {
        $('.address-card').removeClass('selected');
        $(this).addClass('selected');
        
        const addressId = $(this).find('input[name="address_id"]').val();
        $('input[name="address_id"][type="hidden"]').val(addressId);
    });
    
    $('.form-control').on('blur', function() {
        const $field = $(this);
        const value = $field.val().trim();
        
        if ($field.prop('required') && !value) {
            $field.addClass('is-invalid');
            if (!$field.next('.error-message').length) {
                $field.after('<div class="error-message">This field is required</div>');
            }
        } else {
            $field.removeClass('is-invalid');
            $field.next('.error-message').remove();
        }
    });
    
    $('.form-control').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.error-message').remove();
    });
});
</script>
@endpush
@endsection