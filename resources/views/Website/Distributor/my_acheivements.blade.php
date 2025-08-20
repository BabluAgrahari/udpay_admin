<div class="tab-panel active edit-form-open" id="my-acheivements">
    <h3 class="tab-title account-top">My Acheivements</h3>
    <div class="tab-content-body">
        <div class="rank-container">
            <h6 id="status-text">All Inactive</h6>

            <div class="row g-3 justify-content-center">
            
            <!-- Rank 1 -->
            <div class="col-md-2 col-6">
                <div class="rank-card" onclick="setActive(this)">
                <img src="https://img.icons8.com/color/96/medal.png" alt="Bronze">
                <div class="rank-title">Bronze</div>
                <div class="rank-status inactive">Inactive</div>
                <div class="rank-details">5,000 S.V. Required on Levels 2</div>
                <div class="know-more" onclick="toggleInfo(this, event)">Know More...</div>
                <div class="extra-info">
                    Bronze level is the starting achievement with 5,000 S.V. required on Level 2.
                </div>
                </div>
            </div>

            <!-- Rank 2 -->
            <div class="col-md-2 col-6">
                <div class="rank-card" onclick="setActive(this)">
                <img src="https://img.icons8.com/color/96/medal-second-place.png" alt="Silver">
                <div class="rank-title">Silver</div>
                <div class="rank-status inactive">Inactive</div>
                <div class="rank-details">25,000 S.V. Required on Levels 2</div>
                <div class="know-more" onclick="toggleInfo(this, event)">Know More...</div>
                <div class="extra-info">
                    Silver requires 25,000 S.V. and unlocks advanced rewards.
                </div>
                </div>
            </div>

            <!-- Rank 3 -->
            <div class="col-md-2 col-6">
                <div class="rank-card" onclick="setActive(this)">
                <img src="https://img.icons8.com/color/96/medal-first-place.png" alt="Gold">
                <div class="rank-title">Gold</div>
                <div class="rank-status inactive">Inactive</div>
                <div class="rank-details">1,25,000 S.V. Required on Levels 3</div>
                <div class="know-more" onclick="toggleInfo(this, event)">Know More...</div>
                <div class="extra-info">
                    Gold achievers reach 1,25,000 S.V. and gain premium benefits.
                </div>
                </div>
            </div>

            <!-- Add More Ranks Here -->
            
            </div>
        </div>
    </div>
</div>

@push('my-scripts');
<script>
  // Activate card
  function setActive(element) {
    // Reset all to inactive
    document.querySelectorAll('.rank-card').forEach(card => {
      card.classList.remove('active');
      card.querySelector('.rank-status').classList.remove('active');
      card.querySelector('.rank-status').classList.add('inactive');
      card.querySelector('.rank-status').innerText = "Inactive";
    });

    // Set active on clicked one
    element.classList.add('active');
    let status = element.querySelector('.rank-status');
    status.classList.remove('inactive');
    status.classList.add('active');
    status.innerText = "Active";

    // Update top heading
    let title = element.querySelector('.rank-title').innerText;
    document.getElementById('status-text').innerText = "Active: " + title;
  }

  // Toggle extra info
  function toggleInfo(el, event) {
    event.stopPropagation(); // Prevent triggering active state
    let info = el.nextElementSibling;
    if (info.style.display === "block") {
      info.style.display = "none";
      el.innerText = "Know More...";
    } else {
      info.style.display = "block";
      el.innerText = "Close";
    }
  }
</script>
@endpush

<!-- <div class="tab-panel active edit-form-open" id="my-acheivements">
    <h3 class="tab-title account-top">My Acheivements</h3>
    <div class="tab-content-body">
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
</div> -->
