<?php


namespace LaravelWorkcast\Tests;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use LaravelWorkcast\AccessToken;
use LaravelWorkcast\Auth;
use LaravelWorkcast\Endpoints\Events;
use LaravelWorkcast\WorkcastException;
use LaravelWorkcast\WorkcastPagination;

class WithRestFullReadTest extends TestCase
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
    public function get_id()
    {
        $eventsEndpoint = new Events($this->auth);

        Http::fake(function ($request) {
            return Http::response(json_encode([
                'responseCode' => 200,
            ]), 200);
        });

        $eventsEndpoint->get(22);
    }

    /** @test */
    public function get_id_error()
    {
        $eventsEndpoint = new Events($this->auth);

        Http::fake(function ($request) {
            return Http::response(json_encode([]), 200);
        });

        $this->expectException(WorkcastException::class);
        $eventsEndpoint->get(22);
    }

    /** @test */
    public function list()
    {
        $eventsEndpoint = new Events($this->auth);

        Http::fake(function ($request) {
            return Http::response('', 200);
        });

        $response = $eventsEndpoint->list();

        $this->assertInstanceOf(WorkcastPagination::class, $response);
    }

    /** @test */
    public function pagination()
    {
        $eventsEndpoint = new Events($this->auth);

        Http::fake(function ($request) {
            return Http::response('', 200);
        });

        $response = $eventsEndpoint->callPagination('some_link');

        $this->assertInstanceOf(WorkcastPagination::class, $response);
    }
}
