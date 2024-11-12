<?php
namespace App\Application\Actions\Cadastro;

use Psr\Http\Message\ResponseInterface;
use App\Application\Actions\BirthdayAction;


class CadastrarAction extends BirthdayAction
{
    public function action(): ResponseInterface 
    {   

        $dados = $this->validateParams->verifyPost($this->request);

        $r = [
            'name' => strtoupper($dados['name']),
            'nascimento'=> $dados['nascimento'],
            'empresa'=> $dados['empresa']
        ];
        
        $r = $this->birthdayRepository->insert('aniversarios', $r);

        $this->logGenerate()->loggerCSV("cadastro_aniversario","Cadastro de aniversariante realizado com sucesso", 'info', $dados['name']);
        return $this->respondWithData(['status'=>'ok','msg'=>'Cadastro realizado com sucesso']);
    }
}