<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\JsonApi\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\JsonApi;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof JsonApiExceptionInterface) {
            /* @var JsonApi $jsonApi */
            $jsonApi = app()->make(JsonApi::class);
            $resp =  $jsonApi->respond()->genericError($e->getErrorDocument());

            $resp = $resp->withAddedHeader('Access-Control-Allow-Headers', 'Authorization,DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Content-Range,Range');
            $resp = $resp->withAddedHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE, PATCH');
            $resp = $resp->withAddedHeader('Access-Control-Allow-Origin', '*');

            return $resp;
        } else if ($e instanceof ModelNotFoundException || $e instanceof HttpException) {
            return $this->notFound();
        }
        
        return parent::render($request, $e);
    }

    /**
     * Returns json response for not found exception.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notFound($message = 'The resource you are requesting does not exist', $statusCode = 404)
    {
        $errors[] = Error::create()
                    ->setStatus('404')
                    ->setCode('NOT_FOUND')
                    ->setTitle('Not found')
                    ->setDetail($message);
        return $this->jsonResponse($errors, $statusCode);
    }

    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse($errors = [], $statusCode = 404)
    {
        /* @var JsonApi $jsonApi */
        $jsonApi = app()->make(JsonApi::class);
        return $jsonApi->respond()->genericError($this->getErrorDocument(), $errors, $statusCode);
    }

    /**
     * Get error document
     *
     * @return ErrorDocument
     */
    protected function getErrorDocument()
    {
        return new ErrorDocument();
    }
}
