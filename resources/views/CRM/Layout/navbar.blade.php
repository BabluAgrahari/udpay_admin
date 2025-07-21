<nav
    class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="bx bx-menu bx-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search bx-md"></i>
                <input
                    type="text"
                    class="form-control border-0 shadow-none ps-1 ps-sm-2"
                    placeholder="Search..."
                    aria-label="Search..." />
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Place this tag where you want the button to render. -->

            <li class="nav-item lh-1 me-4">
                <!-- <a
                    class="github-button"
                    href="https://github.com/themeselection/sneat-html-admin-template-free"
                    data-icon="octicon-star"
                    data-size="large"
                    data-show-count="true"
                    aria-label="Star themeselection/sneat-html-admin-template-free on GitHub">Star</a> -->

            </li>


            <li class="nav-item lh-1 me-4">
                <!-- <a
                                    class="github-button"
                                    href="https://github.com/themeselection/sneat-html-admin-template-free"
                                    data-icon="octicon-star"
                                    data-size="large"
                                    data-show-count="true"
                                    aria-label="Star themeselection/sneat-html-admin-template-free on GitHub">Star</a> -->
                <span>{{strtoupper(Auth::user()->role)}}</span>
            </li>

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a
                    class="nav-link dropdown-toggle hide-arrow p-0"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{Auth::user()->profile_pic??'../assets/img/avatars/1.png'}}" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{Auth::user()->profile_pic??'../assets/img/avatars/1.png'}}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ucwords(Auth::user()->name)}}</h6>
                                    <small class="text-muted">{{ucwords(Auth::user()->role)}}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        @can('isMerchant')
                        <a class="dropdown-item" href="{{url('crm/profile')}}">
                            <i class="bx bx-user bx-md me-3"></i><span>My Profile</span>
                        </a>
                        @endif
                        @can('isSuperAdmin')
                        <a class="dropdown-item" href="{{url('crm/setting')}}">
                            <i class="bx bx-user bx-md me-3"></i><span>Account</span>
                        </a>
                        @endcan
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{url('crm/reset-password')}}"><i class='bx bxs-key me-3'></i><span>Reset Password</span> </a>
                    </li>
                    <!-- <li>
                        <a class="dropdown-item" href="#">
                            <span class="d-flex align-items-center align-middle">
                                <i class="flex-shrink-0 bx bx-credit-card bx-md me-3"></i><span class="flex-grow-1 align-middle">Billing Plan</span>
                                <span class="flex-shrink-0 badge rounded-pill bg-danger">4</span>
                            </span>
                        </a>
                    </li> -->
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{url('crm/logout')}}">
                            <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>

</nav>


<style>
    .alertalert {
        box-shadow: 0 0.125rem 0.375rem 0 rgba(34, 48, 62, 0.08);
        width: calc(100% - 1.625rem* 2);
        margin: 1rem auto 0;
        border-radius: 0.375rem;
    }
</style>
