<?php

namespace Accounting\Repositories;

use Core\Repositories\BaseRepository;

use Accounting\Models\User;
use Accounting\Models\Permission;

use Illuminate\Support\Facades\Auth;

use Common\Repositories\Contracts\GuidRepositoryInterface;
use Accounting\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $guidRepository;

    public function __construct(User $model, GuidRepositoryInterface $guidRepository
    )
    {
        parent::__construct($model);
        $this->guidRepository = $guidRepository;
    }

    public function findExtend(array $arrCondition, $fields = null, $sort = null, $limit = -1, $offset = 0, $distinct = null,$extend = null )
    {
        $arrReturn = [];

        $query = $this->model;

        if(isset($arrCondition['status']) && $arrCondition['status']){
            $query = $query->where('status', $arrCondition['status']);
        } else{
            $query = $query->where('status','>',0);
        }

        if(isset($arrCondition['q']) && $arrCondition['q']){
            $query = $query->where(function ($query) use ($arrCondition){
                return $query->orWhere('name', 'like', "%" . $arrCondition['q'] . "%")
                ->orWhere('email', 'like', "%" . $arrCondition['q'] . "%")
                ->orWhere('phone', 'like', "%" . $arrCondition['q'] . "%");
            });
        }
        
        //     $query = $query->orWhere('name', 'like', "%" . $arrCondition['q'] . "%")
        //                     ->orWhere('email', 'like', "%" . $arrCondition['q'] . "%")
        //                     ->orWhere('phone', 'like', "%" . $arrCondition['q'] . "%");
        // }

        if(isset($arrCondition['type']) && $arrCondition['type']){
            $query = $query->where('type', $arrCondition['type']);
        }

        $query = $query->orderBy('id', 'asc');

        // Has limit
        if ($limit > 0) {
            $query = $query->take($limit);
        }

        // Has offset
        if ($offset > 0) {
            $query = $query->skip($offset);
        }

        if(isset($arrCondition['images']) && ($arrCondition['images'] == 1)){
            $query->with('images');
        }

        // Execute query
        $arrReturn = $query->get();

        return $arrReturn;
        
    }

    public function delete($id)
    {

        //check permission


        //
        $userDelete = $this->model->where($this->model->getKeyName(),$id)->first();

        if( isset($userDelete['id']) && $userDelete['id']) {
            $userDelete->update(['status' => User::STATUS_INACTIVE]);
            return true;
        }
        return false;
    }

    public function addPermission($arrParams)
    {
        $user = $this->model->where('id', $arrParams['user_id'])->first();


        if(!$user){
            return false;
        }

        $arrPermission = Permission::whereIn('id', $arrParams['arr_permission_id'])
                                    ->where('status', 1)
                                    ->get()
                                    ->toArray();
        $arrUserPermission = [];

        $user_id = Auth::user()->id ?? 0;

       
        foreach ($arrPermission as $key => $permission) {
            $arrUserPermission[] = [
                'user_id' => $user->id,
                'permission_id' => $permission['id'],
                'add_by_user_id' => $user_id
            ];
        }

        $dataReturn = $user->permissions()->createMany($arrUserPermission);

        return $dataReturn;
    }
}
