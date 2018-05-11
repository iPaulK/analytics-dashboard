<?php
declare(strict_types=1);

namespace App\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Links;

/**
 * Class ErrorDocument
 */
class ErrorDocument extends AbstractErrorDocument
{
    /**
     * Get information about JSON API implementation
     *
     * @return null|JsonApiObject
     */
    public function getJsonApi(): ?JsonApiObject
    {
        return new JsonApiObject("1.0");
    }

    /**
     * Get links related to the primary data
     *
     * @return null|Links
     */
    public function getLinks(): ?Links
    {
        return null;
    }

    /**
     * Get non-standard meta-information
     *
     * @return array
     */
    public function getMeta(): array
    {
        return [];
    }
}
