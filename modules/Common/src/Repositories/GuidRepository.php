<?php

namespace Common\Repositories;

use Common\Models\Guid;
use Common\Repositories\Contracts\GuidRepositoryInterface;

use Core\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class GuidRepository extends BaseRepository implements GuidRepositoryInterface
{
    protected $model;

    function __construct(Guid $model)
    {
        $this->model = $model;
    }

    public function generateSKU($type)
    {
        $prefix = str_pad(Guid::PREFIX_CODE_PRODUCT, 4, "0", STR_PAD_RIGHT)  . $type ;

        $strCode = $prefix . '0001' ;

        $nextGuid = $this->_getUuid($prefix,  $strCode);

        return $nextGuid;
    }

    public function generateCode($type)
    {
        $prefix = str_pad(Guid::PREFIX_CODE_PRODUCT, 3, "0", STR_PAD_RIGHT) . date('ymd') . $type ;

        $strCode = $prefix . '0001' ;

        $nextGuid = $this->_getUuid($prefix,  $strCode);

        return $nextGuid;
    }

    public function _getUuid($key, $defaultValue){
        return DB::transaction(function () use ($key, $defaultValue){
            // Lock recode for update
            $guid = $this->model->where('code', $key)->lockForUpdate()->first();
            if(!$guid){
                // Key is not exist, create with default counter
                $guid = $this->model->create([
                    'code' => $key,
                    'counter' => $defaultValue,
                ]);
            }else{
                // Increment counter with 1
                $guid->increment('counter');
            }
            return $guid->counter;
        });

    }

    // public function uploadImage($image_name,$target_dir,$file){

	// 	if (isset($image_name) && isset($target_dir) && isset($file)) {

	// 		$error = '';
	// 		$tmp_name = $file['tmp_name'];
	// 		$target_file = $target_dir.$image_name;

	// 		if($_FILES['fileUpload']['size'] > 10242880){
	//   		 $error ="Only upload files under 9.8M";
	//   		}

	//    		$file_type = pathinfo($_FILES['fileUpload']['name'], PATHINFO_EXTENSION);
	   
	//    		$file_type_allow = array('png','jpg','jpeg');

	// 	    if(!in_array(strtolower($file_type),$file_type_allow)){
	// 	      $error = "Only upload image!";
	// 	    }
	 
	// 	    if(empty($error)){
	// 	    	if (!file_exists($target_dir)){
	// 	            mkdir($target_dir, 0777, true);
	// 	        }
	// 			move_uploaded_file($tmp_name,$target_file);	
	// 			return ['success'=>1,'data'=>$target_file];
	// 		} else {
	// 			return $error;
	// 		}			
	// 	} else {
	// 		return "errRequest";
	// 	}
	// }

    public function uploadImage($file)
    {
        return($file);
    }
}

