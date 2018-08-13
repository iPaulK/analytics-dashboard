<?php

namespace App\Exceptions;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiException;

class TokenInvalidException extends JsonApiException
{
    public function __construct($message = 'Token Signature could not be verified.', $code = 400)
    {
        parent::__construct($message, $code);
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("INVALID_TOKEN")
                ->setTitle("Invalid token")
                ->setDetail($this->getMessage())
        ];
    }
}
