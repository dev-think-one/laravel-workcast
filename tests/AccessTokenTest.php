<?php


namespace LaravelWorkcast\Tests;

use Carbon\Carbon;
use Illuminate\Support\Str;
use LaravelWorkcast\AccessToken;

class AccessTokenTest extends TestCase
{

    /** @test */
    public function validity()
    {
        $accessToken = new AccessToken('123qwe123', 61);

        $this->assertTrue($accessToken->valid());

        $accessToken = new AccessToken('123qwe123', 59);

        $this->assertFalse($accessToken->valid());
    }

    /** @test */
    public function token_text()
    {
        $accessToken = new AccessToken('123qwe123', 61);

        $this->assertEquals('123qwe123', $accessToken->getToken());

        $accessToken = new AccessToken('123qwe123', 59);

        $this->assertEquals('123qwe123', $accessToken->getToken());
    }

    /** @test */
    public function authorization_header()
    {
        $accessToken = new AccessToken('123qwe123', 61);

        $this->assertEquals('Bearer 123qwe123', $accessToken->getAuthorizationHeader());

        $accessToken = new AccessToken('123qwe123', 59);

        $this->assertNull($accessToken->getAuthorizationHeader());
    }

    /** @test */
    public function serialisation()
    {
        $accessToken = new AccessToken('123qwe123', 70);

        $serialised = serialize($accessToken);

        $this->assertTrue(Str::containsAll($serialised, [ 'token', 'expiresIn', 'expiresAt' ]));

        $accessToken = unserialize($serialised);

        $this->assertEquals('Bearer 123qwe123', $accessToken->getAuthorizationHeader());
    }

    /** @test */
    public function serialisation_exception()
    {
        $accessToken = new AccessToken('123qwe123', 70);

        $serialised = serialize($accessToken);
        $serialised = str_replace(Carbon::now()->format('Y-m-d'), 'blah-bl-ah', $serialised);

        $accessToken = unserialize($serialised);

        $this->assertNull($accessToken->getAuthorizationHeader());
    }

    /** @test */
    public function expires_at()
    {
        $accessToken = new AccessToken('123qwe123', 61);

        $this->assertEquals(Carbon::now()->addSeconds(61)->format('Y-m-d H:i:s'), $accessToken->getExpiresAt()->format('Y-m-d H:i:s'));

        $accessToken = new AccessToken('123qwe123', 61, Carbon::now()->addMinutes(10));

        $this->assertEquals(Carbon::now()->addSeconds(600)->format('Y-m-d H:i:s'), $accessToken->getExpiresAt()->format('Y-m-d H:i:s'));

        $accessToken = new AccessToken('123qwe123', 59);

        $this->assertEquals(Carbon::now()->format('Y-m-d H:i:s'), $accessToken->getExpiresAt()->format('Y-m-d H:i:s'));
    }
}
