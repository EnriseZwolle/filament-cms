<?php

namespace Enrisezwolle\FilamentCms\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TranslatableMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;
        $path = $request->path();

        if (count(config('filament-cms.languages', [])) && $this->pathIsMultilingual($request->path())) {
            $locale = Str::before($path, '/');
            $path = Str::after($path, '/');

            app()->setLocale($locale);
        }

        $request->lookupLocale = $locale;
        $request->lookupPath = $path;

        return $next($request);
    }

    protected function pathIsMultilingual(string $path): bool
    {
        $segments = array_filter(explode('/', $path));

        if (count($segments) > 1 && in_array($segments[0], config('filament-cms.languages', []))) {
            return true;
        }

        return false;
    }
}
