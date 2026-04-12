<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Enums\Gender;
use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Throwable;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     * @throws Throwable
     */
    public function create(array $input): User
    {
        $this->validate($input);

        return DB::transaction(function () use ($input): User {
            $address = Address::create([
                'country_id' => $input['country_id'],
                'voivodeship_id' => $input['voivodeship_id'] ?? null,
                'state' => $input['state'] ?? null,
                'post_code' => $input['post_code'],
                'city' => $input['city'],
                'street' => $input['street'],
                'house_number' => $input['house_number'],
                'apartment_number' => $input['apartment_number'] ?? null,
                'post_office' => $input['post_office'],
            ]);

            $mailingAddressId = $address->id;

            if (! empty($input['different_mailing_address'])) {
                $mailingAddress = Address::create([
                    'country_id' => $input['m_country_id'],
                    'voivodeship_id' => $input['m_voivodeship_id'] ?? null,
                    'state' => $input['m_state'] ?? null,
                    'post_code' => $input['m_post_code'],
                    'city' => $input['m_city'],
                    'street' => $input['m_street'],
                    'house_number' => $input['m_house_number'],
                    'apartment_number' => $input['m_apartment_number'] ?? null,
                    'post_office' => $input['m_post_office'],
                ]);

                $mailingAddressId = $mailingAddress->id;
            }

            return User::create([
                'first_name' => $input['first_name'],
                'middle_name' => $input['middle_name'] ?? null,
                'last_name' => $input['last_name'],
                'email' => $input['email'],
                'phone_prefix' => $input['phone_prefix'] ?? '+48',
                'phone_number' => $input['phone_number'],
                'pesel' => $input['pesel'] ?? null,
                'document_number' => $input['document_number'] ?? null,
                'date_of_birth' => $input['date_of_birth'],
                'gender' => $input['gender'],
                'password' => Hash::make($input['password']),
                'address_id' => $address->id,
                'mailing_address_id' => $mailingAddressId,
            ]);
        });
    }

    /**
     * @param  array<string, string>  $input
     */
    protected function validate(array $input): void
    {
        $country = Country::findOrFail($input['country_id']);
        $isPoland = $country->code === 'PL';

        $hasDocumentNumber = ! empty($input['document_number']);

        $hasDifferentMailingAddress = $input['different_mailing_address'] ?? false;

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
                Rule::requiredIf(! $hasDocumentNumber),
                'string',
                'size:11',
                'regex:/^[0-9]{11}$/',
                Rule::unique(User::class),
            ],
            'document_number' => [Rule::requiredIf($hasDocumentNumber), 'string', 'max:30', Rule::unique(User::class)],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'password' => $this->passwordRules(),

            'country_id' => ['required', 'exists:countries,id'],
            'voivodeship_id' => [Rule::requiredIf($isPoland), 'exists:voivodeships,id'],
            'state' => [Rule::requiredIf(! $isPoland), 'string', 'max:255'],
            'post_code' => ['required', 'string', 'max:10'],
            'city' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'house_number' => ['required', 'string', 'max:10'],
            'apartment_number' => ['nullable', 'string', 'max:10'],
            'post_office' => ['required', 'string', 'max:255'],

            'm_country_id' => [Rule::requiredIf($hasDifferentMailingAddress), 'exists:countries,id'],
            'm_voivodeship_id' => [Rule::requiredIf($hasDifferentMailingAddress && $isPoland), 'exists:voivodeships,id'],
            'm_state' => [Rule::requiredIf($hasDifferentMailingAddress && ! $isPoland), 'string', 'max:255'],
            'm_post_code' => [Rule::requiredIf($hasDifferentMailingAddress), 'string', 'max:10'],
            'm_city' => [Rule::requiredIf($hasDifferentMailingAddress), 'string', 'max:255'],
            'm_street' => [Rule::requiredIf($hasDifferentMailingAddress), 'string', 'max:255'],
            'm_house_number' => [Rule::requiredIf($hasDifferentMailingAddress), 'string', 'max:10'],
            'm_apartment_number' => ['nullable', 'string', 'max:10'],
            'm_post_office' => [Rule::requiredIf($hasDifferentMailingAddress), 'string', 'max:255'],
        ])->validate();
    }
}
