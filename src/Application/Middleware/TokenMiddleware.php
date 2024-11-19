<?php
namespace App\Application\Middleware;

use Psr\Http\Server\MiddlewareInterface as Middleware;

use Slim\Psr7\Response;
use App\classes\Helpers;
use App\classes\CreateLogger;
use App\Application\token\Token;

use Firebase\JWT\ExpiredException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
 
 
class TokenMiddleware implements Middleware{
    use Helpers;
    public function process(Request $request, RequestHandler $handler): Response{

        $response = new Response();
       

        $cookie = $request->getHeader("Authorization") ?? null ;
   
        if(!isset($cookie)){
            
         
            $this->logGenerate()->loggerCSV("token_Middleware",'tentativa de acesso sem token/cookie ','warning',$_SERVER['REMOTE_ADDR']);
            $response->getBody()->write('Acesso nao permitido : Token Inexistente');
            // $token->destructHeaderToken();
            return $response->withStatus(403);
        }
        // decodificando o token e verificando validade e expiraçao
        try {
        $key = implode('',$cookie);
        $dados = token::create()->decodedToken($key);
           
        } catch (ExpiredException $e) {    
            $response->getBody()->write('Acesso não Permitido. Token Expirado');
            $this->logGenerate()->loggerCSV("token_Middleware",'tentativa de acesso sem token/cookie ','warning',$_SERVER['REMOTE_ADDR']);
            return $response->withStatus(403);
        }
        
        $response = $handler->handle($request);

        return $response;
    }
        
       
}
    