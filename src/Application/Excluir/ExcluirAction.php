<?php
namespace App\Application\Excluir;

use Psr\Http\Message\ResponseInterface;
use App\Application\Actions\BirthdayAction;

class ExcluirAction extends BirthdayAction{
    public function action(): ResponseInterface {
        $dados = $this->validateParams->verifyPost($this->request);

        $this->birthdayRepository->delete($dados['id'], 'aniversarios');

        $this->logGenerate()->loggerCSV("cadastro_aniversario", "Cadastro de aniversariante excluido com sucesso", 'info', $dados['name']);

        return $this->respondWithData(['status' => 'ok', 'msg' => 'Cadastro excluido com sucesso']);
    }
}