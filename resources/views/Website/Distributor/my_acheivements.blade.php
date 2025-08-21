<div class="tab-panel active edit-form-open" id="my-acheivements">
    <h3 class="tab-title account-top">My Acheivements</h3>
    <div class="tab-content-body">
    <div class="rank-container">
            <h6 id="status-text">All Inactive</h6>
        <div class="row g-3 justify-content-center">
          <!-- Rank 1 -->
          <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="rank-card" onclick="setActive(this)">
              <div class="rank-opicity inactive">
                  <div class="rank-image"><img src="../front_assets/images/bronze.png" alt="Bronze"></div>
                  <div class="rank-title thm-btn">Bronze</div>
              </div>
              <div class="rank-details">5,000 S.V. Required on Levels</div>
              <button class="know-btn color-two" data-target="bronze-info">
                Know More...
              </button>
            </div>
          </div>
          <!-- Rank 2 -->
          <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="rank-card" onclick="setActive(this)">
                <div class="rank-opicity inactive">
                    <div class="rank-image"><img src="../front_assets/images/sliver.png" alt="Bronze"></div>
                    <div class="rank-title thm-btn">Silver</div>
              </div>
              <div class="rank-details">25,000 S.V. Required on Levels 2</div>
            <button class="know-btn color-two" data-target="silver-info">
                Know More...
              </button>
            </div>
          </div>
          <!-- Rank 3 -->
          <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="rank-card" onclick="setActive(this)">
                <div class="rank-opicity inactive">
                     <div class="rank-image"><img src="../front_assets/images/gold.png" alt="Bronze"></div>
                    <div class="rank-title thm-btn">Gold</div>
              </div>
              <div class="rank-details">1,25,000 S.V. Required on Levels 3</div>
              <button class="know-btn color-two" data-target="gold-info">
                Know More...
              </button>
            </div>
          </div>
          <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="rank-card" onclick="setActive(this)">
                <div class="rank-opicity inactive">
                     <div class="rank-image"><img src="../front_assets/images/platinum.png" alt="Bronze"></div>
                    <div class="rank-title thm-btn">Platinum</div>
                </div>
              <div class="rank-details">6,25,000 S.V. Required on Levels 4</div>
             <button class="know-btn color-two" data-target="platinum">
                Know More...
              </button>
            </div>
          </div>
          <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="rank-card" onclick="setActive(this)">
                <div class="rank-opicity inactive">
                     <div class="rank-image">
                        <img src="../front_assets/images/diamond.png" alt="Bronze"></div>
                    <div class="rank-title thm-btn">Diamond</div>
                </div>
              <div class="rank-details">31,25,000 S.V. Required on Levels 5</div>
                <button class="know-btn color-two" data-target="diamond">
                Know More...
              </button>
            </div>
          </div>
          <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="rank-card" onclick="setActive(this)">
                <div class="rank-opicity inactive">
                    <div class="rank-image">
                        <img src="../front_assets/images/crown-diamond.png" alt="Bronze"></div>
                    <div class="rank-title thm-btn">Crown Diamond</div>
                </div>
              <div class="rank-details">1,56,25,000 S.V. Required on Levels 6</div>
              <button class="know-btn color-two" data-target="crown-diamond">
                Know More...
              </button>
            </div>
          </div>
        </div>
        
        <!-- Bottom Info Boxes (all hidden by default) -->
        <div id="bronze-info" class="rank-info" style="display:none;">
          <h4 class="konw-title">Bronze Rank</h4>
          <p class="thm-btn">My Total Earnings  2,500 S.V. </p>
           <div class="progress-container">
            <div class="progress-bar" id="progressBar"></div>
            <div class="progress-text" id="progressText">0%</div>
          </div>
          <p>Earn20,000 S.V. to unlock the next level.</p>
          <div class="row justify-content-center">
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Royalty</h4>
                      <img src="../front_assets/images/money.png" class="">
                      <p>2% of Company<br> Turn Over</p>
                  </div>
              </div>
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Reward</h4>
                      <img src="../front_assets/images/tshirt.png" class="">
                      <p>T-Shirt</p>
                  </div>
              </div>
          </div>
        </div>
        
        <div id="silver-info" class="rank-info" style="display:none;">
          <h4 class="konw-title">Silver Rank</h4>
          <p class="thm-btn">My Total Earnings  20,000 S.V. </p>
           <div class="progress-container">
            <div class="progress-bar" id="progressBar2"></div>
            <div class="progress-text" id="progressText2">0%</div>
          </div>
          <p>Earn 1,05,000 S.V. to unlock the next level.</p>
           <div class="row justify-content-center">
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Royalty</h4>
                      <img src="../front_assets/images/money.png" class="">
                      <p>1.5% of Company<br> Turn Over</p>
                  </div>
              </div>
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Reward</h4>
                      <img src="../front_assets/images/reward.png" class="">
                      <p>Mobile Fund ₹15,000<br> Cto: 2% Monthly</p>
                  </div>
              </div>
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Tour</h4>
                      <img src="../front_assets/images/tour.png" class="">
                      <p>1 Day Educational<br> Prog. Free</p>
                  </div>
              </div>
          </div>
        </div>
        
        <div id="gold-info" class="rank-info" style="display:none;">
          <h4 class="konw-title">Gold Rank</h4>
          <p class="thm-btn">My Total Earnings  1,00,000 S.V. </p>
           <div class="progress-container">
            <div class="progress-bar" id="progressBar3"></div>
            <div class="progress-text" id="progressText3">0%</div>
          </div>
          <p>Earn 25,000 S.V. to unlock the next level</p>
           <div class="row justify-content-center">
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Royalty</h4>
                      <img src="../front_assets/images/money.png" class="">
                      <p>1.5% of Company<br> Turn Over</p>
                  </div>
              </div>
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Reward</h4>
                      <img src="../front_assets/images/leptop.png" class="">
                      <p>Laptop Fund ₹40,000<br> Cto: 2% Monthly</p>
                  </div>
              </div>
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Tour</h4>
                      <img src="../front_assets/images/tour.png" class="">
                      <p>1 Night 2 Days RTP<br> Free</p>
                  </div>
              </div>
          </div>
        </div>
        
        <div id="platinum" class="rank-info" style="display:none;">
          <h4 class="konw-title">Platinum</h4>
          <p class="thm-btn">My Total Earnings  5,00,000 S.V. </p>
           <div class="progress-container">
            <div class="progress-bar" id="progressBar4"></div>
            <div class="progress-text" id="progressText4">0%</div>
          </div>
          <p>Earn 1,25,000 S.V. to unlock the next level</p>
           <div class="row justify-content-center">
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Royalty</h4>
                      <img src="../front_assets/images/money.png" class="">
                      <p>2% of Company<br> Turn Over</p>
                  </div>
              </div>
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Reward</h4>
                      <img src="../front_assets/images/bike.png" class="">
                      <p>Bike Fund ₹1,00,000<br> Cto: 1% Monthly</p>
                  </div>
              </div>
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Tour</h4>
                      <img src="../front_assets/images/tour.png" class="">
                      <p>2 Night 3 Days LDP<br> With Transportation</p>
                  </div>
              </div>
          </div>
        </div>
        
        <div id="diamond" class="rank-info" style="display:none;">
          <h4 class="konw-title">Diamond Rank</h4>
          <p class="thm-btn">My Total Earnings  10,00,000 S.V. </p>
           <div class="progress-container">
            <div class="progress-bar" id="progressBar5"></div>
            <div class="progress-text" id="progressText5">0%</div>
          </div>
          <p>Earn 21,25,000 S.V. to unlock the next level</p>
           <div class="row justify-content-center">
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Royalty</h4>
                      <img src="../front_assets/images/money.png" class="">
                      <p>1% of Company<br> Turn Over</p>
                  </div>
              </div>
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Reward</h4>
                      <img src="../front_assets/images/car.png" class="">
                      <p>Car Fund ₹5,00,000<br> Cto: 1% Monthly</p>
                  </div>
              </div>
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Tour</h4>
                      <img src="../front_assets/images/tour.png" class="">
                      <p>3 Night 4 Days<br> Malaysia Tour Free</p>
                  </div>
              </div>
          </div>
        </div>
        
        <div id="crown-diamond" class="rank-info" style="display:none;">
          <h4 class="konw-title mb-5">Crown Diamond</h4>
          <p class="thm-btn">My Total Earnings  1,00,00,000 S.V. </p>
           <div class="progress-container">
            <div class="progress-bar" id="progressBar6"></div>
            <div class="progress-text" id="progressText6">0%</div>
          </div>
           <div class="row justify-content-center">
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Royalty</h4>
                      <img src="../front_assets/images/money.png" class="">
                      <p>1% of Company<br> Turn Over</p>
                  </div>
              </div>
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Reward</h4>
                      <img src="../front_assets/images/home.png" class="">
                      <p>House Fund ₹1200000<br> Cto: 1% Monthly</p>
                  </div>
              </div>
              <div class="col-lg-4 col-md-6">
                  <div class="features-rank" style="background-image: url('../front_assets/images/royalty.png');">
                      <h4>Tour</h4>
                      <img src="../front_assets/images/tour.png" class="">
                      <p>4 Night 5 Days<br> Thailand Tour Free</p>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>
