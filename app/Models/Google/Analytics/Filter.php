<?php
namespace App\Models\Google\Analytics;

use Google_Model;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Filter
 * @package App\Models\Google\Analytics
 */
class Filter extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'google_analytics_filters';

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
        'accountId',
        'filterId',
        'kind',
        'selfLink',
        'name',
        'type',
        'created_at',
        'updated_at',
    ];

    /**
     * Find last a account by filterId
     *
     * @param string $filterId
     * @return User
     */
    public static function findLastByFilterId($filterId)
    {
        return self::where('filterId', $filterId)
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

        $attributes['filterId'] = $attributes['id'];

        unset($attributes['id']);
        
        return $this->newInstance($attributes);
    }

    /**
     * Compare models
     *
     * @param Filter $filter
     * @return bool
     */
    public function isDiff($filter)
    {
        if (strcmp($this->name, $filter->name) !== 0) {
            return true;
        }
        return false;
    }
}
