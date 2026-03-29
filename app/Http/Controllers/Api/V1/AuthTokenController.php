<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\BodyParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthTokenController extends Controller
{
    /**
     * @unauthenticated
     */
    #[BodyParameter(
        name: 'email',
        type: 'string',
        default: 'admin@localhost',
    )]
    #[BodyParameter(
        name: 'password',
        type: 'string',
        default: 'password',
    )]
    public function __invoke(Request $request): Response
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($validated)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $token = Auth::user()
            ->createToken('api')
            ->plainTextToken;

        return response()->json($token);
    }
}
