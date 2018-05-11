<?php

namespace App\Exceptions;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiException;

class UnauthorizedException extends JsonApiException
{
    public function __construct($message = 'User is not logged in.', $code = 403)
    {
        parent::__construct($message, $code);
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("403")
                ->setCode("USER_UNAUTHORIZED")
                ->setTitle("Forbidden")
                ->setDetail($this->getMessage())
        ];
    }
}
