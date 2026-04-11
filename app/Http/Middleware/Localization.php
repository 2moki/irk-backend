<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class Localization
{
    private const array ALLOWED_LANGUAGES = ['en', 'pl'];

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $localization = $request->header('Accept-Language');
        $localization = in_array($localization, self::ALLOWED_LANGUAGES, true) ? $localization : 'en';
        app()->setLocale($localization);

        return $next($request);
    }
}
