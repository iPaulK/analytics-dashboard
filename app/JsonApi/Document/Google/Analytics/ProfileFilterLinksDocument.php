<?php
namespace App\JsonApi\Document\Google\Analytics;

use App\JsonApi\Transformer\Google\Analytics\ProfileFilterLinkTransformer;
use WoohooLabs\Yin\JsonApi\Document\AbstractCollectionDocument;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Links;

/**
 * Class ProfileFilterLinksDocument
 * @package App\JsonApi\Document\Google\Analytics
 */
class ProfileFilterLinksDocument extends AbstractCollectionDocument
{
    public function __construct(ProfileFilterLinkTransformer $transformer)
    {
        parent::__construct($transformer);
    }
    
    /**
     * Provides information about the "jsonapi" member of the current document.
     *
     * The method returns a new JsonApiObject schema object if this member should be present or null
     * if it should be omitted from the response.
     */
    public function getJsonApi(): ?JsonApiObject
    {
        return new JsonApiObject("1.0");
    }

    /**
     * Provides information about the "meta" member of the current document.
     *
     * The method returns an array of non-standard meta information about the document. If
     * this array is empty, the member won't appear in the response.
     */
    public function getMeta(): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current document.
     *
     * The method returns a new Links schema object if you want to provide linkage data
     * for the document or null if the section should be omitted from the response.
     */
    public function getLinks(): ?Links
    {
        return null;
    }

    /**
     * Get the paginator info
     *
     * @return array
     */
    protected function getPagination()
    {
        return [];
    }
}
