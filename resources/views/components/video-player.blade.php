@props(['videos' => [], 'title' => 'Customer Videos'])

<style>
    /* Video Player Component Styles */
    .video-player-component {
        margin: 20px 0;
    }

    .video-card, .image-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: white;
        margin-bottom: 20px;
    }

    .video-card:hover, .image-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .video-container {
        position: relative;
        width: 100%;
        height: 250px;
        overflow: hidden;
        border-radius: 12px;
        background: #000;
        cursor: pointer;
    }

    .video-player {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .video-container:hover .video-player {
        transform: scale(1.05);
    }

    .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: opacity 0.3s ease;
    }

    .video-overlay:hover {
        opacity: 0.8;
    }

    .video-duration {
        font-weight: 500;
        backdrop-filter: blur(4px);
    }

    .file-type-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 500;
        text-transform: uppercase;
    }

    .image-container {
        position: relative;
        width: 100%;
        height: 250px;
        overflow: hidden;
        border-radius: 12px;
    }

    .customer-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .image-container:hover .customer-image {
        transform: scale(1.05);
    }

    .no-reels-message {
        padding: 40px 20px;
        background: #f8f9fa;
        border-radius: 12px;
        border: 2px dashed #dee2e6;
        text-align: center;
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

    /* Video Modal Styles */
    .video-modal .modal-dialog.modal-lg {
        max-width: 800px;
    }

    .video-modal .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .video-modal .modal-header {
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
    }

    .video-modal .modal-title {
        font-weight: 600;
        color: #333;
    }

    .video-modal .modal-body {
        padding: 1.5rem;
    }

    .video-modal #modalVideo {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        width: 100%;
        height: auto;
        max-height: 70vh;
        border-radius: 8px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .video-container, .image-container {
            height: 200px;
        }

        .video-duration, .file-type-badge {
            font-size: 10px;
            padding: 3px 6px;
        }
    }

    @media (max-width: 576px) {
        .video-container, .image-container {
            height: 180px;
        }
    }
</style>

<div class="video-player-component">
    @if(!empty($title))
    <div class="section-heading mb-4">
        <div class="w-100 text-center">
            <h2 class="mb-2 text-center">{{ $title }}</h2>
            <p>Customer experiences and product demonstrations</p>
        </div>
    </div>
    @endif

    <div class="row">
        @php $hasReels = false; @endphp
        @foreach($videos as $reel)
            @if($reel->status == 1)
                @php $hasReels = true; @endphp
                @if($reel->is_video)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="video-card">
                        <div class="video-container" style="height: 250px; width: 100%; border-radius: 12px; overflow: hidden; position: relative; background: #000;">
                            <div class="file-type-badge" style="position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.7); color: white; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 500; text-transform: uppercase;">VIDEO</div>
                            <video class="video-player" style="height: 100%; width: 100%; object-fit: cover; transition: transform 0.3s ease;"
                                   preload="metadata" muted>
                                <source src="{{ $reel->path }}" type="video/{{ $reel->file_extension }}">
                            </video>
                            <div class="video-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: opacity 0.3s ease;">
                                <i class='bx bx-play-circle' style="font-size: 3rem; color: white;"></i>
                            </div>
                            <div class="video-duration" style="position: absolute; bottom: 10px; right: 10px; background: rgba(0,0,0,0.7); color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">
                                <span class="duration-text">--:--</span>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="image-card">
                        <div class="image-container">
                            <img src="{{ getImageWithFallback($reel->path) }}" alt="Customer Review" class="customer-image">
                            <div class="file-type-badge">IMAGE</div>
                        </div>
                    </div>
                </div>
                @endif
            @endif
        @endforeach

        @if(!$hasReels)
        <div class="col-12 text-center">
            <div class="no-reels-message">
                <i class="fa fa-video-camera fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No customer reels available yet</h5>
                <p class="text-muted">Be the first to share your experience!</p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Video Modal -->
<div class="modal fade video-modal" id="videoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <video id="modalVideo" controls>
                    <source src="" type="">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Video Player Functionality
    $('.video-container').each(function() {
        const container = $(this);
        const video = container.find('.video-player')[0];
        const durationSpan = container.find('.duration-text');
        const overlay = container.find('.video-overlay');

        // Add loading state
        overlay.html('<div class="video-loading" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 1.5rem;"><i class="bx bx-loader-alt bx-spin"></i></div>');

        // Load video duration
        if (video) {
            video.addEventListener('loadedmetadata', function() {
                const duration = Math.floor(video.duration);
                const minutes = Math.floor(duration / 60);
                const seconds = duration % 60;
                durationSpan.text(`${minutes}:${seconds.toString().padStart(2, '0')}`);

                // Restore play button
                overlay.html('<i class="bx bx-play-circle" style="font-size: 3rem; color: white;"></i>');
            });

            video.addEventListener('error', function() {
                overlay.html('<i class="bx bx-error" style="font-size: 2rem; color: #dc3545;"></i>');
                durationSpan.text('Error');
            });
        }

        // Add click handler for video play in popup
        overlay.on('click', function() {
            const videoSrc = $(this).siblings('.video-player').find('source').attr('src');
            const videoExtension = $(this).siblings('.video-player').find('source').attr('type').split('/')[1];

            // Set modal video source
            const modalVideo = $('#modalVideo')[0];
            const modalVideoSource = modalVideo.querySelector('source');
            modalVideoSource.src = videoSrc;
            modalVideoSource.type = `video/${videoExtension}`;

            // Load and show modal
            modalVideo.load();
            $('#videoModal').modal('show');
        });
    });

    // Handle modal close - pause and reset video
    $('#videoModal').on('hidden.bs.modal', function() {
        const modalVideo = $('#modalVideo')[0];
        modalVideo.pause();
        modalVideo.currentTime = 0;
    });

    // Auto-play video when modal opens
    $('#videoModal').on('shown.bs.modal', function() {
        const modalVideo = $('#modalVideo')[0];
        modalVideo.play().catch(function(error) {
            console.log('Auto-play prevented:', error);
        });
    });
});
</script>
@endpush
