<?php
namespace Accounting\Controllers;

use Core\Controllers\BaseController as Controller;
use Core\Models\BaseModel;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

use Accounting\Repositories\Contracts\UserRepositoryInterface;
use Common\Repositories\Contracts\GuidRepositoryInterface;

class UserController extends Controller
{
    private $userRepository,$guidRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,  GuidRepositoryInterface $guidRepository
    ) {
        $this->userRepository = $userRepository;
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

        $arrDataResponse = $this->userRepository->findExtend($arrRequest,null, $arrSort, $limit, $offset,null,$arrExtend);
        
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
            $this->validate($request, [
                'avatar' => 'max:25500|mimes:jpg,bmp,png'
            ]);

        } catch (ValidationException $e) {
            return new JsonResponse([
              'message' => $e->getMessage(),
              'status' => BaseModel::CODE_ERROR,
              'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
          ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //check permission

        $arrRequest = $request->all();

        if($request->hasFile('avatar')){
            $files = $request->file('avatar');
            $name = date('ymdhms') . $files->getClientOriginalName();
            $files->move(public_path() . '/upload/user/avatar', $name);
            $arrRequest['avatar'] = '/upload/user/avatar' . $name;
        }

        $arrDataResponse = $this->userRepository->update($arrRequest,$id);

        return new JsonResponse([
            'message' => 'Success',
            'status' => BaseModel::CODE_SUCCESS,
            'code' => Response::HTTP_OK,
            'data' => $arrDataResponse,
        ], Response::HTTP_OK);

    }

    public function addPermission(Request $request){
        try {
            $this->validate($request, [
                'arr_permission_id' => 'required',
                'user_id' => 'required|integer'
            ]);
        } catch (ValidationException $e) {
            return new JsonResponse([
              'message' => $e->getMessage(),
              'status' => BaseModel::CODE_ERROR,
              'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
          ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $arrRequest = $request->all();

        $arrDataResponse = $this->userRepository->addPermission($arrRequest);

        return new JsonResponse([
            'message' => 'Success',
            'status' => BaseModel::CODE_SUCCESS,
            'code' => Response::HTTP_OK,
            'data' => $arrDataResponse,
        ], Response::HTTP_OK);
    }

    // public function delete($id)
    // {

    //     //check permission


    //     //
    //     $arrDataResponse = $this->productRepository->delete($id);

    //     return new JsonResponse([
    //         'message' => 'Success',
    //         'status' => BaseModel::CODE_SUCCESS,
    //         'code' => Response::HTTP_OK,
    //         'data' => $arrDataResponse,
    //     ], Response::HTTP_OK);
    // }

    // public function detail(Request $request, $id){

    //     $arrRequest = $request->all();

    //     $arrRequest = ['id' => $id];

    //     $arrDataResponse = $this->productRepository->findOne($arrRequest);

    //     return new JsonResponse([
    //         'message' => 'Success',
    //         'status' => BaseModel::CODE_SUCCESS,
    //         'code' => Response::HTTP_OK,
    //         'data' => $arrDataResponse,
    //     ], Response::HTTP_OK);
    // }
    
    // public function uploadMedia(Request $request, $id)
    // {
    //     //check permission


    //     //
    //     try {
    //         $this->validate($request, [
    //           'files' => 'required',
    //           'files.*' =>'max:25500|mimes:jpg,bmp,png,mp4,ogx,oga,ogv,ogg,webm'
    //       ]);
          
    //     } catch (ValidationException $e) {
    //         return new JsonResponse([
    //             'message' => $e->getMessage(),
    //             'status' => BaseModel::CODE_ERROR,
    //             'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
    //         ], Response::HTTP_UNPROCESSABLE_ENTITY);
    //     }

    //     $arrDataResponse = [];

    //     $is_base_file = $request->is_base_file ?: '';

    //     if($request->hasFile('files')){
    //         $files = $request->file('files');
    //         $arrDataResponse = $this->productRepository->upload($files, $is_base_file, $id);
    //     }

        

    //     return new JsonResponse([
    //         'message' => 'Success',
    //         'status' => BaseModel::CODE_SUCCESS,
    //         'code' => Response::HTTP_OK,
    //         'data' => $arrDataResponse,
    //     ], Response::HTTP_OK);
    // }

}
