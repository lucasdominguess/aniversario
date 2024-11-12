<?php

declare(strict_types=1);

use Slim\App;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\User\ListUsersAction;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Cadastro\CadastrarAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

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

     $app->group("",function (Group $group) {
        // $group->post("/sair",SairSessaoAction::class);
        $group->post("/cadastrar",CadastrarAction::class);
     });
};
