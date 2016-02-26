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
        'lead/deleteSource',
        'lead/deleteCre',
        'lead/deleteHerb',
        'lead/saveVoice',
        'service/saveNutritionist',
        'service/saveDoctor',
        'nutritionist/diet/delete',
        'finance/saveCre',
        'finance/saveSource',
        'finance/saveAudit',
        'herb/update'    
    ];
}
