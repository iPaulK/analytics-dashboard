<?php
namespace App\JsonApi\Hydrator;

use Illuminate\Support\Carbon;
use WoohooLabs\Yin\JsonApi\Hydrator\AbstractHydrator as WoohooLabsAbstractHydrator;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;

/**
 * Class AbstractHydrator
 * @package App\JsonApi\Hydrator
 */
abstract class AbstractHydrator extends WoohooLabsAbstractHydrator
{
    /**
     * Hydrates the domain object from the request based on the request method.
     *
     * If the request method is POST then the domain object is hydrated
     * as a create. If it is a PATCH request then the domain object is
     * hydrated as an update.
     *
     * @param mixed $domainObject
     * @return mixed
     * @throws ResourceTypeMissing|JsonApiExceptionInterface
     */
    public function hydrate(RequestInterface $request, ExceptionFactoryInterface $exceptionFactory, $domainObject)
    {
        if ($request->getMethod() === "POST") {
            $domainObject = $this->hydrateForCreate($request, $exceptionFactory, $domainObject);
        } elseif ($request->getMethod() === "PATCH" || $request->getMethod() === "PUT") {
            $domainObject = $this->hydrateForUpdate($request, $exceptionFactory, $domainObject);
        }

        return $domainObject;
    }
}
