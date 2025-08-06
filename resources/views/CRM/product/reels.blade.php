@extends('CRM.Layout.layout')
@section('content')
<style>
    /* Video Player Styles */
    .video-container {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        background: #000;
    }
    
    .video-container video {
        transition: transform 0.3s ease;
    }
    
    .video-container:hover video {
        transform: scale(1.05);
    }
    
    .video-overlay {
        transition: opacity 0.3s ease;
    }
    
    .video-overlay:hover {
        opacity: 0.8;
    }
    
    .video-duration {
        font-weight: 500;
        backdrop-filter: blur(4px);
    }
    
    /* Video Preview Modal Styles */
    .video-preview-container video {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .image-preview-container img {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    /* Enhanced Modal Styles */
    .modal-dialog.modal-lg {
        max-width: 800px;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    /* File Type Indicators */
    .file-type-badge {
        position: absolute;
        top: 5px;
        left: 5px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 500;
        text-transform: uppercase;
    }
    
    /* Loading States */
    .video-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 1.5rem;
    }
    
    /* Responsive Video Container */
    @media (max-width: 768px) {
        .video-container {
            height: 120px !important;
        }
        
        .video-duration {
            font-size: 10px;
            padding: 1px 4px;
        }
        
        .modal-dialog.modal-lg {
            max-width: 95%;
            margin: 1rem auto;
        }
    }
</style>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Manage Reels & Videos - {{ $product->product_name }}</h5>
                    <a href="{{ url('crm/products') }}" class="btn btn-outline-secondary btn-sm">
                        <i class='bx bx-arrow-back'></i>&nbsp;Back to Products
                    </a>
                </div>
                <div class="card-body">
                    <div id="message"></div>
                    <!-- Upload Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class='bx bx-upload'></i> Upload Reels & Videos</h6>
                                </div>
                                <div class="card-body">
                                    <form id="uploadForm" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label for="files" class="form-label">Select Files (Images/Videos)</label>
                                                    <input type="file" class="form-control" id="files" name="files[]" multiple 
                                                           accept="image/*,video/*" required>
                                                    <div class="form-text">
                                                        Supported formats: JPG, PNG, GIF, BMP, WEBP, MP4, AVI, MOV, WMV, FLV, WEBM, MKV (Max: 100MB per file)
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary w-100" id="uploadBtn">
                                                        <i class='bx bx-upload'></i>&nbsp;Upload Files
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <!-- Upload Progress -->
                                    <div id="uploadProgress" class="mt-3" style="display: none;">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted">Uploading files...</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Files List -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class='bx bx-images'></i> Uploaded Files</h6>
                                </div>
                                <div class="card-body">
                                    <div id="filesList" class="row">
                                        <!-- Files will be loaded here via AJAX -->
                                    </div>
                                    
                                    <div id="noFiles" class="text-center py-4" style="display: none;">
                                        <i class='bx bx-images bx-lg text-muted'></i>
                                        <p class="text-muted mt-2">No files uploaded yet</p>
                                    </div>
                                    
                                    <div id="loadingFiles" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="text-muted mt-2">Loading files...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">File Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="previewContent"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    const productId = {{ $product->id }};
    
    // Load files on page load
    loadFiles();
    
    // File upload form submission
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const $uploadBtn = $('#uploadBtn');
        const $progress = $('#uploadProgress');
        const $progressBar = $progress.find('.progress-bar');
        
        // Disable upload button and show progress
        $uploadBtn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i>&nbsp;Uploading...');
        $progress.show();
        $progressBar.css('width', '0%');
        
        $.ajax({
            url: `{{ url('crm/products') }}/${productId}/reels`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percentComplete = (e.loaded / e.total) * 100;
                        $progressBar.css('width', percentComplete + '%');
                    }
                });
                return xhr;
            },
            success: function(response) {
                if (response.status) {
                    alertMsg(true, response.msg, 3000);
                    $('#uploadForm')[0].reset();
                    loadFiles(); // Reload files list
                } else {
                    alertMsg(false, response.msg, 3000);
                }
            },
            error: function(xhr) {
                alertMsg(false, xhr.responseJSON?.msg || 'Upload failed. Please try again.', 3000);
            },
            complete: function() {
                $uploadBtn.prop('disabled', false).html('<i class="bx bx-upload"></i>&nbsp;Upload Files');
                $progress.hide();
            }
        });
    });
    
    // Load files function
    function loadFiles() {
        $('#loadingFiles').show();
        $('#filesList').empty();
        $('#noFiles').hide();
        
        $.ajax({
            url: `{{ url('crm/products') }}/${productId}/reels/list`,
            method: 'GET',
            success: function(response) {
                $('#loadingFiles').hide();
                
                if (response.status && response.record.length > 0) {
                    response.record.forEach(function(file) {
                        addFileToList(file);
                    });
                } else {
                    $('#noFiles').show();
                }
            },
            error: function(xhr) {
                $('#loadingFiles').hide();
                $('#noFiles').show();
                alertMsg(false, 'Failed to load files', 3000);
            }
        });
    }
    
    // Add file to list function
    function addFileToList(file) {
        const fileHtml = `
            <div class="col-md-4 col-lg-3 mb-3" id="file-${file.id}">
                <div class="card h-100">
                    <div class="card-body p-2">
                        <div class="position-relative">
                            ${file.is_video ? 
                                `<div class="video-container" style="height: 150px; width: 100%; border-radius: 8px; overflow: hidden; position: relative;">
                                    <div class="file-type-badge">VIDEO</div>
                                    <video class="img-fluid" style="height: 100%; width: 100%; object-fit: cover;" 
                                           preload="metadata" muted>
                                        <source src="${file.path}" type="video/${file.file_extension}">
                                    </video>
                                    <div class="video-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                        <i class='bx bx-play-circle' style="font-size: 2rem; color: white;"></i>
                                    </div>
                                    <div class="video-duration" style="position: absolute; bottom: 5px; right: 5px; background: rgba(0,0,0,0.7); color: white; padding: 2px 6px; border-radius: 4px; font-size: 11px;">
                                        <span class="duration-text">--:--</span>
                                    </div>
                                </div>` :
                                `<div style="position: relative;">
                                    <div class="file-type-badge">IMAGE</div>
                                    <img src="${file.path}" class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;" alt="File">
                                </div>`
                            }
                            <div class="position-absolute top-0 end-0 p-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-switch" type="checkbox" 
                                           data-id="${file.id}" ${file.status ? 'checked' : ''}>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block">${file.file_extension.toUpperCase()}</small>
                            <small class="text-muted d-block">${file.created_at}</small>
                        </div>
                    </div>
                    <div class="card-footer p-2">
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-sm btn-outline-info preview-btn" 
                                    data-path="${file.path}" data-type="${file.is_video ? 'video' : 'image'}" data-extension="${file.file_extension}">
                                <i class='bx bx-show'></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="${file.id}">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#filesList').append(fileHtml);
        
        // Add video duration for video files
        if (file.is_video) {
            const video = $(`#file-${file.id} video`)[0];
            const durationSpan = $(`#file-${file.id} .duration-text`);
            const overlay = $(`#file-${file.id} .video-overlay`);
            
            // Add loading state
            overlay.html('<div class="video-loading"><i class="bx bx-loader-alt bx-spin"></i></div>');
            
            video.addEventListener('loadedmetadata', function() {
                const duration = Math.floor(video.duration);
                const minutes = Math.floor(duration / 60);
                const seconds = duration % 60;
                durationSpan.text(`${minutes}:${seconds.toString().padStart(2, '0')}`);
                
                // Restore play button
                overlay.html('<i class="bx bx-play-circle" style="font-size: 2rem; color: white;"></i>');
            });
            
            video.addEventListener('error', function() {
                overlay.html('<i class="bx bx-error" style="font-size: 2rem; color: #dc3545;"></i>');
                durationSpan.text('Error');
            });
            
            // Add click handler for video play
            overlay.on('click', function() {
                const videoElement = $(this).siblings('video')[0];
                if (videoElement.paused) {
                    videoElement.play().then(() => {
                        $(this).hide();
                    }).catch(error => {
                        console.error('Error playing video:', error);
                        alertMsg(false, 'Error playing video. Please try again.', 3000);
                    });
                } else {
                    videoElement.pause();
                    $(this).show();
                }
            });
            
            // Show overlay when video ends
            video.addEventListener('ended', function() {
                overlay.show();
            });
            
            // Pause all other videos when one starts playing
            video.addEventListener('play', function() {
                $('video').not(this).each(function() {
                    this.pause();
                    $(this).siblings('.video-overlay').show();
                });
            });
        }
    }
    
    // Status switch handler
    $(document).on('change', '.status-switch', function() {
        const id = $(this).data('id');
        const status = $(this).prop('checked');
        const $switch = $(this);
        
        $switch.prop('disabled', true);
        
        $.ajax({
            url: `{{ url('crm/reels') }}/${id}/status`,
            method: 'POST',
            data: {
                status: status,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            beforeSend: function() {
                $switch.html('<i class="bx bx-loader-alt bx-spin"></i>&nbsp;Updating...');
            },
            success: function(response) {
                $switch.prop('disabled', false).html('<i class="bx bx-check"></i>');
                if (response.status) {
                    alertMsg(true, response.msg, 2000);
                   
                } else {
                    $switch.prop('checked', !status);
                    alertMsg(false, response.msg, 3000);
                    $switch.prop('disabled', false).html('<i class="bx bx-check"></i>');
                }
            },
            error: function(xhr) {
                $switch.prop('checked', !status);
                alertMsg(false, xhr.responseJSON?.msg || 'Failed to update status', 3000);
                $switch.prop('disabled', false).html('<i class="bx bx-check"></i>');
            }
        });
    });
    
    // Delete file handler
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const $btn = $(this);
        
        if (confirm('Are you sure you want to delete this file? This action cannot be undone.')) {
            $btn.prop('disabled', true);
            
            $.ajax({
                url: `{{ url('crm/reels') }}/${id}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                beforeSend: function() {
                  $btn.html('<i class="bx bx-loader-alt bx-spin"></i>&nbsp;Deleting...');
                },
                success: function(response) {
                    $btn.prop('disabled', false).html('<i class="bx bx-trash"></i>');
                    if (response.status) {
                        $(`#file-${id}`).fadeOut(300, function() {
                            $(this).remove();
                            if ($('#filesList .col-md-4').length === 0) {
                                $('#noFiles').show();
                            }
                        });
                        alertMsg(true, response.msg, 3000);
                    } else {
                        alertMsg(false, response.msg, 3000);
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html('<i class="bx bx-trash"></i>');
                    alertMsg(false, xhr.responseJSON?.msg || 'Failed to delete file', 3000);
                }
            });
        }
    });
    
    // Preview file handler
    $(document).on('click', '.preview-btn', function() {
        const path = $(this).data('path');
        const type = $(this).data('type');
        const extension = $(this).data('extension');
        const $modal = $('#previewModal');
        const $content = $('#previewContent');
        
        $content.empty();
        
        if (type === 'video') {
            $content.html(`
                <div class="video-preview-container" style="max-width: 100%; max-height: 70vh;">
                    <video controls style="width: 100%; height: auto; max-height: 70vh; border-radius: 8px;" preload="metadata">
                        <source src="${path}" type="video/${extension}">
                        Your browser does not support the video tag.
                    </video>
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">Format: ${extension.toUpperCase()}</small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">Duration: <span class="video-duration-display">Loading...</span></small>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            
            // Get video duration for preview
            const previewVideo = $content.find('video')[0];
            previewVideo.addEventListener('loadedmetadata', function() {
                const duration = Math.floor(previewVideo.duration);
                const minutes = Math.floor(duration / 60);
                const seconds = duration % 60;
                $content.find('.video-duration-display').text(`${minutes}:${seconds.toString().padStart(2, '0')}`);
            });
        } else {
            $content.html(`
                <div class="image-preview-container">
                    <img src="${path}" class="img-fluid" style="max-height: 70vh; border-radius: 8px;" alt="Preview">
                    <div class="mt-3">
                        <small class="text-muted">Format: ${extension.toUpperCase()}</small>
                    </div>
                </div>
            `);
        }
        
        $modal.modal('show');
    });
});
</script>
@endpush 