<?php
namespace App\Models\Google\Analytics;

use Google_Model;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Goal
 * @package App\Models\Google\Analytics
 */
class Goal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'google_analytics_goals';

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
        'goalId', // string
        'accountId', // string
        'webPropertyId', // string
        'internalWebPropertyId', // string
        'profileId', // string
        'name', // string
        'value', // float
        'active', // boolean
        'type', // string
        'created_at',
        'updated_at',
    ];

    /**
     * Find by profileId
     *
     * @param string $profileId
     * @return Profile
     */
    public static function findByProfileId($profileId)
    {
        return self::where('profileId', $profileId)
            ->orderBy('version', 'desc');
    }

    /**
     * Find last by goalId
     *
     * @param string $goalId
     * @param string $profileId
     * @return \Illuminate\Support\Collection
     */
    public static function findLastByGoalIdAndProfileId($goalId, $profileId)
    {
       return self::where([
            ['goalId', '=', $goalId],
            ['profileId', '=', $profileId]
        ])->orderBy('version', 'desc')->first();
    }

    /**
     * Find by goalId
     *
     * @param string $goalId
     * @param string $profileId
     * @return \Illuminate\Support\Collection
     */
    public static function findByGoalIdAndProfileId($goalId, $profileId)
    {
        return self::where([
            ['goalId', '=', $goalId],
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

        $attributes['goalId'] = $attributes['id'];

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

        if ($this->active !== (bool) $customMetric->active) {
            return true;
        }

        if ($this->value !== (float) $customMetric->value) {
            return true;
        }

        if (strcmp($this->type, $customMetric->type) !== 0) {
            return true;
        }
        
        return false;
    }
}
