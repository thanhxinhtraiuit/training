<?php

namespace A\Models;

use Core\Models\BaseModel as Model;

class A extends Model
{
    public function getAll()
    {
        return [
            'model' => 'a model'
        ];
    }

    public function find($id)
    {
        return [
            'find' => 'a model find ' . $id
        ];
    }
}
