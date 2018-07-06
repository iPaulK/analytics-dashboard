<?php
namespace App\JsonApi\Transformer\Google\Analytics;

use WoohooLabs\Yin\JsonApi\Schema\{
    Link, Links
};
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

/**
 * Class EntityAdWordsLinkTransformer
 * @package App\JsonApi\Transformer
 */
class EntityAdWordsLinkTransformer extends AbstractResourceTransformer
{
    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $entityAdWordsLink
     */
    public function getType($entityAdWordsLink): string
    {
        return "EntityAdWordsLink";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $entityAdWordsLink
     */
    public function getId($entityAdWordsLink): string
    {
        return (string)$entityAdWordsLink["id"];
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $entityAdWordsLink
     */
    public function getMeta($entityAdWordsLink): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $entityAdWordsLink
     */
    public function getLinks($entityAdWordsLink): ?Links
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
     * @param array $entityAdWordsLink
     * @return callable[]
     */
    public function getAttributes($entityAdWordsLink): array
    {
        return [
            "version" => function ($entityAdWordsLink) {
                return $entityAdWordsLink["version"];
            },
            "kind" => function ($entityAdWordsLink) {
                return $entityAdWordsLink["kind"];
            },
            "selfLink" => function ($entityAdWordsLink) {
                return $entityAdWordsLink["selfLink"];
            },            
            "entityAdWordsLinkId" => function ($entityAdWordsLink) {
                return $entityAdWordsLink["entityAdWordsLinkId"];
            },            
            "webPropertyId" => function ($entityAdWordsLink) {
                return $entityAdWordsLink["webPropertyId"];
            },
            "name" => function ($entityAdWordsLink) {
                return $entityAdWordsLink["name"];
            },
            "adWordsAccounts" => function ($entityAdWordsLink) {
                return json_decode($entityAdWordsLink["adWordsAccounts"]);
            },
            "profileIds" => function ($entityAdWordsLink) {
                return json_decode($entityAdWordsLink["profileIds"]);
            },
            "created_at" => function ($entityAdWordsLink) {
                if (isset($entityAdWordsLink["created_at"]) && $entityAdWordsLink["created_at"] instanceof \DateTime) {
                    return $entityAdWordsLink["created_at"]->format(DATE_ISO8601);
                }
                return null;
            },
            "updated_at" => function ($entityAdWordsLink) {
                if (isset($entityAdWordsLink["updated_at"]) && $entityAdWordsLink["updated_at"] instanceof \DateTime) {
                    return $entityAdWordsLink["updated_at"]->format(DATE_ISO8601);
                }
                return null;
            }
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $entityAdWordsLink
     */
    public function getDefaultIncludedRelationships($entityAdWordsLink): array
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
     * @param array $entityAdWordsLink
     * @return callable[]
     */
    public function getRelationships($entityAdWordsLink): array
    {
        return [];
    }
}
