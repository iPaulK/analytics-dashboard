<?php

namespace App\Http\Controllers\Google\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Google\Analytics\Filter;
use App\JsonApi\Transformer\Google\Analytics\FilterTransformer;
use App\JsonApi\Document\Google\Analytics\FiltersDocument;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;

/**
 * Class FiltersController
 * @package App\Http\Controllers
 */
class FiltersController extends Controller
{
  /**
     * Get the list of filters
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @return ResponseInterface
     */
    public function index(Request $request, JsonApi $jsonApi): ResponseInterface
    {
        /** @var \Illuminate\Support\Collection $filters */
        $filters = Filter::filter($request)
            ->latest('created_at')
            ->get()
            ->unique('accountId');
        return $jsonApi->respond()->ok($this->createFiltersDocument(), $filters);
    }

    /**
     * Get history changes
     *
     * @param Request $request
     * @param JsonApi $jsonApi
     * @param string $filterId
     * @return ResponseInterface
     */
    public function history(
      Request $request,
      JsonApi $jsonApi,
      $filterId
    ): ResponseInterface {
        /** @var \Illuminate\Support\Collection $items */
        $items = Filter::findByFilterId($filterId)->paginate();
        return $jsonApi->respond()->ok($this->createFiltersDocument(), $items);
    }

    /**
     * Create filters document
     *
     * @return FiltersDocument
     */
    protected function createFiltersDocument()
    {
        return new FiltersDocument($this->createFilterTransformer());
    }

    /**
     * Create filter resource transformer
     *
     * @return FilterTransformer
     */
    protected function createFilterTransformer()
    {
        return new FilterTransformer();
    }

    /**
     * @return array
     */
    public function options()
    {
        return [];
    }
}