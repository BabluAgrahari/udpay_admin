@extends('CRM.Layout.layout')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row card-header">
            <div class="col-md-10">
                <h5>Edit Category</h5>
            </div>
            <div class="col-md-2 text-right">
                <a href="{{ url('crm/categories') }}" class="btn btn-outline-warning btn-sm">
                    <i class='bx bx-left-arrow-circle'></i>&nbsp;Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div id="message"></div>
            <form id="save" method="post" action="{{ url('crm/categories/'.$category->_id) }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $category->name) }}"
                               placeholder="Enter category name">
                        <span class="text-danger error" id="name_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Parent Category</label>
                        <select name="parent_id" 
                                id="parent_id" 
                                class="form-select @error('parent_id') is-invalid @enderror">
                            <option value="">None</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->_id }}" 
                                        {{ old('parent_id', $category->parent_id) == $parent->_id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-danger error" id="parent_id_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" 
                                  id="description" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Enter description">{{ old('description', $category->description) }}</textarea>
                        <span class="text-danger error" id="description_error"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Icon</label>
                        <input type="file" 
                               name="icon" 
                               id="icon" 
                               class="form-control @error('icon') is-invalid @enderror">
                        @if($category->icon)
                            <div class="mt-2">
                                <img src="{{ $category->icon }}" alt="Category Icon" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif
                        <span class="text-danger error" id="icon_error"></span>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Labels</label>
                        <input type="text" 
                               name="labels" 
                               id="labels" 
                               class="form-control @error('labels') is-invalid @enderror" 
                               value="{{ old('labels', $category->labels ? implode(',', $category->labels) : '') }}"
                               placeholder="Enter labels separated by commas">
                        <small class="text-muted">Enter multiple labels separated by commas</small>
                        <span class="text-danger error" id="labels_error"></span>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="status" 
                                   name="status" 
                                   value="1" 
                                   {{ old('status', $category->status) ? 'checked' : '' }}>
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
                        window.location.href = "{{ url('crm/categories') }}";
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