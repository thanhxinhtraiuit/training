<?php
namespace Products\Controllers;

use Core\Controllers\BaseController as Controller;
use Core\Models\BaseModel;
use Products\Repositories\Contracts\ProductRepositoryInterface;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $arrRequest = $request->all();

        if (isset($arrRequest['sort']) && $arrRequest['sort']) {
            $arrSort['field'] = $arrRequest['sort'];
            $arrSort['dir'] = isset($arrRequest['dir']) ? $arrRequest['dir'] : 'desc';
        } else {
            $arrSort = false;
        }
        $limit = isset($arrRequest['limit']) ? (int)$arrRequest['limit'] : 20;
        $offset = isset($arrRequest['offset']) ? $arrRequest['offset'] : 0;

        $arrExtend = [];

        $arrDataResponse = $this->productRepository->findExtend($arrRequest,null, $arrSort, $limit, $offset,null,$arrExtend);
        
        return new JsonResponse([
            'message' => 'Success',
            'status' => BaseModel::CODE_SUCCESS,
            'code' => Response::HTTP_OK,
            'data' => $arrDataResponse,
        ], Response::HTTP_OK);
    }

    // public function edit($id)
    // {
    //     dump('controller');
    //     return response()->json($this->aService->edit($id));
    // }
}
