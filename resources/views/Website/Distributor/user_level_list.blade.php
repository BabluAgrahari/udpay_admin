<style>
    .table-light th {
        color: #000 !important;
    }
</style>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0">
        <h5 class="mb-0 text-dark"><i class="fa-solid fa-tree-large me-2 text-brand-green"></i>Team Generation</h5>
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
                    </tr>
                </thead>
                <tbody> 
                    @foreach ($records as $record)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $record->user_id }}</td>
                            <td>{{ $record->name }}</td>
                            <td>{{ date('d-m-Y', strtotime($record->upgrade_date)) }}</td>
                            <td>{{ $record->astatus ? 'Active' : 'Inactive' }}</td>
                        </tr>
                    @endforeach
                </tbody>    
            </table>
        </div>
    </div>
</div>