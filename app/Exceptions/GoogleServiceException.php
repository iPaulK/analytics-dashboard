<?php

namespace App\Exceptions;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiException;

class GoogleServiceException extends JsonApiException
{
    public function __construct($message = 'An error occurred during execution; Please try again later.', $code = 400)
    {
        parent::__construct($message, $code);
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("GOOGLE_SERVICE_EXCEPTION")
                ->setTitle("Google Service Exception")
                ->setDetail($this->getMessage())
        ];
    }
}
