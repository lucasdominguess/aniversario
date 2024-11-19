<?php
namespace App\Application\Actions\Cadastro;

use Psr\Http\Message\ResponseInterface;
use App\Application\Actions\BirthdayAction;


class CadastrarAction extends BirthdayAction
{
    public function action(): ResponseInterface 
    {   

        $dados = $this->validateParams->verifyPost($this->request);

        match(true){
            strtoupper($dados['empresa'])=='PREFEITURA' => $dados['id_empresa'] = 1,
            strtoupper($dados['empresa'])=='G4F' => $dados['id_empresa'] = 2,
            strtoupper($dados['empresa'])=='TERCEIRA' => $dados['id_empresa'] = 3,
            default => throw new \Exception('Empresa não encontrada')
        };

        $response = match (true) {
            !isset($dados['name']) => $msg = ['status' => 'fail', 'msg' => 'Necessário fornecer um nome'],
            !isset($dados['nascimento']) => $msg = ['status' => 'fail', 'msg' => 'Necessário fornecer uma data de aniversário'],
            !isset($dados['empresa']) => $msg = ['status' => 'fail', 'msg' => 'Necessário fornecer uma empresa'],
            default => $this->register($dados)
        };

        return $this->respondWithData($response);
    }

    private function register($dados) {
        $r = [
            'id_empresa'=> $dados['id_empresa'],
            'nome' => strtoupper($dados['name']),
            'nascimento'=> $dados['nascimento'],
        ];
        
        $r = $this->birthdayRepository->insert('aniversarios', $r);

        $this->logGenerate()->loggerCSV("cadastro_aniversario","Cadastro de aniversariante realizado com sucesso", 'info', $dados['name']);
        return ['status' => 'ok', 'msg' => 'Cadastro realizado com sucesso'];
    }
}