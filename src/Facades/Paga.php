<?php

namespace Bamilawal\LaravelAnypayAfrica\Facades;

use Illuminate\Support\Facades\Facade;

class Paga extends Facade
{
    protected static function getAccessor() 
    {
        return 'paga';
    }
}