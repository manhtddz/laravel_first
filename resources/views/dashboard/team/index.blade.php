<div class="container mt-5">
    <h2 class="mb-4">Team - Search</h2>
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-primary">
            {{ session('success') }}
        </div>
    @endif
    <!-- Form tìm kiếm -->
    <form action="{{ route('team.index') }}" method="GET" class="mb-3">
        <div class="col-md-4">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Team name"
                value="{{ old('name') }}">
            <p style="color: red;">{{ $error ?? '' }}</p>

        </div>
        <!-- <div class="col-md-4 d-flex align-items-end">
        </div> -->
        <div class="d-flex justify-content-between mt-3 w-100">
            <button type="submit" class="btn btn-primary me-2">Search</button>

            <a href="{{ route('team.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
    <!-- Hiển thị kết quả tìm kiếm -->
    <h3>Search result:</h3>
    @if($teams->isNotEmpty())
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Team</th>
                    <th>Action</th>
                </tr>
            </thead>
            @foreach($teams as $team)
                <tr>
                    <td>{{ $team->id }}</td>
                    <td>{{ $team->name }}</td>
                    <td>
                        <a href="{{ route('team.edit', $team->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form method="POST" action="{{ route('team.delete', $team->id) }}" style="display:inline-block;">
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
        @if ($teams->hasPages())

            <ul class="pagination">
                {{-- Nút First --}}
                @if ($teams->currentPage() > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $teams->url(1) }}">First</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <a class="page-link">First</a>
                    </li>
                @endif

                {{-- Nút Prev --}}
                @if($teams->onFirstPage())
                    <li class="page-item disabled">
                        <a class="page-link">Prev</a>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $teams->previousPageUrl() }}">Prev</a>
                    </li>
                @endif

                {{-- Các trang số --}}
                @for ($i = 1; $i <= $teams->lastPage(); $i++)
                    @if ($i == $teams->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $i }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $teams->url($i) }}">{{ $i }}</a>
                        </li>
                    @endif
                @endfor

                {{-- Nút Next --}}
                @if ($teams->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $teams->nextPageUrl() }}">Next</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <a class="page-link">Next</a>
                    </li>
                @endif

                {{-- Nút Last --}}
                @if ($teams->currentPage() < $teams->lastPage())
                    <li class="page-item">
                        <a class="page-link" href="{{ $teams->url($teams->lastPage()) }}">Last</a>
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
                    <th>Team name</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tr>
                <td colspan="3">No result found</td>
            </tr>
        </table>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>