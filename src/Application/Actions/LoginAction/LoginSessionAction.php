<?php
namespace App\Application\Actions\LoginAction;

use Psr\Http\Message\ResponseInterface;
use App\Infrastructure\Repository\LoginRepository\LoginRepository;


class LoginSessionAction extends LoginAction
{

    public function action(): ResponseInterface
    {
        global $env;
        $request = $this->request->getParsedBody();
        $body = $this->antiXSS->xss_clean($request);

        if (empty($body['login']) || empty($body['senha'])) {
            $msg = ['summary' => 'Login ou Senha inválidos, tente novamente!'];
            return $this->respondWithData($msg);
        }

        // return $this->respondWithData($body);
        try {
            $r = new LoginRepository($this->sqlRepository, $this->birthdayRepository);
            $loginResult = $r->login(strtoupper($body['login']), $body['senha']);

            if (isset($loginResult['error'])) {
                return $this->respondWithData($loginResult['error'], 401);
            }

            // $loginList = ['X397762','X535099','X492420'];
            $loginTest = $this->sqlRepository->selectUserOfId(strtoupper($loginResult[2]), 'usuarios', 'login_rede');

            if (!isset($loginTest[0]['login_rede'])) {
                $msg = ['summary' => 'Sem permissão de acesso, contate o desenvolvedor!'];
                $this->createLogger->loggerCSV('erro_logar_sessao', "Tentativa de login sem permissão de acesso para o usuario: $loginResult[1], email: $loginResult[0] e login: " . strtoupper($loginResult[2]));
                return $this->respondWithData($msg, 401);
            }
            $msg = ['summary' => 'Logado com sucesso!', 'login' => USER_LOGIN, 'email' => USER_EMAIL, 'name' => USER_NAME, 'token' => $loginResult['Token']];
            $this->createLogger->loggerCSV("login_realizado", "Usuario " . USER_NAME . " Realizou login");


            return $this->respondWithData($msg);

        } catch (\Throwable $th) {
            $this->createLogger->loggerCSV('erro_logar_sessao', $th->getMessage());
            throw new \Exception($th->getMessage(), $th->getCode());
        }
    }
}
