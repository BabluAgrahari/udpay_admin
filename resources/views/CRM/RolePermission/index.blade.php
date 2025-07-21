@extends('CRM.Layout.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row card-header pb-3">
                <div class="col-md-10">
                    <h5>Role & Permission</h5>
                </div>
                <div class="col-md-2 text-right">
                    <a href="javascript:void(0);" class="add btn-sm btn btn-outline-primary">
                        <i class='bx bxs-plus-circle'></i>&nbsp;Add Role
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="accordion" id="roleAccordion">
                    @foreach ($roles as $role)
                        <div class="accordion-item">
                                <div class="accordion-header d-flex justify-content-between align-items-center" id="heading{{ $role->id }}">
                                    <button class="accordion-button" type="button" 
                                       >
                                        Role: {{ ucwords(str_replace('_', ' ', $role->role)) }}
                                        <a href="javascript:void(0);" class="edit text-primary ms-2" data-id="{{ $role->id }}"><i class='bx bxs-edit'></i> Edit</a>
                                    </button>
                                    
                                </div>

                            <div id="collapse{{ $role->id }}" class="accordion-collapse collapse show"
                                aria-labelledby="heading{{ $role->id }}">
                                <form id="roleForm_{{$role->_id}}" method="post"
                                    action="{{ url('crm/save-module-permission') }}" method="post">
                                    @csrf
                                   
                                    <input type="hidden" name="role_id" value="{{ $role->_id }}">
                                    <div class="accordion-body">
                                        <strong>Permissions:</strong>
                                        <div class="row">
                                            @foreach (config('global.modules') as $module)
                                                <div class="col-md-3 mb-1">
                                                    <input type="checkbox" class="form-check-input" name="permissions[]"
                                                        value="{{ $module }}"
                                                        {{ in_array($module, $role->permissions) ? 'checked' : '' }}>
                                                    {{ ucwords(str_replace('_', ' ', $module)) }}
                                                </div>
                                            @endforeach
                                        </div>

                                        <hr />
                                        <div class="text-right">

                                            <button type="submit" class="btn btn-outline-success btn-sm" id="saveBtn_{{$role->_id}}">
                                                <i class='bx bx-save'></i> Save
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roleModalLabel">Add Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="roleForm" method="post" action="{{ url('crm/role-permission') }}">
                    @csrf
                    <div id="put"></div>
                    <div class="modal-body">
                        <div id="message"></div>
                        <div class="mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <input type="text" name="role" id="role" class="form-control"
                                placeholder="Enter role name" required>
                            <span class="text-danger error" id="role_error"></span>
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
@endsection

@push('script')
    <script>
        $(document).on('click', '.add', function() {
            $('#roleModal').modal('show');
            $('#roleForm').attr('action', '{{ url('crm/role-permission') }}');
            $('#put').html('');
            $('#roleModalLabel').text('Add Role');
            $('#saveBtn').html('<i class="bx bx-save"></i> Save');
            $('#role').val('');
            $('.error').html('');
            $('#message').html('');
        });

        $(document).on('click', '.edit', function() {
            var id = $(this).data('id');
            var url = "{{ url('crm/role-permission') }}/" + id + '/edit';
            var updateUrl = "{{ url('crm/role-permission') }}/" + id;
            var selector = $(this);
            $.ajax({
                url: url,
                dataType: "JSON",
                type: "GET",
                beforeSend: function() {
                    $('#roleModalLabel').text('Edit Role');
                    $('#roleModal').modal('show');
                    selector.html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...'
                    ).attr('disabled', true);
                },
                success: function(res) {
                    if (res.status) {
                        $('#role').val(res.record.role);
                        $('#roleForm').attr('action', updateUrl);
                        $('#put').html('<input type="hidden" name="_method" value="PUT">');
                        $('#saveBtn').html('<i class="bx bx-save"></i> Update').removeAttr('disabled');
                        $('.error').html('');
                        $('#message').html('');
                    } else {
                        alertMsg(res.status, res.msg, 3000);
                    }
                    selector.html('<i class="bx bxs-edit"></i> Edit').removeAttr('disabled');
                },
                error: function(xhr) {
                    alertMsg(false, 'An error occurred while loading the role.', 3000);
                    selector.html('<i class="bx bxs-edit"></i> Edit').removeAttr('disabled');
                }
            });
        });

        $('#roleForm').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $('.error').html('');
            $('#message').html('');
            var actionUrl = $('#roleForm').attr('action');
            $.ajax({
                type: "POST",
                url: actionUrl,
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveBtn').html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Saving...'
                    ).attr('disabled', true);
                },
                success: function(res) {
                    $('#saveBtn').html('<i class="bx bx-save"></i> Save').removeAttr('disabled');
                    if (res.status) {
                        alertMsg(res.status, res.msg, 3000);
                        setTimeout(() => {
                            $('#roleModal').modal('hide');
                            location.reload();
                        }, 1000);
                    } else {
                        alertMsg(res.status, res.msg, 3000);
                    }
                },
                error: function(xhr) {
                    $('#saveBtn').html('<i class="bx bx-save"></i> Save').removeAttr('disabled');
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors || xhr.responseJSON.validation;
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

        // AJAX for saving permissions per role
        $(document).on('submit', 'form[id^="roleForm_"]', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            var formData = form.serialize();
            btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Saving...').attr('disabled', true);
            form.find('.error').html('');
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: formData,
                dataType: 'json',
                success: function(res) {
                    btn.html('<i class="bx bx-save"></i> Save').removeAttr('disabled');
                    alertMsgSwal(res.status, res.msg, 3000);
                },
                error: function(xhr) {
                    btn.html('<i class="bx bx-save"></i> Save').removeAttr('disabled');
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors || xhr.responseJSON.validation;
                        $.each(errors, (field, messages) => {
                            form.find(`#${field}_error`).html(messages[0]);
                        });
                    } else if (xhr.status === 400) {
                        alertMsgSwal(false, xhr.responseJSON.msg, 3000);
                    } else {
                        alertMsgSwal(false, 'An error occurred while saving permissions.', 3000);
                    }
                }
            });
        });
    </script>
@endpush
