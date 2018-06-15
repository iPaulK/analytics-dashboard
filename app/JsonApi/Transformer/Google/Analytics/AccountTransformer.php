<?php
namespace App\JsonApi\Transformer\Google\Analytics;

use WoohooLabs\Yin\JsonApi\Schema\{
    Link, Links
};
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

/**
 * Class AccountTransformer
 * @package App\JsonApi\Transformer
 */
class AccountTransformer extends AbstractResourceTransformer
{
    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $account
     */
    public function getType($account): string
    {
        return "Account";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $account
     */
    public function getId($account): string
    {
        return (string)$account["id"];
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $account
     */
    public function getMeta($account): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $account
     */
    public function getLinks($account): ?Links
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
     * @param array $account
     * @return callable[]
     */
    public function getAttributes($account): array
    {
        return [
            "version" => function ($account) {
                return $account["version"];
            },
            "accountId" => function ($account) {
                return $account["accountId"];
            },
            "name" => function ($account) {
                return $account["name"];
            },
            "kind" => function ($account) {
                return $account["kind"];
            },
            "selfLink" => function ($account) {
                return $account["selfLink"];
            },
            "permissions" => function ($account) {
                return json_decode($account["permissions"]);
            },
            "starred" => function ($account) {
                return $account["starred"];
            },
            "created_at" => function ($account) {
                if (isset($account["created_at"]) && $account["created_at"] instanceof \DateTime) {
                    return $account["created_at"]->format(DATE_ISO8601);
                }
                return null;
            },
            "updated_at" => function ($account) {
                if (isset($account["updated_at"]) && $account["updated_at"] instanceof \DateTime) {
                    return $account["updated_at"]->format(DATE_ISO8601);
                }
                return null;
            }
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $account
     */
    public function getDefaultIncludedRelationships($account): array
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
     * @param array $account
     * @return callable[]
     */
    public function getRelationships($account): array
    {
        return [];
    }
}
