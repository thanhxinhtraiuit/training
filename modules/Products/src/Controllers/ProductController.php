<?php
namespace Products\Controllers;

use Core\Controllers\BaseController as Controller;
use Core\Models\BaseModel;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

use Products\Repositories\Contracts\ProductRepositoryInterface;
use Common\Repositories\Contracts\GuidRepositoryInterface;
use Products\Models\Product;

class ProductController extends Controller
{
    private $productRepository,$guidRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,  GuidRepositoryInterface $guidRepository
    ) {
        $this->productRepository = $productRepository;
        $this->guidRepository = $guidRepository;
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


    public function insert(Request $request)
    {

        try {
            $this->validate($request, [
              'name' => 'required|string|max:255',
              'type' => 'required|integer',
          ]);
        } catch (ValidationException $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'status' => BaseModel::CODE_ERROR,
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $arrRequest = $request->all();

        //check permission


        //

        // $arrRequest['sku'] = $this->__generate($arrRequest['type']);

        $arrRequest['sku'] = $this->guidRepository->generateSKU($arrRequest['type']);

        // status when create, will update to STATUS_ACTIVE after manager check
        $arrRequest['status'] = Product::STATUS_PENDING;

        $arrRequest['group_id'] = $arrRequest['group_id'] ?? 0;

        $arrDataResponse = $this->productRepository->insert($arrRequest);

        return new JsonResponse([
            'message' => 'Success',
            'status' => BaseModel::CODE_SUCCESS,
            'code' => Response::HTTP_OK,
            'data' => $arrDataResponse,
        ], Response::HTTP_OK);

    }

    public function update(Request $request, $id)
    {
        try {
            if ($id < 1) {
                return false;
            }

        } catch (ValidationException $e) {
            return new JsonResponse([
              'message' => $e->getMessage(),
              'status' => BaseModel::CODE_ERROR,
              'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
          ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //check permission


        //
        $arrRequest = $request->all();

        $arrDataResponse = $this->productRepository->update($arrRequest,$id);

        return new JsonResponse([
            'message' => 'Success',
            'status' => BaseModel::CODE_SUCCESS,
            'code' => Response::HTTP_OK,
            'data' => $arrDataResponse,
        ], Response::HTTP_OK);

    }

    public function delete($id)
    {

        //check permission


        //
        $arrDataResponse = $this->productRepository->delete($id);

        return new JsonResponse([
            'message' => 'Success',
            'status' => BaseModel::CODE_SUCCESS,
            'code' => Response::HTTP_OK,
            'data' => $arrDataResponse,
        ], Response::HTTP_OK);
    }

    public function detail(Request $request, $id){

        $arrRequest = $request->all();

        $arrRequest = ['id' => $id];

        $arrDataResponse = $this->productRepository->findOne($arrRequest);

        return new JsonResponse([
            'message' => 'Success',
            'status' => BaseModel::CODE_SUCCESS,
            'code' => Response::HTTP_OK,
            'data' => $arrDataResponse,
        ], Response::HTTP_OK);
    }
    
    public function uploadMedia(Request $request, $id)
    {
        //check permission


        //
        try {
            $this->validate($request, [
              'files' => 'required',
              'files.*' =>'max:25500|mimes:jpg,bmp,png,mp4,ogx,oga,ogv,ogg,webm'
          ]);
          
        } catch (ValidationException $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'status' => BaseModel::CODE_ERROR,
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $arrDataResponse = [];

        $is_base_file = $request->is_base_file ?: '';

        if($request->hasFile('files')){
            $files = $request->file('files');
            $arrDataResponse = $this->productRepository->upload($files, $is_base_file, $id);
        }

        

        return new JsonResponse([
            'message' => 'Success',
            'status' => BaseModel::CODE_SUCCESS,
            'code' => Response::HTTP_OK,
            'data' => $arrDataResponse,
        ], Response::HTTP_OK);
    }

    public function deleteImage(Request $request)
    {
       //check permission


        //

        try {
            $this->validate($request, [
                'arrId' => 'required'
            ]);
        } catch (ValidationException $e) {
            return new JsonResponse([
              'message' => $e->getMessage(),
              'status' => BaseModel::CODE_ERROR,
              'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
          ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
      
        $arrDataResponse = $this->productRepository->deleteImages($request->arrId);

        return new JsonResponse([
            'message' => 'Success',
            'status' => BaseModel::CODE_SUCCESS,
            'code' => Response::HTTP_OK,
            'data' => $arrDataResponse,
        ], Response::HTTP_OK);

    }

    public function setImageBase(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required|integer'
            ]);
        } catch (ValidationException $e) {
            return new JsonResponse([
              'message' => $e->getMessage(),
              'status' => BaseModel::CODE_ERROR,
              'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
          ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // check permission

        $arrDataResponse = $this->productRepository->setImageBase($request->id);

        return new JsonResponse([
            'message' => 'Success',
            'status' => BaseModel::CODE_SUCCESS,
            'code' => Response::HTTP_OK,
            'data' => $arrDataResponse,
        ], Response::HTTP_OK);

    }
}
