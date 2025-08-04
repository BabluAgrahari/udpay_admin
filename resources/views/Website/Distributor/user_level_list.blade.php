@extends('Website.Layout.app')
@section('content')
<style>
    .table-light th {
        color: #000 !important;
    }
</style>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0">
        <h5 class="mb-0 text-dark"><i class="fa-solid fa-tree-large me-2 text-brand-green"></i>User Level List (Level {{ $level }})</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Upgrade Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody> 
                    @foreach ($records as $record)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $record->user_id }}</td>
                            <td>{{ $record->name }}</td>
                            <td>{{ date('d-m-Y', strtotime($record->upgrade_date)) }}</td>
                            <td>{{ $record->isactive ? 'Active' : 'Inactive' }}</td>
                            <td>
                                <a href="{{ url('distributor/user-level-list/'.$record->lvl) }}" class="thm-btn">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>    
            </table>
        </div>
    </div>
</div>
@endsection