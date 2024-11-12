<?php 
declare(strict_types=1); 
namespace App\Application\Actions;

use App\classes\Helpers;
use voku\helper\AntiXSS;
use App\classes\CreateLogger;

use App\Application\Actions\Action;
use App\classes\RequestParams;
use App\classes\ValidateParams;
use App\Infrastructure\Persistence\User\Sql;
use App\Infrastructure\Persistence\User\RedisConn;
use App\Infrastructure\Repository\BirthdayRepository\BirthdayRepository;

abstract class BirthdayAction extends Action
{   
    use Helpers;
    public function __construct(

        protected BirthdayRepository $birthdayRepository,
        protected ValidateParams $validateParams,
        protected RequestParams $requestParams,
        
        // protected CreateLogger $createLogger,
        // protected RedisConn $redis,
        // protected AntiXSS $antiXSS 

        )
    {
        // parent::__construct($logger);
       
    }
}