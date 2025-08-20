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
                        <!-- Sidebar -->
                        <div class="sidebar">
                            <a href="{{ url('distributor/wallet/add-money') }}" class="tab-btn"><i
                                    class="fa-solid fa-plus"></i>Add Money</a>
                            <a href="{{ url('distributor/wallet/money-transfer') }}" class="tab-btn"><i
                                    class="fa-solid fa-exchange-alt"></i> Money Transfer</a>
                            <a href="{{ url('distributor/wallet/my-payout') }}" class="tab-btn"><i
                                    class="fa-solid fa-box"></i>My Payout</a>
                            <a href="{{ url('distributor/wallet/redeem-transaction') }}" class="tab-btn"><i
                                    class="fa-solid fa-heart"></i> Redeem Transaction</a>
                            <a href="{{ url('distributor/wallet/transaction-history') }}" class="tab-btn active"><i
                                    class="fa-solid fa-book"></i> Transaction History</a>
                            {{-- <a href="{{ url('distributor/wallet/my-earning') }}" class="tab-btn"><i
                                    class="fa-solid fa-heart"></i> My Earning</a> --}}
                            <a href="{{ url('distributor/wallet/logout') }}" class="tab-btn logout"><i
                                    class="fa-solid fa-right-from-bracket"></i> Logout</a>
                        </div>
                        <!-- Content -->
                        <div class="tab-content">
                            <div class="tab-panel active" id="transaction-history">
                                <h3 class="tab-title account-top">Transaction History</h3>
                                <div class="tab-content-body">
                                    @if($transactions->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Txn Date</th>
                                                        <th>Uni Order ID</th>
                                                        <th>Transaction ID</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($transactions as $index => $tnx)
                                                        <tr>
                                                            <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $index + 1 }}</td>
                                                            <td>{{ $tnx->created_at ? date('d-m-Y H:i', strtotime($tnx->created_at)) : 'N/A' }}</td>
                                                            <td>{{ $tnx->order_id ?? 'N/A' }}</td>
                                                            <td>{{ $tnx->transaction_id ?? '' }}</td>
                                                            <td>{{ $tnx->amount ?? 'N/A' }}</td>
                                                            <td>{{ $tnx->status ? 'Success' : 'Pending' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            {{ $transactions->links('pagination::bootstrap-4') }}
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <div class="alert alert-info">
                                                <i class="fa-solid fa-info-circle me-2"></i>
                                                <strong>No redeem transactions found!</strong>
                                                <p class="mb-0 mt-2">You haven't made any redeem requests yet.</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Add any additional JavaScript functionality here if needed
        });
    </script>
@endpush 