<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;
    const FLAG_ACTIVE = 1;
    const FLAG_DELETE = 0;

    const CODE_SUCCESS = 1;
    const CODE_ERROR = 0;
}
