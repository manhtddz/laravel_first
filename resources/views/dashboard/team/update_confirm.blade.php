<div class="container mt-4">
    <div class="card p-4">
        <h4 class="mb-3">Team - Update confirm</h4>

        <form action="{{ route('team.update', $id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label"><strong>Name:</strong></label>
                <p class="border p-2 bg-light">{{ session('team_data.name') }}</p>

                <input type="hidden" name="name" value="{{ session('team_data.name') }}">
                @error('name')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal">
                    Save
                </button>
                <a href="{{ route('team.edit', $id) }}" class="btn btn-secondary">Cancel</a>
            </div>
            @include('dashboard.component.confirm-modal')
        </form>
    </div>
</div>