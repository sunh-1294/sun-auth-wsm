<?php

namespace Sun\Auth\Providers;

use Exception;
use Illuminate\Support\Arr;

class SunProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Base OAuth url
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['email'];

    public function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->baseUrl = Arr::get(app('config'), 'services.sun.base_url');
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->baseUrl . '/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->baseUrl . '/auth/access_token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $userUrl = $this->baseUrl . '/me';
        try {
            $response = $this->getHttpClient()->post($userUrl, [
                'form_params' => [
                    'access_token' => $token,
                ],
                $this->getRequestOptions(),
            ]);
        } catch (Exception $e) {
            return;
        }
        $user = json_decode($response->getBody(), true);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => Arr::get($user, 'employee_code'),
            'avatar' => Arr::get($user, 'avatar'),
            'name' => Arr::get($user, 'name'),
            'email' => Arr::get($user, 'email'),
            'gender' => Arr::get($user, 'gender'),
            'birthday' => Arr::get($user, 'birthday'),
            'phoneNumber' => Arr::get($user, 'phone_number'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequestOptions()
    {
        return [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ];
    }
}
