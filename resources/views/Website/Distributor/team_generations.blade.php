<style>
    .table-light th {
        color: #000 !important;
    }
</style>
<div class="tab-panel active edit-form-open" id="team-generations">
    <h3 class="tab-title account-top">Team Generation</h3>
    <div class="tab-content-body">

        <div class="table-responsive">
            <form action="" method="get">
            <div class="row">
                <div class="col-md-3">
                    <select name="level" id="level" class="form-control">
                        <option value="">Select Level</option>
                        @foreach ($team_generation as $item)
                            <option value="{{ $item->lvl }}">{{ $item->lvl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="rank" id="rank" class="form-control">
                        <option value="">Select Rank</option>
                        <option value="diamond">Diamond</option>
                        <option value="gold">Gold</option>
                        <option value="silver">Silver</option>
                        <option value="bronze">Bronze</option>
                        <option value="none">None</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="thm-btn">Search</button>
                    <a href="{{ url('distributor/team-generation') }}" class="thm-btn">Reset</a>
                </div>
            </div>
            </form>
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

