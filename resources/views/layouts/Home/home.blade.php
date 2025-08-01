
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('home') }}" class="logo">
                <img src="p.png" alt="navbar brand" class="navbar-brand" height="50" />
                <h5 class="text-white m-2">AdminSystem</h5>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <!-- Home Dashboard -->
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>HomeDashborad</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="dashboard">
                        <ul class="nav nav-collapse">
                            @php
                                $role = strtolower(auth()->user()->role ?? '');
                            @endphp

                            @if(in_array($role, ['super_admin', 'admin']))
                                <li>
                                    <a href="{{ route('superadmin.customers.index') }}">
                                        <span class="sub-item">Customer</span>
                                    </a>
                                </li>
                            @elseif($role === 'writer')
                                <li>
                                    <a href="{{ route('writer.dashboard') }}">
                                        <span class="sub-item">Writer Dashboard</span>
                                    </a>
                                </li>
                            @elseif($role === 'customer')
                                <li>
                                    <a href="{{ route('home') }}">
                                        <span class="sub-item">Customer Dashboard</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Aorchor</h4>
                </li>

                @if(in_array($role, ['super_admin', 'admin']))
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#base">
                            <i class="fas fa-layer-group"></i>
                            <p>Base</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="base">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route($role . '.categories.index') }}">
                                        <span class="sub-item">Categories</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if(in_array($role, ['super_admin', 'admin', 'writer', 'customer']))
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#maps">
                            <i class="fas fa-layer-group"></i>
                            <p>Poster</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="maps">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route($role . '.posts.index') }}">
                                        <span class="sub-item">AllPoster</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route($role . '.posts.create') }}">
                                        <span class="sub-item">CreatePoster</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</div>
