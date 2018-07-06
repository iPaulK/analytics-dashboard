<?php
namespace App\Models\Google\Analytics;

use Google_Model;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EntityAdWordsLink
 * @package App\Models\Google\Analytics
 */
class EntityAdWordsLink extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'google_analytics_entity_adwords_links';

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
        'entityAdWordsLinkId',
        'webPropertyId',
        'adWordsAccounts',
        'name',
        'profileIds',
        'created_at',
        'updated_at',
    ];

    /**
     * Find last by webPropertyId
     *
     * @param string $webPropertyId
     * @return Profile
     */
    public static function findByWebPropertyId($webPropertyId)
    {
        return self::where('webPropertyId', $webPropertyId)
            ->orderBy('version', 'desc');
    }

    /**
     * Find by entityAdWordsLinkId
     *
     * @param string $entityAdWordsLinkId
     * @return \Illuminate\Support\Collection
     */
    public static function findLastByEntityAdWordsLinkId($entityAdWordsLinkId)
    {
        return self::where('entityAdWordsLinkId', $entityAdWordsLinkId)
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * Find by entityAdWordsLinkId
     *
     * @param string $entityAdWordsLinkId
     * @return \Illuminate\Support\Collection
     */
    public static function findByEntityAdWordsLinkId($entityAdWordsLinkId)
    {
        return self::where('entityAdWordsLinkId', $entityAdWordsLinkId)
            ->orderBy('version', 'desc');
    }

    /**
     * Create a new entity from Google Model.
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

        $adWordsAccounts = !empty($attributes['adWordsAccounts']) ? $attributes['adWordsAccounts'] : [];
        $attributes['adWordsAccounts'] = json_encode($adWordsAccounts);
        
        $profileIds = !empty($attributes['profileIds']) ? $attributes['profileIds'] : [];
        $attributes['profileIds'] = json_encode($profileIds);

        $attributes['entityAdWordsLinkId'] = $attributes['id'];

        unset($attributes['id']);
        
        return $this->newInstance($attributes);
    }

    /**
     * Compare models
     *
     * @param Profile $entityAdWordsLink
     * @return bool
     */
    public function isDiff($entityAdWordsLink)
    {
        if (strcmp($this->name, $entityAdWordsLink->name) !== 0) {
            return true;
        }

        // compare adWordsAccounts
        $newAdWordsAccounts = json_decode($entityAdWordsLink->adWordsAccounts, true);

        $adWordsAccounts = json_decode($this->adWordsAccounts, true);

        $adWordsAccountsDiff = array_merge(array_diff($adWordsAccounts, $newAdWordsAccounts), array_diff($newAdWordsAccounts, $adWordsAccounts));
        if (count($adWordsAccountsDiff) > 0) {
            return true;
        }

        // compare profileIds
        $newProfileIds = json_decode($entityAdWordsLink->profileIds, true);

        $profileIds = json_decode($this->profileIds, true);

        $newProfileIds = array_merge(array_diff($profileIds, $newAdWordsAccounts), array_diff($newAdWordsAccounts, $profileIds));
        if (count($newProfileIds) > 0) {
            return true;
        }
        
        return false;
    }
}
