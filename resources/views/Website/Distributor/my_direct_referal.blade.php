<style>
    .table-light th {
        color: #000 !important;
    }
</style>
<div class="tab-panel active edit-form-open" id="my-direct-referal">
    <h3 class="tab-title account-top">My Direct Referral</h3>
    <div class="tab-content-body">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Mobile No</th>
                            <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($referrals as $item)
                                <tr>
                                    <td>{{ $item->user_id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->mobile }}</td>
                                    <td>{{ $item->status ? 'Active' : 'Inactive' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
