<?php

namespace LaravelWorkcast\Endpoints;

use Illuminate\Http\Client\Response;
use LaravelWorkcast\WorkcastException;
use LaravelWorkcast\WorkcastPagination;

trait WithRestFullRead
{

    /**
     * @inheritDoc
     */
    public function callPagination(string $link): WorkcastPagination
    {
        $response = $this->httpClient()->get($link);

        return new WorkcastPagination($response, $this->key());
    }

    /**
     * @inheritDoc
     */
    public function list(array $query = []): WorkcastPagination
    {
        $query = array_merge([
            'limit' => 100,
            'skip' => 0,
        ], $query);

        $response = $this->httpClient()->get($this->baseUrl(), $query);

        return new WorkcastPagination($response, $this->key());
    }

    /**
     * @inheritDoc
     */
    public function get($id): Response
    {
        $response = $this->httpClient()->get($this->baseUrl() . "/{$id}");

        if (! $response->ok() || $response->json('responseCode') != 200) {
            throw new WorkcastException('Request error', $response->status());
        }

        return $response;
    }

    abstract public function key():string;
}
