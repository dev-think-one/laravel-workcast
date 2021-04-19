<?php

namespace LaravelWorkcast;

use LaravelWorkcast\Endpoints\Contacts;
use LaravelWorkcast\Endpoints\Events;
use LaravelWorkcast\Endpoints\Presenters;
use LaravelWorkcast\Endpoints\Sessions;

/**
 *
 * @see https://insite.workcast.com/api-webinar-registration-documentation
 * @see https://insite.workcast.com/api-webinar-reporting-documentation
 *
 * @example Workcast::events()->list()
 * @example Workcast::sessions(63001)->list()->items()
 */
class WorkcastApi
{
    protected Auth $auth;

    /**
     * WorkcastApi constructor.
     */
    public function __construct(string $apiKey)
    {
        $this->auth = new Auth($apiKey);
    }

    /**
     * @return Auth
     */
    public function getAuth(): Auth
    {
        return $this->auth;
    }

    /**
     * @return Events
     */
    public function events(): Events
    {
        return new Events($this->getAuth());
    }

    /**
     * @param int $eventId
     *
     * @return Sessions
     */
    public function sessions(int $eventId): Sessions
    {
        return new Sessions($this->getAuth(), $eventId);
    }

    /**
     * @param int $eventId
     *
     * @return Contacts
     */
    public function contacts(int $eventId): Contacts
    {
        return new Contacts($this->getAuth(), $eventId);
    }

    /**
     * @param int $eventId
     *
     * @return Presenters
     */
    public function presenters(int $eventId): Presenters
    {
        return new Presenters($this->getAuth(), $eventId);
    }
}
