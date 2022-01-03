<?php

namespace Accounting\Repositories;

use Core\Repositories\BaseRepository;

use Accounting\Models\Permission;

use Common\Repositories\Contracts\GuidRepositoryInterface;
use Accounting\Repositories\Contracts\PermissionRepositoryInterface;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    protected $guidRepository;

    public function __construct(Permission $model, GuidRepositoryInterface $guidRepository
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

        if(isset($arrCondition['key']) && $arrCondition['key']){
            $query = $query->where('key', $arrCondition['key']);
        }

        if(isset($arrCondition['code']) && $arrCondition['code']){
            $query = $query->where('code', $arrCondition['code']);
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

        // Execute query
        $arrReturn = $query->get();

        return $arrReturn;
        
    }

    public function delete($id)
    {

        //check permission


        //
        $permissionDelete = $this->model->where($this->model->getKeyName(),$id)->first();


        if( isset($permissionDelete['id']) && $permissionDelete['id']) {
            $permissionDelete->update(['status' => Permission::STATUS_INACTIVE]);
            return true;
        }
        return false;
    }

    // public function upload($files, $is_base_file, $id)
    // {
    //     //check permission


    //     //
    //     $product = $this->model->where($this->model->getKeyName(),$id)->with('images')->first();

    //     // neu set is_base , thì bỏ is_base có sẵn

    //     if($is_base_file != 0) {
    //         $product->images()->where('is_base',1)->where('status','!=', 0)->first()->update(['is_base' => 0]);
    //     }

    //     foreach($files as $key => $file)
    //         {
    //             $name = date('ymdhms') . $file->getClientOriginalName();
    //             $file->move(public_path().'/upload/product', $name);
    //             $is_base = $file->getClientOriginalName() == $is_base_file ? 1 : 0 ;
                
    //             if($is_base_file == 0 && $key == 0) {
    //                 $is_base = 1;
    //             }
    //             $arrCreate = [
    //                 'image' => '/upload/product/'. $name,
    //                 'is_base' => $is_base,
    //                 'product_sku' => $product->sku
    //             ];
    //             ProductImages::create($arrCreate);
    //         }

    //     return true;
    // }

    // public function deleteImages($arrId)
    // {
    //     //check permission


    //     //

    //     $images = ProductImages::whereIn('id',$arrId)->update(['status' => 0]);

    //     return true;

    // }

    // public function setImageBase($id)
    // {   
    //     $productImage = ProductImages::where('id',$id)->first();

        
    //     $arrProductImage = ProductImages::where('product_sku', $productImage->product_sku)->update(['is_base' => 0]);
        
    //     $productImage->update(['is_base' => 1]);
        
    //     return true;

    // }
    // // public function __generate($type,$arrParam)
    // // {
    // //     if( $type <= 0 ){
    // //         return false;
    // //     }

    // //     $prefix = str_pad(Product::PREFIX_SKU, 2, "0", STR_PAD_LEFT) . date('ymd') . $type ;

    // //     $strCode = $prefix . '0001' ;

    // //     $nextGuid = $this->guidRepository->_getUuid($prefix,  $strCode);

    // //     return $nextGuid;

    // // }
}
