@extends('CRM.Layout.layout')

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">
            <div class="row card-header">
                <div class="col-md-10">
                    <h5>Create User</h5>
                </div>
                <div class="col-md-2 text-right"><a href="{{ url('crm/user') }}" class="btn btn-outline-warning btn-sm"><i
                            class='bx bx-left-arrow-circle' style="line-height: 0"></i>&nbsp;Back</a></div>
            </div>

            <div class="card-body">
                <div id="message"></div>
                <form id="save" method="post" action="{{ url('crm/user') }}">
                    @csrf
                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label>Name <span class="text-danger"><b>*</b></span></label>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Name">
                            <span class="text-danger" id="name_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Email <span class="text-danger"><b>*</b></span></label>
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="Email">
                            <span class="text-danger" id="email_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Mobile No <span class="text-danger"><b>*</b></span></label>
                            <input type="number" class="form-control" name="mobile_no" id="mobile_no"
                                placeholder="Mobile No">
                            <span class="text-danger" id="mobile_no_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Password <span class="text-danger"><b>*</b></span></label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Password">
                            <span class="text-danger" id="password_msg"></span>
                        </div>

                        <div class="mb-4 col-md-4">
                            <label>Gender <span class="text-danger"><b>*</b></span></label>
                            <select class="form-select" name="gender" id="gender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                            <span class="text-danger" id="gender_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>City <span class="text-danger"><b>*</b></span></label>
                            <input type="text" class="form-control" name="city" id="city"
                                placeholder="City">
                            <span class="text-danger" id="city_msg"></span>
                        </div>


                        <div class="col-md-4 mb-3">
                            <label>State <span class="text-danger"><b>*</b></span></label>
                            <input type="text" class="form-control" name="state" id="state"
                                placeholder="State">
                            <span class="text-danger" id="state_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Pincode <span class="text-danger"><b>*</b></span></label>
                            <input type="number" class="form-control" name="pincode" id="pincode"
                                placeholder="Pincode">
                            <span class="text-danger" id="pincode_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Country <span class="text-danger"><b>*</b></span></label>
                            <input type="number" class="form-control" name="country" id="country"
                                placeholder="Country">
                            <span class="text-danger" id="country_msg"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Address <span class="text-danger"><b>*</b></span></label>
                            <textarea class="form-control" name="address" id="address"
                                placeholder="Address"></textarea>
                            <span class="text-danger" id="address_msg"></span>
                        </div>

                        <div class="mb-4 col-md-4">
                            <label>Status <span class="text-danger"><b>*</b></span></label>
                            <select class="form-select" name="status" id="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <span class="text-danger" id="status_msg"></span>
                        </div>
                    </div>

                    <div class="mb-4 col-md-12 text-center">
                        <button type="submit" class="btn btn-sm btn-outline-primary" id="saveBtn">Save</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
    </div>

    @push('script')
        <script>

            $('form#save').submit(function (e) {

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

                    beforeSend: function () {

                        $('#saveBtn').html(

                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Saving...`

                        ).attr('disabled', true);

                    },

                    success: function (res) {

                        $('#saveBtn').html('Save').removeAttr('disabled');

                        /*Start Validation Error Message*/

                        $('span.text-danger').html('');

                        if (res.validation) {

                            $.each(res.validation, (index, msg) => {

                                $(`#${index}_msg`).html(`${msg}`);

                            })

                            return false;

                        }

                        /*End Validation Error Message*/

                        /*Start Status message*/

                        alertMsg(res.status, res.msg, 3000);

                        /*End Status message*/

                        if (res.status) {

                            $('form#save').trigger('reset');

                            setTimeout(() => {

                                location.reload();

                            }, 1000);

                        }

                    }

                });

            });

            //Date range as a button

            $('#daterange').daterangepicker({

                // ranges: {

                //     'Today': [moment(), moment()],

                //     'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],

                //     'Last 7 Days': [moment().subtract(6, 'days'), moment()],

                //     'Last 30 Days': [moment().subtract(29, 'days'), moment()],

                //     'This Month': [moment().startOf('month'), moment().endOf('month')],

                //     'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(

                //         'month')],

                //     'All Time': ['01/01/2023', moment()],

                // },

                // startDate: moment().subtract(365, 'days'),

                // endDate: moment()

            },

                function (start, end) {

                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))

                }

            )

        </script>

    @endpush

@endsection