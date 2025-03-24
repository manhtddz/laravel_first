<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamUpdateRequest extends FormRequest
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
            "name" => [
                'required',
                'max:128',
                Rule::unique('m_teams', 'name')
                    ->ignore($id)
                    ->where(function ($query) {
                        return $query->whereNot('del_flag', IS_DELETED);
                    }),
            ],
        ];
    }
}
