<?php
namespace App\Models\Google\Analytics;

use Google_Model;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProfileFilterLink
 * @package App\Models\Google\Analytics
 */
class ProfileFilterLink extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'google_analytics_profile_filter_links';

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
        'version', // integer
        'kind', // string
        'selfLink', // string
        'profileFilterLinkId', // string
        'accountId', // string
        'webPropertyId', // string
        'profileId', // string
        'filterId', // string
        'rank', // integer
        'created_at',
        'updated_at',
    ];

    /**
     * Find by profileId
     *
     * @param string $profileId
     * @return ProfileFilterLink
     */
    public static function findByProfileId($profileId)
    {
        return self::where('profileId', $profileId)
            ->orderBy('version', 'desc');
    }

    /**
     * Find last by profileFilterLinkId
     *
     * @param string $profileFilterLinkId
     * @param string $profileId
     * @return \Illuminate\Support\Collection
     */
    public static function findLastByProfileFilterLinkIdAndProfileId($profileFilterLinkId, $profileId)
    {
       return self::where([
            ['profileFilterLinkId', '=', $profileFilterLinkId],
            ['profileId', '=', $profileId]
        ])->orderBy('version', 'desc')->first();
    }

    /**
     * Find by profileFilterLinkId and profileId
     *
     * @param string $profileFilterLinkId
     * @param string $profileId
     * @return \Illuminate\Support\Collection
     */
    public static function findByProfileFilterLinkIdAndProfileId($profileFilterLinkId, $profileId)
    {
        return self::where([
            ['profileFilterLinkId', '=', $profileFilterLinkId],
            ['profileId', '=', $profileId]
        ])->orderBy('version', 'desc');
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

        $attributes['profileFilterLinkId'] = $attributes['id'];

        unset($attributes['id']);
        
        return $this->newInstance($attributes);
    }

    /**
     * Compare models
     *
     * @param ProfileFilterLink $profileFilterLink
     * @return bool
     */
    public function isDiff($profileFilterLink)
    {
        if ($this->rank !== $profileFilterLink->rank) {
            return true;
        }
        
        return false;
    }
}
