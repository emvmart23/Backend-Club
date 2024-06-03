<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'auth/register',
        'auth/login',
        'users/delete/*',
        'users/update/*',
        'products/delete/*',
        'products/update/*',
        'products/create',
        'unit_measures/create',
        'unit_measures/delete/*',
        'unit_measures/update/*',
        'categories/create',
        'categories/delete/*',
        'categories/update/*',
        'roles/create',
        'roles/update/*',
        'roles/delete/*',
        'customers/create',
        'customers/delete/*',
        'attendances/create',
        'attendances/update',
        'boxes/create',
        'boxes/close/*',
        'orders/create',
        'headers/create',
        'attended/*'
    ];
}
