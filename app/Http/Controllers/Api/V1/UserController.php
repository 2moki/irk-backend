<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function update(UpdateUserRequest $request): Response
    {
        $user = $request->user();

        // 1. Zmiana hasła
        if ($request->filled('password')) {
            if (! Hash::check($request->input('current_password'), $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => ['Podane aktualne hasło jest niepoprawne.'],
                ]);
            }

            $user->update([
                'password' => Hash::make($request->input('password')),
            ]);
        }

        // 2. Aktualizacja podstawowych danych użytkownika
        $user->update($request->only([
            'first_name',
            'middle_name',
            'last_name',
            'email',
            'phone_prefix',
            'phone_number',
            'pesel',
            'date_of_birth',
            'gender',
        ]));

        // 3. Aktualizacja lub utworzenie adresu GŁÓWNEGO
        // Pobieramy tylko te dane, które należą do tabeli adresów z głównego poziomu requestu
        $addressData = $request->only(['street', 'house_number', 'apartment_number', 'city', 'post_code', 'country_id']);

        // Warunek: wykonaj zapis tylko jeśli przesłano przynajmniej podstawowe dane adresowe
        if (! empty($addressData['street']) || ! empty($addressData['city'])) {
            if ($user->address_id && $user->address) {
                $user->address->update($addressData);
            } else {
                $address = Address::create($addressData);
                $user->update([
                    'address_id' => $address->id,
                ]);
            }
        }

        // 4. Aktualizacja, utworzenie lub usunięcie adresu KORESPONDENCYJNEGO
        if ($request->input('has_correspondence') === true && $request->has('mailing_address')) {
            $mailingData = $request->input('mailing_address');

            if ($user->mailing_address_id && $user->mailingAddress) {
                $user->mailingAddress->update($mailingData);
            } else {
                $mailingAddress = Address::create($mailingData);
                $user->update([
                    'mailing_address_id' => $mailingAddress->id,
                ]);
            }
        } else {
            // Jeśli użytkownik odznaczył "Inny adres do korespondencji", odpinamy i usuwamy go z bazy
            if ($user->mailing_address_id) {
                $oldMailingId = $user->mailing_address_id;
                $user->update([
                    'mailing_address_id' => null,
                ]);
                Address::destroy($oldMailingId);
            }
        }

        // Zwracamy użytkownika ze świeżymi relacjami (wymagane dla poprawnego działania sklepu Pinia)
        return response()->json([
            'data' => $user->fresh(['address.country', 'mailingAddress.country']),
        ]);
    }
}
