@extends('CRM.Layout.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row card-header pb-3">
                <div class="col-md-8">
                    <h5 class="mb-0">Slider Listing</h5>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('slider.create') }}" class="btn-sm btn btn-outline-primary">
                        <i class="bx bx-plus"></i> Add New
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div id='message'></div>
                    @foreach ($sliders as $type => $group)
                        <div class="col-12 mb-4">
                            <div class="card shadow-sm border-1">
                                <div class="card-header d-flex align-items-center justify-content-between pt-2 pb-2">
                                    <div class="d-flex align-items-center">
                                        Slider Type :
                                        &nbsp;<span class=" fs-5">{{ ucwords(str_replace('_', ' ', $type)) }}</span>
                                        {{-- / <span class="badge text-info ms-3">{{ count($group) }} sliders</span> --}}
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm mb-0">
                                            <thead class="">
                                                <tr>
                                                    <th scope="col">Image</th>
                                                    <th scope="col">Title</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">URL</th>
                                                    <th scope="col" class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($group as $slider)
                                                    <tr>
                                                        <td><img src="{{ $slider->slider_image }}"
                                                                style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;"
                                                                class="border"></td>
                                                        <td>{{ $slider->title }}</td>
                                                        <td><a href="javascript:void(0);" id="{{ $slider->id }}"
                                                                data-status="{{ $slider->status }}"
                                                                class="toggle-status-btn badge {{ $slider->status ? 'bg-success' : 'bg-secondary' }}">{{ $slider->status ? 'Active' : 'Inactive' }}</a>
                                                        </td>
                                                        <td><a
                                                                href="{{ $slider->url }}"target="_blank">{{ $slider->url }}</a>
                                                        </td>
                                                        <td class="text-end">

                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                                    type="button" id="dropdownMenu{{ $slider->id }}"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="bx bx-cog"></i>
                                                                </button>
                                                                <ul class="dropdown-menu"
                                                                    aria-labelledby="dropdownMenu{{ $slider->id }}">
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                            href="{{ url('crm/slider') }}/{{ $slider->id }}/edit">
                                                                            <i class="bx bx-edit"></i> Edit
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item text-danger delete-slider-btn"
                                                                            href="javascript:void(0);" id="remove-slider-{{$slider->id}}" data-id="{{ $slider->id }}"><i
                                                                                class="bx bx-trash"></i> Delete</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @push('script')
        <script>
            $(document).ready(function() {
                // Status toggle
                $(document).on('click', '.toggle-status-btn', function() {
                    var btn = $(this);
                    var sliderId = btn.attr('id');
                    var newStatus = btn.data('status') == 1 ? 0 : 1;
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
                    $.ajax({
                        url: '/crm/slider/' + sliderId + '/status',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: newStatus
                        },
                        dataType: 'JSON',
                        success: function(res) {
                            btn.prop('disabled', false);
                            if (res.success || res.status) {
                                if (newStatus == 1) {
                                    btn.removeClass('bg-secondary').addClass('bg-success').text(
                                        'Active').data('status', 1);
                                } else {
                                    btn.removeClass('bg-success').addClass('bg-secondary').text(
                                        'Inactive').data('status', 0);
                                }
                                alertMsg(true, 'Status updated!', 2000);
                            } else {
                                alertMsg(false, 'Failed to update status.', 2000);
                            }
                        },
                        error: function() {
                            btn.prop('disabled', false);
                            alertMsg(false, 'Error updating status.', 2000);
                        }
                    });
                });

                // Delete slider
                $(document).on('click', '.delete-slider-btn', function(e) {
                    e.preventDefault();
                    var btn = $(this);
                    var sliderId = btn.data('id');
                    if (confirm('Are you sure you want to delete this slider? This action cannot be undone.')) {
                        $.ajax({
                            url: '/crm/slider/' + sliderId,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: 'JSON',
                            beforeSend: function() {
                                btn.prop('disabled', true).html(
                                    '<span class="spinner-border spinner-border-sm"></span>');
                            },
                            success: function(response) {
                                if (response.success || response.status) {
                                    alertMsg(true, response.msg || 'Slider deleted', 3000);
                                    btn.closest('tr').fadeOut(300, function() {
                                        $(this).remove();
                                    });
                                } else {
                                    alertMsg(false, response.msg || 'Failed to delete', 3000);
                                }
                            },
                            error: function() {
                                alertMsg(false, 'Something went wrong!', 3000);
                            },
                            complete: function() {
                                btn.prop('disabled', false).html(
                                    '<i class="bx bx-trash"></i> Delete');
                            }
                        });
                    }
                });

                // Initialize Bootstrap dropdowns
                $(document).ready(function() {
                    console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
                    
                    // Check if Bootstrap is available
                    if (typeof bootstrap !== 'undefined') {
                        console.log('Initializing Bootstrap dropdowns...');
                        // Initialize all dropdowns
                        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
                        console.log('Found dropdown elements:', dropdownElementList.length);
                        dropdownElementList.forEach(function(dropdownToggleEl) {
                            try {
                                new bootstrap.Dropdown(dropdownToggleEl);
                                console.log('Dropdown initialized for:', dropdownToggleEl.id);
                            } catch (error) {
                                console.error('Error initializing dropdown:', error);
                            }
                        });
                    } else {
                        console.log('Bootstrap not available, using fallback...');
                        // Fallback for dropdown functionality if Bootstrap is not available
                        $(document).on('click', '.dropdown-toggle', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            console.log('Dropdown toggle clicked');
                            var $dropdown = $(this).closest('.dropdown');
                            var $menu = $dropdown.find('.dropdown-menu');
                            
                            // Close other dropdowns
                            $('.dropdown-menu').not($menu).removeClass('show');
                            $('.dropdown').not($dropdown).removeClass('show');
                            
                            // Toggle current dropdown
                            $dropdown.toggleClass('show');
                            $menu.toggleClass('show');
                            console.log('Dropdown toggled, show class:', $menu.hasClass('show'));
                        });
                        
                        // Close dropdowns when clicking outside
                        $(document).on('click', function(e) {
                            if (!$(e.target).closest('.dropdown').length) {
                                $('.dropdown-menu').removeClass('show');
                                $('.dropdown').removeClass('show');
                            }
                        });
                    }
                });
            });

            function deleteSlider(sliderId) {
                var self = $('#remove-slider-'+sliderId);
                if (confirm('Are you sure you want to delete this slider? This action cannot be undone.')) {
                    $.ajax({
                        url: "{{ url('crm/slider') }}/" + sliderId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'JSON',
                        beforeSend: function() {
                            self.prop('disabled', true).html(
                                '<span class="spinner-border spinner-border-sm"></span>');
                        },
                        success: function(response) {
                            if (response.status) {
                                alertMsg(true, response.msg, 3000);
                                self.closest('tr').fadeOut(300, function() {
                                    $(this).remove();
                                });
                            } else {
                                alertMsg(false, response.msg, 3000);
                            }
                        },
                        error: function() {
                            alertMsg(false, 'Something went wrong!', 3000);
                        },
                        complete: function() {
                            self.prop('disabled', false).html('<i class="bx bx-trash"></i> Delete');
                        }
                    });
                }
            }
        </script>
    @endpush
    @push('style')
        <style>
            .dropdown-menu {
                position: absolute;
                top: 100%;
                left: 0;
                z-index: 1000;
                display: none;
                float: left;
                min-width: 10rem;
                padding: 0.5rem 0;
                margin: 0.125rem 0 0;
                font-size: 0.875rem;
                color: #212529;
                text-align: left;
                list-style: none;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid rgba(0, 0, 0, 0.15);
                border-radius: 0.375rem;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
            }

            .dropdown-menu.show {
                display: block;
            }

            .dropdown-item {
                display: block;
                width: 100%;
                padding: 0.25rem 1rem;
                clear: both;
                font-weight: 400;
                color: #212529;
                text-align: inherit;
                text-decoration: none;
                white-space: nowrap;
                background-color: transparent;
                border: 0;
            }

            .dropdown-item:hover {
                color: #1e2125;
                background-color: #e9ecef;
            }

            .dropdown-divider {
                height: 0;
                margin: 0.5rem 0;
                overflow: hidden;
                border-top: 1px solid #e9ecef;
            }

            .dropdown {
                position: relative;
            }
        </style>


    @endpush
@endsection
