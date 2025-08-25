<section class="section-padding">
    <div class="container">
        <div class="row">

            <!-- Left Stats -->
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <h5>My Direct</h5>
                            <h3>{{ number_format($my_direct_referal ?? 0, 2) }}</h3>
                            <p>Partners</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <h5>Generation Team</h5>
                            <h3>{{ number_format($generationTeam ?? 0, 2) }}</h3>
                            <p>Partners</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <h5>Total Team</h5>
                            <h3>{{ number_format($totalTeamSv ?? 0, 2) }}</h3>
                            <p>SV</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <h5>My Direct</h5>
                            <h3>{{ number_format($myDirectSv ?? 0, 2) }}</h3>
                            <p>SV</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <h5>Generation 2</h5>
                            <h3>0</h3>
                            <p>SV</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <h5>Self SV</h5>
                            <h3>{{ number_format($current_month_sv ?? 0, 2) }}</h3>
                            <p>Current Month</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <h5>Self SV</h5>
                            <h3>{{ number_format($total_self_sv ?? 0, 2) }}</h3>
                            <p>Total</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <h5>Total</h5>
                            <h3>{{ number_format($total_earning ?? 0, 2) }}</h3>
                            <p>Earning</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile -->
            <div class="col-lg-3">
                <div class="profile-card">
                    <img src="{{asset('')}}front_assets/images/user.png" alt="Profile" class="referral-image">
                    <h5>{{Auth::user()->name ?? ''}}</h5>
                    <p class="mb-3">Uni id : {{ Auth::user()->alpha_num_uid ?? '' }}</p>
                    <p>Rank: <strong>
                        @if(!empty($rank->bronze))
                        1
                        @elseif(!empty($rank->silver))
                        2
                        @elseif(!empty($rank->gold))
                        3
                        @elseif(!empty($rank->diamond))
                        4
                        @elseif(!empty($rank->crown_diamond))
                        5
                        @else
                        0
                        @endif
                        Star</strong></p>
                    <!-- Referral link -->
                    <input type="hidden" id="myInput" value="{{ url('distributor/signup') }}/{{ Auth::user()->user_num ?? '' }}">
                    
                    <!-- Buttons -->
                    <button class="thm-btn btn-referral" id="copyLinkBtn" onclick="copyLink()">Copy Referral Link</button>
                    {{-- only show in mobile -- add function to share link --}}
                    <button class="thm-btn btn-referral mt-2 mobile-share-btn" onclick="shareLink()">Share Referral Link</button>
                    <p class=" mt-3 thm-btn">Wallat Balance: {{ number_format(walletBalance(Auth::user()->user_id), 2) }}</p>
                </div>
            </div>

        </div>
    </div>
</section>
<style>
    /* Hide by default */
    .mobile-share-btn {
      display: none;
    }
    
    /* Show only on small screens (mobile) */
    @media (max-width: 768px) {
      .mobile-share-btn {
        display: block;
      }
    }
    </style>
@push('scripts')
<script>
    function copyLink() {
        var copyText = document.getElementById("myInput");
        // Use modern Clipboard API if available
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(copyText.value).then(() => {
                document.getElementById("copyLinkBtn").innerHTML = "Copied ✅";
            });
        } else {
            // Fallback for older browsers
            copyText.type = 'text'; 
            copyText.select();
            document.execCommand("copy");
            copyText.type = 'hidden';
            document.getElementById("copyLinkBtn").innerHTML = "Copied ✅";
        }
    }

    function shareLink() {
        var shareUrl = document.getElementById("myInput").value;
        if (navigator.share) {
            navigator.share({
                title: "Join me on Our Platform",
                text: "Here’s my referral link, sign up now!",
                url: shareUrl,
            }).catch(console.error);
        } else {
            // Fallback → Copy if share not supported
            copyLink();
            alert("Share not supported in this browser. Referral link copied instead!");
        }
    }
</script>
@endpush
