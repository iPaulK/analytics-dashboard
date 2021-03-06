<?php
namespace App\JsonApi\Transformer\Google\Analytics;

use WoohooLabs\Yin\JsonApi\Schema\{
    Link, Links
};
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

/**
 * Class CustomMetricTransformer
 * @package App\JsonApi\Transformer
 */
class CustomMetricTransformer extends AbstractResourceTransformer
{
    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $data
     */
    public function getType($data): string
    {
        return "CustomMetric";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $data
     */
    public function getId($data): string
    {
        return (string)$data["id"];
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $data
     */
    public function getMeta($data): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $data
     */
    public function getLinks($data): ?Links
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
     * @param array $data
     * @return callable[]
     */
    public function getAttributes($data): array
    {
        return [
            "version" => function ($data) {
                return $data["version"];
            },
            "kind" => function ($data) {
                return $data["kind"];
            },
            "selfLink" => function ($data) {
                return $data["selfLink"];
            },
            "customMetricId" => function ($data) {
                return $data["customMetricId"];
            },
            "accountId" => function ($data) {
                return $data["accountId"];
            },
            "webPropertyId" => function ($data) {
                return $data["webPropertyId"];
            },
            "name" => function ($data) {
                return $data["name"];
            },
            "index" => function ($data) {
                return $data["index"];
            },
            "scope" => function ($data) {
                return $data["scope"];
            },
            "active" => function ($data) {
                return $data["active"];
            },
            "type" => function ($data) {
                return $data["type"];
            },
            "min_value" => function ($data) {
                return $data["min_value"];
            },
            "max_value" => function ($data) {
                return $data["max_value"];
            },
            "created_at" => function ($data) {
                if (isset($data["created_at"]) && $data["created_at"] instanceof \DateTime) {
                    return $data["created_at"]->format(DATE_ISO8601);
                }
                return null;
            },
            "updated_at" => function ($data) {
                if (isset($data["updated_at"]) && $data["updated_at"] instanceof \DateTime) {
                    return $data["updated_at"]->format(DATE_ISO8601);
                }
                return null;
            }
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $data
     */
    public function getDefaultIncludedRelationships($data): array
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
     * @param array $data
     * @return callable[]
     */
    public function getRelationships($data): array
    {
        return [];
    }
}
