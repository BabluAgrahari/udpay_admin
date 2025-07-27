@extends('CRM.Layout.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row card-header">
                <div class="col-md-10">
                    <h5>Units</h5>
                </div>
                <div class="col-md-2 text-right">
                    <a href="javascript:void(0);" class="add btn-sm btn btn-outline-primary"><i
                            class='bx bxs-plus-circle'></i>&nbsp;Add</a>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ url('crm/units') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search unit..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ url('crm/units') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Unit Name</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($units as $index => $unit)
                                <tr>
                                    <td>{{ $index + 1 + ($units->currentPage() - 1) * $units->perPage() }}</td>
                                    <td>{{ $unit->unit }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input status-switch"
                                                id="status{{ $unit->_id }}" data-id="{{ $unit->_id }}"
                                                {{ $unit->status ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status{{ $unit->_id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $unit->dFormat($unit->created) }}</td>
                                    <td>
                                        <a href="javascript:void(0);" _id="{{ $unit->_id }}" data-bs-toggle="tooltip"
                                            data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true"
                                            title="Edit" class="edit text-primary"><i class='bx bxs-edit'></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No units found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $units->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection


@push('script')
    <div class="modal fade" id="createUnitModal" tabindex="-1" aria-labelledby="createUnitModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUnitModalLabel">Create Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="save" method="post" action="{{ url('crm/units') }}" enctype="multipart/form-data">
                    @csrf
                    <div id="put"></div>
                    <div class="modal-body">
                        <div id="message"></div>

                        <div class="mb-3">
                            <label class="form-label">Unit Name <span class="text-danger">*</span></label>
                            <input type="text" name="unit" id="unit" class="form-control"
                                placeholder="Enter unit name" required>
                            <span class="text-danger error" id="unit_error"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="status" name="status" value="1"
                                    checked>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                            <span class="text-danger error" id="status_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="saveBtn">
                            <i class='bx bx-save'></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        $(document).on('click', '.add', function() {
            $('#createUnitModal').modal('show');
            $('#save').attr('action', '{{ url('crm/units') }}');
            $('#put').html('');
            $('#createUnitModalLabel').text('Create Unit');
            $('#saveBtn').html('<i class="bx bx-save"></i> Save');
        });


        $('#save').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $('.error').html('');
            $('#message').html('');

            $.ajax({
                type: "POST",
                url: $('#save').attr('action'),
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveBtn').html(
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Saving...`
                    ).attr('disabled', true);
                },
                success: function(res) {
                    $('#saveBtn').html('<i class="bx bx-save"></i> Save').removeAttr(
                        'disabled');

                    if (res.status) {
                        alertMsg(res.status, res.msg, 3000);
                        setTimeout(() => {
                            $('#createUnitModal').modal('hide');
                            location.reload();
                        }, 1000);
                    } else {
                        alertMsg(res.status, res.msg, 3000);
                    }
                },
                error: function(xhr) {
                    $('#saveBtn').html('<i class="bx bx-save"></i> Save').removeAttr(
                        'disabled');

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.validation;
                        $.each(errors, (field, messages) => {
                            $(`#${field}_error`).html(messages[0]);
                        });
                    } else if (xhr.status === 400) {
                        alertMsg(false, xhr.responseJSON.msg, 3000);
                    } else {
                        alertMsg(false, 'An error occurred while processing your request.', 3000);
                    }
                }
            });
        });

        $(document).on('click', '.edit', function(e) {
            var id = $(this).attr('_id');
            var url = "{{ url('crm/units') }}/" + id + '/edit';
            var selector = $(this);
            $.ajax({
                url: url,
                dataType: "JSON",
                type: "GET",
                beforeSend: function() {
                    selector.html(
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`
                    ).attr('disabled', true);
                },
                success: function(res) {
                    if (res.status) {
                        $('#unit').val(res.record.unit);
                        $('#status').prop('checked', res.record.status);
                        var url_ = "{{ url('crm/units') }}/" + res.record._id;
                        $('#save').attr('action', url_);
                        $('#put').html(
                            `<input type="hidden" id="putField" name="_method" value="PUT">`);
                        $('#saveBtn').html('<i class="bx bx-save"></i> Update');
                        selector.html('<i class="bx bxs-edit"></i>').removeAttr('disabled');
                        $('#createUnitModal').modal('show');
                    } else {
                        alertMsg(res.status, res.msg, 3000);
                    }
                }
            })
        });

        $('.status-switch').change(function() {
            const id = $(this).data('id');
            const status = $(this).prop('checked');

            $.ajax({
                url: '{{ url('crm/units/update-status') }}',
                method: 'POST',
                data: {
                    id: id,
                    status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alertMsg(response.status, response.msg, 3000);
                },
                error: function(xhr) {
                    alertMsg(false, xhr.responseJSON.msg ||
                        'An error occurred while updating status.', 3000);
                }
            });
        });
    </script>
@endpush
