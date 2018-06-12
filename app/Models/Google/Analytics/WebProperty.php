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
     * Find last a account by webPropertyId
     *
     * @param string $webPropertyId
     * @return User
     */
    public static function findLastByWebPropertyId($webPropertyId)
    {
        return self::where('webPropertyId', $webPropertyId)
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * Create a new account from Google Model.
     *
     * @param Google_Model $model
     * @return Account
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function transform(Google_Model $model)
    {
        $object = $model->toSimpleObject();
        
        // convert object to associative array
        $attributes = json_decode(json_encode($object), TRUE);

        $attributes['webPropertyId'] = $attributes['id'];
        
        $attributes['permissions'] = json_encode($attributes['permissions']);

        unset($attributes['id']);
        
        return $this->newInstance($attributes);
    }

    /**
     * Compare models
     *
     * @param Filter $webProperty
     * @return bool
     */
    public function isDiff($webProperty)
    {
        if (strcmp($this->name, $webProperty->name) !== 0) {
            return true;
        }
        return false;
    }
}
