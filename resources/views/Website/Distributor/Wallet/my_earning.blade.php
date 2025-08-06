@extends('Website.Layout.app')

@section('content')
    <style>
        .table-light th {
            color: #000 !important;
        }
    </style>
    <section class="section-padding py-5 pt-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="dashboard">
                        <div class="sidebar">
                            <a href="{{ url('distributor/wallet/add-money') }}" class="tab-btn"><i
                                    class="fa-solid fa-plus"></i>Add Money</a>
                            <a href="{{ url('distributor/wallet/money-transfer') }}" class="tab-btn"><i
                                    class="fa-solid fa-exchange-alt"></i> Money Transfer</a>
                            <a href="{{ url('distributor/wallet/my-payout') }}" class="tab-btn"><i
                                    class="fa-solid fa-box"></i>My Payout</a>
                            <a href="{{ url('distributor/wallet/redeem-transaction') }}" class="tab-btn"><i
                                    class="fa-solid fa-heart"></i> Redeem Transaction</a>
                            <a href="{{ url('distributor/wallet/transaction-history') }}" class="tab-btn"><i
                                    class="fa-solid fa-book"></i> Transaction History</a>
                            <a href="{{ url('distributor/wallet/my-earning') }}" class="tab-btn active"><i
                                    class="fa-solid fa-heart"></i> My Earning</a>
                        </div>
                        <div class="tab-content">
                            <div class="tab-panel active" id="my-earning">
                                <h3 class="tab-title account-top">My Earning</h3>
                                <div class="tab-content-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>2021-01-01</td>
                                                    <td>100</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
