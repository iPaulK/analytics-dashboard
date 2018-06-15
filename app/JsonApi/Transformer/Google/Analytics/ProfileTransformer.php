<?php
namespace App\JsonApi\Transformer\Google\Analytics;

use WoohooLabs\Yin\JsonApi\Schema\{
    Link, Links
};
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

/**
 * Class ProfileTransformer
 * @package App\JsonApi\Transformer
 */
class ProfileTransformer extends AbstractResourceTransformer
{
    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $profile
     */
    public function getType($profile): string
    {
        return "Profile";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $profile
     */
    public function getId($profile): string
    {
        return (string)$profile["id"];
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $profile
     */
    public function getMeta($profile): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $profile
     */
    public function getLinks($profile): ?Links
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
     * @param array $profile
     * @return callable[]
     */
    public function getAttributes($profile): array
    {
        return [
            "version" => function ($profile) {
                return $profile["version"];
            },
            "profileId" => function ($profile) {
                return $profile["profileId"];
            },
            "accountId" => function ($profile) {
                return $profile["accountId"];
            },
            "webPropertyId" => function ($profile) {
                return $profile["webPropertyId"];
            },
            "selfLink" => function ($profile) {
                return $profile["selfLink"];
            },
            "kind" => function ($profile) {
                return $profile["kind"];
            },
            "internalWebPropertyId" => function ($profile) {
                return $profile["internalWebPropertyId"];
            },
            "name" => function ($profile) {
                return $profile["name"];
            },
            "currency" => function ($profile) {
                return $profile["currency"];
            },
            "timezone" => function ($profile) {
                return $profile["timezone"];
            },
            "websiteUrl" => function ($profile) {
                return $profile["websiteUrl"];
            },
            "type" => function ($profile) {
                return $profile["type"];
            },
            "eCommerceTracking" => function ($profile) {
                return $profile["eCommerceTracking"];
            },
            "enhancedECommerceTracking" => function ($profile) {
                return $profile["enhancedECommerceTracking"];
            },
            "botFilteringEnabled" => function ($profile) {
                return $profile["botFilteringEnabled"];
            },
            "starred" => function ($profile) {
                return $profile["starred"];
            },
            "created_at" => function ($profile) {
                if (isset($profile["created_at"]) && $profile["created_at"] instanceof \DateTime) {
                    return $profile["created_at"]->format(DATE_ISO8601);
                }
                return null;
            },
            "updated_at" => function ($profile) {
                if (isset($profile["updated_at"]) && $profile["updated_at"] instanceof \DateTime) {
                    return $profile["updated_at"]->format(DATE_ISO8601);
                }
                return null;
            }
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $profile
     */
    public function getDefaultIncludedRelationships($profile): array
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
     * @param array $profile
     * @return callable[]
     */
    public function getRelationships($profile): array
    {
        return [];
    }
}
