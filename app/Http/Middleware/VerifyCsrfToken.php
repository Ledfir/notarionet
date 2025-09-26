<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/newOrderEmail',
        '/signatureOrderEmail',
        '/userCreditsEmail',
        '/saveOrderImage',
        '/resedEmail',
        '/contrato_amha',
        '/contrato_sandybeach',
        '/contrato_sandybeach_actualizar',

        '/mexican_individual/playapalmeras',
        '/mexican_individual/sterligndev',
        '/mexican_individual/resell',


        '/mexican_corporation/playapalmeras',
        '/mexican_corporation/sterligndev',
        '/mexican_corporation/resell',

        '/foreigner/playapalmeras',
        '/foreigner/sterligndev',
        '/foreigner/resell',

        '/get_contract',

    ];
}
