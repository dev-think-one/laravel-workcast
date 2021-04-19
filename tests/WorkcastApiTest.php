<?php


namespace LaravelWorkcast\Tests;

use LaravelWorkcast\Auth;
use LaravelWorkcast\Endpoints\Contacts;
use LaravelWorkcast\Endpoints\Events;
use LaravelWorkcast\Endpoints\Presenters;
use LaravelWorkcast\Endpoints\Sessions;
use LaravelWorkcast\Workcast;

class WorkcastApiTest extends TestCase
{

    /** @test */
    public function get_auth()
    {
        $this->assertInstanceOf(Auth::class, Workcast::getAuth());
    }

    /** @test */
    public function predefined_endpoints()
    {
        $this->assertInstanceOf(Events::class, Workcast::events());
        $this->assertInstanceOf(Sessions::class, Workcast::sessions(1));
        $this->assertInstanceOf(Contacts::class, Workcast::contacts(1));
        $this->assertInstanceOf(Presenters::class, Workcast::presenters(1));
    }
}
