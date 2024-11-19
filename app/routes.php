<?php

declare(strict_types=1);

use App\Application\Middleware\TokenMiddleware;
use Slim\App;
use App\Application\Excluir\ExcluirAction;

use App\Application\Actions\Editar\EditarAction;
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

    $app->group("", function (Group $group) {
        $group->post("/edit",EditarAction::class);
        $group->post("/delete",ExcluirAction::class);
        $group->post("/cadastrar",CadastrarAction::class);
        $group->get("/listar_usuario",ListUserAction::class); //id ? opcional
        $group->get("/listar_mes",ListAniversariosAction::class);
    })
    ->add(TokenMiddleware::class)
    ;

};
