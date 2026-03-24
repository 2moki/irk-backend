<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Enums\Gender;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'phone_prefix' => ['nullable', 'string', 'max:7'],
            'phone_number' => ['required', 'string', 'max:20'],
            'pesel' => [
                'required',
                'string',
                'size:11',
                'regex:/^[0-9]{11}$/',
                Rule::unique(User::class),
            ],
            'document_number' => ['nullable', 'string', 'max:30', Rule::unique(User::class)],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'first_name' => $input['first_name'],
            'middle_name' => $input['middle_name'] ?? null,
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'phone_prefix' => $input['phone_prefix'] ?? '+48',
            'phone_number' => $input['phone_number'],
            'pesel' => $input['pesel'],
            'document_number' => $input['document_number'] ?? null,
            'date_of_birth' => $input['date_of_birth'],
            'gender' => $input['gender'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
