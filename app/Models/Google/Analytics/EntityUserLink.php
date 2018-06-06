<?php
namespace App\Models\Google\Analytics;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EntityUserLink
 * @package App\Models\Google\Analytics
 */
class EntityUserLink extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'google_analytics_entity_user_link';

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
        'account_id',
        'kind',
        'selfLink',
        'name',
        'permissions',
        'starred',
        'created_at',
        'updated_at',
    ];

    /**
     * Find last a account by accountId
     *
     * @param string $accountId
     * @return User
     */
    public static function findLastByAccountId($accountId)
    {
        return self::where('account_id', $accountId)
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * Create a new account by attributes.
     *
     * @param [] $attributes
     * @return Account
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function createAccount($attributes)
    {
        return $this->newInstance($attributes);
    }

    /**
     * Increment version.
     *
     * @param int $amount
     * @return void
     */
    public function incrementVersion($amount = 1)
    {
        $this->version = $this->version + $amount;
    }

    /**
     * Compare models
     *
     * @param Account $account
     * @return bool
     */
    public function isDiff($account)
    {
        if (strcmp($this->name, $account->name) !== 0) {
            return true;
        }

        /*if ($this->starred !== $account->starred) {
            return true;
        }*/

        /*if ($this->permissions != $account->permissions) {
            return true;
        }*/

        return false;
    }

}
