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
            <a href="{{ url('distributor/payout-generation') }}" class="tab-btn active">Payout Generation</a>
        </div>
        <div class="col-md-2">
            <a href="{{ url('distributor/royalty-payout') }}" class="tab-btn">Royalty Payout</a>
        </div>
        <div class="col-md-2">
            <a href="{{ url('distributor/payout-slip') }}" class="tab-btn">Payout Slip</a>
        </div>
    </div>

    <div class="tab-content">

    <div class="tab-content-body">
        <div class="row">
            <div class="col-md-6">
            <h3>Total Payout: <span class="text-success">{{ $total_payout }}</span></h3>
        </div>
        <div class="col-md-6">
           <form action="{{ url('distributor/payout-generation') }}" method="get">
            <div class="row">
                <div class="col-md-4">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ url('distributor/payout-generation') }}" class="btn btn-secondary">Reset</a>
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
                        <th>Description</th>
                        <th>Status</th>
                        <th>SV</th>
                        <th>Amount</th>
                       
                    </tr>
                </thead>
                <tbody> 
                   @foreach ($payout_generation as $item)
                   <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d M,Y', strtotime($item->created_at)) }}</td>
                        <td>{{ $item->in_type??'' }}</td>
                        <td>
                            @if($item->status == 1)
                            <span class="badge bg-success">Completed</span>
                            @elseif($item->status == 2)
                            <span class="badge bg-warning">Returned</span>
                            @else
                            <span class="badge bg-danger">Pending</span>
                            @endif
                        </td>
                        <td>{{ $item->sv }}</td>
                        <td>{{ $item->amount }}</td>
                   </tr>
                   @endforeach
                </tbody>    
            </table>
        </div>
    </div>
</div>
@endsection