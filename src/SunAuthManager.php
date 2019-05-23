<?php

namespace Sun\Auth;

use Illuminate\Support\Arr;
use Illuminate\Support\Manager;
use Sun\Auth\Providers\SunProvider;

class SunAuthManager extends Manager implements Contracts\Factory
{
    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function with($driver)
    {
        return $this->driver($driver);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Sun\Auth\Providers\AbstractProvider
     */
    protected function createSunDriver()
    {
        $config = Arr::get($this->app['config'], 'services.sun');

        return $this->buildProvider(SunProvider::class, $config);
    }

    /**
     * Build an OAuth 2 provider instance.
     *
     * @param  string  $provider
     * @param  array  $config
     * @return \Sun\Auth\Providers\AbstractProvider
     */
    public function buildProvider($provider, $config)
    {
        return new $provider(
            $this->app['request'],
            $config['client_id'],
            $config['client_secret'],
            value($config['redirect']),
            Arr::get($config, 'guzzle', [])
        );
    }

    /**
     * Format the server configuration.
     *
     * @param  array  $config
     * @return array
     */
    public function formatConfig(array $config)
    {
        return array_merge([
            'identifier' => $config['client_id'],
            'secret' => $config['client_secret'],
            'callback_uri' => value($config['redirect']),
        ], $config);
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return 'sun';
    }
}
