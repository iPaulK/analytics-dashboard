<?php
namespace App\Models\Google\Analytics;

use Google_Model;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomDimension
 * @package App\Models\Google\Analytics
 */
class CustomDimension extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'google_analytics_entity_custom_dimensions';

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
        'customDimensionId',
        'accountId',
        'webPropertyId',
        'name',
        'index',
        'scope',
        'active',
        'selfLink',
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
     * Find last by customDimensionId
     *
     * @param string $customDimensionId
     * @return \Illuminate\Support\Collection
     */
    public static function findLastByCustomDimensionId($customDimensionId)
    {
        return self::where('customDimensionId', $customDimensionId)
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * Find by customDimensionId
     *
     * @param string $customDimensionId
     * @return \Illuminate\Support\Collection
     */
    public static function findByCustomDimensionId($customDimensionId)
    {
        return self::where('customDimensionId', $customDimensionId)
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

        $attributes['customDimensionId'] = $attributes['id'];

        unset($attributes['id']);
        
        return $this->newInstance($attributes);
    }

    /**
     * Compare models
     *
     * @param CustomDimension $customDimension
     * @return bool
     */
    public function isDiff($customDimension)
    {
        if (strcmp($this->name, $customDimension->name) !== 0) {
            return true;
        }

        if ($this->index !== $customDimension->index) {
            return true;
        }

        if (strcmp($this->scope, $customDimension->scope) !== 0) {
            return true;
        }

        if ($this->active !== (bool) $customDimension->active) {
            return true;
        }
        
        return false;
    }
}
