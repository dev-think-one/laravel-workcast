<?php

namespace LaravelWorkcast;

use Illuminate\Http\Client\Response;

class WorkcastPagination
{
    protected Response $raw;

    protected string $key;


    /**
     * WorkcastPagination constructor.
     *
     * @param Response $response
     * @param string $key
     */
    public function __construct(Response $response, string $key)
    {
        $this->raw = $response;
        $this->key = $key;
    }

    /**
     * @return Response
     */
    public function getRawResponse(): Response
    {
        return $this->raw;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function items(?string $key = null, $default = null)
    {
        $key = $this->key . ($key ? ".{$key}" : '');

        return $this->raw->json($key, $default);
    }

    /**
     * @return int
     */
    public function totalCount(): int
    {
        return $this->raw->json('totalCount', 0) ?? 0;
    }

    /**
     * @return bool
     */
    public function hasNext(): bool
    {
        return ! empty($this->raw->json('paging.next'));
    }

    /**
     * @return bool
     */
    public function hasPrev(): bool
    {
        return ! empty($this->raw->json('paging.previous'));
    }

    /**
     * @return string|null
     */
    public function nextLink(): ?string
    {
        return $this->raw->json('paging.next');
    }

    /**
     * @return string|null
     */
    public function prevLink(): ?string
    {
        return $this->raw->json('paging.previous');
    }

    /**
     * Dynamically proxy other methods to the underlying response.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->raw->{$method}(...$parameters);
    }
}
