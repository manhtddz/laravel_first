<?php
use App\Const\Gender;
use App\Const\Position;
use App\Const\Status;
use App\Const\TypeOfWork;
use App\Models\Team;
?>
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="container mt-4">
    <h2 class="mb-3">Employee - Create</h2>
    <form action="{{ route('employee.createConfirm') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="team">Team:</label><br>
            <select class="form-control" id="team" name="team_id">
                @php
                    $selectedTeamId = old('team_id', session('employee_data.team_id')); // Tránh lỗi khi session không có giá trị
                @endphp
                <option value="">{{ '' }}
                </option>
                @foreach ($teams as $team)
                    <option value="{{ $team->id }}" {{ $selectedTeamId == $team->id ? 'selected ' : '' }}>{{ $team->name }}
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

            @if(session('temp_file'))
                <p>File đã tải lên: </p>
                <!-- <a href="{{ asset('storage/' . session('temp_file')) }}" target="_blank">Xem file</a> -->
                <img src="{{ asset('storage/temp/' . session('temp_file')) }}"
                    style="max-width: 200px; margin-top: 10px;">
                <input type="hidden" name="old_avatar" value="{{ session('temp_file','') }}">
            @else
                @if (session('employee_data.avatar'))
                <p>File đã tải lên: </p>
                <img src="{{ asset('storage/temp/' .  session('employee_data.avatar')) }}"
                    style="max-width: 200px; margin-top: 10px;">
                @endif
                <input type="hidden" name="old_avatar" value="{{ session('employee_data.avatar','') }}">
            @endif
            <!-- <img id="previewImage" src="#" alt="Preview" style="display: none; max-width: 200px; margin-top: 10px;">

            @php
                $avatarPath = old('avatar', session('employee_data.avatar'));
            @endphp

            @if ($avatarPath)
                <img id="previewImage" src="{{ asset('storage/temp/' . $avatarPath) }}" alt="Preview"
                    style="max-width: 200px; margin-top: 10px;">
            @endif
        </div>
        <input type="hidden" name="old_avatar" value="{{ session('employee_data.avatar', '')}}"> -->

            <!-- <script>
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
        </script> -->

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="text" class="form-control" name="email"
                    value="{{ old('email', session('employee_data.email')) }}">
                @error('email') <p style="color: red;">{{ $message }}</p> @enderror
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name:</label>
                <input type="text" class="form-control" name="first_name"
                    value="{{ old('first_name', session('employee_data.first_name')) }}">
                @error('first_name') <p style="color: red;">{{ $message }}</p> @enderror
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name:</label>
                <input type="text" class="form-control" name="last_name"
                    value="{{ old('last_name', session('employee_data.last_name')) }}">
                @error('last_name') <p style="color: red;">{{ $message }}</p> @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" name="password"
                    value="{{ old('password', session('employee_data.password')) }}">
                @error('password') <p style="color: red;">{{ $message }}</p> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Gender:</label><br>

                @php
                    $genderOptions = Gender::LIST;
                    $selectedGender = old('gender', session('employee_data.gender')); // Tránh lỗi khi session không có giá trị
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
                    $birthday = old('birthday', session('employee_data.birthday'));
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
                    value="{{ old('address', session('employee_data.address')) }}">
                @error('address') <p style="color: red;">{{ $message }}</p> @enderror
            </div>
            <div class="mb-3">
                <label for="salary" class="form-label">Salary:</label>
                <input type="number" class="form-control" name="salary"
                    value="{{ old('salary', session('employee_data.salary')) }}">
                @error('salary') <p style="color: red;">{{ $message }}</p> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Position:</label><br>

                @php
                    $positionOptions = Position::LIST;
                    $selectedPosition = old('position', session('employee_data.position')); // Tránh lỗi khi session không có giá trị
                @endphp
                <select class="form-control" id="position" name="position">
                    <option value="">{{ '' }}
                    </option>
                    @foreach ($positionOptions as $value => $label)
                        <option value="{{ $value }}" {{ $selectedPosition == $value ? 'selected ' : '' }}>
                            {{ Position::getName($value) }}
                        </option>
                    @endforeach
                </select>

                @error('position')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>


            <div class="mb-3">
                <label class="form-label">Status:</label><br>

                @php
                    $statusOptions = Status::LIST;
                    $selectedStatus = old('status', session('employee_data.status')); // Tránh lỗi khi session không có giá trị
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
                    $selectedTypeOfWork = old('type_of_work', session('employee_data.type_of_work'));
                @endphp

                <select class="form-control" id="type_of_work" name="type_of_work">
                    <option value="">{{ '' }}
                    </option>
                    @foreach ($typeOfWorkOptions as $value => $label)
                        <option value="{{ $value }}" {{ $selectedTypeOfWork == $value ? 'selected ' : '' }}>
                            {{ TypeOfWork::getName($value) }}
                        </option>
                    @endforeach
                </select>

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