<form id="saveCP" method="post" action="{{url('crm/save-cp')}}">
    @csrf
    <div class="row">

        <div class="mb-4 col-md-4">
            <input type="hidden" name="contact_person[aadhar_verified]" value="{{ (!empty($record->contact_person['aadhar_verified']) && $record->contact_person['aadhar_verified'])?1:'' }}" id="aadhar_verified">
            <label>Aadhar No <span class="text-danger"><b>*</b></span></label>
            <input type="text" class="form-control" id="aadhar_no" name="contact_person[aadhar_no]" {{ (!empty($record->contact_person['aadhar_verified']) && $record->contact_person['aadhar_verified'])?'disabled readonly':'' }} value="{{$record->contact_person['aadhar_no']??''}}" placeholder="Enter Aadhar No">
            <span class="text-danger msg" id="aadhar_no_msg"></span>
            <span id="gst_no_msg" class="custom-text-danger text-danger">
                {!! (!empty($record->contact_person['aadhar_verified']) && $record->contact_person['aadhar_verified'])?'<small class="text-success"><i class="fa-solid fa-circle-check"></i><b>Verified</b></small>':'' !!}
            </span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Name <span class="text-danger"><b>*</b></span></label>
            <input type="text" class="form-control" id="name" name="contact_person[name]" value="{{$record->contact_person['name']??''}}" placeholder="Enter Name">
            <span class="text-danger msg" id="contact_person_name_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Phone No <span class="text-danger"><b>*</b></span></label>
            <input type="number" class="form-control" id="phone_no" name="contact_person[phone_no]" value="{{$record->contact_person['phone_no']??''}}" placeholder="Enter Phone No">
            <span class="text-danger msg" id="contact_person_phone_no_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Email <span class="text-danger"><b>*</b></span></label>
            <input type="email" class="form-control" id="email" name="contact_person[email]" value="{{$record->contact_person['email']??''}}" placeholder="Enter Email">
            <span class="text-danger msg" id="contact_person_email_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Gender <span class="text-danger"><b>*</b></span></label>
            <select name="contact_person[gender]" id="gender" class="form-select">
                <option value="">Select</option>
                <option value="male" @selected(!empty($record->contact_person['gender']) && $record->contact_person['gender']=='male')>Male</option>
                <option value="female" @selected(!empty($record->contact_person['gender']) && $record->contact_person['gender']=='female')>Female</option>
                <option value="other" @selected(!empty($record->contact_person['gender']) && $record->contact_person['gender']=='other')>Other</option>
            </select>
            <span class="text-danger msg" id="contact_person_gender_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Designation</label>
            <input type="text" class="form-control" id="designation" name="contact_person[designation]" value="{{$record->contact_person['designation']??''}}" placeholder="Enter Designation">
            <span class="text-danger msg" id="contact_person_designation_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>City <span class="text-danger"><b>*</b></span></label>
            <input type="text" class="form-control" id="city" name="contact_person[city]" value="{{$record->contact_person['city']??''}}" placeholder="Enter City">
            <span class="text-danger msg" id="contact_person_city_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>State <span class="text-danger"><b>*</b></span></label>
            <input type="text" class="form-control" id="state" name="contact_person[state]" value="{{$record->contact_person['state']??''}}" placeholder="Enter State">
            <span class="text-danger msg" id="contact_person_state_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Pincode <span class="text-danger"><b>*</b></span></label>
            <input type="number" class="form-control" id="pincode" name="contact_person[pincode]" value="{{$record->contact_person['pincode']??''}}" placeholder="Enter Pincode">
            <span class="text-danger msg" id="contact_person_pincode_msg"></span>
        </div>

        <div class="mb-4 col-md-4">
            <label>Country <span class="text-danger"><b>*</b></span></label>
            <input type="text" class="form-control" id="country" name="contact_person[country]" value="{{$record->contact_person['country']??''}}" placeholder="Enter Country">
            <span class="text-danger msg" id="contact_person_country_msg"></span>
        </div>

        <div class="mb-4 col-md-6">
            <label>Address <span class="text-danger"><b>*</b></span></label>
            <textarea type="text" class="form-control" rows='1' id="address" name="contact_person[address]">{{$record->contact_person['address']??''}}</textarea>
            <span class="text-danger msg" id="contact_person_address_msg"></span>
        </div>

        <div class="col-md-4 mb-4">
            <label>Profile Pic</label>
            <input type="file" class="form-control" name="profile_pic">
            <span class="text-danger msg" id="profile_pic_msg"></span>
        </div>
        <div class="col-md-2"><img src="{{$record->contact_person['profile_pic']??''}}" style="width: 150px;
    height: 80px;"></div>
        <div class="mb-4 text-center">
            <button type="submit" class="btn btn-outline-primary" id="cpBtn">Update</button>
        </div>
    </div>
</form>

@push('script')
<script>
    $('form#saveCP').submit(function(e) {
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
                $('#cpBtn').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Updating...`).attr('disabled', true);
            },
            success: function(res) {
                $('#cpBtn').html('Update').removeAttr('disabled');

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