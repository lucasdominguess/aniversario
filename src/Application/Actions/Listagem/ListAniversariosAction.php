<?php 
namespace App\Application\Actions\Listagem;

use DateTime;
use DateTimeZone;
use Psr\Http\Message\ResponseInterface;
use App\Application\Actions\BirthdayAction;

class ListAniversariosAction extends BirthdayAction{


public function action (): ResponseInterface
{   
    $date =new DateTime('now',new DateTimeZone('America/Sao_Paulo'));
    $mouth= $date->format('m');

    $r = $this->birthdayRepository->SelectUsersbyDate($mouth);
    
    foreach ($r as $key => $value) {
        $r[$key]['nome'] = strtoupper($value['nome']);
        $date = new DateTime($value['nascimento'],new DateTimeZone('America/Sao_Paulo'));
        $r[$key]['nascimento'] = $date->format('d-m-Y');
    }
    return $this->respondWithData($r);
}
}