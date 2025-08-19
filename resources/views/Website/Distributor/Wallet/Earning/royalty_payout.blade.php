@extends('Website.Layout.app')
@section('content')
<style>
    .table-light th {
        color: #000 !important;
    }
</style>
<div class="tab-panel active edit-form-open" id="user-level-list">

    <div class="row">
        <div class="col-md-2">
            <a href="{{ url('distributor/payout-generation') }}" class="tab-btn">Payout Generation</a>
        </div>
        <div class="col-md-2">
            <a href="{{ url('distributor/royalty-payout') }}" class="tab-btn active">Royalty Payout</a>
        </div>
        <div class="col-md-2">
            <a href="{{ url('distributor/payout-slip') }}" class="tab-btn">Payout Slip</a>
        </div>
    </div>

    <div class="tab-content">

    <div class="tab-content-body">
        <div class="row">
            <div class="col-md-6">
        <div>
            <h3>Total Royalty Payout: <span class="text-success">{{ $total_royalty_payout }}</span></h3>
        </div>
        </div>
        <div class="col-md-6">
            <form action="{{ url('distributor/royalty-payout') }}" method="get">
                <div class="row">
                    <div class="col-md-4">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ url('distributor/royalty-payout') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>User ID</th>
                        <th>Royalty Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody> 
                   @foreach ($royalty_payout as $item)
                   <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d M,Y', strtotime($item->for_date)) }}</td>
                        <td>{{ $item->user_id }}</td>
                        <td>{{ $item->in_type??'' }}</td>
                        <td>{{ $item->amount }}</td>
                        <td>
                            @if($item->status == 1)
                            <span class="badge bg-success">Completed</span>
                            @else
                            <span class="badge bg-danger">Pending</span>
                            @endif
                        </td>
                   </tr>
                   @endforeach
                </tbody>    
            </table>
        </div>
    </div>
</div>
@endsection