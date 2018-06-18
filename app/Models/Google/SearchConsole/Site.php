<?php
namespace App\Models\Google\SearchConsole;

use Google_Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class Site
 * @package App\Models\Google\SearchConsole
 */
class Site extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'google_searchconsole_sites';

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
        'siteUrl',
        'permissionLevel',
        'created_at',
        'updated_at',
    ];

    /**
     * Find last a site by siteUrl
     *
     * @param string $siteUrl
     * @return Site
     */
    public static function findLastBySiteUrl($siteUrl)
    {
        return self::where('siteUrl', $siteUrl)
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * Find sites by siteUrl
     *
     * @param string $siteUrl
     * @return Site
     */
    public static function findBySiteUrl($siteUrl)
    {
        return self::where('siteUrl', $siteUrl)
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
                return $q->orWhere('siteUrl', 'LIKE', '%' . $value . '%');
            });
        }

        return $query;
    }

    /**
     * Create a new site from Google Model.
     *
     * @param Google_Model $model
     * @return Site
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function transform(Google_Model $model)
    {
        $object = $model->toSimpleObject();
        // convert object to associative array
        $attributes = json_decode(json_encode($object), TRUE);        
        return $this->newInstance($attributes);
    }

    /**
     * Compare models
     *
     * @param Site $site
     * @return bool
     */
    public function isDiff($site)
    {
        if (strcmp($this->siteUrl, $site->siteUrl) !== 0) {
            return true;
        }
        return false;
    }
}
