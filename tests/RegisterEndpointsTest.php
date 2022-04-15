<?php


namespace LaravelWorkcast\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use LaravelWorkcast\AccessToken;
use LaravelWorkcast\Auth;
use LaravelWorkcast\Workcast;

class RegisterEndpointsTest extends TestCase
{
    protected Auth $auth;

    public function setUp(): void
    {
        parent::setUp();

        Cache::shouldReceive('get')
             ->twice()
             ->with('workcast_access_token')
             ->andReturn(serialize(new AccessToken('123qwe123', 3600)));

        $this->auth = new Auth('123');
    }

    /** @test */
    public function register()
    {
        /** @var \LaravelWorkcast\RegistrationEndpoints\Register $registerRequest */
        $registerRequest = Workcast::registerRequest();
        $registerRequest->eventPak('foo_eventPak')
            ->addEventSession('foo_EventSession')
            ->addEventSession('bar_EventSession')
            ->contactTitle('foo_contactTitle')
            ->contactFirstName('foo_contactFirstName')
            ->contactLastName('foo_contactLastName')
            ->contactEmail('foo_contactEmail')
            ->contactPhone('foo_contactPhone')
            ->contactJobTitle('foo_contactJobTitle')
            ->contactCompany('foo_contactCompany')
            ->contactAddressLine1('foo_contactAddressLine1')
            ->contactAddressLine2('foo_contactAddressLine2')
            ->contactAddressLine3('foo_contactAddressLine3')
            ->contactCity('foo_contactCity')
            ->contactCountyOrState('foo_contactCountyOrState')
            ->contactPostcode('foo_contactPostcode')
            ->contactCountryCode('foo_contactCountryCode')
            ->addContactContactCustomItem('foo', 'item1')
            ->addContactContactCustomItem('bar', 'item2');

        Http::fake(function (Request $request) {
            $data = $request->data()['registration'];
            $this->assertEquals('foo_eventPak', $data['event']['eventPak']);
            $this->assertEquals('bar_EventSession', $data['event']['sessions'][1]['sessionPak']);
            $this->assertEquals('foo_contactFirstName', $data['contact']['firstName']);

            return Http::response(json_encode([]), 200);
        });

        $registerRequest->send();
    }
}
