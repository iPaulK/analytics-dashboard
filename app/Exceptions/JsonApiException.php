<?php

namespace App\Exceptions;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiException as JAE;

class JsonApiException extends JAE
{
    public function __construct($message = 'An error occurred during execution; Please try again later.', $code = 500)
    {
        parent::__construct($message, $code);
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("500")
                ->setCode("ERROR")
                ->setTitle("Invalid token")
                ->setDetail($this->getMessage())
        ];
    }
}
