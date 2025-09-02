@php
    $role = strtolower(auth()->user()->role ?? '');
    $role = str_replace('super_admin', 'superadmin', $role);
    $panel = in_array($role, ['admin', 'superadmin']) ? $role : $role; // for route prefix
@endphp
<style>
    p {
        font-family: Khmer OS Battambang;
    }

    li a {
        font-family: Khmer OS Battambang;
    }
    h5{
         font-family: Khmer OS Battambang;
    }
</style>
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('home') }}" class="logo">
                <img src="/Logo_Camboshear.png" alt="navbar brand" class="navbar-brand" height="50" />
                <h5 class="text-white m-2">ប្រពន្ធចែករំចែក</h5>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
            </div>
            <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <!-- Home Dashboard -->
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>ទំព័រដើម</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="dashboard">
                        <ul class="nav nav-collapse">
                            @if ($role === 'superadmin')
                                <li>
                                    <a href="{{ route('superadmin.users.index') }}">
                                        <span class="sub-item">គ្រប់គ្រងអ្នកប្រើប្រាស់</span>
                                    </a>
                                </li>
                            @endif

                            @if (in_array($role, ['superadmin', 'admin']))
                                <li>
                                    <a href="{{ route($panel . '.customers.index') }}">
                                        <span class="sub-item">គ្រប់គ្រងអតិថិជន</span>
                                    </a>
                                </li>
                            @endif

                            @if ($role === 'writer')
                                <li>
                                    <a href="{{ route('writer.dashboard') }}">
                                        <span class="sub-item">Writer Dashboard</span>
                                    </a>
                                </li>
                            @elseif($role === 'customer')
                                <li>
                                    <a href="{{ route('home') }}">
                                        <span class="sub-item">អតិថិជន</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Manage</h4>
                </li>

                <!-- Category -->
                @if (in_array($role, ['superadmin', 'admin']))
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#base">
                            <i class="fas fa-layer-group"></i>
                            <p>ប្រភេទការផ្សព្វផ្សាយ</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="base">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route($panel . '.restaurants.index') }}">
                                        <span class="sub-item">ភោជនីដ្ឋាន</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route($panel . '.hospitals.index') }}">
                                        <span class="sub-item">សេវាកម្មសាធារណៈ</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- Poster -->
                @if (in_array($role, ['superadmin', 'admin']))
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#poster">
                            <i class="fas fa-image"></i>
                            <p>ការផ្សព្វផ្សាយ</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="poster">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route($panel . '.categories.index') }}">
                                        <span class="sub-item">ប្រភេទ</span>
                                    </a>

                                </li>
                                <li>
                                    <a href="{{ route($panel . '.posts.index') }}">
                                        <span class="sub-item">ផ្សព្វផ្សាយ</span>
                                    </a>

                                </li>
                                <li>
                                    <a href="{{ route($panel . '.comments.index') }}">
                                        <span class="sub-item">មតិយោបល់</span>
                                    </a>

                                </li>

                            </ul>
                        </div>
                    </li>
                @endif

                <!-- Comments -->
                @if (in_array($role, ['superadmin', 'admin', 'writer']))
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#comment">
                            <i class="fas fa-comments"></i>
                            <p>របាយការណ៍</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="comment">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route($panel . '.comments.index') }}">
                                        <span class="sub-item">All Comments</span>
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
