<?php

namespace Products\Repositories;

use Core\Repositories\BaseRepository;
use Products\Repositories\Contracts\ProductRepositoryInterface;
use Products\Models\Product;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function findExtend(array $arrCondition, $fields = null, $sort = null, $limit = -1, $offset = 0, $distinct = null,$extend = null )
    {
        $arrReturn = [];

        $query = $this->model;

        if(isset($arrCondition['status']) && $arrCondition['status']){
            $query = $query->where('status', $arrCondition['status']);
        } else{
            $query = $query->where('status','>',0);
        }

        if(isset($arrCondition['sku']) && $arrCondition['sku']){
            $query = $query->where('sku', $arrCondition['sku']);
        }

        $query = $query->orderBy('id', 'asc');

        // Has limit
        if ($limit > 0) {
            $query = $query->take($limit);
        }

        // Has offset
        if ($offset > 0) {
            $query = $query->skip($offset);
        }

        // Execute query
        $arrReturn = $query->get();

        return $arrReturn;
        
    }

    public function insert(array $arrParam)
    {
        # code...
    }
}
