<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Stancl\Tenancy\Exceptions\RouteIsMissingTenantParameterException;
use Stancl\Tenancy\Resolvers\PathTenantResolver;
use Stancl\Tenancy\Tenancy;
use App\Models\Tenant;
use Stancl\Tenancy\Middleware\IdentificationMiddleware;
use Stancl\Tenancy\Contracts\TenantCouldNotBeIdentifiedException;

class InitializeTenancyByPathCode extends IdentificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var Route $route */
        $route = $request->route();

        // Only initialize tenancy if tenant is the first parameter
        // We don't want to initialize tenancy if the tenant is
        // simply injected into some route controller action.
        if ($route->parameterNames()[0] === 'tenant_code') {
            $tenant = Tenant::where('Code', $request->route('tenant_code'))->first();

            tenancy()->initialize($tenant);
        } else {
            throw new RouteIsMissingTenantParameterException;
        }

        return $next($request);
    }
}
