<form id="saveCompany" method="post" action="{{url('crm/save-business')}}">
    @csrf
    <div class="row">
        <div class="mb-4 col-md-4">
            <label>Business Name <span class="text-danger"><b>*</b></span></label>
            <input type="text" class="form-control" id="business_name" name="business_details[business_name]" value="{{$record->business_details['business_name']??''}}" placeholder="Enter Business Name">
            <span class="text-danger msg" id="business_details_business_name_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Business Type</label>
            <input type="text" class="form-control" id="business_type" name="business_details[business_type]" value="{{$record->business_details['business_type']??''}}" placeholder="Enter Business Type">
            <span class="text-danger msg" id="business_details_business_type_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Business Phone No <span class="text-danger"><b>*</b></span></label>
            <input type="number" class="form-control" id="business_phone_no" name="business_details[business_phone_no]" value="{{$record->business_details['business_phone_no']??''}}" placeholder="Enter Business Phone No">
            <span class="text-danger msg" id="business_details_business_phone_no_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Business Email <span class="text-danger"><b>*</b></span></label>
            <input type="email" class="form-control" id="business_email" name="business_details[business_email]" value="{{$record->business_details['business_email']??''}}" placeholder="Enter Business Email">
            <span class="text-danger msg" id="business_details_business_type_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Website URL</label>
            <input type="url" class="form-control" id="website_url" name="business_details[website_url]" value="{{$record->business_details['website_url']??''}}" placeholder="Enter Website URL">
            <span class="text-danger msg" id="business_details_website_url_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>GST No <span class="text-danger"><b>*</b></span></label>
            <input type="hidden" name="business_details[gst_verified]"  value="{{ (!empty($record->business_details['gst_verified']) && $record->business_details['gst_verified'])?1:'' }}" id="aadhar_verified">
            <input type="text" class="form-control" id="gst_no" name="business_details[gst_no]" {{ (!empty($record->business_details['gst_verified']) && $record->business_details['gst_verified'])?'disabled readonly':'' }} value="{{$record->business_details['gst_no']??''}}" placeholder="Enter GST No">
            <span class="text-danger msg" id="business_details_gst_no_msg"></span>
            <span id="gst_no_msg" class="custom-text-danger text-danger">
                {!! (!empty($record->business_details['gst_verified']) && $record->business_details['gst_verified'])?'<small class="text-success"><i class="fa-solid fa-circle-check"></i><b>Verified</b></small>':'' !!}
            </span>
        </div>

        <!-- <div class="mb-4 col-md-4">
            <label>Registration No</label>
            <input type="text" class="form-control" id="registration_no" name="business_details[registration_no]" value="{{$record->business_details['registration_no']??''}}" placeholder="Enter Registration No">
            <span class="text-danger msg" id="business_details_registration_no_msg"></span>
        </div> -->

        <div class="mb-4 col-md-4">
            <label>City <span class="text-danger"><b>*</b></span></label>
            <input type="text" class="form-control" id="city" name="business_details[city]" value="{{$record->business_details['city']??''}}" placeholder="Enter City">
            <span class="text-danger msg" id="business_details_city_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>State <span class="text-danger"><b>*</b></span></label>
            <input type="text" class="form-control" id="state" name="business_details[state]" value="{{$record->business_details['state']??''}}" placeholder="Enter State">
            <span class="text-danger msg" id="business_details_state_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Pincode <span class="text-danger"><b>*</b></span></label>
            <input type="number" class="form-control" id="pincode" name="business_details[pincode]" value="{{$record->business_details['pincode']??''}}" placeholder="Enter Pincode">
            <span class="text-danger msg" id="business_details_pincode_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Country <span class="text-danger"><b>*</b></span></label>
            <input type="text" class="form-control" id="country" name="business_details[country]" value="{{$record->business_details['country']??''}}" placeholder="Enter Country">
            <span class="text-danger msg" id="business_details_country_msg"></span>
        </div>

        <div class="mb-4 col-md-6">
            <label>Address <span class="text-danger"><b>*</b></span></label>
            <textarea type="text" class="form-control" id="address" rows="1" name="business_details[address]">{{$record->business_details['address']??''}}</textarea>
            <span class="text-danger msg" id="business_details_address_msg"></span>
        </div>

        <div class="mb-4 text-center">
            <button type="submit" class="btn btn-outline-primary" id="cdBtn">Update</button>
        </div>
    </div>
</form>

@push('script')
<script>
    $('form#saveCompany').submit(function(e) {
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
                $('#cdBtn').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Updating...`).attr('disabled', true);
            },
            success: function(res) {
                $('#cdBtn').html('Update').removeAttr('disabled');

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