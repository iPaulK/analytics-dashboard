<?php

namespace App\Providers;

use WoohooLabs\Yin\JsonApi\JsonApi;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Serializer\JsonDeserializer;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Laravel\Lumen\Application;

/**
 * Class JsonApiProvider
 * @package App\Providers
 */
class JsonApiProvider extends AppServiceProvider
{
    public function register()
    {
        $this->app->singleton(JsonApi::class, function (Application $app) {
            $exceptionFactory = new DefaultExceptionFactory();
            $deserializer = new JsonDeserializer();
            $request = new Request(ServerRequestFactory::fromGlobals(), $exceptionFactory, $deserializer);

            return new JsonApi($request, new Response(), $exceptionFactory);
        });
    }
}
