<?php

// namespace Common\Repositories\Contract;
// use Core\Repositories\BaseRepositoryInterface;

// interface GuidRepositoriesInterface extends BaseRepositoryInterface
// {
//     // public function generate($type, array $arrConfig);

//     public function generateCode(array $arrParram);
// }


namespace Common\Repositories\Contracts;

use Core\Repositories\BaseRepositoryInterface;

interface GuidRepositoryInterface extends BaseRepositoryInterface
{
    public function generateSKU($type);
    public function generateCode($type);
    public function _getUuid($key, $defaultValue);
    public function uploadImage($file);
}
