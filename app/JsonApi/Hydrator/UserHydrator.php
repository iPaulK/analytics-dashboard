<?php
namespace App\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;

/**
 * Class UserHydrator
 * @package App\JsonApi\Hydrator
 */
class UserHydrator extends AbstractHydrator
{
    /**
     * Determines which resource types can be accepted by the hydrator.
     *
     * The method should return an array of acceptable resource types. When such a resource is received for hydration
     * which can't be accepted (its type doesn't match the acceptable types of the hydrator), a ResourceTypeUnacceptable
     * exception will be raised.
     *
     * @return string[]
     */
    protected function getAcceptedTypes(): array
    {
        return ["Users"];
    }

    /**
     * Validates a client-generated ID.
     *
     * If the $clientGeneratedId is not a valid ID for the domain object, then
     * the appropriate exception should be thrown: if it is not well-formed then
     * a ClientGeneratedIdNotSupported exception can be raised, if the ID already
     * exists then a ClientGeneratedIdAlreadyExists exception can be thrown.
     *
     * @throws ClientGeneratedIdNotSupported|JsonApiExceptionInterface
     * @throws ClientGeneratedIdAlreadyExists|JsonApiExceptionInterface
     */
    protected function validateClientGeneratedId(
        string $clientGeneratedId,
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory
    ): void {
        if ($clientGeneratedId !== '') {
            throw $exceptionFactory->createClientGeneratedIdNotSupportedException($request, $clientGeneratedId);
        }
    }

    /**
     * Produces a new ID for the domain objects.
     *
     * UUID-s are preferred according to the JSON API specification.
     */
    protected function generateId(): string
    {
        return '';
    }

    /**
     * You can validate the request.
     *
     * @throws JsonApiExceptionInterface
     */
    protected function validateRequest(RequestInterface $request): void
    {
    }

    /**
     * Sets the given UserID for the domain object.
     *
     * The method mutates the domain object and sets the given ID for it.
     * If it is an immutable object or an array the whole, updated domain
     * object can be returned.
     *
     * @param array $user
     * @return mixed|void
     */
    protected function setId($user, string $id)
    {
        $user["id"] = $id;
        return $user;
    }

    /**
     * Provides the attribute hydrators.
     *
     * The method returns an array of attribute hydrators, where a hydrator is a key-value pair:
     * the key is the specific attribute name which comes from the request and the value is a
     * callable which hydrates the given attribute.
     * These callables receive the domain object (which will be hydrated), the value of the
     * currently processed attribute, the "data" part of the request and the name of the attribute
     * to be hydrated as their arguments, and they should mutate the state of the domain object.
     * If it is an immutable object or an array (and passing by reference isn't used),
     * the callable should return the domain object.
     *
     * @param array $user
     * @return callable[]
     */
    protected function getAttributeHydrator($user): array
    {
        return [
            "first_name" => function ($user, $attribute, $data) {
                $user["first_name"] = $attribute;
                return $user;
            },
            "last_name" => function ($user, $attribute, $data) {
                $user["last_name"] = $attribute;
                return $user;
            },
            "email" => function ($user, $attribute, $data) {
                $user["email"] = $attribute;
                return $user;
            },
            "active" => function ($user, $attribute, $data) {
                $user["active"] = $attribute;
                return $user;
            },
            "password" => function ($user, $attribute, $data) {
                $user["password"] = app('hash')->make($attribute);
                return $user;
            },
        ];
    }

    /**
     * Provides the relationship hydrators.
     *
     * The method returns an array of relationship hydrators, where a hydrator is a key-value pair:
     * the key is the specific relationship name which comes from the request and the value is a
     * callable which hydrate the previous relationship.
     * These callables receive the domain object (which will be hydrated), an object representing the
     * currently processed relationship (it can be a ToOneRelationship or a ToManyRelationship
     * object), the "data" part of the request and the relationship name as their arguments, and
     * they should mutate the state of the domain object.
     * If it is an immutable object or an array (and passing by reference isn't used),
     * the callable should return the domain object.
     *
     * @param array $user
     * @return callable[]
     */
    protected function getRelationshipHydrator($user): array
    {
        return [];
    }
}
