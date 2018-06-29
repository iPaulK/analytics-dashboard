<?php
namespace App\Models\Google\Analytics;

use Google_Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class Filter
 * @package App\Models\Google\Analytics
 */
class Filter extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'google_analytics_filters';

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'version' => 1
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'version',
        'accountId',
        'filterId',
        'kind',
        'selfLink',
        'name',
        'type',
        'created_at',
        'updated_at',
    ];

    /**
     * Find last a account by filterId
     *
     * @param string $filterId
     * @return Filter
     */
    public static function findLastByFilterId($filterId)
    {
        return self::where('filterId', $filterId)
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * Find by filterId
     *
     * @param string $filterId
     * @return Filter
     */
    public static function findByFilterId($filterId)
    {
        return self::where('filterId', $filterId)
            ->orderBy('version', 'desc');
    }

    /**
     * Find by accountId
     *
     * @param string $accountId
     * @return Filter
     */
    public static function findByAccountId($accountId)
    {
        return self::where('accountId', $accountId)
            ->orderBy('version', 'desc');
    }

    /**
     * Apply request params to the builder instance.
     *
     * @param Request $request
     * @param Builder|null $builder
     * @param string|null $roleName
     * @return \Illuminate\Support\Collection
     */
    public static function filter(Request $request, Builder $builder = null, $roleName = null)
    {
        $query = $builder ?? self::query();
        $query = self::applyFilters($query, $request->input('filter'));
        return $query;
    }

    /**
     * @param Builder $query
     * @param array|null $filters
     * @return Builder
     */
    private static function applyFilters(Builder $query, ?array $filters)
    {
        if (!is_array($filters) || count($filters) < 1) {
            return $query;
        }

        if (!empty($filters['query'])) {
            $value = '%' . $filters['query'] . '%';

            $query->where(function (Builder $q) use ($value) {
                return $q->orWhere('id', 'LIKE', '%' . $value . '%')
                    ->orWhere('name', 'LIKE', '%' . $value . '%')
                    ->orWhere('filterId', 'LIKE', '%' . $value . '%');
            });
        }

        return $query;
    }

    /**
     * Create a new account from Google Model.
     *
     * @param Google_Model $model
     * @return Filter
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function transform(Google_Model $model)
    {
        $object = $model->toSimpleObject();
        
        // convert object to associative array
        $attributes = json_decode(json_encode($object), TRUE);

        $attributes['filterId'] = $attributes['id'];

        unset($attributes['id']);
        
        return $this->newInstance($attributes);
    }

    /**
     * Compare models
     *
     * @param Filter $filter
     * @return bool
     */
    public function isDiff($filter)
    {
        if (strcmp($this->name, $filter->name) !== 0) {
            return true;
        }

        if (strcmp($this->type, $filter->type) !== 0) {
            return true;
        }
        return false;
    }
}
