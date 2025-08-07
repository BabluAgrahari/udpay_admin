<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" style="width:13.25rem !important;">
    <div class="app-brand demo">
        <a href="{{ url('crm/dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">

                <img src="{{ user()->logo ?? '' }}" style="width:50%;" alt="logo">
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
            <a href="{{ url('crm/dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Email">Dashboard</div>
            </a>
        </li>


        @if (auth()->user()->hasPermissionTo('user'))
            <li class="menu-item {{ Request::is('crm/user') ? 'active' : '' }}">
                <a href="{{ url('crm/user') }}" class="menu-link">
                    <i class='menu-icon tf-icons bx bx-group'></i>
                    <div class="text-truncate" data-i18n="Email">User</div>
                </a>
            </li>
        @endif

        @if (auth()->user()->hasPermissionTo('panel_user'))
            <li class="menu-item {{ Request::is('crm/panel-users*') ? 'active' : '' }}">
                <a href="{{ url('crm/panel-users') }}" class="menu-link">
                    <i class='menu-icon tf-icons bx bx-user-check'></i>
                    <div class="text-truncate" data-i18n="Email">Panel Users</div>
                </a>
            </li>
        @endif


        <li
            class="menu-item {{ Request::is('crm/brands.*') || Request::is('crm/categories.*') || Request::is('crm/products.*') || Request::is('crm/units.*') || Request::is('crm/stock-history.*') || Request::is('crm/coupons.*') ? 'open' : '' }}">

            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div class="text-truncate" data-i18n="Product Management">Product Management</div>
            </a>
            <ul class="menu-sub">
                @if (auth()->user()->hasPermissionTo('category'))
                    <li class="menu-item {{ Request::is('crm/categories.*') ? 'active' : '' }}">
                        <a href="{{ url('crm/categories') }}" class="menu-link">

                            <div class="text-truncate" data-i18n="Category">Category</div>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('brand'))
                    <li class="menu-item {{ Request::is('crm/brands.*') ? 'active' : '' }}">
                        <a href="{{ url('crm/brands') }}" class="menu-link">

                            <div class="text-truncate" data-i18n="Brand">Brand</div>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('product'))
                    <li class="menu-item {{ Request::is('crm/products.*') ? 'active' : '' }}">
                        <a href="{{ url('crm/products') }}" class="menu-link">

                            <div class="text-truncate" data-i18n="Product">Product</div>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('coupon'))
                    <li class="menu-item {{ Request::is('crm/coupons.*') ? 'active' : '' }}">
                        <a href="{{ url('crm/coupons') }}" class="menu-link">

                            <div class="text-truncate" data-i18n="Coupon">Coupon</div>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('unit'))
                    <li class="menu-item {{ Request::is('crm/units.*') ? 'active' : '' }}">
                        <a href="{{ url('crm/units') }}" class="menu-link">

                            <div class="text-truncate" data-i18n="Unit">Unit</div>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('stock_history'))
                    <li class="menu-item {{ Request::is('crm/stock-history.*') ? 'active' : '' }}">
                        <a href="{{ url('crm/stock-history') }}" class="menu-link">

                            <div class="text-truncate" data-i18n="Stock History">Stock History</div>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

        <li class="menu-item {{ Request::is('crm/slider') || Request::is('crm/reviews*') ? 'open' : '' }}">

            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div class="text-truncate" data-i18n="Manage">Manage</div>
            </a>
            <ul class="menu-sub">
                @if (auth()->user()->hasPermissionTo('slider'))
                    <li class="menu-item {{ Request::is('crm/slider') ? 'active' : '' }}">
                        <a href="{{ url('crm/slider') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="slider">Slider</div>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('review'))
                    <li class="menu-item {{ Request::is('crm/reviews*') ? 'active' : '' }}">
                        <a href="{{ url('crm/reviews') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="reviews">Reviews</div>
                        </a>
                    </li>
                @endif
            </ul>
        </li>



        <li class="menu-item {{ Request::is('crm/order') || Request::is('crm/pickup-addresses.*') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
                <div class="text-truncate" data-i18n="Order Management">Order Management</div>
            </a>
            <ul class="menu-sub">
                @if (auth()->user()->hasPermissionTo('order'))
                    <li class="menu-item {{ Request::is('crm/order') ? 'active' : '' }}">
                        <a href="{{ url('crm/order') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="Order">Orders</div>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('pickup_address'))
                    <li class="menu-item {{ Request::is('crm/pickup-addresses.*') ? 'active' : '' }}">
                        <a href="{{ url('crm/pickup-addresses') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="Pickup Address">Pickup Address</div>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

        <li class="menu-item {{ Request::is('crm/wallet*') ? 'open' : '' }}">

            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-wallet"></i>
                <div class="text-truncate" data-i18n="Deals">Wallet Management</div>
            </a>
            <ul class="menu-sub">
                @if (auth()->user()->hasPermissionTo('transfer_money_to_admin'))
                    <li class="menu-item {{ Request::is('crm/wallet/transfer') ? 'active' : '' }}">
                        <a href="{{ url('crm/wallet/transfer') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="Transfer Money">Transfer Money</div>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('transfer_money_to_user'))
                    <li class="menu-item {{ Request::is('crm/wallet/user-transfer') ? 'active' : '' }}">
                        <a href="{{ url('crm/wallet/user-transfer') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="User Transfer">Transfer Money</div>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermissionTo('transfer_money_to_user_to_user'))
                    <li class="menu-item {{ Request::is('crm/wallet/user-to-user-transfer') ? 'active' : '' }}">
                        <a href="{{ url('crm/wallet/user-to-user-transfer') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="User to User Transfer">User to User Transfer
                            </div>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasPermissionTo('transfer_history'))
                    <li class="menu-item {{ Request::is('crm/wallet/history') ? 'active' : '' }}">
                        <a href="{{ url('crm/wallet/history') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="Transfer History">Transfer History</div>
                        </a>
                    </li>
                @endif
            </ul>
        </li>



        <li class="menu-item {{ Request::is('crm/webhook-key') || Request::is('crm/api') || Request::is('crm/profile') || Request::is('crm/setting') || Request::is('crm/reset-password') ? 'open' : '' }}"
            style="">

            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div class="text-truncate" data-i18n="Account Settings">Account Settings</div>
            </a>
            <ul class="menu-sub">
                @can('isMerchant')
                    <!-- <li class="menu-item {{ Request::is('crm/webhook-key') ? 'active' : '' }}">
                                                                                    <a href="{{ url('crm/webhook-key') }}" class="menu-link">
                                                                                        <div class="text-truncate" data-i18n="Account">Webhook & Key</div>
                                                                                    </a>
                                                                                </li> -->
                    <!-- <li class="menu-item {{ Request::is('crm/api') ? 'active' : '' }}">
                                                                                    <a href="{{ url('crm/api') }}" class="menu-link">
                                                                                        <div class="text-truncate" data-i18n="Notifications">Api</div>
                                                                                    </a>
                                                                                </li> -->

                    <li class="menu-item {{ Request::is('crm/profile') ? 'active' : '' }}">
                        <a href="{{ url('crm/profile') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="Connections">My Account</div>
                        </a>
                    </li>
                @endcan

                @can('isSuperAdmin')
                    <li class="menu-item {{ Request::is('crm/setting') ? 'active' : '' }}">
                        <a href="{{ url('crm/setting') }}" class="menu-link">
                            <div class="text-truncate" data-i18n="Connections">My Account</div>
                        </a>
                    </li>
                @endcan

                <li class="menu-item {{ Request::is('crm/reset-password') ? 'active' : '' }}">
                    <a href="{{ url('crm/reset-password') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Connections">Reset Password</div>
                    </a>
                </li>
            </ul>
        </li>

        @can('isSuperAdmin')
            <ul class="menu-inner py-1">
                <li class="menu-item {{ Request::is('crm/role-permission') ? 'active' : '' }}">
                    <a href="{{ url('crm/role-permission') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-lock"></i>
                        <div class="text-truncate" data-i18n="Role & Permission">Role & Permission</div>
                    </a>
                </li>
            </ul>
        @endcan
    </ul>
</aside>
