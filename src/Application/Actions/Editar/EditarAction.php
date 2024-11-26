<?php
namespace App\Application\Actions\Editar;

use App\classes\Regex;
use Psr\Http\Message\ResponseInterface;
use App\Application\Actions\BirthdayAction;

class EditarAction extends BirthdayAction
{
    /**
     * Editar um aniversariante
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function action(): ResponseInterface
    {
        $dados = $this->validateParams->verifyPost($this->request);

        if (empty($dados['id'])) {
            return $this->respondWithData(['status' => 'fail', 'msg' => 'Necessario fornecer um id'], 404);
        }

        $empresa = strtoupper($dados['empresa'] ?? '');

        if (!in_array($empresa, ['PREFEITURA', 'G4F', 'TERCEIRA'])) {
            throw new \Exception('Empresa não encontrada');
        }

        $dados['id_empresa'] = match ($empresa) {
            'PREFEITURA' => 1,
            'G4F' => 2,
            'TERCEIRA' => 3,
        };

        return $this->respondWithData($this->edit($dados));
    }

        /**
         * Atualiza um aniversariante
         *
         * @param array $dados Dados do aniversariante
         * @return array
         */
    private function edit($dados) {
        $r = [
            'id_empresa'=> $dados['id_empresa'],
            'nome' => strtoupper($dados['name']),
            'nascimento'=> $dados['nascimento'],
        ];
        
        $r = $this->birthdayRepository->update($dados['id'], 'aniversarios', $r);

        $this->logGenerate()->loggerCSV("cadastro_aniversario","Cadastro de aniversariante atualizado com sucesso", 'info', $dados['name']);
        return ['status' => 'ok', 'msg' => 'Cadastro atualizado com sucesso'];
    }
}