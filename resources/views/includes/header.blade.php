<div class="navbar-header">
    <a class="navbar-brand" href="{{ route('home') }}">Spree Task</a>
</div>

<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
</button>

<ul class="nav navbar-right navbar-top-links">
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-user fa-fw"></i> {{ \Illuminate\Support\Facades\Auth::user()->name }} <b class="caret"></b>
        </a>
        <ul class="dropdown-menu dropdown-user">
            <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
            </li>
        </ul>
    </li>
</ul>
<!-- /.navbar-top-links -->