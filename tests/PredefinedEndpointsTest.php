<?php


namespace LaravelWorkcast\Tests;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use LaravelWorkcast\AccessToken;
use LaravelWorkcast\Auth;
use LaravelWorkcast\Endpoints\Contacts;
use LaravelWorkcast\Endpoints\Events;
use LaravelWorkcast\Endpoints\Presenters;
use LaravelWorkcast\Endpoints\Sessions;
use LaravelWorkcast\WorkcastException;

class PredefinedEndpointsTest extends TestCase
{
    protected Auth $auth;

    public function setUp(): void
    {
        parent::setUp();

        Cache::shouldReceive('get')
             ->once()
             ->with('workcast_access_token')
             ->andReturn(serialize(new AccessToken('123qwe123', 3600)));

        $this->auth = new Auth('123');
    }

    /** @test */
    public function events()
    {
        $eventsEndpoint = new Events($this->auth);

        $this->assertEquals('events', $eventsEndpoint->baseUrl());

        $this->assertInstanceOf(PendingRequest::class, $eventsEndpoint->httpClient());
    }

    /** @test */
    public function events_error()
    {
        Cache::shouldReceive('forget')
             ->twice()
             ->with('workcast_access_token')
             ->andReturn(true);
        Cache::shouldReceive('get')
             ->once()
             ->with('workcast_access_token')
             ->andReturn(false);
        $this->auth->forgetToken();
        Http::fake(function ($request) {
            return Http::response('', 200);
        });
        $eventsEndpoint = new Events(new Auth('123'));
        $this->expectException(WorkcastException::class);
        $eventsEndpoint->httpClient();
    }

    /** @test */
    public function contacts()
    {
        $eventsEndpoint = new Contacts($this->auth, 22);

        $this->assertEquals("events/22/contacts", $eventsEndpoint->baseUrl());
    }

    /** @test */
    public function presenters()
    {
        $eventsEndpoint = new Presenters($this->auth, 22);

        $this->assertEquals("events/22/presenters", $eventsEndpoint->baseUrl());
    }

    /** @test */
    public function sessions()
    {
        $eventsEndpoint = new Sessions($this->auth, 22);

        $this->assertEquals("events/22/sessions", $eventsEndpoint->baseUrl());
    }
}
