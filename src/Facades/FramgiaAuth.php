<?php

namespace Sun\Auth\Facades;

use Illuminate\Support\Facades\Facade;
use Sun\Auth\Contracts\Factory;

/**
 * @see \Sun\Auth\FramgiaAuthManager
 */
class FramgiaAuth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
