@php
    $role = strtolower(auth()->user()->role ?? '');
    $role = str_replace('super_admin', 'superadmin', $role); // ✅ Fix name for route prefix
@endphp

<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('home') }}" class="logo">
                <img src="/p.png" alt="navbar brand" class="navbar-brand" height="50" />
                <h5 class="text-white m-2">AdminSystem</h5>
            </a>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                {{-- Home --}}
                <li class="nav-item active">
                    <a href="{{ route($role . '.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- Poster Section for All Roles --}}
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#posterMenu">
                        <i class="fas fa-copy"></i>
                        <p>Poster</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="posterMenu">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route($role . '.posts.index') }}">
                                    <span class="sub-item">All Posters</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route($role . '.posts.create') }}">
                                    <span class="sub-item">Create Poster</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- Management Section for Super Admin and Admin --}}
                @if(in_array($role, ['superadmin', 'admin']))
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#manageMenu">
                        <i class="fas fa-users-cog"></i>
                        <p>Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="manageMenu">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route($role . '.customers.index') }}">
                                    <span class="sub-item">Customers</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route($role . '.categories.index') }}">
                                    <span class="sub-item">Categories</span>
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
