@extends('Website.Layout.app')
@section('content')
<style>
      .table-light th {
        color: #000 !important;
        font-size: 14px;
        white-space: nowrap;
    }
    thead.table-light tr th {
        background: var(--thm-color-two);
    }
</style>
<section class="py-5">
    <div class="container">
        <div class="tab-panel active edit-form-open" id="user-level-list">
            <div class="tab-content">
                <div class="row">
                    <div class="col-md-2">
                        <a href="{{ url('distributor/payout-generation') }}" class="tab-btn">Payout Generation</a>
                    </div>
                    <div class="col-md-2">
                                <a href="{{ url('distributor/royalty-payout') }}" class="tab-btn">Royalty Payout</a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ url('distributor/payout-slip') }}" class="tab-btn active">Payout Slip</a>
                    </div>
                </div>
                <div class="tab-content-body">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Month & Year</th>
                                    <th>Total Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>January 2025</td>
                                    <td>1000</td>
                                    <td><a href="{{ url('distributor/payout-slip/1') }}" class="btn btn-primary">View</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection