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
        'herb/update' ,
        'settings/cart/payment/method/update' ,
        'settings/cart/payment/method/approver/update' ,
        'settings/cart/discount/approver/update' ,
        'settings/cart/discount/update',
        'settings/product/category/name/update',
        'settings/product/category/unit/update',
        'settings/product/unit/update'
    ];
}
