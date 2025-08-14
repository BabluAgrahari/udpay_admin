@extends('Website.Layout.app')
@section('content')
<style>
    .table-light th {
        color: #000 !important;
    }
</style>
<div class="tab-panel active edit-form-open" id="user-level-list">
    <h3 class="tab-title account-top">User Level List (Level {{ $level }})</h3>
    <div class="tab-content-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Upgrade Date</th>
						<th>Month SV</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody> 
                    @foreach ($records as $record)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>UNI{{ $record->user_num }}</td>
                            <td>{{ $record->name }}</td>
                            <td>{{ date('d-m-Y', strtotime($record->upgrade_date)) }}</td>
                            <td>{{ $record->month_sv??0 }}</td>
							<td style="color: {{ $record->is_active ? 'green' : 'red' }}"><i class="fa-solid fa-circle"></i></td>
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