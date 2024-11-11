<?php 
namespace App\classes;


use Slim\Psr7\Request;
use voku\helper\AntiXSS;
use App\classes\RequestParams;

class ValidateParams
{
    use Helpers ;
    public function __construct
    (
        protected AntiXSS $antiXSS 
    )
    {

    }
public function verifyPost(Request $request,bool $verifyDetals=true):array
{       
    $dados = $this->validadeIsNotNull($request->getParsedBody());

    if($verifyDetals) $dados= $this->verifyDetails($request->getParsedBody());

    return $this->antiXSS($dados);
}

public function verifyGet(Request $request,bool $validadeIsNotNull=false,bool $verifyDetals=false):array|null
{   
    $dados = $request->getQueryParams();
   if($validadeIsNotNull) $dados = $this->validadeIsNotNull($dados);

   if($verifyDetals) $dados =$this->antiXSS($this->verifyDetails($dados));

    return $dados;
}
public function antiXSS(array|string|int $dados)
{   //tratamento contra ataque xss 
    return array_map(fn($e)=>$this->antiXSS->xss_clean($e),$dados);
}

public function verifyDetails(array|string|int $dados)
    {    
        foreach ($dados as $key => $value) {
    
            if(in_array($key,RequestParams::DateFields())) $this->verifyDate($value);

            elseif(in_array($key,RequestParams::IdFields())) $this->verifyRegex($value,Regex::NUMERO_INT); 
            
            elseif(is_numeric($value)) $this->verifyRegex($value,Regex::NUMERO_INT);    

            // if(in_array($key,RequestParams::InputFields())) $this->verifyRegex($value,Regex::NUMERO_INT); continue;

        };
       return $dados; 
 }
 public function validadeIsNotNull($dados)
 {  
    if (empty($dados)) throw new \Exception('Nenhum valor informado!');

     foreach ($dados as $key => $value) {

         if($value === null || $value === '') throw new \Exception("Por favor, preencha todos os campos corretamente.");
     }
     
     return $dados;
 }
//  public function verifyRegex(mixed $dados,$regex){

//     foreach ($dados as $key => $value) {
//         if(!preg_match($regex,$value)){
//             throw new \Exception("Dados enviados não correspondem ao padrão requerido");
//         }

//     }
//     return $dados;

// }
}