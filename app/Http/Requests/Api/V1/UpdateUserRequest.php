<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'min:2', 'max:100'],
            'middle_name' => ['sometimes', 'nullable', 'string', 'max:100'],
            'last_name' => ['sometimes', 'string', 'min:2', 'max:100'],
            'current_password' => ['sometimes', 'required_with:password', 'string'],
            'password' => ['sometimes', 'confirmed', 'string', 'min:8'],

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

            // 1. ADRES GŁÓWNY (Struktura płaska)
            'street' => ['nullable', 'string', 'max:255'],
            'house_number' => ['nullable', 'string', 'max:50'],
            'apartment_number' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:100'],
            'post_code' => ['nullable', 'string', 'max:20'],
            'post_office' => ['nullable', 'string', 'max:100'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],

            'has_correspondence' => ['sometimes', 'boolean'],

            // 2. ADRES KORESPONDENCYJNY (Struktura zagnieżdżona)
            'mailing_address' => ['nullable', 'array'],
            'mailing_address.street' => ['required_if:has_correspondence,true', 'nullable', 'string', 'max:255'],
            'mailing_address.house_number' => ['required_if:has_correspondence,true', 'nullable', 'string', 'max:50'],
            'mailing_address.apartment_number' => ['nullable', 'string', 'max:50'],
            'mailing_address.city' => ['required_if:has_correspondence,true', 'nullable', 'string', 'max:100'],
            'mailing_address.post_code' => ['required_if:has_correspondence,true', 'nullable', 'string', 'max:20'],
            'mailing_address.post_office' => ['required_if:has_correspondence,true', 'nullable', 'string', 'max:100'],
            'mailing_address.country_id' => ['required_if:has_correspondence,true', 'nullable', 'integer', 'exists:countries,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_prefix.regex' => 'Prefix musi być w formacie +48',
            'phone_number.regex' => 'Numer telefonu jest niepoprawny',
            'mailing_address.street.required_if' => 'Ulica korespondencyjna jest wymagana.',
            'mailing_address.house_number.required_if' => 'Numer domu korespondencyjnego jest wymagany.',
            'mailing_address.city.required_if' => 'Miasto korespondencyjne jest wymagane.',
            'mailing_address.post_code.required_if' => 'Kod pocztowy korespondencyjny jest wymagany.',
            'mailing_address.post_office.required_if' => 'Placówka pocztowa korespondencyjna jest wymagana.',
            'mailing_address.country_id.required_if' => 'Kraj korespondencyjny jest wymagany.',
        ];
    }
}
