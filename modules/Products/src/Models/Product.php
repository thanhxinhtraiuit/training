<?php

namespace Products\Models;

use Core\Models\BaseModel as Model;

class Product extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 2;

    protected $table = 'products';

    protected $fillable = [
        'id',
        'name',
        'sku',
        'price',
        'description',
        'group_id',
        'status',
        'type',
        'options',
        'note',
    ];

}
