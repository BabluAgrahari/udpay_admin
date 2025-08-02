<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0">
        <h5 class="mb-0 text-dark"><i class="fa-solid fa-tree-large me-2 text-brand-green"></i>My Acheivements</h5>
    </div>
    <div class="card-body">

        <div class="row">
        @if ($achievement)

            @if ($achievement->bronze || $achievement->silver || $achievement->gold || $achievement->diamond)
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-medal me-2 text-brand-gray"></i>
                            <h5 class="card-title">Bronze</h5>
                        </div>
                        <p class="card-text">
                            {{ date('d-m-Y', strtotime($achievement->bronze_date)) }}
                        </p>
                    </div>
                </div>
                </div>
            @endif

            @if ($achievement->silver || $achievement->gold || $achievement->diamond)
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-medal me-2 text-brand-green"></i>
                            <h5 class="card-title">Silver</h5>
                        </div>
                        <p class="card-text">
                            {{ date('d-m-Y', strtotime($achievement->silver_date)) }}
                        </p>
                    </div>
                    </div>
                </div>
            @endif

            @if ($achievement->gold || $achievement->diamond)
                <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-medal me-2 text-brand-yellow"></i>
                            <h5 class="card-title">Gold</h5>
                        </div>
                        <p class="card-text">
                            {{ date('d-m-Y', strtotime($achievement->gold_date)) }}
                        </p>
                    </div>
                    </div>
                </div>
            @endif

            @if ($achievement->diamond)
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-medal me-2 text-brand-red"></i>
                            <h5 class="card-title">Diamond</h5>
                        </div>
                        <p class="card-text">
                            {{ date('d-m-Y', strtotime($achievement->diamond_date)) }}
                        </p>
                    </div>
                    </div>
                </div>
            @endif
        @else
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">No Acheivements</h5>
                </div>
            </div>
        @endif
    </div>
</div>
