<?php

namespace LaravelWorkcast\Endpoints;

use Illuminate\Http\Client\Response;
use LaravelWorkcast\WorkcastException;
use LaravelWorkcast\WorkcastPagination;

interface HasRestFullRead
{
    /**
     * Get paginated list based on link
     *
     * @param string $link
     *
     * @return WorkcastPagination
     * @throws WorkcastException
     */
    public function callPagination(string $link): WorkcastPagination;

    /**
     * Get paginated list based on query
     *
     * @return WorkcastPagination
     * @throws WorkcastException
     */
    public function list(): WorkcastPagination;

    /**
     * Get single item
     *
     * @param $id
     *
     * @return array|mixed
     * @throws WorkcastException
     */
    public function get($id): Response;

    /**
     * @return string
     */
    public function key():string;
}
