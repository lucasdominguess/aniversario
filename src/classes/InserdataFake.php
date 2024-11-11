<?php 
namespace App\classes;

require __DIR__ . '/../../vendor/autoload.php';
// use App\Infrastructure\Connection\Sql;
use PDO;
use App\classes\Helpers;
use App\Infrastructure\Connection\Sql;
use App\Infrastructure\Repository\SqlRepository\SqlRepository;

class InserdataFake 
{ 

    use Helpers;

public function connectDatabase()
{
    try {
        $host = 'localhost';
        $db   = 'boletim_hospitalar';
        $user = 'postgres';
        $pass = '641700';
        $port = '5432'; 
    
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $sql = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Ativar exceções
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Definir o fetch padrão
    ]);
    return $sql;
    
    } catch (\Throwable $th) {
        $this->logGenerate()->loggerCSV("erro_Insert_fake",$th->getMessage());
    }

}
   public function insertBoletim($data,$id_unidade,$idc,$idt,$qtd)
   {    
    try {
        $sql = $this->connectDatabase();

        $cmd = "INSERT into boletim_cirurgias(date,id_unidade,id_cirurgia,id_tipo_cirurgia,qtd) values
                ('$data',$id_unidade,$idc,$idt,$qtd)"; 
        $stmt = $sql->prepare($cmd);
        $stmt->execute();
    } catch (\Throwable $th) {
            echo 'Error: ' . $th->getMessage();
        // $this->logGenerate()->loggerCSV("erro_Insert_fake",$th->getMessage());
    }
 }
 public function insertMonitfake($date,$id_unidade)
 {  
    try {
    $sql = $this->connectDatabase();
    $cmd ="INSERT INTO monitora_cirurgias (datetime,id_unidade)VALUES('$date',$id_unidade)";
    $stmt = $sql->prepare($cmd);
    $stmt->execute();
    echo "Data: $date Monitoramento da  Unidade: $id_unidade Inserido com sucesso.   \n";

 } catch (\Throwable $th) {
    echo 'Error: ' . $th->getMessage();

 }
 }
}

$data = new \DateTime("now");
$data->modify("-1 day");
$d = new \DateTime("2024-01-01");
$key=1;
$id =14;
while ($d < $data) {

    // $d = new \DateTime("2024-08-01");
    
    $date = $d->format('Y-m-d');

    // $id == 40 ? $key = 41 : $key = 1; 
    // $idc == 14 ? 1 : $idc ;  
    
    // sleep(2);
    // $d++;
    for ($id_unidade=14; $id_unidade < 41 ; $id_unidade++) {
        
        for ($idC=1; $idC < 14; $idC++) {
         
            // $idt = rand(1,2);
            $qtd = rand(1,20);
            // sleep(1);
            $insert = new InserdataFake();
            $insert->insertBoletim($date,$id_unidade,$idC,1,$qtd);
            $insert->insertBoletim($date,$id_unidade,$idC,2,$qtd);
            // $insert->insertBoletim($date,$id_unidade,$idC,$idt,$qtd);
            // sleep(1);
        
            // echo $date ."\n";
            // echo $id_unidade ."\n";
            // echo $i ."\n";
            // echo $idt ."\n";
            // echo $qtd ."\n";
            // echo $key ."\n";
            // echo "------------------------------------------------------------ .\n";
            
        }
    echo "Data: $date Boletim da  Unidade: $id_unidade Inserido com sucesso.   \n";

        $insert->insertMonitfake($date,$id_unidade);
    }

    $d->modify('+7 day');
}
    // $key++;

echo "Scritp finalizado com sucesso";


// $insert = new InserdataFake();
// $insert->insertFake('2024-08-01',39,1,1,10);