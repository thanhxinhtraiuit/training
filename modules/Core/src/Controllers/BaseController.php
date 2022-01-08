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

    public function dispatchJob($job, $queueName){
        // Try to dispatch job 10 times
        $loopIndex = 0;
        $dispatchRes = null;
        do{
            $dispatchRes = dispatch($job->onQueue($queueName));
            //Log::error(json_encode(['queue' => $queueName, 'job' => $job, 'result' => $dispatchRes, 'loop' => $loopIndex]));
            if($dispatchRes){
                break;
            }
            $loopIndex++;
            sleep(0.1);
        }while($loopIndex < 2);

        return $dispatchRes;
    }
}
