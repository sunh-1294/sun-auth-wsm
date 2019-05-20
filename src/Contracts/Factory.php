<?php

namespace Sun\Auth\Contracts;

interface Factory
{
    /**
     * Get an OAuth provider implementation.
     *
     * @param  string  $driver
     * @return \Sun\Auth\Contracts\Provider
     */
    public function driver($driver = null);
}
