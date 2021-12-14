<?php
namespace A\Controllers;

use Core\Controllers\BaseController as Controller;
use A\Services\Contracts\AServiceInterface;

class AController extends Controller
{
    private $aService;

    public function __construct(
        AServiceInterface $aService
    ) {
        $this->aService = $aService;
    }

    public function index()
    {
        return response()->json($this->aService->index());
    }

    // public function edit($id)
    // {
    //     dump('controller');
    //     return response()->json($this->aService->edit($id));
    // }
}
