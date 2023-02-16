<?php


namespace LaravelWorkcast\Tests;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use LaravelWorkcast\AccessToken;
use LaravelWorkcast\Auth;

class AuthTest extends TestCase
{
    /** @test */
    public function has_token()
    {
        $auth = new Auth('123');
        $this->assertFalse($auth->hasToken());
    }

    /** @test */
    public function get_token()
    {
        Http::fake(function ($request) {
            return Http::response(json_encode([
                'idToken'   => 'asd123asd',
                'expiresIn' => 3600,
            ]), 200);
        });

        Cache::shouldReceive('get')
             ->once()
             ->with('workcast_access_token')
             ->andReturn(false);

        Cache::shouldReceive('add')
             ->once()
             ->andReturn(true);

        $auth = new Auth('123');
        $this->assertInstanceOf(AccessToken::class, $auth->getToken());
        $this->assertTrue($auth->hasToken());
        // next time receive from memory
        $this->assertInstanceOf(AccessToken::class, $auth->getToken());
    }

    /** @test */
    public function restore_token()
    {
        Cache::shouldReceive('get')
             ->once()
             ->with('workcast_access_token')
             ->andReturn(serialize(new AccessToken('123qwe123', 100)));

        $auth = new Auth('123');
        $this->assertInstanceOf(AccessToken::class, $auth->getToken());
        $this->assertTrue($auth->hasToken());
    }

    /** @test */
    public function request_token()
    {
        Http::fake(function ($request) {
            return Http::response('', 200);
        });

        Cache::shouldReceive('get')
             ->once()
             ->with('workcast_access_token')
             ->andReturn(false);

        Cache::shouldReceive('forget')
             ->once()
             ->with('workcast_access_token')
             ->andReturn(true);

        $auth = new Auth('123');
        $this->assertNull($auth->getToken());
        $this->assertFalse($auth->hasToken());
    }
}
