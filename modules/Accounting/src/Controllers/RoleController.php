<?php

namespace Accounting\Controllers;

use Core\Controllers\BaseController as Controller;
use Core\Models\BaseModel;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
// use Illuminate\Support\Str;

use Accounting\Repositories\Contracts\RoleRepositoryInterface;
use Common\Repositories\Contracts\GuidRepositoryInterface;

class RoleController extends Controller
{
    private $roleRepository, $guidRepository;

    public function __construct(
        RoleRepositoryInterface $roleRepository,
        GuidRepositoryInterface $guidRepository
    ) {
        $this->roleRepository = $roleRepository;
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

        $arrDataResponse = $this->roleRepository->findExtend($arrRequest, null, $arrSort, $limit, $offset, null, $arrExtend);

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

        $arrRequest['group_id'] = $arrRequest['group_id'] ?? 0;

        $arrDataResponse = $this->roleRepository->insert($arrRequest);

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

        $arrDataResponse = $this->roleRepository->update($arrRequest, $id);

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
        $arrDataResponse = $this->roleRepository->delete($id);

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

        $arrDataResponse = $this->roleRepository->findOne($arrRequest);

        return new JsonResponse([
            'message' => 'Success',
            'status' => BaseModel::CODE_SUCCESS,
            'code' => Response::HTTP_OK,
            'data' => $arrDataResponse,
        ], Response::HTTP_OK);
    }

    public function addPermission(Request $request)
    {
        try {
            $this->validate($request, [
                'arr_permission_id' => 'required',
                'role_id' => 'required|integer',
            ]);
        } catch (ValidationException $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'status' => BaseModel::CODE_ERROR,
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $arrParams = $request->all();

        $arrDataResponse = $this->roleRepository->addPermission($arrParams);

        return new JsonResponse([
            'message' => 'Success',
            'status' => BaseModel::CODE_SUCCESS,
            'code' => Response::HTTP_OK,
            'data' => $arrDataResponse,
        ], Response::HTTP_OK);
    }
}
