<div class="container mt-5">
    <h2 class="mb-4">Tìm Kiếm Employee</h2>
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
    <form action="{{ route('employee.index') }}" method="GET" class="mb-3">
        <div class="col-md-4">
            <label for="name" class="form-label">Tên người dùng:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên người dùng"
                value="{{ old('name') }}">
        </div>
        <div class="col-md-4">
            <label for="email" class="form-label">Email:</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Nhập email"
                value="{{ old('email') }}">
        </div>
        <label class="form-label" for="team">Team:</label><br>
        <select class="form-control w-25" id="team" name="team_id">
            <option value="{{ 0 }}">{{ '' }}</option>
            @foreach ($employees as $team)
                <option value="{{ $team->id }}">{{ $team->name }}</option>
            @endforeach
        </select>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Tìm kiếm</button>
        </div>
    </form>
    <!-- Hiển thị kết quả tìm kiếm -->
    <h3>Kết quả tìm kiếm:</h3>
    @if($employees->isNotEmpty())

        @php
            $exIds = [];
            foreach ($employees as $employee) {
                $exIds[] = $employee->id; // Đẩy id vào mảng
            }
            $fields = implode(", ", array_map(fn($id) => "{$id} = :{$id}", $exIds));
        @endphp
        <form method="POST" action="{{ route('employee.export') }}">
            @csrf
            <input type="hidden" name="ids" value="{{ $fields }}">
            <button type="submit" class="btn btn-primary">Export Users</button>
        </form>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th><a href="{{ request()->fullUrlWithQuery(['sortBy' => 'id', 'direction' => $newDirection]) }}"
                            class="text-white">ID</a></th>
                    <th>Avatar</th>
                    <th><a href="{{ request()->fullUrlWithQuery(['sortBy' => 'team_id', 'direction' => $newDirection]) }}"
                            class="text-white">Team</a></th>
                    <th><a href="{{ request()->fullUrlWithQuery(['sortBy' => 'name', 'direction' => $newDirection]) }}"
                            class="text-white">Name</a></th>
                    <th><a href="{{ request()->fullUrlWithQuery(['sortBy' => 'email', 'direction' => $newDirection]) }}"
                            class="text-white">Email</a></th>
                    <th>Action</th>
                </tr>
            </thead>
            @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->id }}</td>
                    <td>
                        <img src="{{ url('storage/app/' . $employee->avatar) }}" width="50" height="50" class="rounded-circle"
                            title="{{ $employee->avatar ?? "Dont't have avatar" }}">
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
                        <a href="{{ route('employee.edit', $employee->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form method="POST" action="{{ route('employee.delete', $employee->id) }}"
                            style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>

        <ul class="pagination">
            {{-- Nút First --}}
            @if ($employees->currentPage() > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $employees->url(1) }}">First</a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link">First</a>
                </li>
            @endif

            {{-- Nút Prev --}}
            @if($employees->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link">Prev</a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $employees->previousPageUrl() }}">Prev</a>
                </li>
            @endif

            {{-- Các trang số --}}
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

            {{-- Nút Next --}}
            @if ($employees->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $employees->nextPageUrl() }}">Next</a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link">Next</a>
                </li>
            @endif

            {{-- Nút Last --}}
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
    @else
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên Employee</th>
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