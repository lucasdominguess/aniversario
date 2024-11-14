<?php 
namespace App\Application\Actions\Listagem;


use DateTime;
use DateTimeZone;
use Psr\Http\Message\ResponseInterface;
use App\Application\Actions\BirthdayAction;


class ListUserAction extends BirthdayAction
{ 


    public function action(): ResponseInterface
    {
        $dados = $this->validateParams->verifyGet($this->request,false,true);
        $id = $dados['id'];

        // if ($id === 0) throw new \Exception("Informe um valor diferente de 0");

        $r = $this->birthdayRepository->SelectUsers($id);

        foreach ($r as $key => $value) {
            $r[$key]['nome'] = mb_strtoupper($value['nome']);
            $date = new DateTime($value['nascimento'],new DateTimeZone('America/Sao_Paulo'));
            $r[$key]['nascimento'] = $date->format('m-d-Y');
        }

        return $this->respondWithData($r);

    }

}