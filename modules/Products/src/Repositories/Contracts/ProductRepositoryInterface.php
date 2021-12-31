<?php

namespace Products\Repositories\Contracts;

use Core\Repositories\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function findExtend(array $arrCondition, $fields = null, $sort = null, $limit = -1, $offset = 0, $distinct = null,$extend = null );
    public function delete($id);
    public function upload($files, $is_base_file, $id);
    public function deleteImages($arrId);
    public function setImageBase($id);
}
