<?php

namespace Products\Models;

use Core\Models\BaseModel as Model;
use Products\Models\ProductImages;

class Product extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 2;

    const TYPE_FLOWER = 1;
    const TYPE_OAK = 2;
    const TYPE_MAPLE = 3;
    const TYPE_ELM = 4;
    const TYPE_ASH = 5;

    const PREFIX_SKU = 11 ;

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

    public function images()
    {
        return $this->hasMany(ProductImages::class, 'product_sku', 'sku');
    }

}
