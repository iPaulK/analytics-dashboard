<?php
namespace App\Models\Google\Analytics;

use Google_Model;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EntityUserLink
 * @package App\Models\Google\Analytics
 */
class EntityUserLink extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'google_analytics_entity_user_links';

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
        'userLinkId',
        'accountId',
        'selfLink',
        'kind',
        'permissions',
        'created_at',
        'updated_at',
    ];

    /**
     * Find last a account by userLinkId
     *
     * @param string $userLinkId
     * @return EntityUserLink
     */
    public static function findLastByUserLinkId($userLinkId)
    {
        return self::where('userLinkId', $userLinkId)
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * Find by accountId
     *
     * @param string $accountId
     * @return EntityUserLink
     */
    public static function findByAccountId($accountId)
    {
        return self::where('accountId', $accountId)
            ->orderBy('version', 'desc');
    }

    /**
     * Find by userLinkId
     *
     * @param string $userLinkId
     * @return EntityUserLink
     */
    public static function findByUserLinkId($userLinkId)
    {
        return self::where('userLinkId', $userLinkId)
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
                    ->orWhere('userLinkId', 'LIKE', '%' . $value . '%');
            });
        }

        return $query;
    }

    /**
     * Create a new account from Google Model.
     *
     * @param Google_Model $model
     * @return EntityUserLink
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function transform(Google_Model $model)
    {
        $object = $model->toSimpleObject();
        
        // convert object to associative array
        $attributes = json_decode(json_encode($object), TRUE);

        $attributes['userLinkId'] = $attributes['id'];

        $attributes['permissions'] = json_encode($attributes['permissions']);

        unset($attributes['id']);
        
        return $this->newInstance($attributes);
    }

    /**
     * Compare models
     *
     * @param EntityUserLink $entityUserLink
     * @return bool
     */
    public function isDiff($entityUserLink)
    {
        // compare permissions
        $newPermissions = json_decode($entityUserLink->permissions, true);
        $newLocal = $newPermissions['local'];
        $newEffective = $newPermissions['effective'];

        $permissions = json_decode($this->permissions, true);
        $local = $permissions['local'];
        $effective = $permissions['effective'];

        $localDiff = array_merge(array_diff($local, $newLocal), array_diff($newLocal, $local));
        $effectiveDiff = array_merge(array_diff($effective, $newEffective), array_diff($newEffective, $effective));

        if (count($localDiff) > 0 || count($effectiveDiff) > 0 ) {
            return true;
        }

        return false;
    }

}
