<?php

declare(strict_types=1);

use Slim\App;

use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\User\ListUsersAction;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Listagem\ListUserAction;
use App\Application\Actions\Cadastro\CadastrarAction;
use App\Application\Actions\Listagem\ListAllUserAction;
use App\Application\Actions\Listagem\ListOneUserAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Actions\LoginAction\LoginSessionAction;
use App\Application\Actions\Listagem\ListAniversariosAction;


return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        global $env;
        return $response
            ->withHeader('Access-Control-Allow-Origin', "$env[access_origin]")
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization,X-Arquivo')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true');
     });

    $app->post("/login",LoginSessionAction::class);

    $app->post("/cadastrar",CadastrarAction::class);
   
    $app->get("/listar_usuario",ListUserAction::class); //id ? opcional
    $app->get("/listar_mes",ListAniversariosAction::class);
   

};
