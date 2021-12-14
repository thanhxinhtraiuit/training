<?php

namespace A\Services;

use Core\Services\BaseService;
use A\Services\Contracts\AServiceInterface;
use A\Repositories\Contracts\ARepositoryInterface;

class AService extends BaseService implements AServiceInterface
{
    private $aRepositoryInterface;

    public function __construct(
        ARepositoryInterface $aRepositoryInterface
    ) {
        $this->aRepositoryInterface = $aRepositoryInterface;
    }

    public function index()
    {
        $data = $this->aRepositoryInterface->get();

        if($data) {
            return [
                'error' => 0,
                'data' => $data
            ];
        }
    }

    public function edit($id)
    {
        dump('service');
        $data = $this->aRepositoryInterface->find($id);

        if($data) {
            return [
                'error' => 0,
                'data' => $data
            ];
        }
    }
}
