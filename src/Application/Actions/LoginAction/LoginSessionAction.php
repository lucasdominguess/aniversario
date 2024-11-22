<?php
namespace App\Application\Actions\LoginAction;

use Psr\Http\Message\ResponseInterface;
use App\Infrastructure\Repository\LoginRepository\LoginRepository;


class LoginSessionAction extends LoginAction
{

    public function action(): ResponseInterface
    {
        global $env;
        $body = $this->validateParams->verifyPost($this->request);
        $body;

        if (!isset($body['login']) || !isset($body['senha'])) {
            $msg = ['summary' => 'Por favor, preencha todos os campos corretamente!'];
            $this->createLogger->loggerCSV('erro_logar_sessao', "Tentativa de login sem preencher todos os campos: " . json_encode($body));
            throw new \Exception($msg['summary']);
        }

        try {
            $r = new LoginRepository($this->sqlRepository, $this->birthdayRepository);
            $loginResult = $r->login(strtoupper($body['login']), $body['senha']);

            if (isset($loginResult['error'])) {
                throw new \Exception($loginResult['error']['summary']);
            }

            $loginTest = $this->sqlRepository->selectUserOfId(strtoupper($loginResult[2]), 'usuarios', 'login_rede');

            if (!isset($loginTest[0]['login_rede'])) {
                $msg = ['summary' => 'Sem permissão de acesso, contate o desenvolvedor!'];
                $this->createLogger->loggerCSV('erro_logar_sessao', "Tentativa de login sem permissão de acesso para o usuario: $loginResult[1], email: $loginResult[0] e login: " . strtoupper($loginResult[2]));
                throw new \Exception($msg['summary']);
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
