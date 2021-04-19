<?php
namespace LaravelWorkcast\Endpoints;

use LaravelWorkcast\Auth;

class Presenters extends AbstractEndpoint implements HasRestFullRead
{
    use WithRestFullRead;

    protected int $eventId;

    public function __construct(Auth $auth, int $eventId)
    {
        $this->eventId = $eventId;

        parent::__construct($auth);
    }

    public function baseUrl(): string
    {
        return "events/{$this->eventId}/" . $this->key();
    }

    public function key(): string
    {
        return 'presenters';
    }
}
