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
                        <th>Level</th>
                        <th>Total Count</th>
                        <th>Total Green</th>
                        <th>Total Red</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($team_generation as $item)
                        <tr>
                            <td>{{ $item->lvl }}</td>
                            <td>{{ $item->Tcnt }}</td>
                            <td>{{ $item->Tgreen }}</td>
                            <td>{{ $item->Tred }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
