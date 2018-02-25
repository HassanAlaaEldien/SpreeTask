<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
                <a href="{{ route('home') }}" class="active"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
            </li>
            <li>
                <a href="{{ route('listNews') }}">
                    <i class="fa fa-bar-chart-o fa-fw"></i> News
                </a>
            </li>
            @can('listUsers',\App\User::class)
                <li>
                    <a href="{{ route('listUsers') }}">
                        <i class="fa fa-edit fa-fw"></i> Users
                    </a>
                </li>
            @endcan
        </ul>
    </div>
</div>