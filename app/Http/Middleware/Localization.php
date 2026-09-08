<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Localization
{
    public function handle($request, Closure $next)
    {
        $rawLocale = $request->header('Accept-Language');
        $locale = 'ar';

        if (!empty($rawLocale)) {
            $langCode = strtolower(substr(trim($rawLocale), 0, 2));
            if (in_array($langCode, ['ar', 'en'], true)) {
                $locale = $langCode;
            }
        }

        App::setLocale($locale);

        return $next($request);
    }
}
