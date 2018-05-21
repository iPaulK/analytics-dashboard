<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Google\Client;

class Google extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }
}