</div>

@push('my-scripts');
<script>
    function updateProgress(barId, textId, percent) {
      const progressBar = document.getElementById(barId);
      const progressText = document.getElementById(textId);

      progressBar.style.width = percent + "%";
      progressText.textContent = percent + "%";

      // Move the circle with the bar
      progressText.style.left = percent + "%";
    }

    // Example: set multiple progress values
    updateProgress("progressBar", "progressText", 50); // 50%
    updateProgress("progressBar2", "progressText2", 75); // 75%
    updateProgress("progressBar3", "progressText3", 30); // 30%
    updateProgress("progressBar4", "progressText4", 45); 
    updateProgress("progressBar5", "progressText5", 65); 
    updateProgress("progressBar6", "progressText6", 74); 
  </script>
<script>

  function setActive(element) {
  // reset all to inactive
  document.querySelectorAll('.rank-card').forEach(card => {
    card.classList.remove('active');
    card.classList.add('inactive');
  });

  // set clicked one active
  element.classList.remove('inactive');
  element.classList.add('active');

  // update heading
  let title = element.querySelector('.rank-title').innerText;
  document.getElementById('status-text').innerText = "Active: " + title;
}
document.querySelectorAll(".know-btn").forEach(btn => {
  btn.addEventListener("click", function() {
    const targetId = this.getAttribute("data-target");
    const infoBox = document.getElementById(targetId);

    if (infoBox.style.display === "block") {
      infoBox.style.display = "none"; // close if already open
    } else {
      infoBox.style.display = "block"; // open new one
    }
  });
});
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
