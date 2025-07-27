@extends('CRM.Layout.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row card-header">
                <div class="col-md-10">
                    <h5>Add Slider</h5>
                </div>
                <div class="col-md-2 text-right">
                    <a href="{{ url('crm/slider') }}" class="btn btn-outline-warning btn-sm">
                        <i class='bx bx-left-arrow-circle'></i>&nbsp;Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div id='message'></div>
                <form id="sliderForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" required placeholder="Enter Title">
                            <span class="text-danger error" id="title_error"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select select2" required>
                                <option value="">Select Type</option>
                                <option value="web">Web</option>
                                <option value="app_main">App Main</option>
                                <option value="app_offer">App Offer</option>
                                <option value="cat">Cat</option>
                                <option value="cat1">Cat1</option>
                                <option value="bus_banner">Bus Banner</option>
                                <option value="fina_main">Fina Main</option>
                                <option value="insurance_main">Insurance Main</option>
                                <option value="pro_cat">Pro Cat</option>
                                <option value="mob_recharge">Mob Recharge</option>
                                <option value="unilearn_main">Unilearn Main</option>
                                <option value="eduction_main">Eduction Main</option>
                                <option value="legal_main">Legal Main</option>
                                <option value="recharge_main">Recharge Main</option>
                                <option value="deals">Deals</option>
                                <option value="new">New</option>
                            </select>
                            <span class="text-danger error" id="type_error"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <span class="text-danger error" id="status_error"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">URL</label>
                            <input type="url" name="url" id="url" class="form-control" placeholder="Enter URL">
                            <span class="text-danger error" id="url_error"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Images <span class="text-danger">*</span></label>
                            <input type="file" name="image[]" id="imageInput" class="form-control" multiple required
                                accept="image/*">
                            <div id="imagePreview" class="mt-2"></div>
                            <span class="text-danger error" id="image_error"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm" id="saveBtn">
                                <i class='bx bx-save'></i> Add Slider
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
            $('#imageInput').on('change', function() {
                $('#imagePreview').html('');
                if (this.files) {
                    $.each(this.files, function(i, file) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#imagePreview').append('<img src="' + e.target.result +
                                '" width="100" class="me-2 mb-2" />');
                        }
                        reader.readAsDataURL(file);
                    });
                }
            });
            $('#sliderForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $('.error').html('');
                $('#saveBtn').html(
                    `<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>&nbsp;Saving...`
                    ).attr('disabled', true);
                $.ajax({
                    url: '{{ url('crm/slider') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'JSON',
                    success: function(res) {
                        $('#saveBtn').html('<i class="bx bx-save"></i> Add Slider').removeAttr('disabled');
                        if (res.status) {
                            alertMsg(true, 'Slider(s) added successfully!', 3000);
                            setTimeout(function(){ location.href = '{{ url('crm/slider') }}'; }, 1200);
                        } else {
                            alertMsg(false, 'Failed to add slider(s).', 3000);
                        }
                    },
                    error: function(xhr) {
                        $('#saveBtn').html('<i class="bx bx-save"></i> Add Slider').removeAttr('disabled');
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.validation) {
                            const errors = xhr.responseJSON.validation;
                            $.each(errors, (field, messages) => {
                                $(`#${field}_error`).html(messages[0]);
                            });
                        } else {
                            alertMsg(false, 'Validation error!', 3000);
                        }
                    }
                });
            });
        });
    </script>
@endpush
