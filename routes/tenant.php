<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use App\Http\Middleware\InitializeTenancyByPathCode;

$tenantRoutesClosure = function () {
//    Route::get('/', function () {
//        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('ID');
//    });

    Auth::routes();

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/profile', function () {
        // Only authenticated users may access this route...
        return 'Auth: This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    })->middleware('auth.basic');
};

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group($tenantRoutesClosure);

Route::domain('membership.localhost')->group(function () use ($tenantRoutesClosure) {
//    Route::group([
//        'prefix' => '/{tenant_code}',
//        'middleware' => [InitializeTenancyByPathCode::class],
//        'where' => ['tenant_code' => '[A-Za-z]{4}'],
//    ], $tenantRoutesClosure);
//
//    Route::group([
//        'prefix' => '/{tenant}',
//        'middleware' => [InitializeTenancyByPath::class],
//        'where' => ['tenant' => '[0-9]+'],
//    ], $tenantRoutesClosure);
});
