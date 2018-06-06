<?php
namespace App\Models\Google;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RequestQueue
 * @package App\Models\Google
 */
class RequestQueue extends Model
{
    const STATUS_PENDING = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_ERROR = 3;
    // const STATUS_RUNNING = 4;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'google_request_queue';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'command',
        'params',
        'status',
        'messages',
        'created_at',
        'updated_at',
    ];
}
