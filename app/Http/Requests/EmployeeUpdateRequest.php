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
                        return $query->whereNot('del_flag', IS_DELETED);
                    })
            ],
            'first_name' => ['required', 'string', 'max:128'],
            'last_name' => ['required', 'string', 'max:128'],
            'gender' => ['required', 'in:1,2', 'integer'],
            'birthday' => ['required', 'date', 'before:today'],
            'address' => ['required', 'string', 'max:256'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:128'],
            'salary' => ['required', 'integer', 'min:0'],
            'position' => ['required', 'in:1,2,3,4,5', 'integer'],
            'status' => ['required', 'in:1,2', 'integer'],
            'type_of_work' => ['required', 'in:1,2,3,4', 'integer'],
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
                $path = $file->store('temp', 'public'); // temporary store in storage/temp
                $tempFileName = str_replace('temp/', '', $path);
                session()->put('temp_file', $tempFileName);
            } else {
                session()->put('temp_file', $this->input('uploaded_avatar'));
            }
        } elseif ($this->has('uploaded_avatar')) {
            session()->put('temp_file', $this->input('uploaded_avatar'));
        }
    }
}
