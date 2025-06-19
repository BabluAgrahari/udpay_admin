<form id="savekyc" method="post" action="{{url('crm/save-kyc')}}">
    @csrf
    <div class="row mb-5 ">
        <div class="col-md-4">
            <label>ID Type<span class="text-danger"><b>*</b></span></label>
            <select name="kyc_details[id_type]" id="id_type" class="form-select">
                <option value="">Select</option>
                <option value="aadhar" @selected(!empty($record->kyc_details['id_type']) && $record->kyc_details['id_type']=='aadhar')>Aadhar Card</option>
                <option value="driving_licence" @selected(!empty($record->kyc_details['id_type']) && $record->kyc_details['id_type']=='driving_licence')>Driving Licence</option>
                <option value="passport" @selected(!empty($record->kyc_details['id_type']) && $record->kyc_details['id_type']=='passport')>Passport</option>
            </select>
            <span class="text-danger msg" id="kyc_details_id_type_msg"></span>
        </div>

        <div class="col-md-6" id="idDocs">
            @if(!empty($record->kyc_details['id_docs']))
            <label>ID Docs</label>
            <a href="{{$record->kyc_details['id_docs']}}" target="_blank">{{$record->kyc_details['id_docs']}}</a>
            @endif

            @if(!empty($record->kyc_details['aadhar_docs_1']))
            <label>Docs</label><br>
            <a href="{{$record->kyc_details['aadhar_docs_1']}}" target="_blank">{{$record->kyc_details['aadhar_docs_1']}}</a>
            @endif

            @if(!empty($record->kyc_details['aadhar_docs_2']))
           
            <a href="{{$record->kyc_details['aadhar_docs_2']}}" target="_blank">{{$record->kyc_details['aadhar_docs_2']}}</a>
            @endif

        </div>

    </div>
    <div class="row mb-5 ">
        <div class="col-md-4">
            <label>Pancard No<span class="text-danger"><b>*</b></span></label>
            <input type="hidden" name="kyc_details[aadhar_verified]" value="{{ (!empty($record->kyc_details['pancard_verified']) && $record->kyc_details['pancard_verified'])?1:'' }}" id="aadhar_verified">
            <input type="text" class="form-control" name="kyc_details[pancard_no]"  {{ (!empty($record->kyc_details['pancard_verified']) && $record->kyc_details['pancard_verified'])?'disabled readonly':'' }} value="{{$record->kyc_details['pancard_no']??''}}" placeholder="Enter Pancard No">
            <span class="text-danger msg" id="kyc_details_pancard_no_msg"></span>
            <span id="gst_no_msg" class="custom-text-danger text-danger">
                {!! (!empty($record->kyc_details['pancard_verified']) && $record->kyc_details['pancard_verified'])?'<small class="text-success"><i class="fa-solid fa-circle-check"></i><b>Verified</b></small>':'' !!}
            </span>
        </div>
        <div class="col-md-4">
            <label>Pancard Docs<span class="text-danger"><b>*</b></span></label>
            <input type="file" class="form-control" name="pancard_docs">
            <span class="text-danger msg" id="pancard_docs_msg"></span>
            @if(!empty($record->kyc_details['pancard_docs']))
            <a href="{{$record->kyc_details['pancard_docs']}}" target="_blank">{{$record->kyc_details['pancard_docs']}}</a>
            @endif
        </div>
    </div>
    <div class="row mb-5 ">
        <div class="col-md-4">
            <label>Business Registration Document <span class="text-danger"><b>*</b></span></label>
            <input type="file" class="form-control" name="business_registration_docs">
            <span class="text-danger msg" id="business_registration_docs_msg"></span>
        </div>
        <div class="col-md-4">
            @if(!empty($record->kyc_details['business_registration_docs']))
            <label>Business Registration Document</label>
            <a href="{{$record->kyc_details['business_registration_docs']}}" target="_blank">{{$record->kyc_details['business_registration_docs']}}</a>
            @endif
        </div>
    </div>
    <div class="row mb-5 ">
        <div class="col-md-4">
            <label>Address Proff <span class="text-danger"><b>*</b></span></label>
            <input type="file" class="form-control" name="address_proff">
            <span class="text-danger msg" id="address_proff_msg"></span>
        </div>
        <div class="col-md-4">
            @if(!empty($record->kyc_details['address_proff']))
            <label>Address Proff</label>
            <a href="{{$record->kyc_details['address_proff']}}" target="_blank">{{$record->kyc_details['address_proff']}}</a>
            @endif
        </div>
    </div>
    <div class="row mb-5 ">
        <div class="text-center">
            <button type="submit" class="btn btn-outline-primary" id="kycBtn">Update</button>
        </div>
    </div>
</form>

@push('script')
<script>
    $(document).on('change', '#id_type', function() {

        var type = $(this).val();
        if (type == 'aadhar') {
            $('#idDocs').html(`
            <div class="row">
            <div class="col-md-6">
            <label>Front Docs <span class="text-danger"><b>*</b></span></label>
            <input type="file" class="form-control" name="aadhar_docs_1">
            <span class="text-danger" id="aadhar_docs_1_msg"></span>
        </div>
        <div class="col-md-6">
            <label>Back Docs <span class="text-danger"><b>*</b></span></label>
            <input type="file" class="form-control" name="aadhar_docs_2">
            <span class="text-danger msg" id="aadhar_docs_2_msg"></span>
        </div>
        </div>`).removeClass('col-md-4').addClass('col-md-6');
        } else {
            $('#idDocs').html(`
             <div class="row">
            <div class="col-md-12" >
            <label>Docs <span class="text-danger"><b>*</b></span></label>
            <input type="file" class="form-control" name="id_docs">
            <span class="text-danger msg" id="id_docs_msg"></span>
        </div></div>`).removeClass('col-md-6').addClass('col-md-4');
        }
    });

    $('form#savekyc').submit(function(e) {
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
                $('#kycBtn').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Updating...`).attr('disabled', true);
            },
            success: function(res) {
                $('#kycBtn').html('Update').removeAttr('disabled');

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