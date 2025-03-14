<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('team.index') }}">My Website</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Team Management -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Request::routeIs('team.*') ? 'active' : '' }}" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        Team management
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item {{ Request::routeIs('team.index') ? 'active' : '' }}" href="{{ route('team.index') }}">Search</a></li>
                        <li><a class="dropdown-item {{ Request::routeIs('team.create') ? 'active' : '' }}" href="{{ route('team.create') }}">Create</a></li>
                    </ul>
                </li>

                <!-- Employee Management -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Request::routeIs('employee.*') ? 'active' : '' }}" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        Employee management
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item {{ Request::routeIs('employee.index') ? 'active' : '' }}" href="{{ route('employee.index') }}">Search</a></li>
                        <li><a class="dropdown-item {{ Request::routeIs('employee.create') ? 'active' : '' }}" href="{{ route('employee.create') }}">Create</a></li>
                    </ul>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('auth.logout') }}">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
