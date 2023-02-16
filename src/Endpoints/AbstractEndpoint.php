<?php

namespace LaravelWorkcast\Endpoints;

use Illuminate\Http\Client\PendingRequest;
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

    abstract public function baseUrl(): string;

    abstract public function key(): string;

    /**
     * @return PendingRequest
     * @throws WorkcastException
     */
    public function httpClient(): PendingRequest
    {
        $token = $this->auth->getToken();
        if (!$token || !($token instanceof AccessToken)) {
            throw new WorkcastException('Token is empty or has not valid structure');
        }
        if (!$token->valid()) {
            throw new WorkcastException('Token not valid ['.var_export($token->getRawData(), true).']');
        }

        return Http::withToken($token->getToken())
            ->withOptions(array_merge(
                config('workcast.http_reporting_config', []),
                [
                    'base_uri' => config('workcast.reporting_url'),
                ]
            ));
    }
}
