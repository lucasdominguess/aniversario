<?php
namespace App\Application\Actions\Cadastro;

use Psr\Http\Message\ResponseInterface;
use App\Application\Actions\BirthdayAction;


class CadastrarAction extends BirthdayAction
{

    public function action(): ResponseInterface
    {
        $dados = $this->validateParams->verifyPost($this->request);

        $empresa = strtoupper($dados['empresa'] ?? '');
        if (!in_array($empresa, ['PREFEITURA', 'G4F', 'TERCEIRA'])) {
            throw new \Exception('Empresa não encontrada');
        }
        $dados['id_empresa'] = match ($empresa) {
            'PREFEITURA' => 1,
            'G4F' => 2,
            'TERCEIRA' => 3
        };

        $requiredFields = ['name' => 'um nome', 'nascimento' => 'uma data de aniversário', 'empresa' => 'uma empresa'];
        foreach ($requiredFields as $field => $message) {
            if (!isset($dados[$field])) {
                return $this->respondWithData(['status' => 'fail', 'msg' => "Necessário fornecer $message"]);
            }
        }

        return $this->respondWithData($this->register($dados));
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