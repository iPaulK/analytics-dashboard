<?php
namespace App\JsonApi\Transformer\Google\SearchConsole;

use WoohooLabs\Yin\JsonApi\Schema\{
    Link, Links
};
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

/**
 * Class SiteTransformer
 * @package App\JsonApi\Transformer
 */
class SiteTransformer extends AbstractResourceTransformer
{
    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $site
     */
    public function getType($site): string
    {
        return "Site";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $site
     */
    public function getId($site): string
    {
        return (string)$site["id"];
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $site
     */
    public function getMeta($site): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $site
     */
    public function getLinks($site): ?Links
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
     * @param array $site
     * @return callable[]
     */
    public function getAttributes($site): array
    {
        return [
            "version" => function ($site) {
                return $site["version"];
            },
            "siteUrl" => function ($site) {
                return $site["siteUrl"];
            },
            "permissionLevel" => function ($site) {
                return $site["permissionLevel"];
            },
            "created_at" => function ($site) {
                if (isset($site["created_at"]) && $site["created_at"] instanceof \DateTime) {
                    return $site["created_at"]->format(DATE_ISO8601);
                }
                return null;
            },
            "updated_at" => function ($site) {
                if (isset($site["updated_at"]) && $site["updated_at"] instanceof \DateTime) {
                    return $site["updated_at"]->format(DATE_ISO8601);
                }
                return null;
            }
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $site
     */
    public function getDefaultIncludedRelationships($site): array
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
     * @param array $site
     * @return callable[]
     */
    public function getRelationships($site): array
    {
        return [];
    }
}
