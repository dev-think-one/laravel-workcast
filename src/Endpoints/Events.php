<?php

namespace LaravelWorkcast\Endpoints;

class Events extends AbstractEndpoint implements HasRestFullRead
{
    use WithRestFullRead;


    public function baseUrl(): string
    {
        return $this->key();
    }

    public function key(): string
    {
        return 'events';
    }
}
