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
                            <a href="{{ url('crm/wallet/transfer') }}" class="btn btn-outline-primary me-2">
                                <i class="bx bx-transfer me-1"></i>Transfer Money
                            </a>
                           
                        </div>
                    @endcan
                    @can('isAdmin')
                        <div>
                            <a href="{{ url('crm/wallet/user-transfer') }}" class="btn btn-outline-primary me-2">
                                <i class="bx bx-user-plus me-1"></i>Transfer Money
                            </a>
                            <a href="{{ url('crm/wallet/user-to-user-transfer') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-group me-1"></i>User to User Transfer
                            </a>
                        </div>
                    @endcan
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form id="walletHistoryFilterForm" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Filter by User</label>
                                    <select class="form-select form-select-sm" name="user_id" id="user_id">
                                        <option value="">All Users</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->_id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Transaction Type</label>
                                    <select class="form-select form-select-sm" name="type" id="type">
                                        <option value="">All Types</option>
                                        <option value="credit">Credit</option>
                                        <option value="debit">Debit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="date_range" class="form-label">Date Range</label>
                                    <input type="text" class="form-control form-control-sm" name="date_range" id="date_range" placeholder="MM/DD/YYYY - MM/DD/YYYY">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
                                        <button type="button" id="resetFilter" class="btn btn-outline-secondary btn-sm">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Transactions Table -->
                    <div class="table-responsive">
                        <table id="walletHistoryTable" class="table table-hover table-sm w-100 text-nowrap">
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
                            <tbody></tbody>
                        </table>
                    </div>
                    {{-- DataTable handles pagination --}}
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
    var table = $('#walletHistoryTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        pagingType: 'simple_numbers',
        ajax: {
            url: '{{ url('crm/wallet/history/datatable-list') }}',
            data: function (d) {
                d.user_id = $('#user_id').val();
                d.type = $('#type').val();
                d.date_range = $('#date_range').val();
            }
        },
        columns: [
            { data: 'index', name: 'index', orderable: false, searchable: false },
            { data: 'user', name: 'user' },
            { data: 'type', name: 'type' },
            { data: 'amount', name: 'amount' },
            { data: 'closing_amount', name: 'closing_amount' },
            { data: 'source', name: 'source' },
            { data: 'action_by', name: 'action_by' },
            { data: 'date', name: 'date' },
            { data: '_id', name: 'actions', orderable: false, searchable: false, render: function(data, type, row) {
                return `<button type="button" class="btn btn-sm btn-outline-primary view-details" data-transaction-id="${data}"><i class="bx bx-show"></i> View</button>`;
            }}
        ],
        order: [[7, 'desc']],
        lengthMenu: [10, 25, 50, 100, 500],
        pageLength: 10,
        scrollX: false,
    });

    // Filter form submit
    $('#walletHistoryFilterForm').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
    // Reset filter
    $('#resetFilter').on('click', function() {
        $('#walletHistoryFilterForm')[0].reset();
        table.ajax.reload();
    });

    // View transaction details
    $('#walletHistoryTable').on('click', '.view-details', function() {
        var transactionId = $(this).data('transaction-id');
        $('#transactionDetailsContent').html('<div class="text-center"><div class="spinner-border" role="status"></div><p class="mt-2">Loading...</p></div>');
        $('#transactionDetailsModal').modal('show');
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