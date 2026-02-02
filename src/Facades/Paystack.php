<?php

namespace Bamilawal\LaravelAnypayAfrica\Facades;

use Illuminate\Support\Facades\Facade;

class Paystack extends Facade
{
    protected static function getFacadeAccessor() 
    {
        return 'paystack';
    }
}