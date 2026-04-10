<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

final class DecryptEmail
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('email')) {
            $newRequest = $request->all();
            $newRequest['email'] = Crypt::decryptString($newRequest['email']);

            $request->replace($newRequest);
        }

        return $next($request);
    }
}
