<?php
namespace App\JsonApi\Document;

use App\JsonApi\Transformer\UserResourceTransformer;
use WoohooLabs\Yin\JsonApi\Document\AbstractCollectionDocument;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use Illuminate\Pagination\AbstractPaginator;

/**
 * Class UsersDocument
 * @package App\JsonApi\Document
 */
class UsersDocument extends AbstractCollectionDocument
{
    public function __construct(UserResourceTransformer $transformer)
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
        return [
            'pagination' => $this->getPagination(),
        ];
    }

    /**
     * Provides information about the "links" member of the current document.
     *
     * The method returns a new Links schema object if you want to provide linkage data
     * for the document or null if the section should be omitted from the response.
     */
    public function getLinks(): ?Links
    {
        return null; //Links::createWithoutBaseUri()->setPagination("/users", $this->domainObject);
    }

    /**
     * Get the paginator info
     *
     * @return array
     */
    protected function getPagination()
    {
        if ($this->domainObject instanceof AbstractPaginator) {
            return [
                'current_page' => $this->domainObject->currentPage(),
                'from' => $this->domainObject->firstItem(),
                'last_page' => $this->domainObject->lastPage(),
                'per_page' => $this->domainObject->perPage(),
                'to' => $this->domainObject->lastItem(),
                'total' => $this->domainObject->total(),
            ];
        }
        return [];
    }
}
