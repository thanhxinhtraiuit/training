<?php

namespace Accounting\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

use Accounting\Repositories\Contracts\UserRepositoryInterface;

//demo job
class UserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $logger, $arrParam;
    private $userRepository;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($arrParam)
    {
        $this->arrParam = $arrParam;

        $path = storage_path() . '/logs';

        $fileName = 'UserJob' . date('y-m-d') . '.log';

        $this->logger = new Logger('UserJob');

        $this->logger->pushHandler(new StreamHandler($path . $fileName, Logger::INFO));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;

        $this->__addBasePermission();
    }

    public function __addBasePermission()
    {
        $arr = [
            "user_id" => $this->arrParam['user_id'],
            "arr_permission_id" => ['6'] //base permission
        ];
        
        $this->userRepository->addPermission($arr);
        
        $this->logger->info("Add base permission to user :" . $this->arrParam['user_id']);
    }
}
