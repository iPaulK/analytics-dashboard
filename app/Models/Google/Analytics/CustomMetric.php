<?php
namespace App\Models\Google\Analytics;

use Google_Model;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomMetric
 * @package App\Models\Google\Analytics
 */
class CustomMetric extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'google_analytics_entity_custom_metrics';

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
        'customMetricId', // string
        'accountId', // string
        'webPropertyId', // string
        'name', // string
        'scope', // string
        'index', // integer
        'active', // boolean
        'type', // string
        'min_value', // string
        'max_value', // string
        'selfLink', // string
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
     * Find last by customMetricId
     *
     * @param string $customMetricId
     * @param string $webPropertyId
     * @return \Illuminate\Support\Collection
     */
    public static function findLastByCustomMetricIdAndWebPropertyId($customMetricId, $webPropertyId)
    {
        return self::where([
            ['customMetricId', '=', $customMetricId],
            ['webPropertyId', '=', $webPropertyId]
        ])->orderBy('version', 'desc')->first();
    }

    /**
     * Find by customMetricId
     *
     * @param string $customMetricId
     * @param string $webPropertyId
     * @return \Illuminate\Support\Collection
     */
    public static function findByCustomMetricIdAndWebPropertyId($customMetricId, $webPropertyId)
    {
        return self::where([
            ['customMetricId', '=', $customMetricId],
            ['webPropertyId', '=', $webPropertyId]
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

        $attributes['customMetricId'] = $attributes['id'];

        unset($attributes['id']);
        
        return $this->newInstance($attributes);
    }

    /**
     * Compare models
     *
     * @param CustomMetric $customMetric
     * @return bool
     */
    public function isDiff($customMetric)
    {
        if (strcmp($this->name, $customMetric->name) !== 0) {
            return true;
        }

        if ($this->index !== $customMetric->index) {
            return true;
        }

        if ($this->active !== (bool) $customMetric->active) {
            return true;
        }

        if (strcmp($this->scope, $customMetric->scope) !== 0) {
            return true;
        }

        if (strcmp($this->type, $customMetric->type) !== 0) {
            return true;
        }

        if (strcmp($this->min_value, $customMetric->min_value) !== 0) {
            return true;
        }

        if (strcmp($this->max_value, $customMetric->max_value) !== 0) {
            return true;
        }
        
        return false;
    }
}
