<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
            <img src="/images/default_user.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
            @if(!empty($loggedUser))
                <p>{{ $loggedUser->name }}</p>
                <a href="#"><i class="fa  fa-hand-o-right"></i> View Profile</a>
            @else
                <p>Login</p>
                <a href="{{ route('login') }}"><i class="fa  fa-hand-o-right"></i> To continue</a>
            @endif
        </div>
    </div>
    @if(!empty($loggedUser))
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="{{ Request::is('dashboard')? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-pie-chart"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview {{ Request::is('reports/*')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-briefcase"></i>
                    <span>Reports</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('reports/account-statement')? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="fa fa-circle-o text-green"></i> Account Statement
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ (Request::is('transportations/*') || Request::is('transportations')) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-road"></i>
                    <span>Transportations</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('transportations/create')? 'active' : '' }}">
                        <a href="{{ route('transportations.create') }}">
                            <i class="fa fa-circle-o text-yellow"></i> Register
                        </a>
                    </li>
                    <li class="{{ Request::is('transportations')? 'active' : '' }}">
                        <a href="{{ route('transportations.index') }}">
                            <i class="fa fa-circle-o text-aqua"></i> List
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ ( Request::is('supply/*') || Request::is('supply') )? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-refresh"></i>
                    <span>Material Supply</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('supply/create')? 'active' : '' }}">
                        <a href="{{ route('supply.create') }}">
                            <i class="fa fa-circle-o text-yellow"></i> Register
                        </a>
                    </li>
                    <li class="{{ Request::is('supply')? 'active' : '' }}">
                        <a href="{{ route('supply.index') }}">
                            <i class="fa fa-circle-o text-aqua"></i> List
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('expense/*') || Request::is('expense')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-wrench"></i>
                    <span>Services & Expences</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('expense/create')? 'active' : '' }}">
                        <a href="{{route('expenses.create') }}">
                            <i class="fa fa-circle-o text-yellow"></i> Register
                        </a>
                    </li>
                    <li class="{{ Request::is('expense')? 'active' : '' }}">
                        <a href="{{ route('expenses.index') }}">
                            <i class="fa fa-circle-o text-aqua"></i> List
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('voucher/*') || Request::is('voucher')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-envelope-o"></i>
                    <span>Vouchers & Reciepts</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('voucher/create')? 'active' : '' }}">
                        <a href="{{route('vouchers.create') }}">
                            <i class="fa fa-circle-o text-yellow"></i> Register
                        </a>
                    </li>
                    <li class="{{ Request::is('voucher')? 'active' : '' }}">
                        <a href="{{route('vouchers.index') }}">
                            <i class="fa fa-circle-o text-aqua"></i> List
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('site/*') || Request::is('site')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-map"></i>
                    <span>Site</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('site/create')? 'active' : '' }}">
                        <a href="{{route('sites.create') }}">
                            <i class="fa fa-circle-o text-yellow"></i> Register
                        </a>
                    </li>
                    <li class="{{ Request::is('site')? 'active' : '' }}">
                        <a href="{{route('sites.index') }}">
                            <i class="fa fa-circle-o text-aqua"></i> List
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('account/*') || Request::is('account') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-book"></i>
                    <span>Accounts</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('account/create')? 'active' : '' }}">
                        <a href="{{route('accounts.create') }}">
                            <i class="fa fa-circle-o text-yellow"></i> Register
                        </a>
                    </li>
                    <li class="{{ Request::is('account')? 'active' : '' }}">
                        <a href="{{route('accounts.index') }}">
                            <i class="fa fa-circle-o text-aqua"></i> List
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('employee/*') || Request::is('employee')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-male"></i>
                    <span>Employees</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('employee/create')? 'active' : '' }}">
                        <a href="{{route('employees.create') }}">
                            <i class="fa fa-circle-o text-yellow"></i> Register
                        </a>
                    </li>
                    <li class="{{ Request::is('employee')? 'active' : '' }}">
                        <a href="{{route('employees.index') }}">
                            <i class="fa fa-circle-o text-aqua"></i> List
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('trucks/*') || Request::is('trucks') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-truck"></i>
                    <span>Trucks</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('trucks/create')? 'active' : '' }}">
                        <a href="{{route('trucks.create') }}">
                            <i class="fa fa-circle-o text-yellow"></i> Register
                        </a>
                    </li>
                    <li class="{{ Request::is('trucks')? 'active' : '' }}">
                        <a href="{{route('trucks.index') }}">
                            <i class="fa fa-circle-o text-aqua"></i> List
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    @endif
</section>
<!-- /.sidebar -->
