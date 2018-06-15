<?php
namespace App\Models\Google\Analytics;

use Google_Model;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Profile
 * @package App\Models\Google\Analytics
 */
class Profile extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'google_analytics_profiles';

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
        'kind',
        'selfLink',
        'profileId',
        'accountId',
        'webPropertyId',
        'internalWebPropertyId',
        'name',
        'currency',
        'timezone',
        'websiteUrl',
        'type',
        'eCommerceTracking',
        'enhancedECommerceTracking',
        'botFilteringEnabled',
        'starred',
        'created_at',
        'updated_at',
    ];

    /**
     * Find last a profile by profileId
     *
     * @param string $profileId
     * @return Profile
     */
    public static function findLastByProfileId($profileId)
    {
        return self::where('profileId', $profileId)
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * Find profiles by profileId
     *
     * @param string $profileId
     * @return \Illuminate\Support\Collection
     */
    public static function findByProfileId($profileId)
    {
        return self::where('profileId', $profileId)
            ->orderBy('version', 'desc');
    }
    
    /**
     * Find profiles by webpropertyId
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
     * @return Profile
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function transform(Google_Model $model)
    {
        $object = $model->toSimpleObject();
        
        // convert object to associative array
        $attributes = json_decode(json_encode($object), TRUE);

        $attributes['profileId'] = $attributes['id'];

        unset($attributes['id']);
        
        return $this->newInstance($attributes);
    }

    /**
     * Compare models
     *
     * @param Profile $profile
     * @return bool
     */
    public function isDiff($profile)
    {
        /*if (strcmp($this->kind, $profile->kind) !== 0) {
            return true;
        }
        if (strcmp($this->selfLink, $profile->selfLink) !== 0) {
            return true;
        }
        if (strcmp($this->accountId, $profile->accountId) !== 0) {
            return true;
        }
        if (strcmp($this->webPropertyId, $profile->webPropertyId) !== 0) {
            return true;
        }
        if (strcmp($this->internalWebPropertyId, $profile->internalWebPropertyId) !== 0) {
            return true;
        }*/
        if (strcmp($this->name, $profile->name) !== 0) {
            return true;
        }
        if (strcmp($this->currency, $profile->currency) !== 0) {
            return true;
        }
        if (strcmp($this->timezone, $profile->timezone) !== 0) {
            return true;
        }
        if (strcmp($this->websiteUrl, $profile->websiteUrl) !== 0) {
            return true;
        }
        /*if (strcmp($this->type, $profile->type) !== 0) {
            return true;
        }
        if (strcmp($this->eCommerceTracking, $profile->eCommerceTracking) !== 0) {
            return true;
        }
        if (strcmp($this->enhancedECommerceTracking, $profile->enhancedECommerceTracking) !== 0) {
            return true;
        }
        if (strcmp($this->botFilteringEnabled, $profile->botFilteringEnabled) !== 0) {
            return true;
        }
        if (strcmp($this->starred, $profile->starred) !== 0) {
            return true;
        }*/
        return false;
    }
}
