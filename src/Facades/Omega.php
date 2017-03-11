<?php

namespace artworx\omegacp\Facades;

use Illuminate\Support\Facades\Facade;

class Omega extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'omega';
    }
}
