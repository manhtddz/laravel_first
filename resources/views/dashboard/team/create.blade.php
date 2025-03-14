@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="container mt-4">
    <h2 class="mb-3">Team - Create</h2>
    <form action="{{ route('team.createConfirm') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" name="name" value="{{ old('name', session('team_data.name')) }}">
            @error('name') <p style="color: red;">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="btn btn-success">Confirm</button>
    </form>
</div>