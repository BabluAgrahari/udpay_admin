<form id="saveBank" method="post" action="{{url('crm/save-bank')}}">
    @csrf
    <div class="row">
        <div class="mb-4 col-md-4">
            <label>Account Holder Name<span class="text-danger"><b>*</b></span></label>
            <input type="text" class="form-control" id="holder_name" name="bank_details[holder_name]" value="{{$record->bank_details['holder_name']??''}}" placeholder="Enter Account Holder Name">
            <span class="msg text-danger" id="bank_details_holder_name_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Bank Name <span class="text-danger"><b>*</b></span></label>
            <input type="text" class="form-control" id="bank_name" name="bank_details[bank_name]" value="{{$record->bank_details['bank_name']??''}}" placeholder="Enter Bank Name">
            <span class="msg text-danger" id="bank_details_bank_name_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Account No <span class="text-danger"><b>*</b></span></label>
            <input type="text" class="form-control" id="account_no" name="bank_details[account_no]" value="{{$record->bank_details['account_no']??''}}" placeholder="Enter Account No">
            <span class="msg text-danger" id="bank_details_account_no_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>IFSC Code</label>
            <input type="text" class="form-control" id="ifsc_code" name="bank_details[ifsc_code]" value="{{$record->bank_details['ifsc_code']??''}}" placeholder="Enter IFSC Code">
            <span class="msg text-danger" id="bank_details_ifsc_code_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Account Type <span class="text-danger"><b>*</b></span></label>
            <select name="bank_details[type]" id="type" class="form-select">
                <option value="">Select</option>
                <option value="saving" @selected(!empty($record->bank_details['type']) && $record->bank_details['type']=='saving')>Saving</option>
                <option value="current" @selected(!empty($record->bank_details['type']) && $record->bank_details['type']=='current')>current</option>
                <option value="other" @selected(!empty($record->bank_details['type']) && $record->bank_details['type']=='other')>Other</option>
            </select>
            <span class="msg text-danger" id="bank_details_type_msg"></span>
        </div>

        <div class="mb-4 text-center">
            <button type="submit" class="btn btn-outline-primary" id="bankBtn">Update</button>
        </div>
    </div>
</form>

@push('script')
<script>
    $('form#saveBank').submit(function(e) {
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
                $('#bankBtn').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Updating...`).attr('disabled', true);
            },
            success: function(res) {
                $('#bankBtn').html('Update').removeAttr('disabled');

                /*Start Validation Error Message*/
                $('span.msg').html('');
                if (res.validation) {
                    $.each(res.validation, (index, msg) => {
                        let key = index.replace('.', '_');
                        $(`#${key}_msg`).html(`${msg}`);
                    })
                    return false;
                }
                /*End Validation Error Message*/
                /*Start Status message*/
                Swal.fire({
                    title: res.status ? 'Success' : 'Error',
                    text: res.msg,
                    icon: res.status ? 'success' : 'error'
                });
                /*End Status message*/
            }
        });
    });
</script>
@endpush