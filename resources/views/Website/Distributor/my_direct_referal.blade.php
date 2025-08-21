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
                            <th>Rank</th>
                            <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($referrals as $item)
                                <tr>
                                    <td>UNI{{ $item->user_num }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->mobile }}</td>
                                    <td>@if(!empty($item->rank->diamond))
                                        <span class="badge bg-success">Diamond
                                            <i class="fa-solid fa-gem"></i>
                                        </span>
                                    @elseif(!empty($item->rank->gold))
                                        <span class="badge bg-primary">Gold
                                            <i class="fa-solid fa-star"></i>
                                        </span>
                                        <i class="fa-solid fa-star"></i>
                                    @elseif(!empty($item->rank->silver))
                                        <span class="badge bg-warning">Silver
                                            <i class="fa-solid fa-star"></i>
                                        </span>
                                        <i class="fa-solid fa-star"></i>
                                    @elseif(!empty($item->rank->bronze))
                                        <span class="badge bg-danger">Bronze
                                            <i class="fa-solid fa-star"></i>
                                        </span>
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        <span class="badge bg-danger">N/A
                                            <i class="fa-solid fa-star"></i>
                                        </span>
                                        @endif
                                    </td>
                                    <td style="color: {{ $item->isactive ? 'green' : 'red' }}">
										<i class="fa-solid fa-circle"></i>
									</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
