@extends('CRM.Layout.layout')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="row card-header pb-0">
            <div class="col-md-10">
                <h5>Product Reviews</h5>
            </div>
            <div class="col-md-2 text-right">
                {{-- <button type="button" class="btn btn-outline-info btn-sm" onclick="loadStatistics()">
                    <i class="bx bx-stats"></i>&nbsp;Statistics
                </button> --}}
            </div>
        </div>

        <div class="card-body">
            <!-- Statistics Cards -->
            <div class="row mb-4" id="statisticsCards">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0" id="totalReviews">-</h4>
                                    <p class="mb-0">Total Reviews</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bx bx-message-square-detail" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0" id="activeReviews">-</h4>
                                    <p class="mb-0">Active Reviews</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bx bx-check-circle" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0" id="inactiveReviews">-</h4>
                                    <p class="mb-0">Inactive Reviews</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bx bx-pause-circle" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0" id="averageRating">-</h4>
                                    <p class="mb-0">Average Rating</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bx bx-star" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rating Distribution -->
            <div class="row mb-4" id="ratingDistribution">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Rating Distribution</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-2 col-4 mb-2">
                                    <div class="border rounded p-2">
                                        <h5 class="text-warning mb-1">5★</h5>
                                        <p class="mb-0" id="fiveStar">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2 col-4 mb-2">
                                    <div class="border rounded p-2">
                                        <h5 class="text-warning mb-1">4★</h5>
                                        <p class="mb-0" id="fourStar">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2 col-4 mb-2">
                                    <div class="border rounded p-2">
                                        <h5 class="text-warning mb-1">3★</h5>
                                        <p class="mb-0" id="threeStar">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2 col-4 mb-2">
                                    <div class="border rounded p-2">
                                        <h5 class="text-warning mb-1">2★</h5>
                                        <p class="mb-0" id="twoStar">-</p>
                                    </div>
                                </div>
                                <div class="col-md-2 col-4 mb-2">
                                    <div class="border rounded p-2">
                                        <h5 class="text-warning mb-1">1★</h5>
                                        <p class="mb-0" id="oneStar">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ url('crm/reviews') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search reviews, products, customers..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="product_id" class="form-select form-select-sm">
                            <option value="">All Products</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->product_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="rating" class="form-select form-select-sm">
                            <option value="">All Ratings</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
                        <a href="{{ url('crm/reviews') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive mt-3">
                <table id="reviewsTable" class="table table-hover w-100 text-nowrap">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Review Details Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Review Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reviewModalBody">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Modal -->
<div class="modal fade" id="statisticsModal" tabindex="-1" aria-labelledby="statisticsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statisticsModalLabel">Review Statistics</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="statisticsModalBody">
                <!-- Statistics will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
$(document).ready(function() {
    // Load statistics on page load
    loadStatisticsOnPage();

    var table = $('#reviewsTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        pagingType: 'simple_numbers',
        ajax: {
            url: '{{ url('crm/reviews/datatable-list') }}',
            data: function (d) {
                d.search = $('input[name="search"]').val();
                d.status = $('select[name="status"]').val();
                d.product_id = $('select[name="product_id"]').val();
                d.rating = $('select[name="rating"]').val();
            }
        },
        columns: [
            { 
                data: 'product_name', 
                name: 'product_name',
                render: function(data, type, row) {
                    return `<div class="d-flex align-items-center">
                        <img src="${row.product_image}" alt="${data}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                        <div>
                            <strong>${data}</strong>
                        </div>
                    </div>`;
                }
            },
            { 
                data: 'user_name', 
                name: 'user_name',
                render: function(data, type, row) {
                    return `<div>
                        <strong>${data}</strong>
                        <br>
                        <small class="text-muted">${row.user_email}</small>
                    </div>`;
                }
            },
            { 
                data: 'rating', 
                name: 'rating',
                render: function(data, type, row) {
                    let stars = '';
                    for (let i = 1; i <= 5; i++) {
                        if (i <= data) {
                            stars += '<i class="bx bxs-star text-warning"></i>';
                        } else {
                            stars += '<i class="bx bx-star text-muted"></i>';
                        }
                    }
                    return `<div class="d-flex align-items-center">
                        ${stars}
                        <span class="ms-1">(${data})</span>
                    </div>`;
                }
            },
            { 
                data: 'review', 
                name: 'review',
                render: function(data, type, row) {
                    return `<div class="text-truncate" style="max-width: 200px;" title="${data}">
                        ${data.length > 100 ? data.substring(0, 100) + '...' : data}
                    </div>`;
                }
            },
            { 
                data: 'status', 
                name: 'status',
                render: function(data, type, row) {
                    return `<span class="badge bg-${data == '1' ? 'success' : 'danger'}">
                        ${data == '1' ? 'Active' : 'Inactive'}
                    </span>`;
                }
            },
            { data: 'created_at', name: 'created_at' },
            { 
                data: 'id', 
                name: 'actions', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    return `<div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-icon btn-outline-info" onclick="viewReview(${data})" title="View Details">
                            <i class="bx bx-show"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-icon btn-outline-${row.status == '1' ? 'warning' : 'success'}" 
                                onclick="toggleStatus(${data}, '${row.status == '1' ? '0' : '1'}')" 
                                title="${row.status == '1' ? 'Deactivate' : 'Activate'}">
                            <i class="bx bx-${row.status == '1' ? 'pause' : 'play'}"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger" onclick="deleteReview(${data})" title="Delete">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>`;
                }
            }
        ],
        order: [[5, 'desc']], // Sort by date descending
        lengthMenu: [10, 25, 50, 100, 500],
        pageLength: 10,
        scrollX: false,
    });

    // Filter form
    $('form[action="{{ url('crm/reviews') }}"]').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
});

