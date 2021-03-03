<?php

namespace WebId\Flan\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->isFiltersRoute($request) && !config('flan.routing.filters.active')) {
            abort(404);
        }

        if ($this->isExportRoute($request) && !config('flan.routing.export.active')) {
            abort(404);
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function isFiltersRoute(Request $request): bool
    {
        $route = $request->route();
        if ($route && $route->getName() === null) {
            return false;
        }

        return str_starts_with(
            $route->getName(),
            config('flan.routing.filters.name')
        );
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function isExportRoute(Request $request): bool
    {
        $route = $request->route();
        if (!$route || $route->getName() === null) {
            return false;
        }

        return str_starts_with(
            $route->getName(),
            config('flan.routing.export.name')
        );
    }
}
