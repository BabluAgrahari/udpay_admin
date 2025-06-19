@extends('CRM.Layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row card-header">
            <div class="col-md-10">
                <h5>Edit Brand</h5>
            </div>
            <div class="col-md-2 text-right">
                <a href="{{ url('crm/brands') }}" class="btn btn-outline-warning btn-sm">
                    <i class='bx bx-left-arrow-circle'></i>&nbsp;Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div id="message"></div>
            <form id="save" method="post" action="{{ url('crm/brands/'. $brand->_id) }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" 
                            placeholder="Enter brand name" value="{{ $brand->name }}" required>
                        <span class="text-danger error" id="name_error"></span>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" 
                            placeholder="Enter description">{{ $brand->description }}</textarea>
                        <span class="text-danger error" id="description_error"></span>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Current Icon</label>
                        <div class="row">
                            @if($brand->icon)
                                <div class="col-md-2 mb-2">
                                    <img src="{{ asset($brand->icon) }}" alt="Brand Icon" class="img-thumbnail">
                                </div>
                            @else
                                <div class="col-12">
                                    <p>No icon uploaded</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">New Icon</label>
                        <input type="file" name="icon" id="icon" class="form-control" accept="image/*">
                        <small class="text-muted">Leave empty to keep existing icon</small>
                        <span class="text-danger error" id="icon_error"></span>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="status" name="status" value="1" 
                                {{ $brand->status ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
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
    // Clear validation errors on input change
    $('input, select, textarea').on('change keyup', function() {
        $(this).removeClass('is-invalid');
        $(`#${$(this).attr('id')}_error`).html('');
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
                $('#saveBtn').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Updating...`).attr('disabled', true);
            },
            success: function(res) {
                $('#saveBtn').html('Update').removeAttr('disabled');
                alertMsg(res.status, res.msg, 3000);

                if (res.status) {
                    setTimeout(() => {
                        window.location.href = "{{ url('crm/brands') }}";
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
                    alertMsg(false, xhr.responseJSON.msg || 'An error occurred while processing your request.', 3000);
                }
            }
        });
    });
});
</script>
@endpush 