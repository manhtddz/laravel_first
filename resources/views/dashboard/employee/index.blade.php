<div class="container mt-5">
    <h2 class="mb-4">Employee - Search</h2>
    @if (session(SESSION_ERROR))
        <div class="alert alert-danger">
            {{ session(SESSION_ERROR) }}
        </div>
    @endif
    @if (session(SESSION_SUCCESS))
        <div class="alert alert-primary">
            {{ session(SESSION_SUCCESS) }}
        </div>
    @endif
    <!-- Search Form -->
    <form action="{{ route('employee.index') }}" method="GET" class="mb-3">
        <div class="col-md-4">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                value="{{ request()->query('name') }}">
        </div>
        <div class="col-md-4">
            <label for="email" class="form-label">Email:</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Email"
                value="{{ request()->query('email') }}">
        </div>
        <label class="form-label" for="team">Team:</label><br>
        <select class="form-control w-25" id="team" name="team_id">
            <option value="0" {{ request()->query('team_id') == 0 ? 'selected' : '' }}>{{ '' }}</option>
            @foreach ($teams as $team)
                <option value="{{ $team->id }}" {{ request()->query('team_id') == $team->id ? 'selected' : '' }}>
                    {{ $team->name }}
                </option>
            @endforeach
        </select>
        <div class="d-flex justify-content-between mt-3 w-100">
            <button type="submit" class="btn btn-primary me-2">Search</button>

            <a href="{{ route('employee.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
    <!-- Search result -->
    <h3>Search result:</h3>
    @if($employees->isNotEmpty())

        @php
            $fields = implode(", ", array_map(fn($id) => "{$id} = :{$id}", $employeeIds));
        @endphp
        <form method="POST" action="{{ route('employee.export') }}">
            @csrf
            <input type="hidden" name="ids" value="{{ $fields }}">
            <button type="submit" class="btn btn-primary">Export Users</button>
        </form>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th><a href="{{ request()->fullUrlWithQuery(['sortBy' => 'id', 'direction' => $direction === "asc" ? "desc" : "asc"]) }}"
                            class="text-white">ID ↕</a></th>
                    <th>Avatar</th>
                    <th><a href="{{ request()->fullUrlWithQuery(['sortBy' => 'team_id', 'direction' => $direction === "asc" ? "desc" : "asc"]) }}"
                            class="text-white">Team ↕</a></th>
                    <th><a href="{{ request()->fullUrlWithQuery(['sortBy' => 'name', 'direction' => $direction === "asc" ? "desc" : "asc"]) }}"
                            class="text-white">Name ↕</a></th>
                    <th><a href="{{ request()->fullUrlWithQuery(['sortBy' => 'email', 'direction' => $direction === "asc" ? "desc" : "asc"]) }}"
                            class="text-white">Email ↕</a></th>
                    <th>Action</th>
                </tr>
            </thead>
            @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->id }}</td>
                    <td>
                        <img src="{{ url(APP_URL . $employee->avatar) }}" width="50" height="50" class="rounded-circle"
                            title="{{ $employee->avatar ?? NO_AVATAR }}">
                    </td>
                    <td>
                        {{ $employee->team->name }}
                    </td>
                    <td>
                        {{ $employee->name }}
                    </td>
                    <td>
                        {{ $employee->email }}
                    </td>
                    <td>
                        <a href="{{ route('employee.edit', $employee->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form method="POST" action="{{ route('employee.delete', $employee->id) }}"
                            style="display:inline-block;">
                            @csrf
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#confirmModal">
                                Delete
                            </button>
                            @include('dashboard.component.confirm-modal')
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>

        @if ($employees->hasPages())

            <ul class="pagination">
                {{-- First --}}
                @if ($employees->currentPage() > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $employees->url(1) }}">First</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <a class="page-link">First</a>
                    </li>
                @endif

                {{-- Prev --}}
                @if($employees->onFirstPage())
                    <li class="page-item disabled">
                        <a class="page-link">Prev</a>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $employees->previousPageUrl() }}">Prev</a>
                    </li>
                @endif

                {{-- Index page --}}
                @for ($i = 1; $i <= $employees->lastPage(); $i++)
                    @if ($i == $employees->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $i }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $employees->url($i) }}">{{ $i }}</a>
                        </li>
                    @endif
                @endfor

                {{-- Next --}}
                @if ($employees->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $employees->nextPageUrl() }}">Next</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <a class="page-link">Next</a>
                    </li>
                @endif

                {{-- Last --}}
                @if ($employees->currentPage() < $employees->lastPage())
                    <li class="page-item">
                        <a class="page-link" href="{{ $employees->url($employees->lastPage()) }}">Last</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <a class="page-link">Last</a>
                    </li>
                @endif
            </ul>
        @endif
    @else
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Avatar</th>
                    <th>Team</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tr>
                <td colspan="3">{{ NO_RESULT }}</td>
            </tr>
        </table>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>