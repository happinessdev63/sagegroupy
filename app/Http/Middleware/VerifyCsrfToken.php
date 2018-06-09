<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/apiv1/files/create',
        '/apiv1/files/create/*',
        '/apiv1/files/avatar',
        '/apiv1/files/agencyAvatar/*',
        '/contact',
        '/prelaunch',
        '/prelaunchSurvey',
    ];
}
