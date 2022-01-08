<?php

namespace Accounting\Commands;

use Accounting\Models\User;
use Illuminate\Console\Command;
use Accounting\Repositories\Contracts\UserRepositoryInterface;

// demo command
class UserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update {--phone=}'; 

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add can_use_inside permission'; // demo command

    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $userRepository;
    
     public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $phone = $this->option('phone');

        $this->__addPermissionToUser($phone);

        echo  'user with ' . $phone . ' has been accept use inside';
        return;
    }

    public function __addPermissionToUser($phone)
    {
        $user = $this->userRepository->findOne(['phone' => $phone]);

        $arrParams = [
            "user_id" => $user->id,
            "arr_permission_id" => ['5'] // can_use_inside
        ];

        $this->userRepository->addPermission($arrParams);

    }
}
