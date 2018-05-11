<?php
namespace App\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Schema\{
    Link, Links
};
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

/**
 * Class AccessTokenResourceTransformer
 * @package App\JsonApi\Transformer
 */
class AccessTokenResourceTransformer extends AbstractResourceTransformer
{
    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $domainObject
     */
    public function getType($domainObject): string
    {
        return "AccessToken";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $domainObject
     */
    public function getId($domainObject): string
    {
        return uniqid();
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $domainObject
     */
    public function getMeta($domainObject): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $domainObject
     */
    public function getLinks($domainObject): ?Links
    {
        return null;
    }

    /**
     * Provides information about the "attributes" member of the current resource.
     *
     * The method returns an array where the keys signify the attribute names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return the value of the corresponding attribute.
     *
     * @param array $domainObject
     * @return callable[]
     */
    public function getAttributes($domainObject): array
    {
        return [
            "access_token" => function ($domainObject) {
                return $domainObject["access_token"];
            },
            "token_type" => function ($domainObject) {
                return $domainObject["token_type"];
            },
            "expires_in" => function ($domainObject) {
                return $domainObject["expires_in"];
            }
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $domainObject
     */
    public function getDefaultIncludedRelationships($domainObject): array
    {
        return [];
    }

    /**
     * Provides information about the "relationships" member of the current resource.
     *
     * The method returns an array where the keys signify the relationship names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return a new relationship instance (to-one or to-many).
     *
     * @param array $domainObject
     * @return callable[]
     */
    public function getRelationships($domainObject): array
    {
        return [];
    }
}
