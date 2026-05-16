<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Models\Address;
use Illuminate\Support\Facades\Hash; // 🔥 Wymagane do weryfikacji hasła
use Illuminate\Validation\ValidationException; // 🔥 Do rzucania błędów walidacji
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function update(UpdateUserRequest $request): Response
    {
        $user = $request->user();

        // 1. Zmiana hasła (jeśli przesłano pole password)
        if ($request->filled('password')) {
            // Sprawdzenie, czy obecne hasło zgadza się z bazą
            if (! Hash::check($request->input('current_password'), $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => ['Podane aktualne hasło jest niepoprawne.'],
                ]);
            }

            // Aktualizacja hasła z automatycznym haszowaniem (Laravel 10/11 haszuje automatycznie, ale dla pewności dajemy Hash::make)
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

        // 3. Adres (Twój dotychczasowy kod)
        if ($request->has('address')) {
            if ($user->address_id) {
                $user->address()->update($request->input('address'));
            } else {
                $address = Address::create($request->input('address'));
                $user->update([
                    'address_id' => $address->id,
                ]);
            }
        }

        return response()->json([
            'data' => $user->fresh('address'),
        ]);
    }
}