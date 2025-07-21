@extends('CRM.Layout.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row card-header pb-0">
                <div class="col-md-10">
                    <h5>Categories</h5>
                </div>
                <div class="col-md-2 text-right">
                    <a href="{{ url('crm/categories/create') }}" class="btn btn-outline-primary btn-sm">
                        <i class='bx bx-plus'></i>&nbsp;Add New
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div id="message"></div>

                <!-- Filter Section -->
                <form id="filterForm" action="javascript:void(0);" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Search</label>
                                <input type="text" name="search" id="search" class="form-control form-control-sm"
                                    placeholder="Search by name or description">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="status" id="status" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Parent Category</label>
                                <select name="parent_id" id="parent_id" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    @foreach ($parentCategories as $parent)
                                        <option value="{{ $parent->_id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                        Filter
                                    </button>
                                    <button type="button" id="resetBtn" class="btn btn-outline-secondary btn-sm">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Table Section -->
                <table id="categoriesTable" class="table table-hover table-sm w-100 text-nowrap">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Short</th>
                            <th>Parent</th>
                            <th>Status</th>
                            <th>Labels</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {

            var table = $('#categoriesTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                pagingType: 'simple_numbers',
                ajax: {
                    url: '{{ url('crm/categories/datatable-list') }}',
                    data: function(d) {
                        d.search = $('#search').val();
                        d.status = $('#status').val();
                        d.parent_id = $('#parent_id').val();
                    }
                },
                columns: [{
                        data: 'icon',
                        name: 'icon',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return data ?
                                `<img src="${data}" class="img-thumbnail" style="max-height:50px;">` :
                                '<span class="text-muted">No icon</span>';
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'short',
                        name: 'short',
                        render: function(data, type, row) {
                            return `<div class="input-group input-group-sm"><input type="text" class="form-control short-code" value="${data ?? ''}" data-id="${row._id}" placeholder="Enter short code"><button class="btn btn-outline-primary update-short" type="button" data-id="${row._id}"><i class='bx bx-save'></i></button></div>`;
                        }
                    },
                    {
                        data: 'parent_name',
                        name: 'parent_name'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            return `<div class="form-check form-switch"><input type="checkbox" class="form-check-input status-toggle" data-id="${row._id}" ${data ? 'checked' : ''}></div>`;
                        }
                    },
                    {
                        data: 'labels',
                        name: 'labels',
                        render: function(data) {
                            if (!data) return '';
                            return data.map(label =>
                                `<span class="badge bg-label-info">${label}</span>`).join(' ');
                        }
                    },
                    {
                        data: '_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<a href="${baseUrl}/crm/categories/${data}/edit" class="btn btn-sm btn-icon btn-outline-primary"><i class='bx bx-edit'></i></a> 
                <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-category" data-id="${data}"><i class='bx bx-trash'></i></button>`;
                        }
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                lengthMenu: [10, 25, 50, 100, 500],
                pageLength: 10,
                scrollX: false,
            });

            // Filter form
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });
            $('#resetBtn').on('click', function() {
                $('#filterForm')[0].reset();
                table.ajax.reload();
            });

            // Delegate status toggle, short code update, and delete actions
            $('#categoriesTable').on('change', '.status-toggle', function() {
                const categoryId = $(this).data('id');
                const status = $(this).prop('checked') ? 1 : 0;
                const $toggle = $(this);

                $.ajax({
                    url: "{{ url('crm/categories/update-status') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: categoryId,
                        status: status
                    },
                    beforeSend: function() {
                        $toggle.prop('disabled', true);
                    },
                    success: function(res) {
                        $toggle.prop('disabled', false);
                        alertMsg(res.status, res.msg, 3000);
                        if (!res.status) {
                            $toggle.prop('checked', !status);
                        }
                    },
                    error: function(xhr) {
                        $toggle.prop('disabled', false);
                        $toggle.prop('checked', !status);
                        if (xhr.status === 422) {
                            alertMsg(false, xhr.responseJSON.validation, 3000);
                        } else if (xhr.status === 400) {
                            alertMsg(false, xhr.responseJSON.msg, 3000);
                        } else {
                            alertMsg(false, xhr.responseJSON.msg ||
                                'An error occurred while processing your request.', 3000);
                        }
                    }
                });
            });
            $('#categoriesTable').on('click', '.update-short', function() {
                const categoryId = $(this).data('id');
                const $input = $(this).closest('.input-group').find('.short-code');
                const shortCode = $input.val();
                const $btn = $(this);

                $.ajax({
                    url: "{{ url('crm/categories/update-short') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: categoryId,
                        short: shortCode
                    },
                    beforeSend: function() {
                        $btn.prop('disabled', true);
                    },
                    success: function(res) {
                        $btn.prop('disabled', false);
                        alertMsg(res.status, res.msg, 3000);
                        if (!res.status) {
                            $input.val($input.data('original-value'));
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false);
                        if (xhr.status === 422) {
                            alertMsg(false, xhr.responseJSON.validation, 3000);
                        } else if (xhr.status === 400) {
                            alertMsg(false, xhr.responseJSON.msg, 3000);
                        } else {
                            alertMsg(false, xhr.responseJSON.msg ||
                                'An error occurred while processing your request.', 3000);
                        }
                    }
                });
            });
            $('#categoriesTable').on('click', '.delete-category', function() {
                const categoryId = $(this).data('id');
                const $row = $(this).closest('tr');

                if (confirm('Are you sure you want to delete this category?')) {
                    $.ajax({
                        url: "{{ url('crm/categories') }}/" + categoryId,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            $row.find('button').prop('disabled', true);
                        },
                        success: function(res) {
                            alertMsg(res.status, res.msg, 3000);
                            if (res.status) {
                                $row.fadeOut(400, function() {
                                    $(this).remove();
                                    if ($('tbody tr').length === 0) {
                                        $('tbody').html(
                                            '<tr><td colspan="7" class="text-center">No categories found.</td></tr>'
                                            );
                                    }
                                });
                            }
                        },
                        error: function(xhr) {
                            $row.find('button').prop('disabled', false);
                            if (xhr.status === 422) {
                                alertMsg(false, xhr.responseJSON.validation, 3000);
                            } else if (xhr.status === 400) {
                                alertMsg(false, xhr.responseJSON.msg, 3000);
                            } else {
                                alertMsg(false, xhr.responseJSON.msg ||
                                    'An error occurred while processing your request.', 3000
                                    );
                            }
                        }
                    });
                }
            });
        });
        var baseUrl = '{{ url('') }}';
    </script>
@endpush