// Load statistics on page load
function loadStatisticsOnPage() {
    $.ajax({
        url: '{{ url("crm/reviews-statistics") }}',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                updateStatisticsDisplay(response.stats);
            }
        },
        error: function() {
            console.log('Error loading statistics');
        }
    });
}

// Update statistics display
function updateStatisticsDisplay(stats) {
    $('#totalReviews').text(stats.total_reviews);
    $('#activeReviews').text(stats.active_reviews);
    $('#inactiveReviews').text(stats.inactive_reviews);
    $('#averageRating').text(stats.average_rating);
    
    $('#fiveStar').text(stats.rating_distribution['5_star']);
    $('#fourStar').text(stats.rating_distribution['4_star']);
    $('#threeStar').text(stats.rating_distribution['3_star']);
    $('#twoStar').text(stats.rating_distribution['2_star']);
    $('#oneStar').text(stats.rating_distribution['1_star']);
}

// View review details
function viewReview(reviewId) {
    $.ajax({
        url: '{{ url("crm/reviews") }}/' + reviewId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                const review = response.review;
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= review.rating) {
                        stars += '<i class="bx bxs-star text-warning"></i>';
                    } else {
                        stars += '<i class="bx bx-star text-muted"></i>';
                    }
                }

                $('#reviewModalBody').html(`
                    <div class="row">
                        <div class="col-md-4">
                            <img src="${review.product_image ? review.product_image : '{{ asset("front_assets/images/no_image.jpeg") }}'}" 
                                 alt="${review.product_name}" class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <h6>Product: ${review.product_name}</h6>
                            <p><strong>Customer:</strong> ${review.user_name}</p>
                            <p><strong>Email:</strong> ${review.user_email}</p>
                            <p><strong>Rating:</strong> ${stars} (${review.rating}/5)</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-${review.status == '1' ? 'success' : 'danger'}">
                                    ${review.status == '1' ? 'Active' : 'Inactive'}
                                </span>
                            </p>
                            <p><strong>Created:</strong> ${review.created_at}</p>
                            <p><strong>Updated:</strong> ${review.updated_at}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Review:</h6>
                            <div class="border rounded p-3 bg-light">
                                ${review.review}
                            </div>
                        </div>
                    </div>
                `);
                $('#reviewModal').modal('show');
            } else {
                showSnackbar(response.message, 'error');
            }
        },
        error: function() {
            showSnackbar('Error loading review details', 'error');
        }
    });
}

// Toggle review status
function toggleStatus(reviewId, newStatus) {
    const statusText = newStatus == '1' ? 'activate' : 'deactivate';
    
    if (confirm(`Are you sure you want to ${statusText} this review?`)) {
        $.ajax({
            url: '{{ url("crm/reviews") }}/' + reviewId + '/status',
            type: 'PUT',
            data: {
                status: newStatus,
                '_token': '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    showSnackbar(response.msg, 'success');
                    $('#reviewsTable').DataTable().ajax.reload();
                    // Refresh statistics after status change
                    loadStatisticsOnPage();
                } else {
                    showSnackbar(response.msg, 'error');
                }
            },
            error: function() {
                showSnackbar('Error updating review status', 'error');
            }
        });
    }
}

// Delete review
function deleteReview(reviewId) {
    if (confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
        $.ajax({
            url: '{{ url("crm/reviews") }}/' + reviewId,
            type: 'DELETE',
            data: {
                '_token': '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    showSnackbar(response.msg, 'success');
                    $('#reviewsTable').DataTable().ajax.reload();
                    // Refresh statistics after deletion
                    loadStatisticsOnPage();
                } else {
                    showSnackbar(response.msg, 'error');
                }
            },
            error: function() {
                showSnackbar('Error deleting review', 'error');
            }
        });
    }
}

// Load statistics
function loadStatistics() {
    $.ajax({
        url: '{{ url("crm/reviews-statistics") }}',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                const stats = response.stats;
                $('#statisticsModalBody').html(`
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h3>${stats.total_reviews}</h3>
                                    <p>Total Reviews</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h3>${stats.active_reviews}</h3>
                                    <p>Active Reviews</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h3>${stats.inactive_reviews}</h3>
                                    <p>Inactive Reviews</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h3>${stats.average_rating}</h3>
                                    <p>Average Rating</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6>Rating Distribution:</h6>
                            <div class="row">
                                <div class="col-md-2 text-center">
                                    <div class="border rounded p-2">
                                        <h5>5★</h5>
                                        <p class="mb-0">${stats.rating_distribution['5_star']}</p>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <div class="border rounded p-2">
                                        <h5>4★</h5>
                                        <p class="mb-0">${stats.rating_distribution['4_star']}</p>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <div class="border rounded p-2">
                                        <h5>3★</h5>
                                        <p class="mb-0">${stats.rating_distribution['3_star']}</p>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <div class="border rounded p-2">
                                        <h5>2★</h5>
                                        <p class="mb-0">${stats.rating_distribution['2_star']}</p>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <div class="border rounded p-2">
                                        <h5>1★</h5>
                                        <p class="mb-0">${stats.rating_distribution['1_star']}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                $('#statisticsModal').modal('show');
            } else {
                showSnackbar(response.message, 'error');
            }
        },
        error: function() {
            showSnackbar('Error loading statistics', 'error');
        }
    });
}
</script>
@endpush 