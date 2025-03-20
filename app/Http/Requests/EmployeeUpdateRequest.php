<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route("id");

        return [
            'team_id' => ['required', 'integer', Rule::exists('m_teams', 'id')],
            'email' => [
                'required',
                'email',
                'max:128',
                Rule::unique('m_employees', 'email')
                    ->ignore($id)
                    ->where(function ($query) {
                        return $query->whereNot('del_flag', 1);
                    })
            ],
            'first_name' => ['required', 'string', 'max:128'],
            'last_name' => ['required', 'string', 'max:128'],
            // 'password' => ['required', 'string', 'min:6', 'max:64'],
            'gender' => ['required', 'in:1,2', 'integer'], // 1 = Nam, 2 = Nữ
            'birthday' => ['required', 'date', 'before:today'],
            'address' => ['required', 'string', 'max:256'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:128'], // Hoặc dùng ['nullable', 'image', 'mimes:jpg,png,jpeg']
            'salary' => ['required', 'integer', 'min:0'],
            'position' => ['required', 'in:1,2,3,4,5', 'integer'], // Giả sử các vị trí có giá trị A, B, C
            'status' => ['required', 'in:1,2', 'integer'], // 2 = Không hoạt động, 1 = Hoạt động
            'type_of_work' => ['required', 'in:1,2,3,4', 'integer'], // F = Full-time, P = Part-time, C = Contract
        ];
    }
    public function messages(): array
    {
        return [
            'team_id.required' => 'Team ID is required.',
            'team_id.integer' => 'Team ID must be an integer.',

            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            'email.max' => 'Email must not exceed 128 characters.',
            'email.unique' => 'This email has already been taken.',

            'first_name.required' => 'First name is required.',
            'first_name.string' => 'First name must be a string.',
            'first_name.max' => 'First name must not exceed 128 characters.',

            'last_name.required' => 'Last name is required.',
            'last_name.string' => 'Last name must be a string.',
            'last_name.max' => 'Last name must not exceed 128 characters.',

            // 'password.required' => 'Password is required.',
            // 'password.string' => 'Password must be a string.',
            // 'password.min' => 'Password must be at least 6 characters.',
            // 'password.max' => 'Password must not exceed 64 characters.',

            'gender.required' => 'Gender is required.',
            'gender.in' => 'Gender must be 1 (Male) or 2 (Female).',
            'gender.integer' => 'Gender must be an integer.',

            'birthday.required' => 'Birthday is required.',
            'birthday.date' => 'Invalid date format.',

            'address.required' => 'Address is required.',
            'address.string' => 'Address must be a string.',
            'address.max' => 'Address must not exceed 256 characters.',

            'avatar.image' => 'Avatar must be an image file.',
            'avatar.mimes' => 'Avatar must be a JPG, PNG, or JPEG file.',
            'avatar.max' => 'Avatar size must not exceed 5MB.',

            'salary.required' => 'Salary is required.',
            'salary.integer' => 'Salary must be an integer.',
            'salary.min' => 'Salary must be at least 0.',

            'position.required' => 'Position is required.',
            'position.in' => 'Position must be one of: 1, 2, 3, 4, 5.',
            'position.integer' => 'Position must be an integer.',

            'status.required' => 'Status is required.',
            'status.in' => 'Status must be 1 (On working) or 2 (Retired).',
            'status.integer' => 'Status must be an integer.',

            'type_of_work.required' => 'Type of work is required.',
            'type_of_work.in' => 'Type of work must be one of: 1, 2, 3, 4.',
            'type_of_work.integer' => 'Type of work must be an integer.',

        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $this->storeTempFile();
        parent::failedValidation($validator);
    }

    private function storeTempFile()
    {
        if ($this->hasFile('avatar')) {
            $file = $this->file('avatar');
            if (in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
                $path = $file->store('temp', 'public'); // Lưu vào storage/temp
                $tempFileName = str_replace('temp/', '', $path);
                session()->put('temp_file', $tempFileName);
            } else {
                session()->put('temp_file', $this->input('uploaded_avatar'));
            }
        } elseif ($this->has('uploaded_avatar')) {
            // dd($this->input('uploaded_avatar'));
            session()->put('temp_file', $this->input('uploaded_avatar'));
        }
    }
}
