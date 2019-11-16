<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
            <img src="{{ !empty($loggedUser) ? Voyager::image($loggedUser->avatar, '/images/default_user.jpg') : '/images/default_user.jpg' }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
            @if(!empty($loggedUser))
                <p>{{ $loggedUser->name }}</p>
                <a href="{{ route('user.profile.edit') }}"><i class="fa  fa-hand-o-right"></i> View Profile</a>
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
                        <a href="{{ route('reports.account-statement') }}">
                            <i class="fa fa-circle-o text-green"></i> Account Statement
                        </a>
                    </li>
                    <li class="{{ Request::is('reports/credit-statement')? 'active' : '' }}">
                        <a href="{{ route('reports.credit-statement') }}">
                            <i class="fa fa-circle-o text-blue"></i> Credit Statement
                        </a>
                    </li>
                    <li class="{{ Request::is('reports/profit-loss-statement')? 'active' : '' }}">
                        <a href="{{ route('reports.profit-loss-statement') }}">
                            <i class="fa fa-circle-o text-orange"></i> Profit Losss Statement
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
            <li class="treeview {{ Request::is('expenses/*') || Request::is('expenses') || Request::is('fuel/refill') || Request::is('certificates') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-wrench"></i>
                    <span>Services & Expences</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('expenses/create')? 'active' : '' }}">
                        <a href="{{route('expenses.create') }}">
                            <i class="fa fa-circle-o text-yellow"></i> Register
                        </a>
                    </li>
                    <li class="{{ Request::is('expenses')? 'active' : '' }}">
                        <a href="{{ route('expenses.index') }}">
                            <i class="fa fa-circle-o text-aqua"></i> List
                        </a>
                    </li>
                    <li class="{{ Request::is('certificates')? 'active' : '' }}">
                        <a href="{{route('trucks.certificates') }}">
                            <i class="fa fa-circle-o text-red"></i> Certificates
                        </a>
                    </li>
                    <li class="{{ Request::is('fuel/refill')? 'active' : '' }}">
                        <a href="{{route('expense.fuel.refill') }}">
                            <i class="fa fa-circle-o text-blue"></i> Fuel Refill
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('vouchers/*') || Request::is('vouchers')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-envelope-o"></i>
                    <span>Vouchers & Reciepts</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('vouchers/create')? 'active' : '' }}">
                        <a href="{{route('vouchers.create') }}">
                            <i class="fa fa-circle-o text-yellow"></i> Register
                        </a>
                    </li>
                    <li class="{{ Request::is('vouchers')? 'active' : '' }}">
                        <a href="{{route('vouchers.index') }}">
                            <i class="fa fa-circle-o text-aqua"></i> List
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('sites/*') || Request::is('sites')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-map"></i>
                    <span>Site</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('sites/create')? 'active' : '' }}">
                        <a href="{{route('sites.create') }}">
                            <i class="fa fa-circle-o text-yellow"></i> Register
                        </a>
                    </li>
                    <li class="{{ Request::is('sites')? 'active' : '' }}">
                        <a href="{{route('sites.index') }}">
                            <i class="fa fa-circle-o text-aqua"></i> List
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('accounts/*') || Request::is('accounts') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-book"></i>
                    <span>Accounts</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('accounts/create')? 'active' : '' }}">
                        <a href="{{route('accounts.create') }}">
                            <i class="fa fa-circle-o text-yellow"></i> Register
                        </a>
                    </li>
                    <li class="{{ Request::is('accounts')? 'active' : '' }}">
                        <a href="{{route('accounts.index') }}">
                            <i class="fa fa-circle-o text-aqua"></i> List
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ Request::is('employees/*') || Request::is('employees')? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-male"></i>
                    <span>Employees</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('employees/create')? 'active' : '' }}">
                        <a href="{{route('employees.create') }}">
                            <i class="fa fa-circle-o text-yellow"></i> Register
                        </a>
                    </li>
                    <li class="{{ Request::is('employees')? 'active' : '' }}">
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
