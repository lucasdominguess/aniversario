<?php
namespace App\classes;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\classes\CreateLogger;
use App\Infrastructure\Connection\Sql;
use App\Infrastructure\Repository\SqlRepository\SqlRepository;

trait Helpers{
    public function dd($var)
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
        die();
    }
    public function logGenerate()
    {
        return $log = new CreateLogger();
    }
    public function sendEmail()
    {
        return $email = new Email();
    }

    public function safeCall(callable $func, string $errorMessage = 'Um erro ocorreu, por favor tente novamente mais tarde'): array
    {
        try {
            return $func();
        } catch (\Exception $e) {
            return [
                'summary' => $errorMessage,
                'cod' => 500
            ];
        }
    }

    public function post(Request $request): array
	{
		if(!$form = $request->getParsedBody())
			throw new \Exception('Nenhum valor informado!');
       
            foreach ($form as $key => $value) {
                if ($value === null || !preg_match(Regex::NUMERO_INT,$value)) {
                    throw new \Exception("Por favor, preencha todos os campos corretamente.");
                    // return 
                }
            } 
		// return $this->antixss($form, array_keys($form));
       return array_map(fn($e)=>$this->antiXSS->xss_clean($e),$form);
	}
 
   
  

    public function verifyDayOfWeek() 
    {
        $date = $GLOBALS['DAY']->format('D');
    
    return match ($date) {
        // 'Mon' => [$GLOBALS['date'], 'Mon'],// forçar data para segunda feira
    
        'Mon' => [$GLOBALS['date'], 'Mon'],
        'Tue' => [$GLOBALS['DAY']->modify('-1 day')->format('Y-m-d'), 'Tue'],
        'Wed' => [$GLOBALS['DAY']->modify('-2 day')->format('Y-m-d'), 'Wed'],
        'Thu' => [$GLOBALS['DAY']->modify('-3 day')->format('Y-m-d'), 'Thu'],
        'Fri' => [$GLOBALS['DAY']->modify('-4 day')->format('Y-m-d'), 'Mon'],
        'Sat' => [$GLOBALS['DAY']->modify('-5 day')->format('Y-m-d'), 'Sat'],
        'Sun' => [$GLOBALS['DAY']->modify('-6 day')->format('Y-m-d'), 'Sun'],
      
        default => throw new \Exception('Dia da semana não tratado')
    };
        // return [$GLOBALS['date']];
    }
 
    public function remove_key($dados,$remove) {
        
        if (empty($dados)) throw new \Exception('Estes dados não foram encontrados');

        foreach ($dados as &$linha) {
            foreach($remove as $key){
                unset($linha[$key]);
            }
        }
        return $dados;
    }


    public function verifyMondayOrTuesday($date) {
        $this->verifyDate($date);
    
        // 1 = Segunda-feira,2 = Terça-feira, 3 = Quarta-feira, 4 = Quinta-feira, 5 = Sexta-feira, 6 = Sábado, 7 = Domingo

        $dayOfWeek = date('N', strtotime($date));
    
        if (!$dayOfWeek == 1 || !$dayOfWeek == 2) {
            throw new \Exception("A data não pode ser diferente de segunda ou terça-feira.");
        }
    
        return $date; 
    }

    public function verifyDate($date) {
        [$year, $month, $day] = explode('-', $date);
        $check = match (true) {
            $date === null => 'Data inválida',
            !preg_match(Regex::DATE, $date) => 'Formato de data inválido',
            ((int)$day||(int)$month) < 1 => 'Dia ou mês inválido',
            $date > $GLOBALS['date']=> 'Data maior que o dia atual, favor verificar',
            default => null
        };

        if ($check) {
            throw new \Exception($check);
        }

        return $date;
    }
    public function verifyRegex($dados,$regex){
        if(!preg_match($regex,$dados)){
            throw new \Exception("Dados enviados nao correspondem ao padrao requerido");
        }
    }
    public function monitoraFormat($dados) {
        
        if (empty($dados)) throw new \Exception('Não ha dados de monitoramento');

        foreach ($dados as &$linha) {
            $linha['data'] = $linha['data'].' '.$linha['hora'];            
            $date = new \Datetime($linha['data']);
            $linha['data'] = $date->format('d/m/Y H:i:s');
            unset($linha['id'], $linha['id_unidade'],$linha['id_adm'],$linha['hora']);
        }
        return $dados;
    }
  
    public function verifyRegexArray(string |array $dados,$regex){

        foreach ($dados as $key => $value) {
            if(!preg_match($regex,$value)){
                throw new \Exception("Dados enviados não correspondem ao padrão requerido");
            }
    
        }
        return $dados;
    
    }


}