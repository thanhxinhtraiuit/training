<?php

namespace Accounting\Models;

use Core\Models\BaseModel as Model;

class Permission extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    protected $table = 'permission';

    protected $fillable = [
        'id',
        'key',
        'url',
        'status',
        'group',
        'code'
    ];

}
