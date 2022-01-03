<?php

namespace Accounting\Models;

use Core\Models\BaseModel as Model;

class UserPermission extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $table = 'user_permission';

    protected $fillable = [
        'id',
        'user_id',
        'permission_id',
        'add_by_user_id',
    ];

}
