<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @package App\Models
 */
class User extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'active',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'role',
    ];

    /**
     * Define an inverse one-to-many relationship with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Find a user by email
     *
     * @param string $email
     * @return User
     */
    public static function findByEmailOrFail($email)
    {
        return self::where('email', $email)->firstOrFail();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id,
        ];
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return "id";
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * Check if the user has a role.
     *
     * @param string $roleName
     * @return bool
     */
    public function isRole($roleName)
    {
        return $this->role->name == $roleName;
    }

    /**
     * Check if the user has at least one role.
     *
     * @param array $roles
     * @return bool
     */
    public function isOneRole($roles)
    {
        foreach ($roles as $roleName) {
            if ($this->isRole($roleName)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if the current user has a role by its name.
     *
     * @param string|array $name Role name or array of role names.
     * @return bool
     */
    public function hasRole($name)
    {
        if (is_array($name)) {
            return $this->isOneRole($name);
        }
        return $this->isRole($name);
    }

    /**
     * Apply request params to the builder instance.
     *
     * @param Request $request
     * @param Builder|null $builder
     * @param string|null $roleName
     * @return Builder
     */
    public static function filter(Request $request, Builder $builder = null, $roleName = null)
    {
        $query = $builder ?? self::query();

        // Apply role to the builder instance.
        if (isset($roleName) && array_key_exists($roleName, Role::getAvailableRoles())) {
            $query->whereHas('role', function ($q) use ($roleName) {
                $q->where('roles.name', $roleName);
            });
        }

        $query = self::applyFilters($query, $request->input('filter'));
        $query = self::applySort($query, $request->input('sort'), $request->input('order'));

        return $query;
    }

    /**
     * @param Builder $query
     * @param array|null $filters
     * @return Builder
     */
    private static function applyFilters(Builder $query, ?array $filters)
    {
        if (!is_array($filters) || count($filters) < 1) {
            return $query;
        }

        if (!empty($filters['query'])) {
            $value = '%' . $filters['query'] . '%';

            $query->where(function (Builder $q) use ($value) {
                return $q->orWhere('id', 'LIKE', '%' . $value . '%')
                    ->orWhere('first_name', 'LIKE', '%' . $value . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $value . '%')
                    ->orWhere('email', 'LIKE', '%' . $value . '%');
            });
        }

        // if (isset($filters['active']) && in_array($filters['active'], ['active', 'inactive'])) {
        //     if ($filters['active'] === 'active') {
        //         $query->where('active', true);
        //     } elseif ($filters['active'] === 'inactive') {
        //         $query->where('active', false);
        //     }
        // }

        return $query;
    }

    /**
     * @param Builder $query
     * @param $sort
     * @return Builder
     */
    private static function applySort(Builder $query, $sort = 'created_at', $dir = 'asc')
    {
        if (!in_array($sort, ['id', 'first_name', 'last_name', 'email', 'updated_at', 'created_at'])) {
            $sort = 'created_at';
        }

        if (!in_array($dir, ['asc', 'desc'])) {
            $dir = 'asc';
        }

        $query->orderBy($sort, $dir);

        return $query;
    }
}
