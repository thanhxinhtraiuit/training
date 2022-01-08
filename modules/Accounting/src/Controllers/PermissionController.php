<?php
namespace Accounting\Controllers;

use Core\Controllers\BaseController as Controller;
use Core\Models\BaseModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
// use Illuminate\Support\Str;

use Accounting\Repositories\Contracts\PermissionRepositoryInterface;
use Common\Repositories\Contracts\GuidRepositoryInterface;

class PermissionController extends Controller
{
    private $permissionRepository, $guidRepository;

    public function __construct(
        PermissionRepositoryInterface $permissionRepository,
        GuidRepositoryInterface $guidRepository
    ) {
        $this->permissionRepository = $permissionRepository;
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

        

        $arrDataResponse = $this->permissionRepository->findExtend($arrRequest, null, $arrSort, $limit, $offset, null, $arrExtend);

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
                'key' => 'required|string|max:255',
                'url' => 'required|string',
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

        $arrRequest['group'] = $arrRequest['group'] ?? "";

        $arrRequest['code'] = $arrRequest['code'] ?? "";

        $arrDataResponse = $this->permissionRepository->insert($arrRequest);

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

        $arrDataResponse = $this->permissionRepository->update($arrRequest, $id);

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
        $arrDataResponse = $this->permissionRepository->delete($id);

        return new JsonResponse([
            'message' => 'Success',
            'status' => BaseModel::CODE_SUCCESS,
            'code' => Response::HTTP_OK,
            'data' => $arrDataResponse,
        ], Response::HTTP_OK);
    }

    public function detail(Request $request, $id)
    {

        $arrRequest = $request->all();
        
        $arrRequest = ['id' => $id];
        $arrDataResponse = $this->permissionRepository->findOne($arrRequest);

        return new JsonResponse([
            'message' => 'Success',
            'status' => BaseModel::CODE_SUCCESS,
            'code' => Response::HTTP_OK,
            'data' => $arrDataResponse,
        ], Response::HTTP_OK);
    }
}
