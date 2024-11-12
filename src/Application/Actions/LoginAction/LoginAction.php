<?php 
declare(strict_types=1); 
namespace App\Application\Actions\LoginAction;

use App\classes\Helpers;
use App\Infrastructure\Repository\SqlRepository\SqlRepository;
use voku\helper\AntiXSS;
use App\classes\CreateLogger;
use App\Application\Actions\Action;
use App\Infrastructure\Repository\SqlRepository\SqlInterface;




// use App\Infrastructure\Connection\RedisConn;



abstract class LoginAction extends Action
{
    use Helpers;
    public function __construct(
        protected SqlRepository $sqlRepository,
        protected CreateLogger $createLogger,
        protected AntiXSS $antiXSS ,
        )
    {}
}