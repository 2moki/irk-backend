<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;

class UserController extends Controller
{
public function update(Request $request)
{
    $user = $request->user();

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

    if ($request->has('address')) {
        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            $request->input('address')
        );
    }

    return response()->json([
        'data' => $user->fresh('address.country')
    ]);
}
}