<?php
namespace App\Models\Google\Analytics;

use Google_Model;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomDataSource
 * @package App\Models\Google\Analytics
 */
class CustomDataSource extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'google_analytics_entity_custom_data_sources';

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
        'customDataSourceId',
        'accountId',
        'webPropertyId',
        'name',
        'description',
        'type',
        'uploadType',
        'importBehavior',
        'profilesLinked',
        'created_at',
        'updated_at',
    ];

    /**
     * Find by webPropertyId
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
     * Find last by customDataSourceId
     *
     * @param string $customDataSourceId
     * @return \Illuminate\Support\Collection
     */
    public static function findLastByCustomDataSourceId($customDataSourceId)
    {
        return self::where('customDataSourceId', $customDataSourceId)
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * Find by customDataSourceId
     *
     * @param string $customDataSourceId
     * @return \Illuminate\Support\Collection
     */
    public static function findByCustomDataSourceId($customDataSourceId)
    {
        return self::where('customDataSourceId', $customDataSourceId)
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

        $attributes['customDataSourceId'] = $attributes['id'];

        $profilesLinked = !empty($attributes['profilesLinked']) ? $attributes['profilesLinked'] : [];
        $attributes['profilesLinked'] = json_encode($profilesLinked);

        unset($attributes['id']);
        
        return $this->newInstance($attributes);
    }

    /**
     * Compare models
     *
     * @param CustomDataSource $customDataSource
     * @return bool
     */
    public function isDiff($customDataSource)
    {
        if (strcmp($this->name, $customDataSource->name) !== 0) {
            return true;
        }

        if (strcmp($this->description, $customDataSource->description) !== 0) {
            return true;
        }

        if (strcmp($this->type, $customDataSource->type) !== 0) {
            return true;
        }

        if (strcmp($this->uploadType, $customDataSource->uploadType) !== 0) {
            return true;
        }

        if (strcmp($this->importBehavior, $customDataSource->importBehavior) !== 0) {
            return true;
        }

        // compare profilesLinked
        $new = json_decode($this->profilesLinked, true);
        $last = json_decode($customDataSource->profilesLinked, true);

        $diff = array_merge(array_diff($last, $new), array_diff($new, $last));
        if (count($diff) > 0) {
            return true;
        }
        
        return false;
    }
}
