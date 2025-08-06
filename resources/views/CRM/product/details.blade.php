@extends('CRM.Layout.layout')

@section('title', 'Product Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Product Details - {{ $product->product_name }}</h5>
                    <a href="{{ url('crm/products') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Products
                    </a>
                </div>
                <div class="card-body">
                    <div id="message"></div>
                    <form id="ingredientsForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="details" class="form-label">Details</label>
                                    <textarea class="form-control ckeditor" id="details" name="details" rows="12">{{ $detail->details ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="key_ings" class="form-label">Key Ingredients</label>
                                    <textarea class="form-control ckeditor" id="key_ings" name="key_ings" rows="12">{{ $detail->key_ings ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="uses" class="form-label">Uses</label>
                                    <textarea class="form-control ckeditor" id="uses" name="uses" rows="12">{{ $detail->uses ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="result" class="form-label">Result</label>
                                    <textarea class="form-control ckeditor" id="result" name="result" rows="12">{{ $detail->result ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ ($detail->status ?? '1') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="saveBtn">
                                    <i class="bx bx-save"></i> Save Details
                                </button>
                                <button type="button" class="btn btn-secondary" id="resetBtn">
                                    <i class="bx bx-reset"></i> Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Saving details...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<script>
$(document).ready(function() {
    // Initialize CKEditor for all textareas
    let editors = {};
    let useFallback = false;
    
    // Check if CKEditor is available
    if (typeof ClassicEditor === 'undefined') {
        alertMsg(false, 'CKEditor failed to load. Using fallback textarea.', 5000);
        useFallback = true;
    }
    
    $('.ckeditor').each(function() {
        const elementId = $(this).attr('id');
        
        if (useFallback) {
            // Use regular textarea as fallback
            editors[elementId] = {
                getContent: function() {
                    return $('#' + elementId).val();
                },
                setContent: function(content) {
                    $('#' + elementId).val(content);
                }
            };
            return;
        }
        
        ClassicEditor
            .create(document.getElementById(elementId), {
                toolbar: {
                    items: [
                        'undo', 'redo',
                        '|', 'heading',
                        '|', 'bold', 'italic', 'strikethrough', 'underline',
                        '|', 'link', 'blockQuote', 'insertTable', 'mediaEmbed',
                        '|', 'bulletedList', 'numberedList',
                        '|', 'indent', 'outdent',
                        '|', 'fontSize', 'fontColor', 'fontBackgroundColor',
                        '|', 'alignment',
                        '|', 'horizontalLine', 'pageBreak',
                        '|', 'removeFormat'
                    ]
                },
                language: 'en',
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        'imageStyle:full',
                        'imageStyle:side'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn',
                        'tableRow',
                        'mergeTableCells'
                    ]
                },
                licenseKey: '',
            })
            .then(editor => {
                editors[elementId] = editor;
                console.log('CKEditor initialized successfully for:', elementId);
            })
            .catch(error => {
                console.error('CKEditor initialization error:', error);
                alertMsg(false, 'CKEditor failed to initialize. Using fallback textarea.', 5000);
                
                // Fallback to regular textarea
                editors[elementId] = {
                    getContent: function() {
                        return $('#' + elementId).val();
                    },
                    setContent: function(content) {
                        $('#' + elementId).val(content);
                    }
                };
            });
    });

    // Form submission
    $('#ingredientsForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        // Update form data with CKEditor content
        Object.keys(editors).forEach(function(editorId) {
            const content = editors[editorId].getData();
            formData.set(editorId, content);
        });
        
        // Add status value
        formData.set('status', $('#status').is(':checked') ? '1' : '0');
        
        $.ajax({
            url: '{{ url("crm/products/store-detail") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#saveBtn').html('<i class="bx bx-loader-circle bx-spin"></i> Saving...').prop('disabled', true);
            },
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    alertMsg(true, response.msg, 3000);
                } else {
                    alertMsg(false, response.msg, 3000);
                }
                $('#saveBtn').html('<i class="bx bx-save"></i> Save Details').prop('disabled', false);
            },
            error: function(xhr) {
                $('#saveBtn').html('<i class="bx bx-save"></i> Save Details').prop('disabled', false);
                let errorMessage = 'An error occurred while saving the details.';
                
                if (xhr.responseJSON && xhr.responseJSON.msg) {
                    errorMessage = xhr.responseJSON.msg;
                } else if (xhr.responseJSON && xhr.responseJSON.validation) {
                    errorMessage = Object.values(xhr.responseJSON.validation).flat().join('\n');
                }
                
                alertMsg(false, errorMessage, 3000);
            }
        });
    });

    // Reset button
    $('#resetBtn').on('click', function() {
        if (confirm('Are you sure? This will reset all fields to their original values.')) {
            // Reset all CKEditor instances
            Object.keys(editors).forEach(function(editorId) {
                const originalValue = $('#' + editorId).val();
                editors[editorId].setData(originalValue);
            });
            
            // Reset status checkbox
            $('#status').prop('checked', {{ ($detail->status ?? '1') == '1' ? 'true' : 'false' }});
            
            alertMsg(true, 'All fields have been reset to their original values.', 3000);
        }
    });

    // Auto-save functionality (optional)
    let autoSaveTimer;
    $('.ckeditor').on('input', function() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(function() {
            // Auto-save after 30 seconds of inactivity
            console.log('Auto-save triggered');
        }, 30000);
    });
    
    // Check CKEditor loading status after 5 seconds
    setTimeout(function() {
        if (Object.keys(editors).length === 0 && !useFallback) {
            alertMsg(false, 'CKEditor failed to load. Using fallback textarea.', 5000);
            useFallback = true;
            
            // Initialize fallback for all textareas
            $('.ckeditor').each(function() {
                const elementId = $(this).attr('id');
                if (!editors[elementId]) {
                    editors[elementId] = {
                        getContent: function() {
                            return $('#' + elementId).val();
                        },
                        setContent: function(content) {
                            $('#' + elementId).val(content);
                        }
                    };
                }
            });
        }
    }, 5000);
});
</script>
@endpush
