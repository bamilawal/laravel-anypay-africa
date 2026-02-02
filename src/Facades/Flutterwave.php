<?php

namespace Bamilawal\LaravelAnypayAfrica\Facades;

use Illuminate\Support\Facades\Facade;

class Flutterwave extends Facade
{
    protected static function getAccessor() 
    {
        return 'flutterwave';
    }
}