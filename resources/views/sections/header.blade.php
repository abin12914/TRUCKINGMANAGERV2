<!-- Logo -->
<a href="#" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>TM</b>2</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>TM</b>2</span>
</a>
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </a>

    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            @if(!$expiredCertTrucks->isEmpty() || !$criticalCertTrucks->isEmpty())
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-danger">~</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">Important Messages</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                @if(!$expiredCertTrucks->isEmpty())
                                    <li><!-- Task item -->
                                        <a href="{{ route('trucks.certificates') }}">
                                            <h3 class="text-red">
                                                Certificates expired
                                                <small class="pull-right">Renew now</small>
                                            </h3>
                                        </a>
                                    </li>
                                    <!-- end task item -->
                                @endif
                                @if(!$criticalCertTrucks->isEmpty())
                                    <li><!-- Task item -->
                                        <a href="{{ route('trucks.certificates') }}">
                                            <h3 class="text-orange">
                                                Certificates expiring soon
                                                <small class="pull-right">Renew now</small>
                                            </h3>
                                        </a>
                                    </li>
                                    <!-- end task item -->
                                @endif
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="{{ route('trucks.certificates') }}">View all certificates</a>
                        </li>
                    </ul>
                </li>
            @endif
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ !empty($loggedUser) ? Voyager::image($loggedUser->avatar, '/images/default_user.jpg') : '/images/default_user.jpg' }}" class="user-image" alt="User Image">
                    <span class="hidden-xs">{{ $loggedUser->name }}</span>
                </a>
                <ul class="dropdown-menu">
                    <!-- User image -->
                    <li class="user-header">
                        <img src="{{ !empty($loggedUser) ? Voyager::image($loggedUser->avatar, '/images/default_user.jpg') : '/images/default_user.jpg' }}" class="img-circle" alt="User Image">
                        <p>
                            {{ $loggedUser->name }}
                        </p>
                    </li>

                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="{{ route('user.profile.edit') }}" class="btn btn-default btn-flat">Profile</a>
                        </div>
                        <div class="pull-right">
                            <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->
            <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
            </li>
        </ul>
    </div>
</nav>
