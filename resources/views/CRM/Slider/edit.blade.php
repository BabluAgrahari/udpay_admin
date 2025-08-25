@extends('CRM.Layout.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row card-header">
                <div class="col-md-10">
                    <h5>Edit Slider</h5>
                </div>
                <div class="col-md-2 text-right">
                    <a href="{{ url('crm/slider') }}" class="btn btn-outline-warning btn-sm">
                        <i class='bx bx-left-arrow-circle'></i>&nbsp;Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div id='message'></div>
                <form id="sliderEditForm" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ $slider->title }}" required>
                            <span class="text-danger error" id="title_error"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select select2" required>
                                <option value="">Select Type</option>
                                @foreach (['web', 'app_main', 'app_offer', 'cat', 'cat1', 'bus_banner', 'fina_main', 'insurance_main', 'pro_cat', 'mob_recharge', 'unilearn_main', 'eduction_main', 'legal_main', 'recharge_main', 'deals', 'new','app_slider','app_home'] as $type)
                                    <option value="{{ $type }}" {{ $slider->type == $type ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $type)) }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error" id="type_error"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required id="statusSelect">
                                <option value="1" {{ $slider->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $slider->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <span class="text-danger error" id="status_error"></span>

                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">URL</label>
                            <input type="url" name="url" class="form-control" value="{{ $slider->url }}">
                            <span class="text-danger error" id="url_error"></span>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Current Image</label><br>
                            <img src="/{{ $slider->image }}" width="120" id="currentImage">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Change Image</label>
                            <input type="file" name="image[]" class="form-control" accept="image/*" id="imageInput" multiple>
                            <div id="imagePreview" class="mt-2"></div>
                            <span class="text-danger error" id="image_error"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm" id="updateBtn">
                                <i class='bx bx-save'></i> Update Slider
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
                // Image preview
                $('#imageInput').on('change', function() {
                    var preview = $('#imagePreview');
                    preview.html('');
                    if (this.files && this.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            preview.html('<img src="' + e.target.result + '" width="100" />');
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                // AJAX form submit
                $('#sliderEditForm').on('submit', function(e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    $('.error').html('');
                    $('#updateBtn').html(
                        `<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>&nbsp;Updating...`
                    ).attr('disabled', true);
                    $.ajax({
                        url: '{{ url('crm/slider/' . $slider->id) }}',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'JSON',
                        success: function(res) {
                            $('#updateBtn').html('Update').removeAttr('disabled');
                            if (res.status) {
                                alertMsg(true, 'Slider updated successfully!', 3000);
                                setTimeout(function() {
                                    location.href = '{{ url('crm/slider') }}';
                                }, 1200);
                            } else {
                                alertMsg(false, 'Failed to update slider.', 3000);
                            }
                        },
                        error: function(xhr) {
                            $('#updateBtn').html('Update').removeAttr('disabled');
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
@endsection
