<?php
use App\Const\Gender;
use App\Const\Position;
use App\Const\Status;
use App\Const\TypeOfWork;
use App\Models\Team;
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
            <img id="previewImage" src="#" alt="Preview" style="display: none; max-width: 200px; margin-top: 10px;">

            @php
                $avatarPath =  $employee->avatar;
            @endphp

            @if ($avatarPath)
                <img id="previewImage" src="{{ asset('storage/app/' . $avatarPath) }}" alt="Preview"
                    style="max-width: 200px; margin-top: 10px;">
            @endif
        </div>
        <input type="hidden" name="old_avatar" value="{{ $employee->avatar }}">

        <script>
            document.getElementById('avatar').addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const preview = document.getElementById('previewImage');
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>

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
                $selectedGender = old('gender', session('employee_data.gender', $employee->gender)); // Tránh lỗi khi session không có giá trị
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
                    $birthday = $birthday->format('Y-m-d'); // Chuyển về định dạng phù hợp nếu là Carbon
                } elseif (!empty($birthday)) {
                    $birthday = date('Y-m-d', strtotime($birthday)); // Nếu là chuỗi, đảm bảo đúng định dạng
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
                $selectedPosition = old('position', session('employee_data.position',$employee->position)); // Tránh lỗi khi session không có giá trị
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
                $selectedStatus = old('status', session('employee_data.status', $employee->status)); // Tránh lỗi khi session không có giá trị
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

        <button type="submit" class="btn btn-success">Confirm</button>
    </form>
</div>