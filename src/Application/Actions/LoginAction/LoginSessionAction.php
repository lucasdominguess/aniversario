<?php 
namespace App\Application\Actions\LoginAction;

use Psr\Http\Message\ResponseInterface;
use App\Infrastructure\Repository\LoginRepository\LoginRepository;


class LoginSessionAction extends LoginAction {

    public function action(): ResponseInterface {
        global $env ;
        $request = $this->request->getParsedBody();
        $body = $this->antiXSS->xss_clean($request);

        if (empty($body['login']) || empty($body['senha'])) {
            $msg = ['summary' => 'Login ou Senha inválidos, tente novamente!'];
            return $this->respondWithData($msg);
        }

        // return $this->respondWithData($body);
        try {
            $r = new LoginRepository($this->sqlRepository);
            $loginResult = $r->login(strtoupper($body['login']), $body['senha']);

            if (isset($loginResult['error'])) {
                return $this->respondWithData($loginResult['error'],401);
            }

            $msg = ['summary' => 'Logado com sucesso!','login👌' => USER_LOGIN,'email' => USER_EMAIL,'name' => USER_NAME,'token'=>$loginResult['Token']];
            $this->createLogger->loggerCSV("login_realizado", "Usuario " . USER_NAME . " Realizou login");
     

            return $this->respondWithData($msg);

        } catch (\Throwable $th) {
            $this->createLogger->loggerCSV('erro_logar_sessao', $th->getMessage());
            throw new \Exception($th->getMessage(), $th->getCode());
        }
    }
}
