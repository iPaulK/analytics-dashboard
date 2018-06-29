<?php
namespace App\JsonApi\Transformer\Google\Analytics;

use WoohooLabs\Yin\JsonApi\Schema\{
    Link, Links
};
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

/**
 * Class EntityUserLinkTransformer
 * @package App\JsonApi\Transformer
 */
class EntityUserLinkTransformer extends AbstractResourceTransformer
{
    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $entityUserLink
     */
    public function getType($entityUserLink): string
    {
        return "EntityUserLink";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $entityUserLink
     */
    public function getId($entityUserLink): string
    {
        return (string)$entityUserLink["id"];
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $entityUserLink
     */
    public function getMeta($entityUserLink): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $entityUserLink
     */
    public function getLinks($entityUserLink): ?Links
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
     * @param array $entityUserLink
     * @return callable[]
     */
    public function getAttributes($entityUserLink): array
    {
        return [
            "version" => function ($entityUserLink) {
                return $entityUserLink["version"];
            },
            "userLinkId" => function ($entityUserLink) {
                return $entityUserLink["userLinkId"];
            },
            "accountId" => function ($entityUserLink) {
                return $entityUserLink["accountId"];
            },
            "kind" => function ($entityUserLink) {
                return $entityUserLink["kind"];
            },
            "selfLink" => function ($entityUserLink) {
                return $entityUserLink["selfLink"];
            },
            "permissions" => function ($entityUserLink) {
                return json_decode($entityUserLink["permissions"]);
            },
            "created_at" => function ($entityUserLink) {
                if (isset($entityUserLink["created_at"]) && $entityUserLink["created_at"] instanceof \DateTime) {
                    return $entityUserLink["created_at"]->format(DATE_ISO8601);
                }
                return null;
            },
            "updated_at" => function ($entityUserLink) {
                if (isset($entityUserLink["updated_at"]) && $entityUserLink["updated_at"] instanceof \DateTime) {
                    return $entityUserLink["updated_at"]->format(DATE_ISO8601);
                }
                return null;
            }
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $entityUserLink
     */
    public function getDefaultIncludedRelationships($entityUserLink): array
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
     * @param array $entityUserLink
     * @return callable[]
     */
    public function getRelationships($entityUserLink): array
    {
        return [];
    }
}
