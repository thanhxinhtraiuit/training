<?php
namespace Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    protected function handleError(\Exception $ex){
        info($ex->getMessage());
        info($ex->getTraceAsString());
        return new JsonResponse(sprintf('{"message": "%s", "code": 500, "status": 0, "sign": "handle-error"}', $ex->getMessage()), 500);
    }
}
