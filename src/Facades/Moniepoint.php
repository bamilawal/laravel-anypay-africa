<?php

namespace Bamilawal\LaravelAnypayAfrica\Facades;

use Illuminate\Support\Facades\Facade;

class Moniepoint extends Facade
{
    protected static function getAccessor() 
    {
        return 'moniepoint';
    }
}