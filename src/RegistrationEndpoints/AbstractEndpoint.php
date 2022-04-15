<?php

namespace LaravelWorkcast\RegistrationEndpoints;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use LaravelWorkcast\AccessToken;
use LaravelWorkcast\Auth;
use LaravelWorkcast\WorkcastException;

abstract class AbstractEndpoint
{
    protected Auth $auth;

    /**
     * AbstractEndpoint constructor.
     *
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    abstract public function send(): Response;

    /**
     * @return PendingRequest
     * @throws WorkcastException
     */
    public function httpClient(): PendingRequest
    {
        $token = $this->auth->getToken();
        if (!$token || !($token instanceof AccessToken) || !$token->valid()) {
            throw new WorkcastException('Token not valid');
        }

        return Http::withToken($token->getToken())
            ->withOptions(array_merge(
                config('workcast.http_registration_config', []),
                [
                    'base_uri' => config('workcast.registration_url'),
                ]
            ));
    }
}
