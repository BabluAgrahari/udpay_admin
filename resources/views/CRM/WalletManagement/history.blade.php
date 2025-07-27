@extends('CRM.Layout.layout')

@section('title', 'Transfer History')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Transfer History</h5>
                    @can('isSuperAdmin')
                        <div>
                            <a href="{{ url('crm/wallet/transfer') }}" class="btn btn-primary me-2">
                                <i class="bx bx-transfer me-1"></i>Transfer Money
                            </a>
                           
                        </div>
                    @endcan
                    @can('isAdmin')
                        <div>
                            <a href="{{ url('crm/wallet/user-transfer') }}" class="btn btn-primary me-2">
                                <i class="bx bx-user-plus me-1"></i>Transfer Money
                            </a>
                            <a href="{{ url('crm/wallet/user-to-user-transfer') }}" class="btn btn-secondary">
                                <i class="bx bx-group me-1"></i>User to User Transfer
                            </a>
                        </div>
                    @endcan
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ url('crm/wallet/history') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Filter by User</label>
                                    <select class="form-select" name="user_id" id="user_id">
                                        <option value="">All Users</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->_id }}" {{ request('user_id') == $user->_id ? 'selected' : '' }}>
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Transaction Type</label>
                                    <select class="form-select" name="type" id="type">
                                        <option value="">All Types</option>
                                        <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                                        <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="date_range" class="form-label">Date Range</label>
                                    {!! daterange(request()->all()) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="{{ url('crm/wallet/history') }}" class="btn btn-secondary">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Transactions Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead >
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Closing Balance</th>
                                    <th>Source</th>
                                    <th>Action By</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $index => $transaction)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($transaction->user)
                                                {{ $transaction->user->first_name }} {{ $transaction->user->last_name }}
                                                <br><small class="text-muted">{{ $transaction->user->email }}</small>
                                            @else
                                                <span class="text-muted">User not found</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transaction->type == 'credit')
                                                <span class="badge bg-label-success">Credit</span>
                                            @else
                                                <span class="badge bg-label-danger">Debit</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ mSign($transaction->amount) }}</strong>
                                        </td>
                                        <td>{{ mSign($transaction->closing_amount) }}</td>
                                        <td>
                                            @switch($transaction->source)
                                                @case('admin_transfer')
                                                    <span class="badge bg-label-primary">Admin Transfer</span>
                                                    @break
                                                @case('user_transfer')
                                                    <span class="badge bg-label-warning">User Transfer</span>
                                                    @break
                                                @case('user_to_user_transfer')
                                                    <span class="badge bg-label-secondary">User to User Transfer</span>
                                                    @break
                                                @case('system')
                                                    <span class="badge bg-label-info">System</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-label-info">{{ ucwords(str_replace('_', ' ', $transaction->source)) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($transaction->actionBy)
                                                {{ $transaction->actionBy->first_name }} {{ $transaction->actionBy->last_name }}
                                            @else
                                                <span class="text-muted">System</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $transaction->created ? $transaction->dFormat($transaction->created) : 'N/A' }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary view-details" 
                                                    data-transaction-id="{{ $transaction->_id }}">
                                                <i class="bx bx-show"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No transactions found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($transactions->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $transactions->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Details Modal -->
<div class="modal fade" id="transactionDetailsModal" tabindex="-1" aria-labelledby="transactionDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionDetailsModalLabel">Transaction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="transactionDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    // View transaction details
    $('.view-details').click(function() {
        var transactionId = $(this).data('transaction-id');
        
        // Show loading
        $('#transactionDetailsContent').html('<div class="text-center"><div class="spinner-border" role="status"></div><p class="mt-2">Loading...</p></div>');
        $('#transactionDetailsModal').modal('show');
        
        // Fetch transaction details
        $.ajax({
            url: '{{ url("crm/wallet/transaction-details") }}',
            type: 'GET',
            data: { transaction_id: transactionId },
            success: function(response) {
                if (response.status) {
                    var data = response.record;
                    var content = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Transaction Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>User:</strong></td>
                                        <td>${data.user_name}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Type:</strong></td>
                                        <td>
                                            ${data.transaction.type === 'credit' ? 
                                                '<span class="badge bg-label-success">Credit</span>' : 
                                                '<span class="badge bg-label-danger">Debit</span>'}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Amount:</strong></td>
                                        <td>${data.amount_formatted}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Closing Balance:</strong></td>
                                        <td>${data.closing_amount_formatted}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Additional Details</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Source:</strong></td>
                                        <td>
                                            ${data.transaction.source === 'admin_transfer' ? 
                                                '<span class="badge bg-label-primary">Admin Transfer</span>' : 
                                                data.transaction.source === 'user_transfer' ? 
                                                '<span class="badge bg-label-warning">User Transfer</span>' :
                                                data.transaction.source === 'user_to_user_transfer' ? 
                                                '<span class="badge bg-label-secondary">User to User Transfer</span>' :
                                                `<span class="badge bg-label-info">${data.transaction.source.replace('_', ' ').toUpperCase()}</span>`}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Action By:</strong></td>
                                        <td>${data.action_by}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date:</strong></td>
                                        <td>${data.created_at}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Remarks:</strong></td>
                                        <td>${data.transaction.remarks || 'No remarks'}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    `;
                    $('#transactionDetailsContent').html(content);
                } else {
                    $('#transactionDetailsContent').html('<div class="alert alert-danger">' + response.msg + '</div>');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    $('#transactionDetailsContent').html('<div class="alert alert-danger">Validation error occurred.</div>');
                } else if (xhr.status === 400) {
                    $('#transactionDetailsContent').html('<div class="alert alert-danger">' + xhr.responseJSON.msg + '</div>');
                } else {
                    $('#transactionDetailsContent').html('<div class="alert alert-danger">Failed to load transaction details.</div>');
                }
            }
        });
    });
});
</script>
@endpush 