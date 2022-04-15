<?php


namespace LaravelWorkcast\Tests;

use Illuminate\Http\Client\Response;
use LaravelWorkcast\WorkcastPagination;
use Mockery\Mock;

class WorkcastPaginationTest extends TestCase
{

    /** @test */
    public function pagination_data()
    {
        /** @var Response $spy */
        $spy = $this->spy(Response::class);

        $pagination = new WorkcastPagination($spy, 'contacts');

        $this->assertEquals($spy, $pagination->getRawResponse());

        /** @var Mock $spy */
        $spy->shouldReceive('json')
            ->once()
            ->with('contacts', null)
            ->andReturn([[], []]);

        $spy->shouldReceive('json')
            ->once()
            ->with('contacts.1.title', null)
            ->andReturn('Zoom Effect');

        $spy->shouldReceive('json')
            ->once()
            ->with('contacts.1.bla_bla', 'Something')
            ->andReturn('Something');

        $spy->shouldReceive('json')
            ->once()
            ->with('totalCount', 0)
            ->andReturn(3);

        $spy->shouldReceive('json')
            ->twice()
            ->with('paging.next')
            ->andReturn('https://link');

        $spy->shouldReceive('json')
            ->twice()
            ->with('paging.previous')
            ->andReturn(null);

        $this->assertCount(2, $pagination->items());
        $this->assertEquals('Zoom Effect', $pagination->items('1.title'));
        $this->assertEquals('Something', $pagination->items('1.bla_bla', 'Something'));
        $this->assertEquals(3, $pagination->totalCount());
        $this->assertTrue($pagination->hasNext());
        $this->assertFalse($pagination->hasPrev());
        $this->assertNull($pagination->prevLink());
        $this->assertEquals('https://link', $pagination->nextLink());
        $this->assertEquals('contacts', $pagination->getKey());
    }

    /** @test */
    public function propangate_call()
    {
        /** @var Response $spy */
        $spy = $this->spy(Response::class);

        $pagination = new WorkcastPagination($spy, 'contacts');

        $spy->shouldReceive('someMethod')
            ->once()
            ->with('data');

        $pagination->someMethod('data');
    }
}
