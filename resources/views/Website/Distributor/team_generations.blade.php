<style>
    .table-light th {
        color: #000 !important;
    }
</style>
<div class="tab-panel active edit-form-open" id="team-generations">
    <h3 class="tab-title account-top">Team Generation</h3>
    <div class="tab-content-body">

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Level</th>
                        <th>Total Team</th>
                        <th>Month SV</th>
                        <th>Total SV</th>
                        {{-- <th>Status</th> --}}
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($team_generation as $item)
                        <tr>
                            <td>{{ $item->lvl }}</td>
                            <td>{{ $item->Tcnt }}</td>
                            <td>{{ $item->Tgreen }}</td>
                            <td>{{ $item->Tred }}</td>
                            {{-- <td>{{ $item->isactive > 0 ? 'Active' : 'Inactive' }}</td> --}}
                            <td>
                                <a href="{{ url('distributor/user-level-list/'.$item->lvl) }}" class="thm-btn">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

