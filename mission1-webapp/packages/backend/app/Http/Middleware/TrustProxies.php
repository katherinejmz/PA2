<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Symfony\Component\HttpFoundation\Request;

class TrustProxies extends Middleware
{
    
    // Liste des proxys de confiance.
    // "*" = tout proxy est accepté (utile pour localhost, nginx, etc.)
    
    protected $proxies = '*';

    
    // Headers utilisés pour déterminer IP réelle du client.
    
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
