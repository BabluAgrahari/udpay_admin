<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" style="width:13.25rem !important;">
    <div class="app-brand demo">
        <a href="{{url('crm/dashboard')}}" class="app-brand-link">
            <span class="app-brand-logo demo">

                <img src="{{user()->logo ?? ''}}" style="width:50%;" alt="logo">
            </span>
            <!-- <span class="app-brand-text demo menu-text fw-bold ms-2">Pay</span> -->
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ Request::is('crm/dashboard') ? 'active' : '' }}">
            <a href="{{url('crm/dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Email">Dashboard</div>
            </a>
        </li>

        @can('isSuperAdmin')
            <li class="menu-item {{ Request::is('crm/user') ? 'active' : '' }}">
                <a href="{{url('crm/user')}}" class="menu-link">
                    <i class='menu-icon tf-icons bx bx-group'></i>
                    <div class="text-truncate" data-i18n="Email">User</div>
                </a>
            </li>


            <li
                class="menu-item {{ Request::is('crm/brands') || Request::is('crm/categories') || Request::is('crm/products') || Request::is('crm/reset-password') ? 'open' : '' }}">

                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div class="text-truncate" data-i18n="Deals">Deals</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ Request::is('crm/categories') ? 'active' : '' }}">
                        <a href="{{url('crm/categories')}}" class="menu-link">
                            <div class="text-truncate" data-i18n="Connections">Category</div>
                        </a>
                    </li>

                    <li class="menu-item {{ Request::is('crm/brands') ? 'active' : '' }}">
                        <a href="{{url('crm/brands')}}" class="menu-link">
                            <div class="text-truncate" data-i18n="Connections">Brand</div>
                        </a>
                    </li>

                    <li class="menu-item {{ Request::is('crm/products') ? 'active' : '' }}">
                        <a href="{{url('crm/products')}}" class="menu-link">
                            <div class="text-truncate" data-i18n="Connections">Product</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        <!-- <li class="menu-item">
            <a href="{{url('crm/caller-agent')}}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-user-voice'></i>
                <div class="text-truncate" data-i18n="Email">Caller Agent</div>
            </a>
        </li> -->


        @can('isAdmin')
            <!--            
                                            <li class="menu-item {{ Request::is('crm/invoice/all') ? 'active' : '' }}">
                                                <a href="{{url('crm/invoice/all')}}" class="menu-link">

                                                    <i class='menu-icon bx bxs-file-pdf'></i>
                                                    <div class="text-truncate" data-i18n="Email">Invoice</div>
                                                </a>
                                            </li> -->
        @endcan

        <li class="menu-item {{ Request::is('crm/webhook-key') || Request::is('crm/api') || Request::is('crm/profile') || Request::is('crm/setting') || Request::is('crm/reset-password') ? 'open' : '' }}"
            style="">

            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div class="text-truncate" data-i18n="Account Settings">Account Settings</div>
            </a>
            <ul class="menu-sub">
                @can('isMerchant')
                    <!-- <li class="menu-item {{ Request::is('crm/webhook-key') ? 'active' : '' }}">
                                                        <a href="{{url('crm/webhook-key')}}" class="menu-link">
                                                            <div class="text-truncate" data-i18n="Account">Webhook & Key</div>
                                                        </a>
                                                    </li> -->
                    <!-- <li class="menu-item {{ Request::is('crm/api') ? 'active' : '' }}">
                                                        <a href="{{url('crm/api')}}" class="menu-link">
                                                            <div class="text-truncate" data-i18n="Notifications">Api</div>
                                                        </a>
                                                    </li> -->

                    <li class="menu-item {{ Request::is('crm/profile') ? 'active' : '' }}">
                        <a href="{{url('crm/profile')}}" class="menu-link">
                            <div class="text-truncate" data-i18n="Connections">My Account</div>
                        </a>
                    </li>
                @endcan

                @can('isSuperAdmin')
                    <li class="menu-item {{ Request::is('crm/setting') ? 'active' : '' }}">
                        <a href="{{url('crm/setting')}}" class="menu-link">
                            <div class="text-truncate" data-i18n="Connections">My Account</div>
                        </a>
                    </li>
                @endcan

                <li class="menu-item {{ Request::is('crm/reset-password') ? 'active' : '' }}">
                    <a href="{{url('crm/reset-password')}}" class="menu-link">
                        <div class="text-truncate" data-i18n="Connections">Reset Password</div>
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</aside>