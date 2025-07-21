@extends('CRM.Layout.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row card-header">
                <div class="col-md-10">
                    <h5>Add Stock</h5>
                </div>
                <div class="col-md-2 text-right">
                    <a href="{{ url('crm/stock-history') }}" class="btn btn-outline-warning btn-sm">
                        <i class='bx bx-left-arrow-circle'></i>&nbsp;Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form id="stockForm" method="POST" action="{{ url('crm/stock-history') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product <span class="text-danger">*</span></label>
                            <select name="product_id" id="product_id" class="form-select select2" required>
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->_id }}" data-has-variant="{{ $product->is_variant }}">
                                        {{ $product->product_name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error" id="product_id_error"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stock <span class="text-danger">*</span></label>
                            <input type="number" name="stock" id="stock" class="form-control" required min="0"
                                step="0.01">
                            <span class="text-danger error" id="stock_error"></span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Unit <span class="text-danger">*</span></label>
                            <select name="unit_id" id="unit_id" class="form-select select2" required>
                                <option value="">Select Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->_id }}">{{ $unit->unit }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error" id="unit_id_error"></span>
                        </div>

                        <div class="col-md-5 mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control"
                                placeholder="Enter Remarks"></textarea>
                            <span class="text-danger error" id="remarks_error"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <i class='bx bx-save'></i> Add Stock
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

            $('form#stockForm').submit(function(e) {
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
                            $('form#save').trigger('reset');
                            setTimeout(() => {
                                window.location.href = "{{ url('crm/stock-history') }}";
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
