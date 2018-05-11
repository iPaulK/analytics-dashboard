<?php

namespace App\Exceptions;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiException;

class AuthenticationException extends JsonApiException
{
    public function __construct($message = 'Invalid credentials', $code = 401)
    {
        parent::__construct($message, $code);
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("401")
                ->setCode("AUTH_FAILED")
                ->setTitle("Authentication failed")
                ->setDetail($this->getMessage())
        ];
    }
}
