@extends('CRM.Layout.layout')

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">

            <div class="row card-header">

                <div class="col-md-10">

                    <h5>Create Loan</h5>

                </div>

                <div class="col-md-2 text-right"><a href="{{ url('crm/loan') }}" class="btn btn-outline-warning btn-sm"><i

                            class='bx bx-left-arrow-circle' style="line-height: 0"></i>&nbsp;Back</a></div>

            </div>



            <div class="card-body">

                <div id="message"></div>

                <form id="save" method="post" action="{{ url('crm/loan') }}">

                    @csrf

                    <div class="row">



                        <div class="mb-4 col-md-3">

                            <label>Select Branch <span class="text-danger"><b>*</b></span></label>

                            <select class="form-select form-select-sm" name="branch_id" id="branch_id">

                                <option value="">Select</option>

                                @foreach ($branch as $br)

                                    <option value="{{ $br->_id }}">{{ $br->branch_name ?? '' }}</option>

                                @endforeach

                            </select>

                            <span class="text-danger" id="branch_id_msg"></span>

                        </div>



                        <div class="mb-4 col-md-3">

                            <label>Select Employee <span class="text-danger"><b>*</b></span></label>

                            <select class="form-select form-select-sm" name="employee_id" id="employee_id">

                                <option value="">Select</option>



                            </select>

                            <span class="text-danger" id="employee_id_msg"></span>

                        </div>



                        <div class="mb-4 col-md-2">

                            <label>Status <span class="text-danger"><b>*</b></span></label>

                            <select class="form-select form-select-sm" name="status" id="status">

                                <option value="1">Active</option>

                                <option value="0">Inactive</option>

                            </select>

                            <span class="text-danger" id="status_msg"></span>

                        </div>

                    </div>



                    {{-- <div class="row">

                        <div class="col-md-6"> --}}

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label>Loan Amount <span class="text-danger"><b>*</b></span></label>

                            <input type="numeric" class="form-control form-control-sm" name="loan_amount" id="loan_amount"

                                placeholder="Loan Amount">

                            <span class="text-danger" id="loan_amount_msg"></span>

                        </div>



                        <div class="col-md-8 mb-3">

                            <label>Comment</label>

                            <textarea rows="1" class="form-control form-control-sm" name="comment" id="comment" placeholder="Enter Comment"></textarea>

                            <span class="text-danger" id="comment_msg"></span>

                        </div>

                    </div>



                    <div class="row d-none mb-3" id="loanCalcution">

                        <input type="hidden" id="loan_id" name="loan_id">

                        <input type="hidden" id="emi_amount" name="loan[emi_amount]">

                        <input type="hidden" id="last_emi" name="loan[last_emi]">

                        <input type="hidden" id="max_tenure" name="loan[max_tenure]">

                        <input type="hidden" id="interest_rate" name="loan[interest_rate]">

                        <input type="hidden" id="interest_amount" name="loan[interest_amount]">

                        <input type="hidden" id="total_amount_with_interest" name="loan[total_amount_with_interest]">



                        <div class="col-md-8">

                            <table class="table table-sm">

                                <tr>

                                    <th>Loan Amount</th>

                                    <td id="loanAmountHtml"></td>

                                </tr>

                                <tr>

                                    <th>Interest Rate</th>

                                    <td id="interestTateHtml"></td>

                                </tr>

                                <tr>

                                    <th>Interest Amount</th>

                                    <td id="interestAmountHtml"></td>

                                </tr>

                                <tr>

                                    <th>Max Tenure</th>

                                    <td id="maxTenureHtml"></td>

                                </tr>

                                <tr>

                                    <th>EMI Amount </th>

                                    <td id="emiAmountHtml"></td>

                                </tr>

                            </table>

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

            $(document).on('change', '#branch_id', function() {

                var branch_id = $(this).val();

                var url = "{{ url('crm/get-employee') }}/" + branch_id;

                $.ajax({

                    url: url,

                    type: 'GET',

                    dataType: "JSON",

                    success: function(res) {

                        if (res.status) {

                            $('#employee_id').html(res.html);

                        } else {

                            toastr.error(res.msg, 'Error');

                        }

                    }

                })

            });



            $(document).on('change', '#employee_id', function() {

                var employee_id = $(this).val();

                var url = "{{ url('crm/get-employee-loan') }}/" + employee_id;

                $.ajax({

                    url: url,

                    type: 'GET',

                    dataType: "JSON",

                    success: function(res) {

                        if (res.status) {

                            $('#loanAmountHtml').html(res.record.loan_amount);

                            $('#interestTateHtml').html(`${res.record.interest_rate} %`);

                            $('#interest_rate').val(res.record.interest_rate);

                            $('#emi_amount').val(res.record.emi_amount);

                            $('#loan_amount').val(res.record.loan_amount);

                            $('#loan_id').val(res.record.loan_id);

                            $('#loanCalcution').removeClass('d-none');

                            loanCalculate();

                        } else {

                            toastr.error(res.msg, 'Error');

                        }

                    }

                })

            });



            $(document).on('keyup', '#loan_amount', function() {

                loanCalculate();

            })



            function loanCalculate() {

                var loan_amount = $('#loan_amount').val();

                var emi_amount = $('#emi_amount').val();

                var interest_rate = $('#interest_rate').val();



                var interest_amount = (loan_amount * interest_rate) / 100;

                let total_loan_amount = parseFloat(loan_amount) + parseFloat(interest_amount);



                let max_tenure = Math.ceil(parseFloat(total_loan_amount / emi_amount));



                let totalEmiAmount = emi_amount * max_tenure;

                let lastEmi = totalEmiAmount - total_loan_amount;

                lastEmi = emi_amount - lastEmi;

                if (total_loan_amount <= emi_amount)

                    lastEmi = total_loan_amount;



                $('#interestAmountHtml').html(interest_amount);

                $('#maxTenureHtml').html(`${(max_tenure=='NaN'?0:max_tenure)} Months`);

                $('#emiAmountHtml').html(

                    `${emi_amount<total_loan_amount?emi_amount+' /Month':''}  ${lastEmi>0?'<b>Last Month EMI-</b>'+lastEmi:''}`

                );



                $('#total_amount_with_interest').val(total_loan_amount);

                $('#emi_amount').val(emi_amount);

                $('#last_emi').val(lastEmi);

                $('#max_tenure').val(max_tenure);

                $('#interest_amount').val(interest_amount);

            }



            $('form#save').submit(function(e) {

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

                        $('#saveBtn').html(

                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Saving...`

                        ).attr('disabled', true);

                    },

                    success: function(res) {



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

        </script>

    @endpush

@endsection

