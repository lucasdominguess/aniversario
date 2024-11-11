<?php
namespace App\classes;

use App\Infrastructure\Repository\SqlRepository\SqlRepository;
class InputTables extends SqlRepository { 
    public  function loadIds(string|int $id_column,string $table_name,string $letra='E'): array
    {  
       try {
      
        $stmt = $this->sql->prepare("SELECT $id_column from $table_name");     
        $stmt->execute();
        $input=$stmt->fetchAll(\PDO::FETCH_ASSOC); 
   
         $dados = [];
         foreach ($input as $key => $value) {
             $key +=1;
             $n= "$letra$key";
             $dados[$n] = $key;
         
         }
 
         return $dados;
       } catch (\Throwable $th) {
        $this->createLogger->loggerCSV('erro_input_tables',$th->getMessage());
        throw new \Exception('Ocorreu um erro ao tentar buscar os ids das colunas');
       }
     
}
public  function loadIdsOfUnidades(string|int $id_column,string $table_name,string $letra='E',$id_unidade=''): array
{  
   try {
  
    $stmt = $this->sql->prepare("SELECT $id_column from $table_name
    WHERE id_unidade = :a
    ");
    $array = [':a'=>$id_unidade];
    $this->sql->setParams($stmt,$array);
    $stmt->execute();
    $input=$stmt->fetchAll(\PDO::FETCH_ASSOC); 

     $dados = [];
     foreach ($input as $key => $value) {
         $key +=1;
         $n= "$letra$key";
         $dados[$n] = $key;
     
     }

     return $dados;
   } catch (\Throwable $th) {
    $this->createLogger->loggerCSV('erro_input_tables_unidades',$th->getMessage());
    throw new \Exception('Ocorreu um erro ao tentar buscar os ids das colunas');
   }
 
}
}

