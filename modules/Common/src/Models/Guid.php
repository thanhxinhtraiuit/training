<?php

namespace Common\Models;

use Core\Models\BaseModel;

class Guid extends BaseModel
{
    // product
    
    const CODE_PRODUCT = 11;

    protected $fillable = [
        'code',
        'counter',
    ];

}
