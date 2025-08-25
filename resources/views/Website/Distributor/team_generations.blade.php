<style>
    .table-light th {
        color: #000 !important;
        font-size: 14px;
        white-space: nowrap;
    }

    thead.table-light tr th {
        background: var(--thm-color-two);
    }
</style>
<div class="tab-panel active" id="team-generations">
    <h3 class="tab-title account-top">Team Generation</h3>
    <div class="tab-content-body">
        <form action="" method="get">
            <div class="row m-0">
                <div class="col-md-3 mb-2">
                    <select name="level" id="level" class="form-control">
                        <option value="">Select Level</option>
                        @foreach ($team_generation as $item)
                            <option value="{{ $item->lvl }}">{{ $item->lvl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="rank" id="rank" class="form-control">
                        <option value="">Select Rank</option>
                        <option value="diamond">Diamond</option>
                        <option value="gold">Gold</option>
                        <option value="silver">Silver</option>
                        <option value="bronze">Bronze</option>
                        <option value="none">None</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2 d-flex gap-2 align-items-center">
                    <button type="submit" class="thm-btn py-1 px-3">Search</button>
                    <a href="{{ url('distributor/team-generation') }}" class="thm-btn py-1 px-3">Reset</a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Level</th>
                        <th>Total Team</th>
                        <th>Active/Inactive</th>

                        <th>Month SV</th>
                        <th>Total SV</th>
                        {{-- <th>Status</th> --}}
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php ?>
                    @foreach ($team_generation as $item)
                        <?php
                        //convert into laravel query
                        $monthAp = DB::table('payout')
                            ->select(DB::raw('sum(sv) as totalAp'))
                            ->where('parent_id', auth()->user()->user_num)
                            ->where('level', $item->lvl)
                            ->where('cur_date', 'like', date('Y-m') . '%')
                            ->first();
                        $monthSv = $monthAp->totalAp ?? 0;
                        
                        $totAp = DB::table('payout')
                            ->select(DB::raw('sum(sv) as totalAp'))
                            ->where('parent_id', auth()->user()->user_num)
                            ->where('level', $item->lvl)
                            ->first();
                        $totSv = $totAp->totalAp ?? 0;
                        ?>
                        <tr>
                            <td>{{ $item->lvl }}</td>
                            <td>{{ $item->Tcnt }}</td>
                            <td><span class="text-success">{{ $item->Tgreen }}</span>/<span
                                    class="text-danger">{{ $item->Tred }}</span></td>

                            <td>{{ $monthSv }}</td>
                            <td>{{ $totSv }}</td>
                            {{-- <td>{{ $item->isactive > 0 ? 'Active' : 'Inactive' }}</td> --}}
                            <td>
                                <a href="{{ url('distributor/user-level-list/' . $item->lvl) }}"
                                    class="thm-btn">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
