<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Models\Address;

class UserController extends Controller
{
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();

        // 🔹 update user basic data
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

        // 🔥 ADDRESS FIX
        if ($request->has('address')) {

            if ($user->address_id) {
                // update existing
                $user->address()->update($request->input('address'));
            } else {
                // create new
                $address = Address::create($request->input('address'));

                $user->update([
                    'address_id' => $address->id
                ]);
            }
        }

        return response()->json([
            'data' => $user->fresh('address'),
        ]);
    }
}