<?php
use App\Const\Gender;
use App\Const\Position;
use App\Const\Status;
use App\Const\TypeOfWork;
?>
<div class="container mt-4">
    <h2 class="mb-3">Employee - Update</h2>
    <form action="{{ route('employee.updateConfirm',$employee->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="team">Team:</label><br>

            <select class="form-control" id="team" name="team_id">
                @foreach ($teams as $team)
                    <option value="{{ $team->id }}" {{ $employee->team_id === $team->id ? 'selected' : '' }}>{{ $team->name }}
                    </option>
                @endforeach
            </select>
            @error('team_id')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="avatar" class="form-label">Avatar:</label>
            <input type="file" class="form-control" name="avatar" id="avatar" accept="image/*">
            @error('avatar')
                <p style="color: red;">{{ $message }}</p>
            @enderror
            @php
                $tempFile = session('temp_file') ?? session('employee_data.avatar');
                $avatarPath = $employee->avatar;
                $isTempFile = $tempFile && $tempFile !== $avatarPath;
                $imageSrc = $isTempFile ? asset(TEMP_URL . $tempFile) : asset(APP_URL . $avatarPath);
            @endphp

            @if ($tempFile || $avatarPath)
                <p>File đã tải lên: </p>
                <img src="{{ $imageSrc }}" style="max-width: 200px; margin-top: 10px;">
            @endif

            <input type="hidden" name="uploaded_avatar" value="{{ $tempFile ?? $avatarPath }}">

            <input type="hidden" name="old_avatar" value="{{ $employee->avatar }}">

        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="text" class="form-control" name="email"
                value="{{ old('email', session('employee_data.email', $employee->email)) }}">
            @error('email')            <p style="color: red;">{{ $message }}</p> @enderror
        </div>
        <div class="mb-3">
            <label for="first_name" class="form-label">First Name:</label>
            <input type="text" class="form-control" name="first_name"
                value="{{ old('first_name', session('employee_data.first_name', $employee->first_name)) }}">
            @error('first_name')            <p style="color: red;">{{ $message }}</p> @enderror
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name:</label>
            <input type="text" class="form-control" name="last_name"
                value="{{ old('last_name', session('employee_data.last_name', $employee->last_name)) }}">
            @error('last_name')            <p style="color: red;">{{ $message }}</p> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Gender:</label><br>

            @php
                $genderOptions = Gender::LIST;
                $selectedGender = old('gender', session('employee_data.gender', $employee->gender));
            @endphp

            @foreach ($genderOptions as $value => $label)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="gender_{{ $value }}" value="{{ $value }}"
                        {{ $selectedGender == $value ? 'checked ' : '' }}>
                    <label class="form-check-label" for="gender_{{ $value }}">{{ $label }}</label>
                </div>
            @endforeach

            @error('gender')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>


        <div class="mb-3">
            <label for="birthday" class="form-label">Birthday:</label>

            @php
                $birthday = old('birthday', session('employee_data.birthday', $employee->birthday));
                if ($birthday instanceof \Carbon\Carbon) {
                    $birthday = $birthday->format('Y-m-d');
                } elseif (!empty($birthday)) {
                    $birthday = date('Y-m-d', strtotime($birthday));
                }
            @endphp

            <input type="date" class="form-control" name="birthday" value="{{ $birthday }}">

            @error('birthday')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address:</label>
            <input type="text" class="form-control" name="address"
                value="{{ old('address', session('employee_data.address', $employee->address)) }}">
            @error('address')            <p style="color: red;">{{ $message }}</p> @enderror
        </div>
        <div class="mb-3">
            <label for="salary" class="form-label">Salary:</label>
            <input type="number" class="form-control" name="salary"
                value="{{ old('salary', session('employee_data.salary', $employee->salary)) }}">
            @error('salary')            <p style="color: red;">{{ $message }}</p> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Position:</label><br>

            @php
                $positionOptions = Position::LIST;
                $selectedPosition = old('position', session('employee_data.position',$employee->position));
            @endphp

            @foreach ($positionOptions as $value => $label)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="position" id="position_{{ $value }}" value="{{ $value }}"
                        {{ $selectedPosition == $value ? 'checked ' : '' }}>
                    <label class="form-check-label" for="position_{{ $value }}">{{ $label }}</label>
                </div>
            @endforeach

            @error('position')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>


        <div class="mb-3">
            <label class="form-label">Status:</label><br>

            @php
                $statusOptions = Status::LIST;
                $selectedStatus = old('status', session('employee_data.status', $employee->status));
            @endphp

            @foreach ($statusOptions as $value => $label)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="status_{{ $value }}" value="{{ $value }}"
                        {{ $selectedStatus == $value ? 'checked ' : '' }}>
                    <label class="form-check-label" for="status_{{ $value }}">{{ $label }}</label>
                </div>
            @endforeach

            @error('status')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Type of Work:</label><br>

            @php
                $typeOfWorkOptions = TypeOfWork::LIST;
                $selectedTypeOfWork = old('type_of_work', session('employee_data.type_of_work', $employee->type_of_work));
            @endphp

            @foreach ($typeOfWorkOptions as $value => $label)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type_of_work" id="type_of_work_{{ $value }}"
                        value="{{ $value }}" {{ $selectedTypeOfWork == $value ? 'checked ' : '' }}>
                    <label class="form-check-label" for="type_of_work_{{ $value }}">{{ $label }}</label>
                </div>
            @endforeach

            @error('type_of_work')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">
                    Confirm
                </button>
                <a href="{{ route('employee.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
</div>