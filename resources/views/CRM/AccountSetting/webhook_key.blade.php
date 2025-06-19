@extends('CRM.Layout.layout')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div id="message"></div>
    <div class="col-xl-12">
        <div class="nav-align-top nav-tabs-shadow mb-6">
            <ul class="nav nav-tabs nav-fill" role="tablist">
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-home" aria-controls="navs-justified-home" aria-selected="true">
                        <span class="d-none d-sm-block"> <i class='bx bxs-cog bx-sm me-1_5 align-text-bottom'></i> Webhook/IP Address


                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-block"> <i class='bx bxs-key bx-sm me-1_5 align-text-bottom'></i> Api Key</span>
                    </button>
                </li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
                    <div class="row">
                        <div class="m-auto col-md-6">
                            <form id="saveWebhook" method="post" action="{{url('crm/save-webhook')}}">
                                @csrf
                                <div class="mb-4">
                                    <label>IP Address</label>
                                    <input type="text" class="form-control" id="ip_address" name="ip_address" value="{{$record->ip_address??''}}" placeholder="Enter Your IP Address">
                                    <span class="text-danger" id="ip_address_msg"></span>
                                </div>

                                <div class="mb-4">
                                    <label>Webhook URL (PG)</label>
                                    <input type="url" class="form-control" id="webhook_url" name="webhook_url" value="{{$record->webhook_url??''}}" placeholder="Enter Webhook URL">
                                    <span class="text-danger" id="webhook_url_msg"></span>
                                </div>

                                <div class="mb-4">
                                    <label>Webhook URL (Payment Link)</label>
                                    <input type="url" class="form-control" id="paymetn_link_webhook_url" name="paymetn_link_webhook_url" value="{{$record->paymetn_link_webhook_url??''}}" placeholder="Enter Payment Link Webhook URL">
                                    <span class="text-danger" id="paymetn_link_webhook_url_msg"></span>
                                </div>

                                <div class="mb-4 text-center">
                                    <button type="submit" class="btn btn-outline-primary" {{!isVerified()?'disabled':''}} id="saveBtn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
                    <div class="row">
                        <div class="m-auto col-md-6">
                            <form id="resetToken" method="post" action="{{url('crm/reset-key')}}">
                                @csrf
                                <div class="mb-4">
                                    <label>Client Id</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="client_id" readonly name="client_id" value="{{!isVerified()?str_repeat('x', strlen($user->client_id)):$user->client_id??''}}" placeholder="Enter Client Id">
                                        <span id="basic-default-password2" class="input-group-text cursor-pointer"><i class='bx bx-copy'></i></span>
                                    </div>
                                    <span><b class="text-danger">*</b> <small>The client key remains the same once generated and will not be reset.</small></span><br>
                                    <span class="text-danger" id="client_id_msg"></span>
                                </div>
                                <div class="mb-4">
                                    <label>Secret Key</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="secret_key" readonly name="secret_key" value="{{!isVerified()?str_repeat('x', strlen($user->secret_key)):str_repeat('x', strlen($user->secret_key))}}" placeholder="Enter Secret Key">
                                        <span id="basic-default-password2" class="input-group-text cursor-pointer"><i class='bx bx-copy'></i></span>
                                    </div>
                                    <span><b class="text-danger">*</b> <small>When a new secret key is generated, the old key will automatically expire.</small></span><br>
                                    <span class="text-danger" id="secret_key_msg"></span>
                                </div>
                                <div class="mb-4">
                                    <label>Encryption Key</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="encryption_key" readonly name="encryption_key" value="{{!isVerified()?str_repeat('x', strlen($user->encryption_key)):str_repeat('x', strlen($user->encryption_key))}}" placeholder="Enter Encryption Key">
                                        <span id="basic-default-password2" class="input-group-text cursor-pointer"><i class='bx bx-copy'></i></span>
                                    </div>
                                    <span><b class="text-danger">*</b> <small>When a new Encryption key is generated, the old key will automatically expire.</small></span><br>
                                    <span class="text-danger" id="secret_key_msg"></span>
                                </div>
                                <div class="mb-4 text-center">
                                    <button type="submit" class="btn btn-outline-primary" {{!isVerified()?'disabled':''}} id="resetBtn">Reset key</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $('form#saveWebhook').submit(function(e) {
        e.preventDefault();
        formData = new FormData(this);
        let url = $(this).attr('action');
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#saveBtn').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Saving...`).attr('disabled', true);
            },
            success: function(res) {

                $('#saveBtn').html('Submit').removeAttr('disabled');

                /*Start Validation Error Message*/
                $('span.text-danger').html('');
                if (res.validation) {
                    $.each(res.validation, (index, msg) => {
                        $(`#${index}_msg`).html(`${msg}`);
                    })
                    return false;
                }
                /*End Validation Error Message*/

                if (res.status) {
                    $('#ip_address').val(res.ip_address);
                    $('#webhook_url').val(res.webhook_url);
                }
                /*Start Status message*/
                alertMsg(res.status, res.msg, 3000);
                /*End Status message*/
            }
        });
    });


    $('form#resetToken').submit(function(e) {
        e.preventDefault();
        formData = new FormData(this);
        let url = $(this).attr('action');
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#resetBtn').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Saving...`).attr('disabled', true);
            },
            success: function(res) {

                $('#resetBtn').html('Reset Key').removeAttr('disabled');

                /*Start Validation Error Message*/
                $('span.text-danger').html('');
                if (res.validation) {
                    $.each(res.validation, (index, msg) => {
                        $(`#${index}_msg`).html(`${msg}`);
                    })
                    return false;
                }
                /*End Validation Error Message*/

                if (res.status) {
                    $('#client_id').val(res.client_id);
                    $('#secret_key').val(res.secret_key);
                    $('#encryption_key').val(res.encryption_key);
                }
                /*Start Status message*/
                alertMsg(res.status, res.msg, 3000);
                /*End Status message*/
            }
        });
    });
</script>
@endpush
@endsection