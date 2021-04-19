<?php


namespace LaravelWorkcast;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for workcast api
 *
 * Class Workcast
 * @package LaravelWorkcast
 */
class Workcast extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \LaravelWorkcast\WorkcastApi::class;
    }
}
