<?php
namespace App\Application\Actions\Editar;

use App\classes\Regex;
use Psr\Http\Message\ResponseInterface;
use App\Application\Actions\BirthdayAction;

class EditarAction extends BirthdayAction
{
    public function action(): ResponseInterface
    {

        $dados = $this->validateParams->verifyPost($this->request);

        if (!isset($dados['id'])) {
            $msg = ['status' => 'fail', 'msg' => 'Necessario fornecer um id'];
            return $this->respondWithData($msg, 404);
        }
        $response = match (true) {
            !empty($isset['id']) => $msg = ['status' => 'fail', 'msg' => 'Necessario fornecer um id'],
            empty($dados['name']) => $msg = ['status' => 'fail', 'msg' => 'Necessario fornecer um nome'],
            empty($dados['nascimento']) => $msg = ['status' => 'fail', 'msg' => 'Necessario fornecer uma data de nascimento'],
            empty($dados['id_empresa']) => $msg = ['status' => 'fail', 'msg' => 'Necessario fornecer uma empresa'],
            default => $this->edit($dados)
        };

        
        return $this->respondWithData($response);
    }
    private function edit($dados) {
        $r = [
            'id_empresa'=> $dados['empresa'],
            'nome' => strtoupper($dados['name']),
            'nascimento'=> $dados['nascimento'],
        ];
        
        $r = $this->birthdayRepository->update($dados['id'], 'aniversarios', $r);

        $this->logGenerate()->loggerCSV("cadastro_aniversario","Cadastro de aniversariante atualizado com sucesso", 'info', $dados['name']);
        return ['status' => 'ok', 'msg' => 'Cadastro atualizado com sucesso'];
    }
}