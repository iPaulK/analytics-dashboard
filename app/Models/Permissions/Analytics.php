<?php
namespace App\Models\Permissions;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Analytics
 * @package App\Models\Permissions
 */
class Analytics extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ga_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'account_id',
    ];
}
