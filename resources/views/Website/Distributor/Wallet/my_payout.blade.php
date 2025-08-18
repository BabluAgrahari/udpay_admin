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
                            <a href="{{ url('distributor/wallet/my-payout') }}" class="tab-btn active"><i
                                    class="fa-solid fa-box"></i>My Payout</a>
                            <a href="{{ url('distributor/wallet/redeem-transaction') }}" class="tab-btn"><i
                                    class="fa-solid fa-heart"></i> Redeem Transaction</a>
                            <a href="{{ url('distributor/wallet/transaction-history') }}" class="tab-btn"><i
                                    class="fa-solid fa-book"></i> Transaction History</a>
                            <a href="{{ url('distributor/wallet/my-earning') }}" class="tab-btn"><i
                                    class="fa-solid fa-heart"></i> My Earning</a>
                            <a href="{{ url('distributor/wallet/logout') }}" class="tab-btn logout"><i
                                    class="fa-solid fa-right-from-bracket"></i> Logout</a>
                        </div>
                        <!-- Content -->
                        <div class="tab-content">
                            <div class="tab-panel active" id="my-payout">
                                <h3 class="tab-title account-top">My Payout</h3>
                                <div class="tab-content-body">
                                    @if($payouts->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>For Date</th>
                                                        <th>Description</th>
                                                        <th>Amount</th>
                                                        <th>TDS & Service Charges</th>
                                                        <th>New Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($payouts as $index => $payout)
                                                        <tr>
                                                            <td>{{ ($payouts->currentPage() - 1) * $payouts->perPage() + $index + 1 }}</td>
                                                            <td>{{ $payout->for_date ? date('d-m-Y H:i', strtotime($payout->for_date)) : 'N/A' }}</td>
                                                            <td>{{ $payout->description ?? 'Payout Transaction' }}</td>
                                                            <td class="text-success">₹{{ number_format($payout->amount, 2) }}</td>
                                                            <td class="text-danger">
                                                                ₹{{ number_format($payout->tds_amt, 2) }}
                                                            </td>
                                                            <td class="text-primary fw-bold">
                                                                ₹{{ number_format($payout->net_amt, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            {{ $payouts->links('pagination::bootstrap-4') }}
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <div class="alert alert-info">
                                                <i class="fa-solid fa-info-circle me-2"></i>
                                                <strong>No payout transactions found!</strong>
                                                <p class="mb-0 mt-2">You haven't made any payout requests yet.</p>
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