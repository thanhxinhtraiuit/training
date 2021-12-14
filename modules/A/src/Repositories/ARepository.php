<?php

namespace A\Repositories;

use Core\Repositories\BaseRepository;
use A\Repositories\Contracts\ARepositoryInterface;
use A\Models\A;

class ARepository extends BaseRepository implements ARepositoryInterface
{
    public function __construct(A $model)
    {
        parent::__construct($model);
    }

    public function get()
    {
        return $this->model->getAll();
    }

//    public function find($id)
//    {
//        dump(' a repo');
//        return ['a'];
//    }
}
