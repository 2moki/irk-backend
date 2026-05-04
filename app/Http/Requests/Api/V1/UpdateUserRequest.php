<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
   public function rules(): array
{
    return [
            'first_name' => ['sometimes', 'string', 'min:2', 'max:100'],
            'middle_name' => ['sometimes', 'nullable', 'string', 'max:100'],
            'last_name' => ['sometimes', 'string', 'min:2', 'max:100'],

            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],

            'phone_prefix' => ['sometimes', 'string', 'regex:/^\+\d{1,4}$/'],
            'phone_number' => ['sometimes', 'string', 'regex:/^\d{6,15}$/'],

            'pesel' => ['sometimes', 'nullable', 'string', 'size:11'],

            'date_of_birth' => ['sometimes', 'date', 'before:today'],

            'gender' => ['sometimes', Rule::in(['male', 'female', 'other'])],

        // 🔥 NAJWAŻNIEJSZE
        'address' => ['nullable', 'array'],

        'address.street' => ['nullable', 'string'],
        'address.house_number' => ['nullable', 'string'],
        'address.apartment_number' => ['nullable', 'string'],
        'address.city' => ['nullable', 'string'],
        'address.post_code' => ['nullable', 'string'],
    ];
}
    public function messages(): array
    {
        return [
            'address.post_code.regex' => 'Kod pocztowy musi mieć format XX-XXX',
            'phone_prefix.regex' => 'Prefix musi być w formacie +48',
            'phone_number.regex' => 'Numer telefonu jest niepoprawny',
        ];
    }
}
