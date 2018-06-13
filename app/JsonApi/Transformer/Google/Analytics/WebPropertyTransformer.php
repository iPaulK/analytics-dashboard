<?php
namespace App\JsonApi\Transformer\Google\Analytics;

use WoohooLabs\Yin\JsonApi\Schema\{
    Link, Links
};
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

/**
 * Class WebPropertyTransformer
 * @package App\JsonApi\Transformer
 */
class WebPropertyTransformer extends AbstractResourceTransformer
{
    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $property
     */
    public function getType($property): string
    {
        return "WebProperty";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $property
     */
    public function getId($property): string
    {
        return (string)$property["id"];
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $property
     */
    public function getMeta($property): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $property
     */
    public function getLinks($property): ?Links
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
     * @param array $property
     * @return callable[]
     */
    public function getAttributes($property): array
    {
        return [
            "version" => function ($property) {
                return $property["version"];
            },
            "accountId" => function ($property) {
                return $property["accountId"];
            },
            "webpropertyId" => function ($property) {
                return $property["webpropertyId"];
            },
            "name" => function ($property) {
                return $property["name"];
            },
            "internalWebPropertyId" => function ($property) {
                return $property["internalWebPropertyId"];
            },
            "kind" => function ($property) {
                return $property["kind"];
            },
            "selfLink" => function ($property) {
                return $property["selfLink"];
            },
            "websiteUrl" => function ($property) {
                return $property["websiteUrl"];
            },
            "level" => function ($property) {
                return $property["level"];
            },
            "profileCount" => function ($property) {
                return $property["profileCount"];
            },
            "industryVertical" => function ($property) {
                return $property["industryVertical"];
            },
            "defaultProfileId" => function ($property) {
                return $property["defaultProfileId"];
            },
            "permissions" => function ($property) {
                return json_decode($property["permissions"]);
            },
            "starred" => function ($property) {
                return $property["starred"];
            },
            "created_at" => function ($property) {
                if (isset($property["created_at"]) && $property["created_at"] instanceof \DateTime) {
                    return $property["created_at"]->format(DATE_ISO8601);
                }
                return null;
            },
            "updated_at" => function ($property) {
                if (isset($property["updated_at"]) && $property["updated_at"] instanceof \DateTime) {
                    return $property["updated_at"]->format(DATE_ISO8601);
                }
                return null;
            }
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $property
     */
    public function getDefaultIncludedRelationships($property): array
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
     * @param array $property
     * @return callable[]
     */
    public function getRelationships($property): array
    {
        return [];
    }
}
