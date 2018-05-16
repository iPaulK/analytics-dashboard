<?php
namespace App\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Schema\{
    Link, Links
};
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

/**
 * Class UserResourceTransformer
 * @package App\JsonApi\Transformer
 */
class UserResourceTransformer extends AbstractResourceTransformer
{
    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $user
     */
    public function getType($user): string
    {
        return "User";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $user
     */
    public function getId($user): string
    {
        return (string)$user["id"];
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $user
     */
    public function getMeta($user): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $user
     */
    public function getLinks($user): ?Links
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
     * @param array $user
     * @return callable[]
     */
    public function getAttributes($user): array
    {
        return [
            "first_name" => function ($user) {
                return $user["first_name"];
            },
            "last_name" => function ($user) {
                return $user["last_name"];
            },
            "email" => function ($user) {
                return $user["email"];
            },
            "created_at" => function ($user) {
                if (isset($user["created_at"]) && $user["created_at"] instanceof \DateTime) {
                    return $user["created_at"]->format(DATE_ISO8601);
                }
                return null;
            },
            "updated_at" => function ($user) {
                if (isset($user["updated_at"]) && $user["updated_at"] instanceof \DateTime) {
                    return $user["updated_at"]->format(DATE_ISO8601);
                }
                return null;
            }
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $user
     */
    public function getDefaultIncludedRelationships($user): array
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
     * @param array $user
     * @return callable[]
     */
    public function getRelationships($user): array
    {
        return [];
    }
}
