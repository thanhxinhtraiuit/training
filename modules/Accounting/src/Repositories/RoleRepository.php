<?php

namespace Accounting\Repositories;

use Core\Repositories\BaseRepository;

use Accounting\Models\Role;
use Accounting\Models\Permission;
use Accounting\Models\RolePermission;
use Accounting\Models\User;

use Common\Repositories\Contracts\GuidRepositoryInterface;
use Accounting\Repositories\Contracts\RoleRepositoryInterface;

use Illuminate\Support\Facades\Auth;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    protected $guidRepository;

    public function __construct(Role $model, GuidRepositoryInterface $guidRepository
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

        if(isset($arrCondition['name']) && $arrCondition['name']){
            $query = $query->where('name', $arrCondition['name']);
        }

        if(isset($arrCondition['group_id']) && $arrCondition['group_id']){
            $query = $query->where('group_id', $arrCondition['group_id']);
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
        // Has Relationships
        if(isset($arrCondition['permissions']) && ($arrCondition['permissions'] == 1)){
            $query->with('permissions');
        }

        // Execute query
        $arrReturn = $query->get();

        return $arrReturn;
        
    }

    public function delete($id)
    {

        //check permission


        //
        $roleDelete = $this->model->where($this->model->getKeyName(),$id)->first();


        if( isset($roleDelete['id']) && $roleDelete['id']) {
            $roleDelete->update(['status' => Role::STATUS_INACTIVE]);
            return true;
        }
        return false;
    }

    public function addPermission($arrParams)
    {
        
        if(!$arrParams['arr_permission_id'] || !is_array($arrParams['arr_permission_id'])){
            return "The given data was invalid. arr_permission_id not match";
        }

        $role = $this->model->where('id',$arrParams['role_id'])->first();

        
        if(!$role){
            return "The given data was invalid. role_id not match";
        }

        $arrPermission = Permission::whereIn('id', $arrParams['arr_permission_id'])
                                    ->where('status', 1)
                                    ->get();
        
        $user_id = Auth::user()->id;

        $arrRolePermission = [];

        foreach ($arrPermission->toArray() as $key => $permission) {
            $arrRolePermission[] = [
                'role_id' => $arrParams['role_id'],
                'permission_id' => $permission['id'],
                'user_id' => $user_id
            ];
        }
        $ArrRolePermission = $role->rolePermission()->createMany($arrRolePermission);
        
        return $ArrRolePermission;
    }
   
}
