<?php

namespace Accounting\Models;

use Core\Models\BaseModel as Model;
use Accounting\Models\RolePermission;
use Accounting\Models\Permission;

class Role extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $table = 'role';

    protected $fillable = [
        'id',
        'name',
        'status',
        'group_id',
    ];

    public function rolePermission()
    {
        return $this->hasMany(RolePermission::class, 'role_id', 'id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id' );
    }
}
