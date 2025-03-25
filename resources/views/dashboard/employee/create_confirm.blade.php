<?php
use App\Const\Gender;
use App\Const\Position;
use App\Const\Status;
use App\Const\TypeOfWork;
use App\Models\Team;
?>

<div class="container mt-4">
    <div class="card p-4">
        <h4 class="mb-3">Employee - Create confirm</h4>

        <form action="{{ route('employee.create') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label"><strong>Avatar:</strong></label>
                @php
                    $avatarPath = session('employee_data.avatar');
                @endphp

                @if ($avatarPath)
                    <img id="previewImage" src="{{ url(TEMP_URL . $avatarPath) }}"
                        style="max-width: 200px; margin-top: 10px;">
                    <input type="hidden" name="avatar" value="{{ session('employee_data.avatar') }}">
                @else
                    <p style="color: red;">{{ NO_AVATAR }}</p>
                @endif
                @error('avatar')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Team:</strong></label>
                <p class="border p-2 bg-light">{{ Team::getFieldById(session('employee_data.team_id'), 'name')  }}</p>

                <input type="hidden" name="team_id" value="{{ session('employee_data.team_id') }}">
                @error('team_id')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>First name:</strong></label>
                <p class="border p-2 bg-light">{{ session('employee_data.first_name') }}</p>

                <input type="hidden" name="first_name" value="{{ session('employee_data.first_name') }}">
                @error('first_name')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Last name:</strong></label>
                <p class="border p-2 bg-light">{{ session('employee_data.last_name') }}</p>

                <input type="hidden" name="last_name" value="{{ session('employee_data.last_name') }}">
                @error('last_name')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Email:</strong></label>
                <p class="border p-2 bg-light">{{ session('employee_data.email') }}</p>

                <input type="hidden" name="email" value="{{ session('employee_data.email') }}">
                @error('email')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Gender:</strong></label>
                <p class="border p-2 bg-light">{{ Gender::getName(session('employee_data.gender'))}}</p>

                <input type="hidden" name="gender" value="{{ session('employee_data.gender') }}">
                @error('gender')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Birthday:</strong></label>
                <p class="border p-2 bg-light">{{ session('employee_data.birthday')}}</p>

                <input type="hidden" name="birthday" value="{{ session('employee_data.birthday') }}">
                @error('birthday')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Address:</strong></label>
                <p class="border p-2 bg-light">{{ session('employee_data.address') }}</p>

                <input type="hidden" name="address" value="{{ session('employee_data.address') }}">
                @error('address')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Salary:</strong></label>
                <p class="border p-2 bg-light">{{ session('employee_data.salary') }}</p>

                <input type="hidden" name="salary" value="{{ session('employee_data.salary') }}">
                @error('salary')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Position:</strong></label>
                <p class="border p-2 bg-light">{{ Position::getName(session('employee_data.position'))}}</p>

                <input type="hidden" name="position" value="{{ session('employee_data.position') }}">
                @error('position')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Type Of Work:</strong></label>
                <p class="border p-2 bg-light">{{ TypeOfWork::getName(session('employee_data.type_of_work'))}}</p>

                <input type="hidden" name="type_of_work" value="{{ session('employee_data.type_of_work') }}">
                @error('type_of_work')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Status:</strong></label>
                <p class="border p-2 bg-light">{{ Status::getName(session('employee_data.status'))}}</p>

                <input type="hidden" name="status" value="{{ session('employee_data.status') }}">
                @error('status')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Password:</strong></label>
                <p class="border p-2 bg-light">{{ session('employee_data.password') }}</p>

                <input type="hidden" name="password" value="{{ session('employee_data.password') }}">
                @error('password')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal">
                    Save
                </button>
                <a href="{{ route('employee.create') }}" class="btn btn-secondary">Cancel</a>
            </div>
            @include('dashboard.component.confirm-modal')

        </form>
    </div>
</div>