<?php

namespace Wepa\Procedures\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Wepa\Procedures\Procedures
 */
class Procedures extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Wepa\Procedures\Procedures::class;
    }
}
