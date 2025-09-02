<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:100','unique:roles,name'],
            'slug' => ['required','string','max:100','unique:roles,slug'],
            'daily_credits' => ['required','integer','min:0'],
            'meta' => ['nullable','array'],
        ];
    }
}
