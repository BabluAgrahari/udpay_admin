@extends('Website.Layout.app')
@section('content')
<style>
    .table-light th {
        color: #000 !important;
    }
    thead.table-light tr th {
        white-space: nowrap;
        font-size: 14px;
    }
    thead.table-light tr th {
        background: var(--thm-color-two);
    }
</style>
<div class="section-padding">
    <div class="container">
        <div class="tab-panel active edit-form-open" id="user-level-list">
            <h4 class="mb-3">User Level List (Level {{ $level }})</h4>
            <div class="tab-content-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light user-lavel-head">
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
                                    <td style="color: {{ $record->isactive ? 'green' : 'red' }}"><i class="fa-solid fa-circle"></i></td>
                                    <td>
                                        <a href="{{ url('distributor/user-level-list/'.$record->lvl.'/'.$record->user_num) }}" class="thm-btn">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>    
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection