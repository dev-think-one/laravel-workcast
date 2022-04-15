<?php

namespace LaravelWorkcast;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Auth
{
    protected ?AccessToken $accessToken = null;

    protected string $cacheKey = 'workcast_access_token';

    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;

        $this->restoreToken();
    }

    public function hasToken(): bool
    {
        return $this->accessToken && $this->accessToken->valid();
    }

    public function getToken(): ?AccessToken
    {
        if ($this->hasToken()) {
            return $this->accessToken;
        }

        return $this->requestToken();
    }

    protected function requestToken(): ?AccessToken
    {
        $response = Http::withHeaders([
            'APIKey' => $this->apiKey,
        ])->withOptions(array_merge(
            config('workcast.http_auth_config', []),
            [
                'base_uri' => config('workcast.auth_url'),
            ]
        ))->post('/signin');

        if ($response->ok() && ($responseData = $response->json()) &&
             !empty($responseData['idToken']) && !empty($responseData['expiresIn'])
        ) {
            $this->accessToken = new AccessToken($responseData['idToken'], $responseData['expiresIn']);
        } else {
            $this->accessToken = null;
        }

        $this->storeToken();

        return $this->accessToken;
    }

    protected function restoreToken(): void
    {
        $tokenData = Cache::get($this->cacheKey);
        $token     = unserialize($tokenData);
        if ($token && ($token instanceof AccessToken) && $token->valid()) {
            $this->accessToken = $token;
        }
    }

    protected function storeToken(): bool
    {
        if ($this->accessToken && $this->accessToken->valid()) {
            return Cache::add($this->cacheKey, serialize($this->accessToken), $this->accessToken->getExpiresAt());
        }

        return Cache::forget($this->cacheKey);
    }

    public function forgetToken(): void
    {
        $this->accessToken = null;
        Cache::forget($this->cacheKey);
    }
}
