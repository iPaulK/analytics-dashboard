<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * Class Role
 * @package App\Models
 */
class Role extends Model
{
    const ROLE_ADMIN = 'admin';
    const ROLE_EMPLOYEE = 'employee';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Retrieve available roles
     *
     * @return array
     */
    protected static function getAvailableRoles()
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_EMPLOYEE => 'Employee',
        ];
    }

    /**
     * Define a one-to-many relationship with User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if the user is admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->name == self::ROLE_ADMIN;
    }
    /**
     * Check if the user is employee.
     *
     * @return bool
     */
    public function isEmployee()
    {
        return $this->name == self::ROLE_EMPLOYEE;
    }
}
