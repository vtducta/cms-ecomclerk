<?php \Optimait\Laravel\Helpers\Nav::setSegments(Request::segments());?>

<aside class="left-sidebar">
    <div class="scroll-sidebar">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap">NAVIGATION</li>

                <li class="<?php echo \Optimait\Laravel\Helpers\Nav::isActiveMultiple(array('dashboard')) ? 'active' : ''; ?>">
                    <a href="<?php echo URL::to('/dashboard'); ?>">
                        <i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a>
                    </a>
                </li>
				
				<li class="<?php echo \Optimait\Laravel\Helpers\Nav::isActiveMultiple(array('areports')) ? 'active' : ''; ?>">
                    <a href="{{ route('webpanel.reports.index') }}" title="reports">
                        <i class="mdi mdi-chart-areaspline"></i>
                        <span class="hide-menu">Reports</span>
                    </a>
                </li>

                <li class="<?php echo \Optimait\Laravel\Helpers\Nav::isActiveMultiple(array('vendors','vendorProducts')) ? 'active' : ''; ?>">
                    <a href="#" title="Vendors" class="has-arrow" aria-expanded="false">
                        <i class="mdi mdi-file"></i>
                        <span class="hide-menu">Vendors</span>
                    </a>
                    <ul class="collapse" aria-expanded="false">
                        <li><a href="{{ route('webpanel.vendors.index') }}" title="Vendors">Manage Vendors</a></li>
                        <li><a href="{{ route('webpanel.vendor-products.index') }}" title="VendorProducts">Manage Vendor Products</a></li>
                    </ul>
                </li>

                <li class="<?php echo \Optimait\Laravel\Helpers\Nav::isActiveMultiple(array('ProductFBA')) ? 'active' : ''; ?>">
                    <a href="{{ route('webpanel.fbaproducts.index') }}" title="ProductFBA">
                        <i class="mdi mdi-account"></i>
                        <span class="hide-menu">Product FBA</span>
                    </a>
                </li>

                <li class="<?php echo \Optimait\Laravel\Helpers\Nav::isActiveMultiple(array('purchaseOrders')) ? 'active' : ''; ?>">
                    <a href="{{ route('webpanel.purchaseorders.index') }}" title="PurchaseOrders">
                        <i class="mdi mdi-account"></i>
                        <span class="hide-menu">Purchase Orders</span>
                    </a>
                </li>

                <li class="<?php echo \Optimait\Laravel\Helpers\Nav::isActiveMultiple(array('products')) ? 'active' : ''; ?>">
                    <a href="{{ sysRoute('products.index') }}">
                        <i class="mdi mdi-amazon"></i><span class="hide-menu">Intake</span>
                    </a>
                </li>

                <li class="<?php echo \Optimait\Laravel\Helpers\Nav::isActiveMultiple(array('integration')) ? 'active' : ''; ?>">
                    <a href="{{ sysRoute('integrations.index') }}">
                        <i class="mdi mdi-power-plug"></i><span class="hide-menu">Integrations</span>
                    </a>
                </li>

                @if (Auth::user()->isAdmin())
                <li class="<?php echo \Optimait\Laravel\Helpers\Nav::isActiveMultiple(array('users')) ? 'active' : ''; ?>">
                    <a href="{{ route('webpanel.users.index') }}" title="Users">
                        <i class="mdi mdi-account"></i>
                        <span class="hide-menu">Manage Users</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('logout') }}">
                        <i class="mdi mdi-login"></i><span class="hide-menu">Log Out</span></a>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

</aside>

