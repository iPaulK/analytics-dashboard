<?php
namespace App\Models\Google\Analytics;

use Google_Model;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WebProperty
 * @package App\Models\Google\Analytics
 */
class WebProperty extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'google_analytics_webproperties';

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
        'webpropertyId',

        'kind',
        'selfLink',
        'accountId',
        'internalWebPropertyId',
        'name',
        'websiteUrl',
        'level',
        'profileCount',
        'industryVertical',
        'defaultProfileId',
        'permissions',
        'starred',

        'created_at',
        'updated_at',
    ];

    /**
     * Find last a property by webPropertyId
     *
     * @param string $webPropertyId
     * @return User
     */
    public static function findLastByWebPropertyId($webPropertyId)
    {
        return self::where('webpropertyId', $webPropertyId)
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * Find properties by accountId
     *
     * @param string $accountId
     * @return \Illuminate\Support\Collection
     */
    public static function findByAccountId($accountId)
    {
        return self::where('accountId', $accountId)
            ->orderBy('version', 'desc');
    }
    
    /**
     * Find properties by webpropertyId
     *
     * @param string $webpropertyId
     * @return \Illuminate\Support\Collection
     */
    public static function findByWebPropertyId($webpropertyId)
    {
        return self::where('webpropertyId', $webpropertyId)
            ->orderBy('version', 'desc');
    }

    /**
     * Create a new property from Google Model.
     *
     * @param Google_Model $model
     * @return WebProperty
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function transform(Google_Model $model)
    {
        $object = $model->toSimpleObject();
        
        // convert object to associative array
        $attributes = json_decode(json_encode($object), TRUE);

        $attributes['webpropertyId'] = $attributes['id'];
        
        $attributes['permissions'] = json_encode($attributes['permissions']);

        unset($attributes['id']);
        
        return $this->newInstance($attributes);
    }

    /**
     * Compare models
     *
     * @param WebProperty $webProperty
     * @return bool
     */
    public function isDiff($webProperty)
    {
        // compare names
        if (strcmp($this->name, $webProperty->name) !== 0) {
            return true;
        }

        if (strcmp($this->websiteUrl, $webProperty->websiteUrl) !== 0) {
            return true;
        }

        if (strcmp($this->level, $webProperty->level) !== 0) {
            return true;
        }

        if (strcmp($this->industryVertical, $webProperty->industryVertical) !== 0) {
            return true;
        }
        
        if (strcmp($this->internalWebPropertyId, $webProperty->internalWebPropertyId) !== 0) {
            return true;
        }

        // compare starred
        /*if ($this->starred !== $account->starred) {
            return true;
        }*/
        
        // compare permissions
        $newPermissions = json_decode($account->permissions, true);
        $newEffective = $newPermissions['effective'];

        $permissions = json_decode($this->permissions, true);
        $effective = $permissions['effective'];

        $effectiveDiff = array_merge(array_diff($effective, $newEffective), array_diff($newEffective, $effective));

        if (count($effectiveDiff) > 0 ) {
            return true;
        }
        return false;
    }
}
