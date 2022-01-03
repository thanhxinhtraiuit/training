<?php

namespace Accounting\Repositories\Contracts;

use Core\Repositories\BaseRepositoryInterface;

interface PermissionRepositoryInterface extends BaseRepositoryInterface
{
    public function findExtend(array $arrCondition, $fields = null, $sort = null, $limit = -1, $offset = 0, $distinct = null,$extend = null );
    public function delete($id);
    
}
