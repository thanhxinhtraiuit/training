<?php

namespace Accounting\Models;

use Core\Models\BaseModel as Model;
use Accounting\Models\Role;
use Accounting\Models\UserPermission;

class User extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 2;

    protected $table = 'users';

    protected $fillable = [
        'id',
        'name',
        'role_id',
        'facebook_id',
        'status',
        'config',
        'address',
        'code',
        'birth',
        'phone',
        'avatar'

    ];

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function permissions()
    {
        return $this->hasMany(UserPermission::class, 'user_id', 'id');
    }
}
