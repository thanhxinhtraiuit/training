<?php

namespace Accounting\Models;

use Core\Models\BaseModel as Model;

class RolePermission extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $table = 'role_permission';

    protected $fillable = [
        'id',
        'role_id',
        'permission_id',
        'user_id',
    ];

}
