<?php

namespace Products\Models;

use Core\Models\BaseModel as Model;

class ProductImages extends Model
{

    protected $table = 'product_images';

    protected $fillable = [
        'id',
        'product_sku',
        'image',
        'is_base',
        'status'
    ];

}
